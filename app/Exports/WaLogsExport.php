<?php

namespace App\Exports;

use App\Models\WaLog;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WaLogsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(
        private readonly int $month,
        private readonly int $year,
    ) {
    }

    public function collection()
    {
        return WaLog::query()
            ->when($this->month > 0, fn (Builder $query) => $query->whereMonth('sent_at', $this->month))
            ->when($this->year > 0, fn (Builder $query) => $query->whereYear('sent_at', $this->year))
            ->orderByDesc('sent_at')
            ->get(['sent_at', 'phone', 'message', 'status', 'response'])
            ->map(fn (WaLog $log) => [
                'sent_at' => $log->sent_at?->format('Y-m-d H:i:s'),
                'phone' => $log->phone,
                'message' => $log->message,
                'status' => $log->status,
                'response' => $log->response,
            ]);
    }

    public function headings(): array
    {
        return ['sent_at', 'phone', 'message', 'status', 'response'];
    }
}
