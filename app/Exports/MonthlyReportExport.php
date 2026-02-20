<?php

namespace App\Exports;

use App\DTO\Admin\Report\ReportDatasetDTO;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MonthlyReportExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(
        private readonly ReportDatasetDTO $dataset,
        private readonly Builder $recapQuery,
    ) {
    }

    public function sheets(): array
    {
        return [
            new ReportMemberRecapSheet($this->recapQuery),
            new ReportSummarySheet('monthly', $this->dataset->period->startDate . ' s/d ' . $this->dataset->period->endDate, $this->dataset->summary),
        ];
    }
}
