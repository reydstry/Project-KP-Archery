<?php

namespace App\DTO\Admin\Report;

final readonly class ReportSummaryDTO
{
    public function __construct(
        public int $totalMemberAktif,
        public int $totalSesiPeriode,
        public int $totalAttendancePresent,
    ) {
    }
}
