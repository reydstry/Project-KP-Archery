<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MonthlyReportExport;
use App\Exports\WeeklyReportExport;
use App\Http\Controllers\Controller;
use App\Services\Admin\ReportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct(
        private readonly ReportService $reportService,
    ) {
    }

    public function export(Request $request)
    {
        $mode = $request->query('mode', 'monthly');

        if ($mode === 'weekly') {
            $validated = $request->validate([
                'mode' => ['required', 'in:weekly'],
                'start_date' => ['required', 'date'],
                'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            ]);

            $dataset = $this->reportService->weeklyDataset($validated['start_date'], $validated['end_date']);
            $recapQuery = $this->reportService->memberRecapQuery($dataset->period);
            $fileName = sprintf('report-weekly-%s-to-%s.xlsx', $dataset->period->startDate, $dataset->period->endDate);

            return Excel::download(new WeeklyReportExport($dataset, $recapQuery), $fileName);
        }

        $validated = $request->validate([
            'mode' => ['required', 'in:monthly'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'min:2020', 'max:2100'],
        ]);

        $dataset = $this->reportService->monthlyDataset((int) $validated['month'], (int) $validated['year']);
        $recapQuery = $this->reportService->memberRecapQuery($dataset->period);
        $fileName = sprintf('report-monthly-%04d-%02d.xlsx', (int) $validated['year'], (int) $validated['month']);

        return Excel::download(new MonthlyReportExport($dataset, $recapQuery), $fileName);
    }
}
