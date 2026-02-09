<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SessionBooking;
use App\Models\TrainingSession;
use App\Models\TrainingSessionSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Get all bookings for a training session with attendance status
     */
    public function getSessionBookings(TrainingSession $trainingSession, Request $request)
    {
        // Verify coach owns this session
        $coach = auth()->user()->coach;
        if (!$coach || $trainingSession->coach_id !== $coach->id) {
            return response()->json([
                'message' => 'You can only view your own training sessions'
            ], 403);
        }

        $slotId = $request->query('slot_id');

        $slotIdsQuery = TrainingSessionSlot::query()
            ->where('training_session_id', $trainingSession->id);

        if ($slotId) {
            $slotIdsQuery->where('id', $slotId);
        }

        $slotIds = $slotIdsQuery->pluck('id');

        $bookings = SessionBooking::with([
            'memberPackage.member',
            'attendance',
            'trainingSessionSlot.sessionTime',
        ])
            ->whereIn('training_session_slot_id', $slotIds)
            ->where('status', 'confirmed')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'member_name' => $booking->memberPackage->member->name,
                    'member_id' => $booking->memberPackage->member->id,
                    'slot' => [
                        'id' => $booking->trainingSessionSlot?->id,
                        'session_time' => [
                            'id' => $booking->trainingSessionSlot?->sessionTime?->id,
                            'name' => $booking->trainingSessionSlot?->sessionTime?->name,
                            'start_time' => $booking->trainingSessionSlot?->sessionTime?->start_time,
                            'end_time' => $booking->trainingSessionSlot?->sessionTime?->end_time,
                        ],
                    ],
                    'has_attendance' => !is_null($booking->attendance),
                    'attendance_status' => $booking->attendance?->status,
                    'validated_at' => $booking->attendance?->validated_at,
                    'notes' => $booking->attendance?->notes,
                ];
            });

        return response()->json([
            'session' => [
                'id' => $trainingSession->id,
                'date' => $trainingSession->date,
                'status' => $trainingSession->status?->value,
            ],
            'bookings' => $bookings,
            'total_bookings' => $bookings->count(),
            'attended' => $bookings->where('attendance_status', 'present')->count(),
            'absent' => $bookings->where('attendance_status', 'absent')->count(),
            'not_validated' => $bookings->where('has_attendance', false)->count(),
        ]);
    }

    /**
     * Validate attendance for a booking
     */
    public function validateAttendance(SessionBooking $sessionBooking, Request $request)
    {
        // Get training session
        $trainingSession = $sessionBooking->trainingSessionSlot?->trainingSession;

        if (!$trainingSession) {
            return response()->json([
                'message' => 'Training session not found for this booking'
            ], 404);
        }

        // Verify coach owns this session
        $coach = auth()->user()->coach;
        if (!$coach || $trainingSession->coach_id !== $coach->id) {
            return response()->json([
                'message' => 'You can only validate attendance for your own training sessions'
            ], 403);
        }

        // Verify booking is confirmed
        if ($sessionBooking->status !== 'confirmed') {
            return response()->json([
                'message' => 'Can only validate attendance for confirmed bookings'
            ], 422);
        }

        // Validate request
        $validated = $request->validate([
            'status' => 'required|in:present,absent',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if attendance already exists
        $attendance = Attendance::where('session_booking_id', $sessionBooking->id)->first();

        if ($attendance) {
            return response()->json([
                'message' => 'Attendance already validated for this booking'
            ], 422);
        }

        // Get member package
        $memberPackage = $sessionBooking->memberPackage;

        // If marking as present, check and deduct quota
        if ($validated['status'] === 'present') {
            // Check remaining quota
            $remainingSessions = $memberPackage->total_sessions - $memberPackage->used_sessions;
            if ($remainingSessions <= 0) {
                return response()->json([
                    'message' => 'Member has no remaining sessions in package',
                    'total_sessions' => $memberPackage->total_sessions,
                    'used_sessions' => $memberPackage->used_sessions,
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            // Create attendance record
            $attendance = Attendance::create([
                'session_booking_id' => $sessionBooking->id,
                'status' => $validated['status'],
                'validated_by' => auth()->id(),
                'validated_at' => now(),
                'notes' => $validated['notes'] ?? null,
            ]);

            // Deduct quota only if present
            if ($validated['status'] === 'present') {
                $memberPackage->increment('used_sessions');
            }

            DB::commit();

            return response()->json([
                'message' => 'Attendance validated successfully',
                'attendance' => [
                    'id' => $attendance->id,
                    'booking_id' => $sessionBooking->id,
                    'member_name' => $sessionBooking->memberPackage->member->name,
                    'status' => $attendance->status,
                    'validated_at' => $attendance->validated_at,
                    'notes' => $attendance->notes,
                ],
                'remaining_sessions' => $memberPackage->fresh()->total_sessions - $memberPackage->fresh()->used_sessions,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to validate attendance',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update attendance status
     */
    public function update(SessionBooking $sessionBooking, Request $request)
    {
        // Get training session
        $trainingSession = $sessionBooking->trainingSessionSlot?->trainingSession;

        if (!$trainingSession) {
            return response()->json([
                'message' => 'Training session not found for this booking'
            ], 404);
        }

        // Verify coach owns this session
        $coach = auth()->user()->coach;
        if (!$coach || $trainingSession->coach_id !== $coach->id) {
            return response()->json([
                'message' => 'You can only update attendance for your own training sessions'
            ], 403);
        }

        // Get attendance
        $attendance = Attendance::where('session_booking_id', $sessionBooking->id)->first();

        if (!$attendance) {
            return response()->json([
                'message' => 'Attendance not found for this booking'
            ], 404);
        }

        // Validate request
        $validated = $request->validate([
            'status' => 'required|in:present,absent',
            'notes' => 'nullable|string|max:500',
        ]);

        $memberPackage = $sessionBooking->memberPackage;
        $oldStatus = $attendance->status;
        $newStatus = $validated['status'];

        // Check quota if changing from absent to present
        if ($oldStatus === 'absent' && $newStatus === 'present') {
            $remainingSessions = $memberPackage->total_sessions - $memberPackage->used_sessions;
            if ($remainingSessions <= 0) {
                return response()->json([
                    'message' => 'Member has no remaining sessions in package',
                    'total_sessions' => $memberPackage->total_sessions,
                    'used_sessions' => $memberPackage->used_sessions,
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            // Update attendance
            $attendance->update([
                'status' => $newStatus,
                'notes' => $validated['notes'] ?? $attendance->notes,
                'validated_at' => now(),
            ]);

            // Handle quota changes
            if ($oldStatus === 'absent' && $newStatus === 'present') {
                // Changed from absent to present: deduct quota
                $memberPackage->increment('used_sessions');
            } elseif ($oldStatus === 'present' && $newStatus === 'absent') {
                // Changed from present to absent: refund quota
                $memberPackage->decrement('used_sessions');
            }

            DB::commit();

            return response()->json([
                'message' => 'Attendance updated successfully',
                'attendance' => [
                    'id' => $attendance->id,
                    'booking_id' => $sessionBooking->id,
                    'member_name' => $sessionBooking->memberPackage->member->name,
                    'status' => $attendance->status,
                    'validated_at' => $attendance->validated_at,
                    'notes' => $attendance->notes,
                ],
                'remaining_sessions' => $memberPackage->fresh()->total_sessions - $memberPackage->fresh()->used_sessions,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update attendance',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
