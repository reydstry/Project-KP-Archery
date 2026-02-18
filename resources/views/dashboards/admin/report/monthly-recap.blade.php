@extends('layouts.admin')

@section('title', 'Rekap Bulanan')
@section('subtitle', 'Ringkasan performa member dan attendance bulanan')

@section('content')
<div class="space-y-4" x-data="monthlyRecapPage()">
    <div class="bg-white border border-slate-200 rounded-xl p-4 grid grid-cols-1 md:grid-cols-4 gap-3">
        <x-form-input label="Bulan" name="month" type="number" min="1" max="12" x-model="month" />
        <x-form-input label="Tahun" name="year" type="number" min="2020" max="2100" x-model="year" />
        <div class="md:col-span-2 flex items-end">
            <button class="w-full px-4 py-2.5 rounded-lg bg-[#1a307b] text-white text-sm font-semibold">Tampilkan Rekap</button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <x-stat-card title="Total Member Aktif" tone="blue">
            <span x-text="summary.total_active_members"></span>
        </x-stat-card>
        <x-stat-card title="Total Sesi Bulan Ini" tone="amber">
            <span x-text="summary.total_sessions"></span>
        </x-stat-card>
        <x-stat-card title="Total Attendance" tone="green">
            <span x-text="summary.total_attendance"></span>
        </x-stat-card>
    </div>

    <x-table :headers="['Nama Member', 'Paket', 'Total Hadir', 'Status Paket']">
        <template x-for="item in rows" :key="item.id">
            <tr>
                <td class="px-4 py-3 font-semibold text-slate-800" x-text="item.name"></td>
                <td class="px-4 py-3 text-slate-700" x-text="item.package"></td>
                <td class="px-4 py-3 text-slate-700" x-text="item.total_present"></td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-md border text-xs font-semibold"
                          :class="item.package_status === 'aktif' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200'"
                          x-text="item.package_status"></span>
                </td>
            </tr>
        </template>
    </x-table>
</div>

@push('scripts')
<script>
function monthlyRecapPage() {
    return {
        month: 2,
        year: 2026,
        summary: {
            total_active_members: 187,
            total_sessions: 38,
            total_attendance: 624,
        },
        rows: [
            { id: 1, name: 'Rudi Hartono', package: 'Regular 12x', total_present: 9, package_status: 'aktif' },
            { id: 2, name: 'Alya Putri', package: 'Premium 16x', total_present: 14, package_status: 'aktif' },
            { id: 3, name: 'Bima Sakti', package: 'Trial', total_present: 2, package_status: 'nonaktif' },
        ]
    }
}
</script>
@endpush
@endsection
