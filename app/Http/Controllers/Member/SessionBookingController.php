<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberPackage;
use App\Models\SessionBooking;
use App\Models\TrainingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionBookingController extends Controller
{
    /**
     * Display a listing of member's bookings
     */
    public function index(Request $request)
    {
        $member = Member::where('user_id', auth()->id())
            ->where('is_self', true)
            ->first();
        
        if (!$member) {
            return response()->json([
                'message' => 'Member profile not found',
            ], 404);
        }

        // Get all member packages for this member
        $memberPackageIds = MemberPackage::where('member_id', $member->id)->pluck('id');

        $query = SessionBooking::with(['memberPackage.member', 'trainingSession.sessionTime', 'trainingSession.coach'])
            ->whereIn('member_package_id', $memberPackageIds);

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(15);

        return response()->json($bookings);
    }

    /**
     * Book a training session
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'training_session_id' => 'required|exists:training_sessions,id',
            'member_package_id' => 'required|exists:member_packages,id',
            'notes' => 'nullable|string|max:500',
        ]);

        // Get member
        $member = Member::where('user_id', auth()->id())
            ->where('is_self', true)
            ->first();
        
        if (!$member) {
            return response()->json([
                'message' => 'Member profile not found. Please register first.',
            ], 404);
        }

        // Verify member package belongs to this member
        $memberPackage = MemberPackage::where('id', $validated['member_package_id'])
            ->where('member_id', $member->id)
            ->first();

        if (!$memberPackage) {
            return response()->json([
                'message' => 'Member package not found or does not belong to you',
            ], 404);
        }

        // Check if package is active
        if (!$memberPackage->is_active) {
            return response()->json([
                'message' => 'Your package is not active',
            ], 422);
        }

        // Check if package has expired
        if ($memberPackage->end_date->isPast()) {
            return response()->json([
                'message' => 'Your package has expired',
            ], 422);
        }

        // Check if package has remaining sessions (quota > 0)
        $remainingSessions = $memberPackage->total_sessions - $memberPackage->used_sessions;
        if ($remainingSessions <= 0) {
            return response()->json([
                'message' => 'No remaining sessions in your package',
                'total_sessions' => $memberPackage->total_sessions,
                'used_sessions' => $memberPackage->used_sessions,
            ], 422);
        }

        // Get training session
        $trainingSession = TrainingSession::findOrFail($validated['training_session_id']);

        // Check if session is open
        if ($trainingSession->status->value !== 'open') {
            return response()->json([
                'message' => 'Training session is not open for booking',
                'session_status' => $trainingSession->status->value,
            ], 422);
        }

        // Check if session is in the future
        if ($trainingSession->date->isPast()) {
            return response()->json([
                'message' => 'Cannot book past sessions',
            ], 422);
        }

        // Check if session is full
        $currentBookings = SessionBooking::where('training_session_id', $trainingSession->id)
            ->where('status', 'confirmed')
            ->count();

        if ($currentBookings >= $trainingSession->max_participants) {
            return response()->json([
                'message' => 'Training session is full',
                'current_bookings' => $currentBookings,
                'max_participants' => $trainingSession->max_participants,
            ], 422);
        }

        // Check if already booked
        $existingBooking = SessionBooking::where('member_package_id', $memberPackage->id)
            ->where('training_session_id', $trainingSession->id)
            ->where('status', 'confirmed')
            ->exists();

        if ($existingBooking) {
            return response()->json([
                'message' => 'You have already booked this session',
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Create booking
            $booking = SessionBooking::create([
                'member_package_id' => $memberPackage->id,
                'training_session_id' => $trainingSession->id,
                'booked_by' => auth()->id(),
                'status' => 'confirmed',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Increment used_sessions
            $memberPackage->increment('used_sessions');

            DB::commit();

            return response()->json([
                'message' => 'Session booked successfully',
                'data' => $booking->load(['memberPackage.member', 'trainingSession.sessionTime', 'trainingSession.coach']),
                'remaining_sessions' => $memberPackage->fresh()->total_sessions - $memberPackage->fresh()->used_sessions,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to book session',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified booking
     */
    public function show(SessionBooking $sessionBooking)
    {
        // Verify booking belongs to the authenticated member
        $member = Member::where('user_id', auth()->id())
            ->where('is_self', true)
            ->first();
        
        if (!$member || $sessionBooking->memberPackage->member_id !== $member->id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        return response()->json($sessionBooking->load(['memberPackage.member', 'trainingSession.sessionTime', 'trainingSession.coach']));
    }

    /**
     * Cancel a booking
     */
    public function cancel(SessionBooking $sessionBooking)
    {
        // Verify booking belongs to the authenticated member
        $member = Member::where('user_id', auth()->id())
            ->where('is_self', true)
            ->first();
        
        if (!$member || $sessionBooking->memberPackage->member_id !== $member->id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        // Check if already cancelled
        if ($sessionBooking->status === 'cancelled') {
            return response()->json([
                'message' => 'Booking is already cancelled',
            ], 422);
        }

        // Check if session is in the future (can only cancel future sessions)
        if ($sessionBooking->trainingSession->date->isPast()) {
            return response()->json([
                'message' => 'Cannot cancel past sessions',
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Cancel booking
            $sessionBooking->cancel();

            // Decrement used_sessions (refund the session)
            $sessionBooking->memberPackage->decrement('used_sessions');

            DB::commit();

            return response()->json([
                'message' => 'Booking cancelled successfully',
                'data' => $sessionBooking->fresh()->load(['memberPackage.member', 'trainingSession']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to cancel booking',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
