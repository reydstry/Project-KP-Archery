<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Package;
use App\Models\TrainingSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MonthlyReportController extends Controller
{
    public function index(Request $request)
    {
        $month = max(1, min(12, (int) $request->integer('month', now()->month)));
        $year = max(2020, min(2100, (int) $request->integer('year', now()->year)));
        $packageId = $request->integer('package_id') ?: null;

        $sort = $request->string('sort', 'name')->toString();
        $direction = strtolower($request->string('direction', 'asc')->toString()) === 'desc' ? 'desc' : 'asc';

        $periodStart = Carbon::create($year, $month, 1)->startOfMonth();
        $periodEnd = $periodStart->copy()->endOfMonth();

        $totalSessions = TrainingSession::query()
            ->whereBetween('date', [$periodStart->toDateString(), $periodEnd->toDateString()])
            ->count();

        $membersQuery = Member::query()
            ->active()
            ->where('is_active', true)
            ->with([
                // Eager load package/class data used by report rows.
                'memberPackages' => function ($query) use ($periodStart, $periodEnd, $packageId) {
                    $query
                        ->with('package')
                        ->where(function ($dateQuery) use ($periodStart, $periodEnd) {
                            $dateQuery
                                ->whereDate('start_date', '<=', $periodEnd->toDateString())
                                ->whereDate('end_date', '>=', $periodStart->toDateString());
                        })
                        ->when($packageId, function ($packageQuery) use ($packageId) {
                            $packageQuery->where('package_id', $packageId);
                        })
                        ->orderByDesc('is_active')
                        ->orderByDesc('start_date');
                },
                // Eager load member -> sessions -> attendance for selected month.
                'attendances' => function ($query) use ($periodStart, $periodEnd) {
                    $query
                        ->whereHas('session', function ($sessionQuery) use ($periodStart, $periodEnd) {
                            $sessionQuery->whereBetween('date', [$periodStart->toDateString(), $periodEnd->toDateString()]);
                        })
                        ->with('session:id,date,status');
                },
            ]);

        if ($packageId) {
            $membersQuery->whereHas('memberPackages', function ($query) use ($packageId, $periodStart, $periodEnd) {
                $query
                    ->where('package_id', $packageId)
                    ->whereDate('start_date', '<=', $periodEnd->toDateString())
                    ->whereDate('end_date', '>=', $periodStart->toDateString());
            });
        }

        $members = $membersQuery->orderBy('name')->get();

        $rows = $members->map(function (Member $member) use ($totalSessions) {
            $selectedPackage = $member->memberPackages->firstWhere('is_active', true) ?: $member->memberPackages->first();
            $attendedSessions = $member->attendances->count();
            $attendanceRate = $totalSessions > 0
                ? round(($attendedSessions / $totalSessions) * 100, 1)
                : 0.0;

            $totalSlots = (int) ($selectedPackage?->total_sessions ?? 0);
            $usedSlots = (int) ($selectedPackage?->used_sessions ?? 0);
            $remainingSlots = max(0, $totalSlots - $usedSlots);

            return [
                'member_id' => $member->id,
                'member_name' => $member->name,
                'package_name' => $selectedPackage?->package?->name ?? '-',
                'is_package_active' => (bool) ($selectedPackage?->is_active ?? false),
                'total_sessions' => $totalSessions,
                'attended_sessions' => $attendedSessions,
                'attendance_rate' => $attendanceRate,
                'used_slots' => $usedSlots,
                'remaining_slots' => $remainingSlots,
                'is_low_attendance' => $attendanceRate < 50,
            ];
        });

        $rows = $this->sortRows($rows, $sort, $direction)->values();

        $totalMembers = $rows->count();
        $totalAttendance = (int) $rows->sum('attended_sessions');
        $averageAttendance = ($totalMembers > 0 && $totalSessions > 0)
            ? round(($totalAttendance / ($totalMembers * $totalSessions)) * 100, 1)
            : 0.0;

        $summary = [
            'total_members' => $totalMembers,
            'total_sessions' => $totalSessions,
            'average_attendance' => $averageAttendance,
            'members_trained' => $members->filter(fn($m) => $m->attended_sessions > 0)->count(),
        ];

        $packages = Package::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('dashboards.admin.reports.monthly', [
            'rows' => $rows,
            'summary' => $summary,
            'packages' => $packages,
            'filters' => [
                'month' => $month,
                'year' => $year,
                'package_id' => $packageId,
                'sort' => $sort,
                'direction' => $direction,
            ],
        ]);
    }

    private function sortRows(Collection $rows, string $sort, string $direction): Collection
    {
        $sortMap = [
            'name' => 'member_name',
            'package' => 'package_name',
            'total_sessions' => 'total_sessions',
            'attended_sessions' => 'attended_sessions',
            'remaining_slots' => 'remaining_slots',
            'attendance_rate' => 'attendance_rate',
        ];

        $sortKey = $sortMap[$sort] ?? 'member_name';
        $sorted = $rows->sortBy($sortKey, SORT_NATURAL | SORT_FLAG_CASE);

        return $direction === 'desc' ? $sorted->reverse() : $sorted;
    }
}
