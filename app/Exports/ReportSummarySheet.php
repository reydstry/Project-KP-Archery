<?php

namespace App\Exports;

use App\DTO\Admin\Report\ReportSummaryDTO;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportSummarySheet implements FromArray, ShouldAutoSize, WithTitle
{
    public function __construct(
        private readonly string $mode,
        private readonly string $periodLabel,
        private readonly ReportSummaryDTO $summary,
    ) {
    }

    public function title(): string
    {
        return 'Summary';
    }

    public function array(): array
    {
        return [
            ['Mode', strtoupper($this->mode)],
            ['Periode', $this->periodLabel],
            ['Total Member Aktif', $this->summary->totalMemberAktif],
            ['Total Sesi Periode', $this->summary->totalSesiPeriode],
            ['Total Attendance Hadir', $this->summary->totalAttendancePresent],
            ['Total Attendance Absen', $this->summary->totalAttendanceAbsent],
        ];
    }
}
