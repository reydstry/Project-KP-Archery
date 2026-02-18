@extends('layouts.admin')

@section('title', 'Reminder Settings')
@section('subtitle', 'Pengaturan reminder paket member yang akan expired')

@section('content')
<div class="space-y-4" x-data="reminderPage()">
    <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-4">
        <x-form-input label="Hari Sebelum Expired" name="days_before" type="number" min="1" max="30" x-model="daysBefore" hint="Default 7 hari sebelum tanggal berakhir paket." />

        <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg">
            <input type="checkbox" x-model="enabled" class="rounded border-slate-300 text-[#1a307b]">
            <span class="text-sm font-medium text-slate-700">Aktifkan Reminder Otomatis</span>
        </label>

        <x-alert-box type="warning" title="Jadwal Cron">
            Reminder command dijalankan oleh scheduler harian. Pastikan cron server aktif: <span class="font-mono">* * * * * php artisan schedule:run</span>
        </x-alert-box>

        <div class="flex justify-end">
            <button @click="save()" :disabled="loading || saving" class="px-4 py-2.5 rounded-lg bg-[#1a307b] text-white text-sm font-semibold disabled:opacity-50">
                <span x-show="!saving">Simpan Pengaturan</span>
                <span x-show="saving">Menyimpan...</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function reminderPage() {
    return {
        daysBefore: 7,
        enabled: true,
        loading: false,
        saving: false,
        async init() {
            this.loading = true;
            try {
                const response = await window.API.get('/admin/whatsapp/reminder-settings');
                const data = response?.data ?? {};
                this.daysBefore = Number(data.days_before_expired ?? 7);
                this.enabled = Boolean(data.enabled ?? true);
            } catch (error) {
                window.showToast?.(error?.message || 'Gagal memuat reminder settings.', 'error');
            } finally {
                this.loading = false;
            }
        },
        async save() {
            this.saving = true;
            try {
                const payload = {
                    enabled: Boolean(this.enabled),
                    days_before_expired: Number(this.daysBefore || 7),
                };

                const response = await window.API.put('/admin/whatsapp/reminder-settings', payload);
                window.showToast?.(response?.message || 'Reminder settings berhasil disimpan.', 'success');
            } catch (error) {
                window.showToast?.(error?.message || 'Gagal menyimpan reminder settings.', 'error');
            } finally {
                this.saving = false;
            }
        }
    }
}
</script>
@endpush
@endsection
