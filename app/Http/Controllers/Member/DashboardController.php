<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Member;

class DashboardController extends Controller
{
    /**
     * Get member dashboard data
     */
    public function index()
    {
        $user = auth()->user();

        // Get member profile that belongs to the authenticated user (is_self = true)
        $member = Member::where('user_id', $user->id)
            ->where('is_self', true)
            ->first();

        if (!$member) {
            return response()->json([
                'message' => 'Member profile not found. Please register as member first.',
            ], 404);
        }

        // Get active member package with remaining quota
        $activePackage = $member->memberPackages()
            ->where('is_active', true)
            ->where('end_date', '>=', now())
            ->whereHas('package', function ($q) {
                $q->where('is_active', true);
            })
            ->with('package')
            ->first();

        $quotaData = null;
        if ($activePackage) {
            $quotaData = [
                'package_name' => $activePackage->package->name,
                'total_sessions' => $activePackage->total_sessions,
                'used_sessions' => $activePackage->used_sessions,
                'remaining_sessions' => $activePackage->total_sessions - $activePackage->used_sessions,
                'start_date' => $activePackage->start_date,
                'end_date' => $activePackage->end_date,
                'days_remaining' => now()->diffInDays($activePackage->end_date, false),
            ];
        }

        $attendanceHistory = Attendance::query()
            ->where('member_id', $member->id)
            ->with([
                'session.slots.sessionTime',
                'session.slots.coaches',
            ])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($attendance) use ($activePackage) {
                $session = $attendance->session;
                $firstSlot = $session?->slots?->first();
                $sessionTime = $firstSlot?->sessionTime;
                $coachNames = $firstSlot?->coaches
                    ? $firstSlot->coaches->pluck('name')->filter()->values()->all()
                    : [];

                return [
                    'id' => $attendance->id,
                    'session_date' => $session?->date,
                    'session_time' => $sessionTime?->name,
                    'coach_name' => !empty($coachNames) ? implode(', ', $coachNames) : '-',
                    'package_name' => $activePackage?->package?->name,
                    'attendance_status' => 'present',
                    'validated_at' => $attendance->created_at,
                    'notes' => null,
                ];
            });

        $totalAttended = Attendance::query()
            ->where('member_id', $member->id)
            ->count();

        $attendanceStats = [
            'total_attended' => $totalAttended,
            'total_absent' => 0,
        ];

        // Get achievements
        $achievements = $member->achievements()
            ->where('type', 'member')
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($achievement) {
                return [
                    'id' => $achievement->id,
                    'type' => $achievement->type,
                    'title' => $achievement->title,
                    'description' => $achievement->description,
                    'date' => $achievement->date,
                    'photo_path' => $achievement->photo_path,
                ];
            });

        return response()->json([
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'status' => $member->status,
            ],
            'quota' => $quotaData,
            'attendance' => [
                'history' => $attendanceHistory,
                'statistics' => $attendanceStats,
            ],
            'achievements' => $achievements,
        ]);
    }
}
