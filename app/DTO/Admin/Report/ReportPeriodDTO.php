<?php

namespace App\DTO\Admin\Report;

final readonly class ReportPeriodDTO
{
    public function __construct(
        public string $startDate,
        public string $endDate,
    ) {
    }
}
