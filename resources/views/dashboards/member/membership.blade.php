@extends('dashboards.member._layout')

@section('title', 'Keanggotaan')
@section('subtitle', 'Kelola data keluarga dan keanggotaan')

@section('content')
<div x-data="membershipData()" x-init="fetchMembers()">
    
    <!-- Registration Section -->
    <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
        <h3 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
            Registrasi Member Baru
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Register Self Form -->
            <div class="border border-slate-200 rounded-xl p-6 bg-gradient-to-br from-blue-50 to-slate-50">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800">Daftar Sendiri</h4>
                        <p class="text-sm text-slate-600">Registrasi untuk diri sendiri</p>
                    </div>
                </div>
                
                <form @submit.prevent="registerSelf">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap</label>
                            <input type="text" x-model="selfForm.name" required
                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nomor HP</label>
                            <input type="text" x-model="selfForm.phone"
                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <button type="submit" :disabled="selfForm.submitting"
                                class="w-full px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm hover:shadow-md">
                            <span x-show="!selfForm.submitting">Daftar Sekarang</span>
                            <span x-show="selfForm.submitting">Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Register Child Form -->
            <div class="border border-slate-200 rounded-xl p-6 bg-gradient-to-br from-green-50 to-slate-50">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800">Daftar Anak</h4>
                        <p class="text-sm text-slate-600">Registrasi untuk anak/keluarga</p>
                    </div>
                </div>
                
                <form @submit.prevent="registerChild">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap Anak</label>
                            <input type="text" x-model="childForm.name" required
                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nomor HP</label>
                            <input type="text" x-model="childForm.phone"
                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <button type="submit" :disabled="childForm.submitting"
                                class="w-full px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-semibold hover:from-green-600 hover:to-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm hover:shadow-md">
                            <span x-show="!childForm.submitting">Daftar Anak</span>
                            <span x-show="childForm.submitting">Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Members List -->
    <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm" style="animation-delay: 0.1s">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                Anggota Keluarga Saya
            </h3>
            <p class="text-sm text-slate-600 mt-1">Daftar semua anggota yang terdaftar di akun Anda</p>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="p-12">
            <div class="flex justify-center items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
        </div>

        <!-- Members Table -->
        <div x-show="!loading" x-cloak class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">No HP</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Tipe</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(member, index) in members" :key="member.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800" x-text="index + 1"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-sm"
                                         x-text="member.name.charAt(0).toUpperCase()">
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800" x-text="member.name"></p>
                                        <p class="text-xs text-slate-500" x-text="'ID: ' + member.id"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600" x-text="member.phone || '-'"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span 
                                    class="px-3 py-1 text-xs font-bold rounded-full"
                                    :class="{
                                        'bg-green-100 text-green-700 border border-green-200': member.status === 'active',
                                        'bg-amber-100 text-amber-700 border border-amber-200': member.status === 'inactive',
                                        'bg-red-100 text-red-700 border border-red-200': member.status === 'suspended'
                                    }"
                                    x-text="member.status === 'active' ? 'Aktif' : member.status === 'inactive' ? 'Tidak Aktif' : 'Suspended'">
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span 
                                    class="px-3 py-1 text-xs font-bold rounded-full"
                                    :class="member.is_self ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-purple-100 text-purple-700 border border-purple-200'"
                                    x-text="member.is_self ? 'Diri Sendiri' : 'Keluarga'">
                                </span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <!-- Empty State -->
            <div x-show="members.length === 0" class="text-center py-16 px-6">
                <svg class="w-24 h-24 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                <h4 class="text-lg font-bold text-slate-700 mb-2">Belum Ada Member Terdaftar</h4>
                <p class="text-slate-500 mb-6">Daftarkan diri Anda atau anggota keluarga menggunakan form di atas</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function membershipData() {
    return {
        loading: true,
        members: [],
        selfForm: {
            name: '',
            phone: '',
            submitting: false
        },
        childForm: {
            name: '',
            phone: '',
            submitting: false
        },
        
        async fetchMembers() {
            this.loading = true;
            try {
                const response = await API.get('/member/my-members');
                this.members = response.data || [];
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal memuat data member', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        async registerSelf() {
            this.selfForm.submitting = true;
            try {
                await API.post('/member/register-self', this.selfForm);
                showToast('Registrasi berhasil! Menunggu approval admin.', 'success');
                this.selfForm = { name: '', phone: '', submitting: false };
                this.fetchMembers();
            } catch (error) {
                console.error('Error:', error);
                showToast(error.message || 'Gagal registrasi', 'error');
            } finally {
                this.selfForm.submitting = false;
            }
        },
        
        async registerChild() {
            this.childForm.submitting = true;
            try {
                await API.post('/member/register-child', this.childForm);
                showToast('Registrasi anak berhasil! Menunggu approval admin.', 'success');
                this.childForm = { name: '', phone: '', submitting: false };
                this.fetchMembers();
            } catch (error) {
                console.error('Error:', error);
                showToast(error.message || 'Gagal registrasi', 'error');
            } finally {
                this.childForm.submitting = false;
            }
        },
        
        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        }
    }
}
</script>
@endpush
@endsection
