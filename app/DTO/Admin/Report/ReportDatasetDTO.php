<?php

namespace App\DTO\Admin\Report;

final readonly class ReportDatasetDTO
{
    public function __construct(
        public string $mode,
        public ReportPeriodDTO $period,
        public ReportSummaryDTO $summary,
    ) {
    }
}
