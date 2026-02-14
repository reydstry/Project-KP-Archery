<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberPackage;
use App\Models\SessionBooking;
use App\Models\TrainingSessionSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionBookingController extends Controller
{
    private function validateSlotBookable(TrainingSessionSlot $slot): ?array
    {
        $trainingSession = $slot->trainingSession;
        if (!$trainingSession) {
            return [
                'message' => 'Training session not found for this slot',
                'code' => 404,
            ];
        }

        $now = now();
        $trainingSession->applyAutoClose($now);

        if ($trainingSession->date->isPast() && !$trainingSession->date->isToday()) {
            return [
                'message' => 'Cannot book past sessions',
                'code' => 422,
            ];
        }

        if ($trainingSession->date->isToday() && !$trainingSession->isBookableAt($now)) {
            return [
                'message' => 'Training session can no longer be booked (past or after 18:00).',
                'code' => 422,
            ];
        }

        if ($trainingSession->status?->value !== 'open') {
            return [
                'message' => 'Training session is not open for booking',
                'code' => 422,
            ];
        }

        $sessionTime = $slot->sessionTime;
        if ($sessionTime && $trainingSession->date->isToday()) {
            $slotEndTime = $sessionTime->end_time;
            $currentTime = $now->format('H:i:s');

            if ($currentTime >= $slotEndTime) {
                return [
                    'message' => 'Cannot book this slot. The session time has already passed.',
                    'code' => 422,
                ];
            }
        }

        return null;
    }

    /**
     * Admin books a session slot for a member (using member_package_id).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'training_session_slot_id' => 'required|exists:training_session_slots,id',
            'member_package_id' => 'required|exists:member_packages,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $memberPackage = MemberPackage::with(['member', 'package'])
            ->where('id', $validated['member_package_id'])
            ->first();

        if (!$memberPackage) {
            return response()->json([
                'message' => 'Member package not found',
            ], 404);
        }

        if (!$memberPackage->is_active) {
            return response()->json([
                'message' => 'Member package is not active',
            ], 422);
        }

        if (!$memberPackage->member?->is_active || $memberPackage->member?->status === 'inactive') {
            return response()->json([
                'message' => 'Member is inactive',
            ], 422);
        }

        if (!$memberPackage->package?->is_active) {
            return response()->json([
                'message' => 'Package is inactive',
            ], 422);
        }

        if ($memberPackage->end_date->isPast()) {
            return response()->json([
                'message' => 'Member package has expired',
            ], 422);
        }

        $remainingSessions = $memberPackage->total_sessions - $memberPackage->used_sessions;
        if ($remainingSessions <= 0) {
            return response()->json([
                'message' => 'No remaining sessions in member package',
                'total_sessions' => $memberPackage->total_sessions,
                'used_sessions' => $memberPackage->used_sessions,
            ], 422);
        }

        $trainingSessionSlot = TrainingSessionSlot::with(['trainingSession', 'sessionTime'])
            ->findOrFail($validated['training_session_slot_id']);

        $trainingSession = $trainingSessionSlot->trainingSession;
        if (!$trainingSession) {
            return response()->json([
                'message' => 'Training session not found for this slot',
            ], 404);
        }

        $now = now();

        $trainingSession->applyAutoClose($now);

        if ($trainingSession->date->isPast() && !$trainingSession->date->isToday()) {
            return response()->json([
                'message' => 'Cannot book past sessions',
            ], 422);
        }

        if ($trainingSession->date->isToday() && !$trainingSession->isBookableAt($now)) {
            return response()->json([
                'message' => 'Training session can no longer be booked (past or after 18:00).',
                'session_status' => $trainingSession->status?->value,
            ], 422);
        }

        $sessionTime = $trainingSessionSlot->sessionTime;
        if ($sessionTime && $trainingSession->date->isToday()) {
            $slotEndTime = $sessionTime->end_time;
            $currentTime = $now->format('H:i:s');

            if ($currentTime >= $slotEndTime) {
                return response()->json([
                    'message' => 'Cannot book this slot. The session time has already passed.',
                    'slot_time' => $sessionTime->start_time . ' - ' . $sessionTime->end_time,
                ], 422);
            }
        }

        if ($trainingSession->status?->value !== 'open') {
            return response()->json([
                'message' => 'Training session is not open for booking',
                'session_status' => $trainingSession->status?->value,
            ], 422);
        }

        $currentBookings = SessionBooking::where('training_session_slot_id', $trainingSessionSlot->id)
            ->where('status', 'confirmed')
            ->count();

        if ($currentBookings >= $trainingSessionSlot->max_participants) {
            return response()->json([
                'message' => 'Training session is full',
                'current_bookings' => $currentBookings,
                'max_participants' => $trainingSessionSlot->max_participants,
            ], 422);
        }

        $existingBooking = SessionBooking::where('member_package_id', $memberPackage->id)
            ->where('training_session_slot_id', $trainingSessionSlot->id)
            ->where('status', 'confirmed')
            ->exists();

        if ($existingBooking) {
            return response()->json([
                'message' => 'Member already booked this session',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $booking = SessionBooking::create([
                'member_package_id' => $memberPackage->id,
                'training_session_slot_id' => $trainingSessionSlot->id,
                'booked_by' => auth()->id(),
                'status' => 'confirmed',
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Session booked successfully',
                'data' => $booking->load([
                    'memberPackage.member',
                    'trainingSessionSlot.sessionTime',
                    'trainingSessionSlot.trainingSession.coach',
                ]),
                'remaining_sessions' => $memberPackage->total_sessions - $memberPackage->used_sessions,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to book session',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, SessionBooking $sessionBooking)
    {
        $validated = $request->validate([
            'training_session_slot_id' => 'required|exists:training_session_slots,id',
        ]);

        if ($sessionBooking->status !== 'confirmed') {
            return response()->json([
                'message' => 'Only confirmed bookings can be updated',
            ], 422);
        }

        $targetSlot = TrainingSessionSlot::with(['trainingSession', 'sessionTime'])
            ->findOrFail($validated['training_session_slot_id']);

        if ((int) $sessionBooking->training_session_slot_id === (int) $targetSlot->id) {
            return response()->json([
                'message' => 'Booking is already in the selected slot',
            ], 422);
        }

        $bookableError = $this->validateSlotBookable($targetSlot);
        if ($bookableError) {
            return response()->json(['message' => $bookableError['message']], $bookableError['code']);
        }

        $currentBookings = SessionBooking::where('training_session_slot_id', $targetSlot->id)
            ->where('status', 'confirmed')
            ->count();

        if ($currentBookings >= $targetSlot->max_participants) {
            return response()->json([
                'message' => 'Target slot is full',
            ], 422);
        }

        $alreadyExists = SessionBooking::where('member_package_id', $sessionBooking->member_package_id)
            ->where('training_session_slot_id', $targetSlot->id)
            ->where('status', 'confirmed')
            ->exists();

        if ($alreadyExists) {
            return response()->json([
                'message' => 'Member already booked in target slot',
            ], 422);
        }

        $sessionBooking->update([
            'training_session_slot_id' => $targetSlot->id,
        ]);

        return response()->json([
            'message' => 'Booking moved successfully',
            'data' => $sessionBooking->fresh()->load([
                'memberPackage.member',
                'trainingSessionSlot.sessionTime',
                'trainingSessionSlot.trainingSession',
            ]),
        ]);
    }

    public function destroy(SessionBooking $sessionBooking)
    {
        if ($sessionBooking->status !== 'confirmed') {
            return response()->json([
                'message' => 'Only confirmed bookings can be removed',
            ], 422);
        }

        $sessionBooking->update([
            'status' => 'canceled',
        ]);

        return response()->json([
            'message' => 'Booking removed successfully',
        ]);
    }
}
