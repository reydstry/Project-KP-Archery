@extends('admin.app')

@section('title', 'Log Pengiriman')
@section('subtitle', 'Audit hasil pengiriman WhatsApp')

@section('content')
<div class="space-y-4" x-data="waLogPage()" x-init="init()">
    <div class="bg-white border border-slate-200 rounded-xl p-4 grid grid-cols-1 md:grid-cols-3 gap-3">
        <x-form-input label="Dari Tanggal" name="from_date" type="date" x-model="fromDate" />
        <x-form-input label="Sampai Tanggal" name="to_date" type="date" x-model="toDate" />
        <div class="flex items-end gap-2">
            <button @click="loadLogs()" class="flex-1 px-4 py-2.5 rounded-lg bg-[#1a307b] text-white text-sm font-semibold">Filter</button>
            <a :href="exportUrl" class="flex-1 text-center px-4 py-2.5 rounded-lg border border-emerald-300 text-emerald-700 bg-emerald-50 text-sm font-semibold">Export</a>
        </div>
    </div>

    <x-table :headers="['Tanggal', 'Nomor', 'Status']">
        <template x-for="log in logs" :key="log.id">
            <tr>
                <td class="px-4 py-3 text-slate-700"
                x-text="log.sent_at 
                    ? new Date(log.sent_at).toISOString().split('T')[0] 
                    : '-'">
                </td>
                <td class="px-4 py-3 text-slate-700" x-text="log.phone"></td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-md border text-xs font-semibold"
                          :class="log.status === 'success' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200'"
                          x-text="log.status"></span>
                </td>
            </tr>
        </template>
    </x-table>

    <div class="text-sm text-slate-500" x-show="loading">Memuat log pengiriman...</div>
</div>

@push('scripts')
<script>
function waLogPage() {
    return {
        fromDate: '',
        toDate: '',
        logs: [],
        loading: false,
        init() {
            this.loadLogs();
        },
        get exportUrl() {
            const now = new Date();
            const month = String(now.getMonth() + 1);
            const year = String(now.getFullYear());
            return `/api/admin/whatsapp/logs/export?month=${month}&year=${year}`;
        },
        async loadLogs() {
            this.loading = true;
            try {
                const params = new URLSearchParams();
                if (this.fromDate) params.set('from', this.fromDate);
                if (this.toDate) params.set('to', this.toDate);

                const response = await window.API.get(`/admin/whatsapp/logs?${params.toString()}`);
                this.logs = Array.isArray(response?.data) ? response.data : [];
            } catch (error) {
                this.logs = [];
                window.showToast?.(error?.message || 'Gagal memuat log WA.', 'error');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endpush
@endsection
