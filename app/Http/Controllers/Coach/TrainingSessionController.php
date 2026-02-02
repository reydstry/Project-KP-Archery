<?php

namespace App\Http\Controllers\Coach;

use App\Enums\TrainingSessionStatus;
use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\TrainingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingSessionController extends Controller
{
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

        $query = TrainingSession::with(['sessionTime', 'coach'])
            ->where('coach_id', $coach->id);

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

        $sessions = $query->orderBy('date')
            ->orderBy('session_time_id')
            ->paginate(15);

        return response()->json($sessions);
    }

    /**
     * Create a new training session
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'session_time_id' => 'required|exists:session_times,id',
            'date' => 'required|date|after_or_equal:today',
            'max_participants' => 'required|integer|min:1|max:50',
        ]);

        // Get coach record
        $coach = Coach::where('user_id', auth()->id())->first();
        
        if (!$coach) {
            return response()->json([
                'message' => 'Coach profile not found',
            ], 404);
        }

        // Check if session already exists for this date and time
        $exists = TrainingSession::where('session_time_id', $validated['session_time_id'])
            ->where('date', $validated['date'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Training session already exists for this date and time',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $session = TrainingSession::create([
                'session_time_id' => $validated['session_time_id'],
                'date' => $validated['date'],
                'coach_id' => $coach->id,
                'max_participants' => $validated['max_participants'],
                'status' => TrainingSessionStatus::OPEN->value,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Training session created successfully',
                'data' => $session->load(['sessionTime', 'coach']),
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
        
        if (!$coach || $trainingSession->coach_id !== $coach->id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        return response()->json($trainingSession->load(['sessionTime', 'coach']));
    }

    /**
     * Update training session quota
     */
    public function updateQuota(Request $request, TrainingSession $trainingSession)
    {
        // Verify coach owns this session
        $coach = Coach::where('user_id', auth()->id())->first();
        
        if (!$coach || $trainingSession->coach_id !== $coach->id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'max_participants' => 'required|integer|min:1|max:50',
        ]);

        $trainingSession->update([
            'max_participants' => $validated['max_participants'],
        ]);

        return response()->json([
            'message' => 'Quota updated successfully',
            'data' => $trainingSession->load(['sessionTime', 'coach']),
        ]);
    }

    /**
     * Open the training session
     */
    public function open(TrainingSession $trainingSession)
    {
        // Verify coach owns this session
        $coach = Coach::where('user_id', auth()->id())->first();
        
        if (!$coach || $trainingSession->coach_id !== $coach->id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
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
        
        if (!$coach || $trainingSession->coach_id !== $coach->id) {
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
        
        if (!$coach || $trainingSession->coach_id !== $coach->id) {
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
}
