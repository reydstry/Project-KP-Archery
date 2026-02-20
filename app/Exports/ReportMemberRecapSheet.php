<?php

namespace App\Exports;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportMemberRecapSheet implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
{
    public function __construct(
        private readonly Builder $query,
    ) {
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function title(): string
    {
        return 'Rekap Member';
    }

    public function headings(): array
    {
        return [
            'Nama Member',
            'Status Member',
            'Nama Paket',
            'Tanggal Mulai Paket',
            'Tanggal Expired Paket',
            'Total Kehadiran',
            'Sisa Kuota',
        ];
    }

    public function map($row): array
    {
        return [
            $row->member_name,
            $row->member_status,
            $row->package_name ?? '-',
            $row->start_date,
            $row->end_date,
            (int) $row->total_attendance,
            (int) $row->remaining_quota,
        ];
    }
}
