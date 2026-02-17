@extends('admin.app')

@section('title', 'Training Session')
@section('subtitle', 'Kelola tanggal sesi dan status tanpa slot/attendance')

@section('content')
<div class="space-y-4" x-data="trainingSessionsPage()" x-init="init()">
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
                          x-text="session.status"></span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex flex-wrap gap-2">
                        <a :href="`/admin/sessions/${session.id}/edit`" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700">Edit Tanggal/Status</a>
                        <a :href="`/admin/training/slots?session=${session.id}`" class="px-3 py-1.5 rounded-lg border border-[#1a307b]/20 bg-[#1a307b]/10 text-xs font-semibold text-[#1a307b]">Slot & Coach</a>
                        <a :href="`/admin/training/attendance?session=${session.id}`" class="px-3 py-1.5 rounded-lg border border-emerald-200 bg-emerald-50 text-xs font-semibold text-emerald-700">Attendance</a>
                    </div>
                </td>
            </tr>
        </template>
    </x-table>
</div>

@push('scripts')
<script>
function trainingSessionsPage() {
    return {
        sessions: [],
        async init() {
            try {
                const result = await window.API.get('/admin/training-sessions');
                this.sessions = Array.isArray(result?.data)
                    ? result.data.map((item) => ({ id: item.id, date: (item.date || '').toString().slice(0,10), status: item.status || 'open' }))
                    : [];
            } catch (error) {
                window.showToast?.(error?.message || 'Gagal memuat training sessions.', 'error');
            }
        }
    }
}
</script>
@endpush
@endsection
