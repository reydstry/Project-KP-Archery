<?php

namespace App\Services\Admin;

use App\DTO\Admin\Report\ReportDatasetDTO;
use App\DTO\Admin\Report\ReportPeriodDTO;
use App\DTO\Admin\Report\ReportSummaryDTO;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function monthlyDataset(int $month, int $year): ReportDatasetDTO
    {
        $date = Carbon::create($year, $month, 1);
        $period = new ReportPeriodDTO(
            startDate: $date->copy()->startOfMonth()->toDateString(),
            endDate: $date->copy()->endOfMonth()->toDateString(),
        );

        return new ReportDatasetDTO(
            mode: 'monthly',
            period: $period,
            summary: $this->summary($period),
        );
    }

    public function weeklyDataset(string $startDate, string $endDate): ReportDatasetDTO
    {
        $period = new ReportPeriodDTO(
            startDate: Carbon::parse($startDate)->toDateString(),
            endDate: Carbon::parse($endDate)->toDateString(),
        );

        return new ReportDatasetDTO(
            mode: 'weekly',
            period: $period,
            summary: $this->summary($period),
        );
    }

    public function memberRecapQuery(ReportPeriodDTO $period): Builder
    {
        $latestMemberPackageSubQuery = DB::table('member_packages as mp_latest')
            ->selectRaw('mp_latest.member_id, MAX(mp_latest.id) as latest_member_package_id')
            ->whereDate('mp_latest.start_date', '<=', $period->endDate)
            ->groupBy('mp_latest.member_id');

        return DB::table('members as m')
            ->leftJoinSub($latestMemberPackageSubQuery, 'latest_mp', function ($join) {
                $join->on('latest_mp.member_id', '=', 'm.id');
            })
            ->leftJoin('member_packages as mp', 'mp.id', '=', 'latest_mp.latest_member_package_id')
            ->leftJoin('packages as p', 'p.id', '=', 'mp.package_id')
            ->leftJoinSub($this->attendanceCountSubQuery($period->startDate, $period->endDate), 'attendance_summary', function ($join) {
                $join->on('attendance_summary.member_id', '=', 'm.id');
            })
            ->select([
                'm.id as member_id',
                'm.name as member_name',
                'm.status as member_status',
                'p.name as package_name',
                'mp.start_date',
                'mp.end_date',
                'mp.total_sessions',
                'mp.used_sessions',
                DB::raw('COALESCE(attendance_summary.total_attendance, 0) as total_attendance'),
                DB::raw('GREATEST(COALESCE(mp.total_sessions, 0) - COALESCE(mp.used_sessions, 0), 0) as remaining_quota'),
            ])
            ->orderBy('m.name');
    }

    public function summary(ReportPeriodDTO $period): ReportSummaryDTO
    {
        $totalMemberAktif = DB::table('members')
            ->where('is_active', true)
            ->where('status', 'active')
            ->count();

        $totalSesiPeriode = DB::table('training_sessions')
            ->whereBetween('date', [$period->startDate, $period->endDate])
            ->count();

        $totalAttendancePresent = DB::table('attendances as a')
            ->join('training_sessions as ts', 'ts.id', '=', 'a.session_id')
            ->whereBetween('ts.date', [$period->startDate, $period->endDate])
            ->count();

        return new ReportSummaryDTO(
            totalMemberAktif: $totalMemberAktif,
            totalSesiPeriode: $totalSesiPeriode,
            totalAttendancePresent: $totalAttendancePresent,
            totalAttendanceAbsent: 0,
        );
    }

    public function monthlyRecap(CarbonInterface $month, int $perPage = 100): LengthAwarePaginator
    {
        $monthStart = $month->copy()->startOfMonth()->toDateString();
        $monthEnd = $month->copy()->endOfMonth()->toDateString();

        return DB::table('member_packages as mp')
            ->join('members as m', 'm.id', '=', 'mp.member_id')
            ->join('packages as p', 'p.id', '=', 'mp.package_id')
            ->leftJoinSub($this->attendanceCountSubQuery($monthStart, $monthEnd), 'attendance_summary', function ($join) {
                $join->on('attendance_summary.member_id', '=', 'm.id');
            })
            ->select([
                'mp.id as member_package_id',
                'm.id as member_id',
                'm.name as member_name',
                'p.id as package_id',
                'p.name as package_name',
                'mp.start_date',
                'mp.end_date',
                'mp.is_active',
                'mp.total_sessions',
                'mp.used_sessions',
                DB::raw('COALESCE(attendance_summary.total_attendance, 0) as total_attendance'),
            ])
            ->orderBy('m.name')
            ->orderBy('mp.end_date')
            ->paginate($perPage);
    }

    private function attendanceCountSubQuery(string $monthStart, string $monthEnd): Builder
    {
        return DB::table('attendances as a')
            ->join('training_sessions as ts', 'ts.id', '=', 'a.session_id')
            ->whereBetween('ts.date', [$monthStart, $monthEnd])
            ->groupBy('a.member_id')
            ->selectRaw('a.member_id, COUNT(*) as total_attendance');
    }
}
