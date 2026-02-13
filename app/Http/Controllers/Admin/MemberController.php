<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\StatusMember;
use App\Enums\UserRoles;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of members.
     */
    public function index()
    {
        $members = Member::with(['user', 'registeredBy'])
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data members berhasil diambil',
            'data' => $members,
        ]);
    }

    /**
     * Store a newly created member.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'registered_by' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_self' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $member = Member::create($data);

        return response()->json([
            'message' => 'Member berhasil dibuat',
            'data' => $member->load(['user', 'registeredBy']),
        ], 201);
    }

    /**
     * Display the specified member.
     */
    public function show(Member $member)
    {
        $member->load(['user', 'registeredBy', 'memberPackages']);

        // Calculate training statistics
        $stats = $this->calculateMemberStats($member);

        return response()->json([
            'message' => 'Data member berhasil diambil',
            'data' => $member,
            'statistics' => $stats,
        ]);
    }

    /**
     * Calculate member training statistics
     */
    private function calculateMemberStats(Member $member)
    {
        $now = now();
        
        // Get all confirmed bookings with attendance records for this member
        $attendances = \App\Models\Attendance::whereHas('sessionBooking', function ($query) use ($member) {
            $query->whereHas('memberPackage', function ($q) use ($member) {
                $q->where('member_id', $member->id);
            })->where('status', 'confirmed');
        })
        ->where('status', 'present')
        ->with(['sessionBooking.trainingSessionSlot.trainingSession'])
        ->get();

        // Count trainings this week (Monday to Sunday)
        $thisWeekStart = $now->copy()->startOfWeek();
        $thisWeekEnd = $now->copy()->endOfWeek();
        $thisWeekCount = $attendances->filter(function ($attendance) use ($thisWeekStart, $thisWeekEnd) {
            $sessionDate = $attendance->sessionBooking?->trainingSessionSlot?->trainingSession?->date;
            return $sessionDate && $sessionDate->between($thisWeekStart, $thisWeekEnd);
        })->count();

        // Count trainings this month
        $thisMonthStart = $now->copy()->startOfMonth();
        $thisMonthEnd = $now->copy()->endOfMonth();
        $thisMonthCount = $attendances->filter(function ($attendance) use ($thisMonthStart, $thisMonthEnd) {
            $sessionDate = $attendance->sessionBooking?->trainingSessionSlot?->trainingSession?->date;
            return $sessionDate && $sessionDate->between($thisMonthStart, $thisMonthEnd);
        })->count();

        // Count trainings this year
        $thisYearStart = $now->copy()->startOfYear();
        $thisYearEnd = $now->copy()->endOfYear();
        $thisYearCount = $attendances->filter(function ($attendance) use ($thisYearStart, $thisYearEnd) {
            $sessionDate = $attendance->sessionBooking?->trainingSessionSlot?->trainingSession?->date;
            return $sessionDate && $sessionDate->between($thisYearStart, $thisYearEnd);
        })->count();

        // Calculate week streak (consecutive weeks with at least one training)
        $weekStreak = $this->calculateWeekStreak($attendances);

        return [
            'training_count_this_week' => $thisWeekCount,
            'training_count_this_month' => $thisMonthCount,
            'training_count_this_year' => $thisYearCount,
            'week_streak' => $weekStreak,
            'total_trainings' => $attendances->count(),
        ];
    }

    /**
     * Calculate consecutive weeks with training
     */
    private function calculateWeekStreak($attendances)
    {
        if ($attendances->isEmpty()) {
            return 0;
        }

        // Group attendances by week
        $weeklyAttendances = $attendances
            ->map(function ($attendance) {
                $sessionDate = $attendance->sessionBooking?->trainingSessionSlot?->trainingSession?->date;
                if (!$sessionDate) return null;
                return $sessionDate->copy()->startOfWeek()->toDateString();
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        if ($weeklyAttendances->isEmpty()) {
            return 0;
        }

        // Check if current week or last week has training
        $now = now();
        $currentWeekStart = $now->copy()->startOfWeek()->toDateString();
        $lastWeekStart = $now->copy()->subWeek()->startOfWeek()->toDateString();
        
        $hasCurrentWeek = $weeklyAttendances->contains($currentWeekStart);
        $hasLastWeek = $weeklyAttendances->contains($lastWeekStart);
        
        // If no training in current or last week, streak is 0
        if (!$hasCurrentWeek && !$hasLastWeek) {
            return 0;
        }

        // Start counting from current week or last week
        $streak = 0;
        $checkWeek = $hasCurrentWeek ? $currentWeekStart : $lastWeekStart;
        
        // Count backwards consecutive weeks
        while ($weeklyAttendances->contains($checkWeek)) {
            $streak++;
            $checkWeek = \Carbon\Carbon::parse($checkWeek)->subWeek()->startOfWeek()->toDateString();
        }

        return $streak;
    }

    /**
     * Update the specified member.
     */
    public function update(Request $request, Member $member)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_self' => ['boolean'],
            'is_active' => ['boolean'],
            'status' => ['sometimes', 'string', 'in:' . implode(',', [
                StatusMember::STATUS_PENDING->value,
                StatusMember::STATUS_ACTIVE->value,
                StatusMember::STATUS_INACTIVE->value,
            ])],
        ]);

        $member->update($data);

        return response()->json([
            'message' => 'Member berhasil diupdate',
            'data' => $member->fresh(['user', 'registeredBy']),
        ]);
    }

    /**
     * Soft delete member (set is_active = false).
     */
    public function destroy(Member $member)
    {
        $member->update([
            'is_active' => false,
            'status' => StatusMember::STATUS_INACTIVE->value,
        ]);

        return response()->json([
            'message' => 'Member berhasil dinonaktifkan',
        ]);
    }

    /**
     * Restore inactive member (set is_active = true).
     */
    public function restore($id)
    {
        $member = Member::findOrFail($id);
        $member->update([
            'is_active' => true,
            'status' => StatusMember::STATUS_ACTIVE->value,
        ]);

        return response()->json([
            'message' => 'Member berhasil diaktifkan kembali',
            'data' => $member->fresh(['user', 'registeredBy']),
        ]);
    }
}
