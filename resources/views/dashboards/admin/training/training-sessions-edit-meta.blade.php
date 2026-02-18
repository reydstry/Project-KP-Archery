@extends('layouts.admin')

@section('title', 'Edit Training Session')
@section('subtitle', 'Update tanggal dan status sesi')

@section('content')
<div class="space-y-4" x-data="trainingSessionEditMetaPage({{ (int)$id }})" x-init="init()">
    <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-4 max-w-2xl">
        <x-form-input label="ID Session" name="session_id" type="text" :value="$id" readonly />
        <x-form-input label="Tanggal Sesi" name="date" type="date" required x-model="form.date" />
        <p x-show="errors.date" class="text-xs text-red-600" x-text="errors.date"></p>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
            <select x-model="form.status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#1a307b]/30">
                <option value="open">Open</option>
                <option value="closed">Closed</option>
                <option value="canceled">Canceled</option>
            </select>
            <p x-show="errors.status" class="text-xs text-red-600 mt-1" x-text="errors.status"></p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a :href="`/admin/training/slots?session=${sessionId}`" class="px-3 py-2 rounded-lg border border-[#1a307b]/20 bg-[#1a307b]/10 text-xs font-semibold text-[#1a307b]">Kelola Slot & Coach</a>
            <a :href="`/admin/sessions/${sessionId}/attendance`" class="px-3 py-2 rounded-lg border border-emerald-200 bg-emerald-50 text-xs font-semibold text-emerald-700">Kelola Attendance</a>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.sessions.index') }}" class="px-4 py-2.5 rounded-lg border border-slate-300 text-sm font-semibold text-slate-700">Kembali</a>
            <button @click="submit()" :disabled="submitting || loading" class="px-4 py-2.5 rounded-lg bg-[#1a307b] text-white text-sm font-semibold disabled:opacity-60 disabled:cursor-not-allowed">
                <span x-show="!submitting">Update Session</span>
                <span x-show="submitting">Menyimpan...</span>
            </button>
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
        loading: false,
        submitting: false,
        errors: {},
        async init() {
            this.loading = true;
            try {
                const data = await window.API.get(`/admin/training-sessions/${this.sessionId}`);
                this.form.date = (data?.date || '').toString().slice(0,10) || this.form.date;
                this.form.status = data?.status || this.form.status;
            } catch (error) {
                window.showToast?.(error?.message || 'Gagal memuat detail session.', 'error');
            } finally {
                this.loading = false;
            }
        },
        async submit() {
            if (this.submitting) return;

            this.submitting = true;
            this.errors = {};

            try {
                await window.API.patch(`/admin/training-sessions/${this.sessionId}`, {
                    date: this.form.date,
                    status: this.form.status,
                });
                window.showToast?.('Session berhasil diperbarui.', 'success');
                window.location.href = '{{ route('admin.sessions.index') }}';
            } catch (error) {
                this.errors = this.mapError(error);
                window.showToast?.(error?.message || 'Gagal update session.', 'error');
            } finally {
                this.submitting = false;
            }
        },
        mapError(error) {
            const message = (error?.message || '').toLowerCase();
            if (message.includes('tanggal') || message.includes('date')) {
                return { date: error.message };
            }
            if (message.includes('status')) {
                return { status: error.message };
            }
            return {};
        }
    }
}
</script>
@endpush
@endsection
