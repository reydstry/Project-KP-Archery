@extends('admin.app')

@section('title', 'WA Blast')
@section('subtitle', 'Kirim pesan WhatsApp ke segment member')

@section('content')
<div class="space-y-4" x-data="waBlastPage()">
    <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-4">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Pesan</label>
            <textarea x-model="message" rows="6" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#1a307b]/30" placeholder="Tulis pesan broadcast..."></textarea>
        </div>

        <div>
            <p class="text-sm font-semibold text-slate-700 mb-2">Target Penerima</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <label class="flex items-center gap-2 p-3 border border-slate-200 rounded-lg">
                    <input type="radio" name="target" value="all" x-model="target" class="text-[#1a307b]">
                    <span class="text-sm">Semua Member</span>
                </label>
                <label class="flex items-center gap-2 p-3 border border-slate-200 rounded-lg">
                    <input type="radio" name="target" value="active" x-model="target" class="text-[#1a307b]">
                    <span class="text-sm">Hanya Member Aktif</span>
                </label>
            </div>
        </div>

        <x-alert-box type="info" title="Preview Penerima">
            Estimasi penerima: <span class="font-bold" x-text="recipientCount"></span> nomor.
        </x-alert-box>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Jadwalkan (opsional)</label>
            <input type="datetime-local" x-model="scheduleAt" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#1a307b]/30">
        </div>

        <div class="flex justify-end">
            <button @click="send()" :disabled="sending || !message.trim()" class="px-5 py-2.5 rounded-lg bg-[#1a307b] hover:bg-[#162a69] text-white text-sm font-semibold disabled:opacity-50">
                <span x-show="!sending">Kirim WA Blast</span>
                <span x-show="sending">Mengirim...</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function waBlastPage() {
    return {
        message: '',
        target: 'active',
        scheduleAt: '',
        sending: false,
        recipientCount: 0,
        async init() {
            await this.loadRecipientCount();
            this.$watch('target', async () => {
                await this.loadRecipientCount();
            });
        },
        async loadRecipientCount() {
            try {
                const data = await window.API.get(`/admin/whatsapp/recipients-count?target=${this.target}`);
                this.recipientCount = Number(data?.count ?? 0);
            } catch (error) {
                this.recipientCount = 0;
                window.showToast?.(error?.message || 'Gagal memuat preview penerima.', 'error');
            }
        },
        async send() {
            this.sending = true;
            try {
                const payload = {
                    message: this.message,
                    target: this.target,
                };

                if (this.scheduleAt) {
                    payload.schedule_at = this.scheduleAt;
                }

                const response = await window.API.post('/admin/whatsapp/blast', payload);
                window.showToast?.(response?.message || 'WA blast berhasil diproses.', 'success');
                this.message = '';
                this.scheduleAt = '';
                await this.loadRecipientCount();
            } catch (error) {
                window.showToast?.(error?.message || 'Gagal mengirim WA blast.', 'error');
            } finally {
                this.sending = false;
            }
        }
    }
}
</script>
@endpush
@endsection
