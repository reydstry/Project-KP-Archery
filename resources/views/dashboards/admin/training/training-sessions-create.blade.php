@extends('layouts.admin')

@section('title', 'Create Training Session')
@section('subtitle', 'Buat sesi berdasarkan tanggal dan status')

@section('content')
<div class="space-y-4" x-data="trainingSessionCreatePage()">
    <x-alert-box type="warning" title="Catatan">
        Halaman ini dipisah khusus untuk metadata sesi (tanggal & status). Pengaturan slot/coach dilakukan di halaman terpisah.
    </x-alert-box>

    <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-4 max-w-2xl">
        <x-form-input label="Tanggal Sesi" name="date" type="date" required x-model="form.date" />
        <p x-show="errors.date" class="text-xs text-red-600" x-text="errors.date"></p>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Status Awal</label>
            <select x-model="form.status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#1a307b]/30">
                <option value="open">Open</option>
                <option value="closed">Closed</option>
                <option value="canceled">Canceled</option>
            </select>
            <p x-show="errors.status" class="text-xs text-red-600 mt-1" x-text="errors.status"></p>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.sessions.index') }}" class="px-4 py-2.5 rounded-lg border border-slate-300 text-sm font-semibold text-slate-700">Kembali</a>
            <button @click="submit()" :disabled="submitting" class="px-4 py-2.5 rounded-lg bg-[#1a307b] text-white text-sm font-semibold disabled:opacity-60 disabled:cursor-not-allowed">
                <span x-show="!submitting">Simpan Session</span>
                <span x-show="submitting">Menyimpan...</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function trainingSessionCreatePage() {
    return {
        form: {
            date: new Date().toISOString().slice(0,10),
            status: 'open',
        },
        errors: {},
        submitting: false,
        async submit() {
            if (this.submitting) return;

            this.errors = {};
            this.submitting = true;

            try {
                await window.API.post('/admin/training-sessions', {
                    date: this.form.date,
                    status: this.form.status,
                });

                window.showToast?.('Session berhasil dibuat.', 'success');
                window.location.href = '{{ route('admin.sessions.index') }}';
            } catch (error) {
                this.errors = this.mapError(error);
                window.showToast?.(error?.message || 'Gagal membuat session.', 'error');
            } finally {
                this.submitting = false;
            }
        },
        mapError(error) {
            if (!error?.message) return {};

            if (error.message.toLowerCase().includes('tanggal')) {
                return { date: error.message };
            }

            if (error.message.toLowerCase().includes('status')) {
                return { status: error.message };
            }

            return {};
        }
    }
}
</script>
@endpush
@endsection
