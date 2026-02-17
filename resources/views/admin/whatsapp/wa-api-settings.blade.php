@extends('admin.app')

@section('title', 'WA API Settings')
@section('subtitle', 'Konfigurasi koneksi API WhatsApp provider')

@section('content')
<div class="space-y-4" x-data="waApiSettingsPage()">
    <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-4">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Provider</label>
            <select x-model="driver" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#1a307b]/30">
                <option value="wablas">Wablas</option>
                <option value="dummy">Dummy (Testing)</option>
            </select>
        </div>

        <x-form-input label="Endpoint URL" name="endpoint_url" type="url" placeholder="https://jkt.wablas.com/api" x-model="endpoint" />
        <x-form-input label="API Token" name="api_key" type="text" placeholder="Masukkan API token" x-model="apiKey" />
        <x-form-input label="Secret Key" name="secret_key" type="text" placeholder="Masukkan secret key" x-model="secretKey" />
        <x-form-input label="Timeout (detik)" name="timeout" type="number" min="3" max="120" x-model="timeout" />

        <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg">
            <input type="checkbox" x-model="sandbox" class="rounded border-slate-300 text-[#1a307b]">
            <span class="text-sm font-medium text-slate-700">Aktifkan Sandbox Mode</span>
        </label>

        <div class="flex justify-end gap-2">
            <button @click="testConnection()" :disabled="loading || saving" class="px-4 py-2.5 rounded-lg border border-slate-300 text-sm font-semibold text-slate-700 disabled:opacity-50">Test Connection</button>
            <button @click="save()" :disabled="loading || saving" class="px-4 py-2.5 rounded-lg bg-[#1a307b] text-white text-sm font-semibold disabled:opacity-50">
                <span x-show="!saving">Simpan Pengaturan</span>
                <span x-show="saving">Menyimpan...</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function waApiSettingsPage() {
    return {
        driver: 'wablas',
        apiKey: '',
        secretKey: '',
        endpoint: '',
        timeout: 15,
        sandbox: false,
        loading: false,
        saving: false,
        testPhone: '',
        async init() {
            this.loading = true;
            try {
                const response = await window.API.get('/admin/whatsapp/settings');
                const data = response?.data ?? {};
                this.driver = data.driver ?? 'wablas';
                this.apiKey = data.token ?? '';
                this.secretKey = data.secret_key ?? '';
                this.endpoint = data.base_url ?? '';
                this.timeout = Number(data.timeout ?? 15);
                this.sandbox = Boolean(data.sandbox ?? false);
            } catch (error) {
                window.showToast(error?.message || 'Gagal memuat pengaturan WhatsApp.', 'error');
            } finally {
                this.loading = false;
            }
        },
        async save() {
            this.saving = true;
            try {
                const payload = {
                    driver: this.driver,
                    base_url: this.endpoint || null,
                    token: this.apiKey || null,
                    secret_key: this.secretKey || null,
                    timeout: Number(this.timeout || 15),
                    sandbox: Boolean(this.sandbox),
                };

                const response = await window.API.put('/admin/whatsapp/settings', payload);
                window.showToast(response?.message || 'Pengaturan WhatsApp berhasil disimpan.', 'success');
            } catch (error) {
                window.showToast(error?.message || 'Gagal menyimpan pengaturan WhatsApp.', 'error');
            } finally {
                this.saving = false;
            }
        },
        async testConnection() {
            const defaultPhone = this.testPhone || '628xxxxxxxxxx';
            const phone = window.prompt('Nomor tujuan test (format 62xxxx):', defaultPhone);
            if (!phone) {
                return;
            }
            this.testPhone = phone;

            try {
                const payload = {
                    phone,
                    message: 'Test koneksi WhatsApp dari halaman pengaturan admin.',
                };

                const response = await window.API.post('/admin/whatsapp/settings/test-connection', payload);
                const data = response?.data ?? {};

                if (data?.success === false) {
                    window.showToast(data?.message || 'Test connection gagal.', 'error');
                    return;
                }

                const details = JSON.stringify(data, null, 2);
                window.showToast((response?.message || 'Test connection berhasil.') + `\n\n${details}`, 'success');
            } catch (error) {
                window.showToast(error?.message || 'Gagal melakukan test connection.', 'error');
            }
        }
    }
}
</script>
@endpush
@endsection
