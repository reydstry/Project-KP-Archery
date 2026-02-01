<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\SessionBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Get attendance history (only from bookings with attendance records)
        $attendanceHistory = SessionBooking::where('member_package_id', function ($query) use ($member) {
            $query->select('id')
                ->from('member_packages')
                ->where('member_id', $member->id);
        })
            ->whereHas('attendance')
            ->with([
                'trainingSession.sessionTime',
                'trainingSession.coach',
                'attendance',
                'memberPackage.package'
            ])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'session_date' => $booking->trainingSession->date,
                    'session_time' => $booking->trainingSession->sessionTime->name ?? null,
                    'coach_name' => $booking->trainingSession->coach->name ?? null,
                    'package_name' => $booking->memberPackage->package->name ?? null,
                    'attendance_status' => $booking->attendance->status,
                    'validated_at' => $booking->attendance->validated_at,
                    'notes' => $booking->attendance->notes,
                ];
            });

        // Count attendance statistics
        $attendanceStats = [
            'total_attended' => SessionBooking::where('member_package_id', function ($query) use ($member) {
                $query->select('id')
                    ->from('member_packages')
                    ->where('member_id', $member->id);
            })
                ->whereHas('attendance', function ($q) {
                    $q->where('status', 'present');
                })
                ->count(),
            'total_absent' => SessionBooking::where('member_package_id', function ($query) use ($member) {
                $query->select('id')
                    ->from('member_packages')
                    ->where('member_id', $member->id);
            })
                ->whereHas('attendance', function ($q) {
                    $q->where('status', 'absent');
                })
                ->count(),
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
