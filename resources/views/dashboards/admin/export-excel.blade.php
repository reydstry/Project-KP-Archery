@extends('admin.app')

@section('title', 'Export Excel')
@section('subtitle', 'Ekspor data report bulanan atau mingguan ke format Excel')

@section('content')
<div class="space-y-4" x-data="exportExcelPage()">
    <div class="bg-white border border-slate-200 rounded-xl p-4 grid grid-cols-1 md:grid-cols-4 gap-3">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Mode</label>
            <select x-model="mode" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                <option value="monthly">Bulanan</option>
                <option value="weekly">Mingguan</option>
            </select>
        </div>

        <template x-if="mode === 'monthly'">
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                <x-form-input label="Bulan" name="month" type="number" min="1" max="12" x-model="month" />
                <x-form-input label="Tahun" name="year" type="number" min="2020" max="2100" x-model="year" />
            </div>
        </template>

        <template x-if="mode === 'weekly'">
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                <x-form-input label="Tanggal Mulai" name="start_date" type="date" x-model="startDate" />
                <x-form-input label="Tanggal Akhir" name="end_date" type="date" x-model="endDate" />
            </div>
        </template>

        <div class="flex items-end">
            <button @click="exportReport()" class="w-full px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold">
                Export Excel
            </button>
        </div>
    </div>

    <x-alert-box type="info" title="Informasi Export">
        Data yang diekspor mencakup member, paket aktif, total kehadiran bulanan, dan status paket pada periode terpilih.
    </x-alert-box>
</div>

@push('scripts')
<script>
function exportExcelPage() {
    const now = new Date();
    const pad = (value) => String(value).padStart(2, '0');
    const currentDate = `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())}`;

    return {
        mode: 'monthly',
        month: now.getMonth() + 1,
        year: now.getFullYear(),
        startDate: currentDate,
        endDate: currentDate,
        exportReport() {
            const params = new URLSearchParams();
            params.set('mode', this.mode);

            if (this.mode === 'weekly') {
                params.set('start_date', this.startDate);
                params.set('end_date', this.endDate);
            } else {
                params.set('month', this.month);
                params.set('year', this.year);
            }

            const url = `/api/admin/reports/export?${params.toString()}`;
            window.location.href = url;
        }
    }
}
</script>
@endpush
@endsection
