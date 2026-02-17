<?php

namespace App\Services\Admin;

use App\DTO\Admin\AdminDashboardData;
use App\Enums\StatusMember;
use App\Enums\TrainingSessionStatus;
use App\Models\Achievement;
use App\Models\Attendance;
use App\Models\Coach;
use App\Models\Member;
use App\Models\MemberPackage;
use App\Models\News;
use App\Models\Package;
use App\Models\TrainingSession;
use App\Models\TrainingSessionSlot;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminDashboardService
{
    private const GLOBAL_STATS_CACHE_KEY = 'admin:dashboard:global-stats';

    private const GLOBAL_STATS_CACHE_TTL_SECONDS = 300;

    public function getDashboardData(): AdminDashboardData
    {
        $today = CarbonImmutable::today();
        $now = CarbonImmutable::now();

        $this->autoClosePastOpenSessions($now);

        return new AdminDashboardData(
            statistics: $this->getGlobalStatistics($today),
            recentPendingMembers: $this->getRecentPendingMembers(),
            todaySessions: $this->getTodaySessions($today),
            alerts: [
                'expiring_packages' => $this->getExpiringPackagesAlert($today),
                'slots_nearly_full' => $this->getSlotsNearlyFullAlert($today),
            ],
            activityToday: $this->getActivityToday($today),
        );
    }

    private function autoClosePastOpenSessions(CarbonImmutable $now): void
    {
        $today = $now->toDateString();

        $shouldCloseToday = $now->hour > TrainingSession::AUTO_CLOSE_HOUR
            || ($now->hour === TrainingSession::AUTO_CLOSE_HOUR && $now->minute >= TrainingSession::AUTO_CLOSE_MINUTE);

        TrainingSession::query()
            ->where('status', TrainingSessionStatus::OPEN)
            ->where('date', $shouldCloseToday ? '<=' : '<', $today)
            ->update(['status' => TrainingSessionStatus::CLOSED]);
    }

    private function getGlobalStatistics(CarbonImmutable $today): array
    {
        return Cache::remember(self::GLOBAL_STATS_CACHE_KEY, self::GLOBAL_STATS_CACHE_TTL_SECONDS, function () use ($today) {
            $weekAhead = $today->addDays(7)->toDateString();
            $todayDate = $today->toDateString();

            return [
                'total_members' => Member::query()->count(),
                'active_members' => Member::query()->where('status', StatusMember::STATUS_ACTIVE)->count(),
                'pending_members' => Member::query()->where('status', StatusMember::STATUS_PENDING)->count(),
                'total_coaches' => Coach::query()->count(),
                'total_packages' => Package::query()->count(),
                'total_news' => News::query()->count(),
                'total_achievements' => Achievement::query()->count(),
                'packages_expiring_7_days' => MemberPackage::query()
                    ->active()
                    ->whereBetween('end_date', [$todayDate, $weekAhead])
                    ->count(),
                'today_sessions' => TrainingSession::query()->whereDate('date', $todayDate)->count(),
                'upcoming_sessions' => TrainingSession::query()->whereDate('date', '>=', $todayDate)->count(),
                'total_sessions' => TrainingSession::query()->count(),
                'today_bookings' => 0,
                'today_attendance' => Attendance::query()
                    ->whereHas('session', function ($query) use ($todayDate) {
                        $query->whereDate('date', $todayDate);
                    })
                    ->count(),
            ];
        });
    }

    private function getRecentPendingMembers(): array
    {
        return Member::query()
            ->pending()
            ->latest('created_at')
            ->limit(5)
            ->get(['id', 'name', 'phone', 'status', 'created_at'])
            ->map(fn (Member $member) => [
                'id' => $member->id,
                'name' => $member->name,
                'phone' => $member->phone,
                'status' => $member->status,
                'created_at' => $member->created_at,
            ])
            ->all();
    }

    private function getTodaySessions(CarbonImmutable $today): array
    {
        return TrainingSession::query()
            ->whereDate('date', $today->toDateString())
            ->withCount('attendances')
            ->with([
                'slots:id,training_session_id,session_time_id,max_participants,created_at,updated_at',
                'slots.sessionTime:id,name,start_time,end_time',
                'slots.coaches:id,name',
                'attendances:id,session_id,member_id,created_at',
                'attendances.member:id,name',
            ])
            ->get(['id', 'date', 'status'])
            ->flatMap(function (TrainingSession $session): Collection {
                $members = $session->attendances
                    ->map(fn (Attendance $attendance) => [
                        'id' => $attendance->member?->id,
                        'name' => $attendance->member?->name,
                    ])
                    ->filter(fn (array $member) => !empty($member['id']))
                    ->values();

                return $session->slots
                    ->sortBy('session_time_id')
                    ->values()
                    ->map(function (TrainingSessionSlot $slot) use ($session, $members) {
                        return [
                            'id' => $slot->id,
                            'training_session_id' => $slot->training_session_id,
                            'date' => $session->date,
                            'status' => $session->status?->value,
                            'max_participants' => $slot->max_participants,
                            'capacity' => $slot->max_participants,
                            'total_bookings' => (int) ($session->attendances_count ?? 0),
                            'total_attendance' => (int) ($session->attendances_count ?? 0),
                            'coaches' => $slot->coaches
                                ->map(fn ($coach) => [
                                    'id' => $coach->id,
                                    'name' => $coach->name,
                                ])
                                ->values(),
                            'members' => $members,
                            'session_time' => [
                                'id' => $slot->sessionTime?->id,
                                'session_name' => $slot->sessionTime?->name,
                                'start_time' => $slot->sessionTime?->start_time,
                                'end_time' => $slot->sessionTime?->end_time,
                            ],
                        ];
                    });
            })
            ->values()
            ->all();
    }

    private function getExpiringPackagesAlert(CarbonImmutable $today): array
    {
        $weekAhead = $today->addDays(7)->toDateString();

        return MemberPackage::query()
            ->active()
            ->whereBetween('end_date', [$today->toDateString(), $weekAhead])
            ->with([
                'member:id,name,phone',
                'package:id,name',
            ])
            ->orderBy('end_date')
            ->limit(10)
            ->get(['id', 'member_id', 'package_id', 'end_date'])
            ->map(fn (MemberPackage $memberPackage) => [
                'member_package_id' => $memberPackage->id,
                'member_id' => $memberPackage->member_id,
                'member_name' => $memberPackage->member?->name,
                'member_phone' => $memberPackage->member?->phone,
                'package_name' => $memberPackage->package?->name,
                'end_date' => $memberPackage->end_date?->toDateString(),
            ])
            ->all();
    }

    private function getSlotsNearlyFullAlert(CarbonImmutable $today): array
    {
        $attendanceSummarySubQuery = DB::table('attendances')
            ->selectRaw('session_id, COUNT(*) as total_attendance')
            ->groupBy('session_id');

        return DB::table('training_session_slots as tss')
            ->join('training_sessions as ts', 'ts.id', '=', 'tss.training_session_id')
            ->leftJoin('session_times as st', 'st.id', '=', 'tss.session_time_id')
            ->leftJoinSub($attendanceSummarySubQuery, 'attendance_summary', function ($join) {
                $join->on('attendance_summary.session_id', '=', 'ts.id');
            })
            ->whereDate('ts.date', $today->toDateString())
            ->whereIn('ts.status', [
                TrainingSessionStatus::OPEN->value,
                TrainingSessionStatus::CLOSED->value,
            ])
            ->where('tss.max_participants', '>', 0)
            ->select([
                'tss.id as slot_id',
                'tss.training_session_id',
                'ts.date',
                'st.name as session_name',
                'st.start_time',
                'st.end_time',
                'tss.max_participants as capacity',
                DB::raw('COALESCE(attendance_summary.total_attendance, 0) as booked'),
                DB::raw('(COALESCE(attendance_summary.total_attendance, 0) / tss.max_participants) as occupancy_rate'),
            ])
            ->whereRaw('(COALESCE(attendance_summary.total_attendance, 0) / tss.max_participants) >= 0.8')
            ->orderByDesc('occupancy_rate')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'slot_id' => (int) $row->slot_id,
                'training_session_id' => (int) $row->training_session_id,
                'date' => $row->date,
                'session_name' => $row->session_name,
                'start_time' => $row->start_time,
                'end_time' => $row->end_time,
                'booked' => (int) $row->booked,
                'capacity' => (int) $row->capacity,
            ])
            ->all();
    }

    private function getActivityToday(CarbonImmutable $today): array
    {
        $todayDate = $today->toDateString();

        $slotNameSubQuery = DB::table('training_session_slots as tss')
            ->leftJoin('session_times as st', 'st.id', '=', 'tss.session_time_id')
            ->selectRaw('tss.training_session_id, MIN(st.name) as session_name')
            ->groupBy('tss.training_session_id');

        $attendanceActivities = DB::table('attendances as a')
            ->join('members as m', 'm.id', '=', 'a.member_id')
            ->join('training_sessions as ts', 'ts.id', '=', 'a.session_id')
            ->leftJoinSub($slotNameSubQuery, 'slot_name', function ($join) {
                $join->on('slot_name.training_session_id', '=', 'a.session_id');
            })
            ->whereDate('ts.date', $todayDate)
            ->orderByDesc('a.created_at')
            ->limit(10)
            ->get([
                'a.id',
                'm.name as member_name',
                'slot_name.session_name',
                'a.created_at as timestamp',
            ])
            ->map(fn ($attendance) => [
                'type' => 'attendance',
                'id' => (int) $attendance->id,
                'member_name' => $attendance->member_name,
                'session_name' => $attendance->session_name,
                'status' => 'present',
                'timestamp' => $attendance->timestamp,
            ]);

        return $attendanceActivities
            ->sortByDesc('timestamp')
            ->take(15)
            ->values()
            ->map(function (array $activity) {
                    $timestamp = $activity['timestamp'] ?? null;

                    if ($timestamp instanceof CarbonInterface) {
                        $activity['timestamp'] = $timestamp->toIso8601String();
                    } elseif (is_string($timestamp) && $timestamp !== '') {
                        $activity['timestamp'] = CarbonImmutable::parse($timestamp)->toIso8601String();
                    } else {
                        $activity['timestamp'] = null;
                    }

                return $activity;
            })
            ->all();
    }
}
