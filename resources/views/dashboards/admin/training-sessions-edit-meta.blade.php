@extends('admin.app')

@section('title', 'Edit Training Session')
@section('subtitle', 'Update tanggal dan status sesi')

@section('content')
<div class="space-y-4" x-data="trainingSessionEditMetaPage({{ (int)$id }})" x-init="init()">
    <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-4 max-w-2xl">
        <x-form-input label="ID Session" name="session_id" type="text" :value="$id" readonly />
        <x-form-input label="Tanggal Sesi" name="date" type="date" required x-model="form.date" />

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
            <select x-model="form.status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#1a307b]/30">
                <option value="open">Open</option>
                <option value="closed">Closed</option>
                <option value="canceled">Canceled</option>
            </select>
        </div>

        <div class="flex flex-wrap gap-2">
            <a :href="`/admin/training/slots?session=${sessionId}`" class="px-3 py-2 rounded-lg border border-[#1a307b]/20 bg-[#1a307b]/10 text-xs font-semibold text-[#1a307b]">Kelola Slot & Coach</a>
            <a :href="`/admin/training/attendance?session=${sessionId}`" class="px-3 py-2 rounded-lg border border-emerald-200 bg-emerald-50 text-xs font-semibold text-emerald-700">Kelola Attendance</a>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.sessions.index') }}" class="px-4 py-2.5 rounded-lg border border-slate-300 text-sm font-semibold text-slate-700">Kembali</a>
            <button @click="submit()" class="px-4 py-2.5 rounded-lg bg-[#1a307b] text-white text-sm font-semibold">Update Session</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function trainingSessionEditMetaPage(sessionId) {
    return {
        sessionId,
        form: {
            date: new Date().toISOString().slice(0,10),
            status: 'open',
        },
        async init() {
            try {
                const data = await window.API.get(`/admin/training-sessions/${this.sessionId}`);
                this.form.date = (data?.date || '').toString().slice(0,10) || this.form.date;
                this.form.status = data?.status || this.form.status;
            } catch (error) {
                window.showToast?.(error?.message || 'Gagal memuat detail session.', 'error');
            }
        },
        submit() {
            window.showToast?.('UI siap. Hubungkan update metadata session sesuai endpoint backend.', 'info');
        }
    }
}
</script>
@endpush
@endsection
