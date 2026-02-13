<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TrainingSessionStatus;
use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\TrainingSession;
use App\Models\TrainingSessionSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingSessionController extends Controller
{
    /**
     * List training sessions (admin can see all coaches).
     */
    public function index(Request $request)
    {
        $query = TrainingSession::with(['slots.sessionTime', 'slots.coaches', 'slots.confirmedBookings.memberPackage.member', 'createdBy']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

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
     * Create a new training session (admin can create for any coach).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',

            // Create a day session with multiple slots
            'slots' => 'required|array|min:1',
            'slots.*.session_time_id' => 'required|exists:session_times,id',
            'slots.*.max_participants' => 'required|integer|min:1|max:50',
            'slots.*.coach_ids' => 'required|array|min:1',
            'slots.*.coach_ids.*' => 'required|exists:coaches,id',
        ]);

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

                // Attach coaches to this specific slot
                $coachIds = collect($slotPayload['coach_ids'])->map(fn ($id) => (int) $id)->filter()->unique()->all();
                $slot->coaches()->attach($coachIds);
            }

            DB::commit();

            return response()->json([
                'message' => 'Training session created successfully',
                'data' => $trainingSession->fresh()->load(['slots.sessionTime', 'slots.coaches', 'createdBy']),
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
     * Show training session including slots.
     */
    public function show(TrainingSession $trainingSession)
    {
        $trainingSession->applyAutoClose(now());

        return response()->json(
            $trainingSession->load(['slots.sessionTime', 'slots.coaches', 'createdBy'])
        );
    }

    /**
     * Update coaches assigned to a training session.
     */
    /**
     * Update coaches assigned to a specific slot.
     */
    public function updateSlotCoaches(Request $request, TrainingSessionSlot $trainingSessionSlot)
    {
        $validated = $request->validate([
            'coach_ids' => 'required|array|min:1',
            'coach_ids.*' => 'required|exists:coaches,id',
        ]);

        $coachIds = collect($validated['coach_ids'])->map(fn ($id) => (int) $id)->filter()->unique()->values()->all();

        $trainingSessionSlot->coaches()->sync($coachIds);

        return response()->json([
            'message' => 'Coaches updated successfully',
            'data' => $trainingSessionSlot->fresh()->load(['sessionTime', 'coaches']),
        ]);
    }
}
