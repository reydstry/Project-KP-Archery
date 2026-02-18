@extends('layouts.admin')

@section('title', 'Training Session')
@section('subtitle', 'Kelola tanggal sesi dan status tanpa slot/attendance')

@section('content')
<div class="space-y-4" x-data="trainingSessionsPage()" x-init="init()">
    <div class="bg-white border border-slate-200 rounded-xl p-4 grid grid-cols-1 md:grid-cols-4 gap-3">
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
            <select x-model="filters.status" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                <option value="">Semua Status</option>
                <option value="open">Scheduled/Ongoing</option>
                <option value="closed">Completed</option>
                <option value="canceled">Cancelled</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Mulai</label>
            <input type="date" x-model="filters.start_date" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Selesai</label>
            <input type="date" x-model="filters.end_date" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
        </div>
        <div class="flex items-end gap-2">
            <button @click="loadSessions()" :disabled="loading" class="px-4 py-2 rounded-lg bg-[#1a307b] text-white text-sm font-semibold disabled:opacity-60">Filter</button>
            <button @click="resetFilters()" :disabled="loading" class="px-4 py-2 rounded-lg border border-slate-300 text-sm font-semibold text-slate-700 disabled:opacity-60">Reset</button>
        </div>
    </div>

    <div class="flex justify-end">
        <a href="{{ route('admin.sessions.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-[#1a307b] hover:bg-[#162a69] text-white text-sm font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Session
        </a>
    </div>

    <x-table :headers="['Tanggal', 'Status', 'Aksi']">
        <template x-for="session in sessions" :key="session.id">
            <tr>
                <td class="px-4 py-3">
                    <p class="font-semibold text-slate-800" x-text="session.date"></p>
                    <p class="text-xs text-slate-500" x-text="`Session #${session.id}`"></p>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-md border text-xs font-semibold"
                          :class="session.status==='open' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : (session.status==='closed' ? 'bg-slate-100 text-slate-700 border-slate-200' : 'bg-red-50 text-red-700 border-red-200')"
                          x-text="statusLabel(session.status)"></span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex flex-wrap gap-2">
                        <a :href="`/admin/sessions/${session.id}/edit`" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700">Edit Tanggal/Status</a>
                        <a :href="`/admin/training/slots?session=${session.id}`" class="px-3 py-1.5 rounded-lg border border-[#1a307b]/20 bg-[#1a307b]/10 text-xs font-semibold text-[#1a307b]">Slot & Coach</a>
                        <a :href="`/admin/sessions/${session.id}/attendance`" class="px-3 py-1.5 rounded-lg border border-emerald-200 bg-emerald-50 text-xs font-semibold text-emerald-700">Attendance</a>
                        <button @click="changeStatus(session, nextStatus(session.status))" :disabled="submittingIds.includes(session.id)" class="px-3 py-1.5 rounded-lg border border-amber-200 bg-amber-50 text-xs font-semibold text-amber-700 disabled:opacity-60">Change Status</button>
                        <button @click="deleteSession(session)" :disabled="submittingIds.includes(session.id)" class="px-3 py-1.5 rounded-lg border border-red-200 bg-red-50 text-xs font-semibold text-red-700 disabled:opacity-60">Delete</button>
                    </div>
                </td>
            </tr>
        </template>
    </x-table>

    <p x-show="loading" class="text-sm text-slate-500">Memuat session...</p>
</div>

@push('scripts')
<script>
function trainingSessionsPage() {
    return {
        sessions: [],
        loading: false,
        submittingIds: [],
        filters: {
            status: '',
            start_date: '',
            end_date: '',
        },
        async init() {
            await this.loadSessions();
        },
        async loadSessions() {
            this.loading = true;
            try {
                const params = new URLSearchParams();
                if (this.filters.status) params.set('status', this.filters.status);
                if (this.filters.start_date) params.set('start_date', this.filters.start_date);
                if (this.filters.end_date) params.set('end_date', this.filters.end_date);

                const suffix = params.toString() ? `?${params.toString()}` : '';
                const result = await window.API.get(`/admin/training-sessions${suffix}`);
                this.sessions = Array.isArray(result?.data)
                    ? result.data.map((item) => ({ id: item.id, date: (item.date || '').toString().slice(0,10), status: item.status || 'open' }))
                    : [];
            } catch (error) {
                window.showToast?.(error?.message || 'Gagal memuat training sessions.', 'error');
            } finally {
                this.loading = false;
            }
        },
        resetFilters() {
            this.filters = { status: '', start_date: '', end_date: '' };
            this.loadSessions();
        },
        statusLabel(status) {
            if (status === 'open') return 'Scheduled/Ongoing';
            if (status === 'closed') return 'Completed';
            if (status === 'canceled') return 'Cancelled';
            return status;
        },
        nextStatus(status) {
            if (status === 'open') return 'closed';
            if (status === 'closed') return 'canceled';
            return 'open';
        },
        async changeStatus(session, status) {
            if (!session?.id || !status) return;

            this.submittingIds = [...this.submittingIds, session.id];
            try {
                await window.API.patch(`/admin/training-sessions/${session.id}/status`, { status });
                window.showToast('Status session berhasil diubah.', 'success');
                await this.loadSessions();
            } catch (error) {
                window.showToast(error?.message || 'Gagal mengubah status session.', 'error');
            } finally {
                this.submittingIds = this.submittingIds.filter((id) => id !== session.id);
            }
        },
        async deleteSession(session) {
            if (!session?.id) return;

            const confirmed = window.confirm(`Hapus Session #${session.id}?`);
            if (!confirmed) return;

            this.submittingIds = [...this.submittingIds, session.id];
            try {
                const response = await window.API.delete(`/admin/training-sessions/${session.id}`);
                window.showToast(response?.message || 'Session berhasil diproses.', 'success');
                await this.loadSessions();
            } catch (error) {
                window.showToast(error?.message || 'Gagal menghapus session.', 'error');
            } finally {
                this.submittingIds = this.submittingIds.filter((id) => id !== session.id);
            }
        },
    }
}
</script>
@endpush
@endsection
