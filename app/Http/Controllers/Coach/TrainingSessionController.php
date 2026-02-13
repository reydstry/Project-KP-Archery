<?php

namespace App\Http\Controllers\Coach;

use App\Enums\TrainingSessionStatus;
use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\TrainingSession;
use App\Models\TrainingSessionSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingSessionController extends Controller
{
    private function autoCloseCoachSessionsIfNeeded(Coach $coach): void
    {
        $now = now();

        $shouldCloseToday = $now->hour > TrainingSession::AUTO_CLOSE_HOUR
            || ($now->hour === TrainingSession::AUTO_CLOSE_HOUR && $now->minute >= TrainingSession::AUTO_CLOSE_MINUTE);

        TrainingSession::query()
            ->where('status', TrainingSessionStatus::OPEN->value)
            ->where('date', $shouldCloseToday ? '<=' : '<', today()->toDateString())
            ->update(['status' => TrainingSessionStatus::CLOSED->value]);
    }

    private function isCoachAssignedToSlot(?Coach $coach, TrainingSessionSlot $slot): bool
    {
        if (!$coach) {
            return false;
        }

        return $slot->coaches()
            ->where('coaches.id', $coach->id)
            ->exists();
    }

    private function isCoachAssignedToAnySlot(?Coach $coach, TrainingSession $trainingSession): bool
    {
        if (!$coach) {
            return false;
        }

        return $trainingSession->slots()
            ->whereHas('coaches', fn ($q) => $q->where('coaches.id', $coach->id))
            ->exists();
    }

    /**
     * Display a listing of training sessions for the authenticated coach
     */
    public function index(Request $request)
    {
        // Get coach record for authenticated user
        $coach = Coach::where('user_id', auth()->id())->first();
        
        if (!$coach) {
            return response()->json([
                'message' => 'Coach profile not found',
            ], 404);
        }

        $this->autoCloseCoachSessionsIfNeeded($coach);

        $query = TrainingSession::with(['slots.sessionTime', 'slots.coaches', 'slots.confirmedBookings.memberPackage.member'])
            ->whereHas('slots.coaches', fn ($q) => $q->where('coaches.id', $coach->id));

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        $sessions = $query->orderBy('date')->paginate(15);

        return response()->json($sessions);
    }

    /**
     * Create a new training session
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',

            // Create a day session with multiple slots
            'slots' => 'required|array|min:1',
            'slots.*.session_time_id' => 'required|exists:session_times,id',
            'slots.*.max_participants' => 'required|integer|min:1|max:50',
            'slots.*.coach_ids' => 'sometimes|array',
            'slots.*.coach_ids.*' => 'required_with:slots.*.coach_ids|exists:coaches,id',
        ]);

        // Get coach record
        $coach = Coach::where('user_id', auth()->id())->first();
        
        if (!$coach) {
            return response()->json([
                'message' => 'Coach profile not found',
            ], 404);
        }

        // Check for duplicate session times
        $sessionTimeIds = collect($validated['slots'])->pluck('session_time_id');
        if ($sessionTimeIds->count() !== $sessionTimeIds->unique()->count()) {
            return response()->json([
                'message' => 'Duplicate session_time_id in slots payload.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Create training session
            $trainingSession = TrainingSession::create([
                'date' => $validated['date'],
                'status' => TrainingSessionStatus::OPEN->value,
                'created_by' => auth()->id(),
            ]);

            // Create slots and assign coaches
            foreach ($validated['slots'] as $slotPayload) {
                $slot = TrainingSessionSlot::create([
                    'training_session_id' => $trainingSession->id,
                    'session_time_id' => $slotPayload['session_time_id'],
                    'max_participants' => $slotPayload['max_participants'],
                ]);

                // Build coach list (always include current coach + any additional coaches)
                $coachIds = collect($slotPayload['coach_ids'] ?? [])
                    ->map(fn ($id) => (int) $id)
                    ->filter()
                    ->push($coach->id)
                    ->unique()
                    ->values()
                    ->all();

                $slot->coaches()->attach($coachIds);
            }

            DB::commit();

            return response()->json([
                'message' => 'Training session created successfully',
                'data' => $trainingSession->fresh()->load(['slots.sessionTime', 'slots.coaches']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create training session',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified training session
     */
    public function show(TrainingSession $trainingSession)
    {
        // Verify coach owns this session
        $coach = Coach::where('user_id', auth()->id())->first();

        if (!$this->isCoachAssignedToAnySlot($coach, $trainingSession)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $trainingSession->applyAutoClose(now());

        return response()->json($trainingSession->load(['slots.sessionTime', 'slots.coaches']));
    }

    /**
     * Update training session quota
     */
    public function updateQuota(Request $request, TrainingSession $trainingSession)
    {
        // Verify coach owns this session
        $coach = Coach::where('user_id', auth()->id())->first();

        if (!$this->isCoachAssignedToAnySlot($coach, $trainingSession)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'slot_id' => 'required|exists:training_session_slots,id',
            'max_participants' => 'required|integer|min:1|max:50',
        ]);

        $slot = TrainingSessionSlot::where('id', $validated['slot_id'])
            ->where('training_session_id', $trainingSession->id)
            ->first();

        if (!$slot) {
            return response()->json([
                'message' => 'Slot not found for this training session',
            ], 404);
        }

        $slot->update([
            'max_participants' => $validated['max_participants'],
        ]);

        return response()->json([
            'message' => 'Quota updated successfully',
            'data' => $trainingSession->fresh()->load(['slots.sessionTime', 'slots.coaches']),
        ]);
    }

    /**
     * Update coaches assigned to a specific slot (must include the current coach).
     */
    public function updateCoaches(Request $request, TrainingSession $trainingSession)
    {
        $coach = Coach::where('user_id', auth()->id())->first();
        if (!$this->isCoachAssignedToAnySlot($coach, $trainingSession)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'slot_id' => 'required|exists:training_session_slots,id',
            'coach_ids' => 'required|array|min:1',
            'coach_ids.*' => 'required|exists:coaches,id',
        ]);

        $slot = TrainingSessionSlot::where('id', $validated['slot_id'])
            ->where('training_session_id', $trainingSession->id)
            ->first();

        if (!$slot) {
            return response()->json([
                'message' => 'Slot not found for this training session',
            ], 404);
        }

        $coachIds = collect($validated['coach_ids'])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->push($coach->id)
            ->unique()
            ->values()
            ->all();

        $slot->coaches()->sync($coachIds);

        return response()->json([
            'message' => 'Coaches updated successfully',
            'data' => $trainingSession->fresh()->load(['slots.sessionTime', 'slots.coaches']),
        ]);
    }

    /**
     * Open the training session
     */
    public function open(TrainingSession $trainingSession)
    {
        // Verify coach owns this session
        $coach = Coach::where('user_id', auth()->id())->first();

        if (!$this->isCoachAssignedToAnySlot($coach, $trainingSession)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        if ($trainingSession->shouldAutoCloseAt(now())) {
            $trainingSession->applyAutoClose(now());
            return response()->json([
                'message' => 'Session can no longer be opened (past or after 18:00).',
                'data' => $trainingSession->fresh(),
            ], 422);
        }

        if ($trainingSession->status === TrainingSessionStatus::OPEN) {
            return response()->json([
                'message' => 'Session is already open',
            ], 422);
        }

        $trainingSession->open();

        return response()->json([
            'message' => 'Training session opened successfully',
            'data' => $trainingSession->fresh(),
        ]);
    }

    /**
     * Close the training session
     */
    public function close(TrainingSession $trainingSession)
    {
        // Verify coach owns this session
        $coach = Coach::where('user_id', auth()->id())->first();

        if (!$this->isCoachAssignedToAnySlot($coach, $trainingSession)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        if ($trainingSession->status === TrainingSessionStatus::CLOSED) {
            return response()->json([
                'message' => 'Session is already closed',
            ], 422);
        }

        $trainingSession->close();

        return response()->json([
            'message' => 'Training session closed successfully',
            'data' => $trainingSession->fresh(),
        ]);
    }

    /**
     * Cancel the training session
     */
    public function cancel(TrainingSession $trainingSession)
    {
        // Verify coach owns this session
        $coach = Coach::where('user_id', auth()->id())->first();

        if (!$this->isCoachAssignedToAnySlot($coach, $trainingSession)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        if ($trainingSession->status === TrainingSessionStatus::CANCELED) {
            return response()->json([
                'message' => 'Session is already canceled',
            ], 422);
        }

        $trainingSession->cancel();

        return response()->json([
            'message' => 'Training session canceled successfully',
            'data' => $trainingSession->fresh(),
        ]);
    }

    /**
     * Delete a training session (day) and its slots.
     * Guard: only allowed when there are no bookings.
     */
    public function destroy(TrainingSession $trainingSession)
    {
        $coach = Coach::where('user_id', auth()->id())->first();

        if (!$this->isCoachAssignedToAnySlot($coach, $trainingSession)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $hasBookings = $trainingSession->bookings()->exists();
        if ($hasBookings) {
            return response()->json([
                'message' => 'Cannot delete session that already has bookings',
            ], 422);
        }

        $trainingSession->delete();

        return response()->json([
            'message' => 'Training session deleted successfully',
        ]);
    }
}
