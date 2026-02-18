@extends('admin.app')

@section('title', 'Create Training Session')
@section('subtitle', 'Buat sesi berdasarkan tanggal dan status')

@section('content')
<div class="space-y-4" x-data="trainingSessionCreatePage()">
    <x-alert-box type="warning" title="Catatan">
        Halaman ini dipisah khusus untuk metadata sesi (tanggal & status). Pengaturan slot/coach dilakukan di halaman terpisah.
    </x-alert-box>

    <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-4 max-w-2xl">
        <x-form-input label="Tanggal Sesi" name="date" type="date" required x-model="form.date" />

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Status Awal</label>
            <select x-model="form.status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#1a307b]/30">
                <option value="open">Open</option>
                <option value="closed">Closed</option>
                <option value="canceled">Canceled</option>
            </select>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.sessions.index') }}" class="px-4 py-2.5 rounded-lg border border-slate-300 text-sm font-semibold text-slate-700">Kembali</a>
            <button @click="submit()" class="px-4 py-2.5 rounded-lg bg-[#1a307b] text-white text-sm font-semibold">Simpan Session</button>
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
        submit() {
            window.showToast?.('UI siap. Hubungkan submit sesuai endpoint yang digunakan tim backend.', 'info');
        }
    }
}
</script>
@endpush
@endsection
