@extends('dashboards.member._layout')

@section('title', 'Profil Saya')
@section('subtitle', 'Kelola informasi profil dan akun Anda')

@section('content')
<div x-data="profileData()" x-init="fetchProfile()">
    
    <!-- Loading State -->
    <div x-show="loading" class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <!-- Profile Content -->
    <div x-show="!loading" x-cloak class="space-y-6">
        
        <!-- Profile Header Card -->
        <div class="card-animate bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-2xl shadow-lg p-8 text-white">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <!-- Avatar -->
                <div class="relative">
                    <div class="w-32 h-32 bg-gradient-to-br from-white/30 to-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border-4 border-white/30">
                        <span class="text-5xl font-bold" x-text="profile.name?.charAt(0).toUpperCase()"></span>
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center border-4 border-blue-600">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- User Info -->
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-3xl font-bold mb-2" x-text="profile.name"></h2>
                    <p class="text-blue-100 mb-4 flex items-center gap-2 justify-center md:justify-start">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        <span x-text="profile.email"></span>
                    </p>

                    <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                        <div class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl">
                            <p class="text-xs text-blue-100 mb-0.5">Role</p>
                            <p class="font-bold text-sm">Member</p>
                        </div>
                        <div class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl">
                            <p class="text-xs text-blue-100 mb-0.5">Status</p>
                            <p class="font-bold text-sm">Active</p>
                        </div>
                        <div class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl">
                            <p class="text-xs text-blue-100 mb-0.5">Bergabung</p>
                            <p class="font-bold text-sm" x-text="formatDate(profile.created_at)"></p>
                        </div>
                    </div>
                </div>

                <!-- Edit Toggle Button -->
                <button @click="editMode = !editMode"
                        class="px-6 py-3 bg-white text-blue-600 rounded-xl font-semibold hover:shadow-xl transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                    <span x-text="editMode ? 'Batal' : 'Edit Profil'"></span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Profile Form -->
            <div class="lg:col-span-2">
                <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6" style="animation-delay: 0.1s">
                    <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        Informasi Pribadi
                    </h3>

                    <form @submit.prevent="saveProfile">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                                <input type="text" x-model="form.name" :disabled="!editMode"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-slate-50 disabled:text-slate-500">
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                                <input type="email" x-model="form.email" :disabled="!editMode"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-slate-50 disabled:text-slate-500">
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Nomor HP</label>
                                <input type="text" x-model="form.phone" :disabled="!editMode"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-slate-50 disabled:text-slate-500">
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Alamat</label>
                                <textarea x-model="form.address" :disabled="!editMode" rows="3"
                                          class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-slate-50 disabled:text-slate-500"></textarea>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div x-show="editMode" class="mt-6 flex gap-3">
                            <button type="submit"
                                    :disabled="submitting"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-semibold hover:from-blue-600 hover:to-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm hover:shadow-md">
                                <span x-show="!submitting">Simpan Perubahan</span>
                                <span x-show="submitting">Menyimpan...</span>
                            </button>
                            <button type="button" @click="editMode = false; resetForm()"
                                    class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-all">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Section -->
                <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mt-6" style="animation-delay: 0.2s">
                    <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        Ganti Password
                    </h3>

                    <form @submit.prevent="changePassword">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Password Lama</label>
                                <input type="password" x-model="passwordForm.current_password"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Password Baru</label>
                                <input type="password" x-model="passwordForm.new_password"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password Baru</label>
                                <input type="password" x-model="passwordForm.new_password_confirmation"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <button type="submit"
                                    :disabled="passwordSubmitting"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-slate-600 to-slate-700 text-white rounded-xl font-semibold hover:from-slate-700 hover:to-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm hover:shadow-md">
                                <span x-show="!passwordSubmitting">Ganti Password</span>
                                <span x-show="passwordSubmitting">Memproses...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Stats Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Activity Stats -->
                <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6" style="animation-delay: 0.1s">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                        Statistik Aktivitas
                    </h3>

                    <div class="space-y-3">
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-slate-50 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-slate-600">Total Booking</span>
                                <span class="text-2xl font-bold text-blue-600" x-text="stats.total_bookings || 0"></span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>

                        <div class="p-4 bg-gradient-to-br from-green-50 to-slate-50 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-slate-600">Hadir</span>
                                <span class="text-2xl font-bold text-green-600" x-text="stats.total_attended || 0"></span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full" 
                                     :style="`width: ${stats.total_bookings ? (stats.total_attended / stats.total_bookings * 100) : 0}%`"></div>
                            </div>
                        </div>

                        <div class="p-4 bg-gradient-to-br from-amber-50 to-slate-50 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-slate-600">Prestasi</span>
                                <span class="text-2xl font-bold text-amber-600" x-text="stats.total_achievements || 0"></span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-2 rounded-full" 
                                     :style="`width: ${Math.min(stats.total_achievements * 10, 100)}%`"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card-animate bg-gradient-to-br from-slate-800 via-slate-700 to-slate-800 rounded-2xl shadow-lg p-6 text-white" style="animation-delay: 0.2s">
                    <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('member.bookings.create') }}"
                           class="block px-4 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl transition-all">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                <span class="font-semibold">Booking Sesi Baru</span>
                            </div>
                        </a>

                        <a href="{{ route('member.bookings') }}"
                           class="block px-4 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl transition-all">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                <span class="font-semibold">Lihat Booking</span>
                            </div>
                        </a>

                        <a href="{{ route('member.achievements') }}"
                           class="block px-4 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl transition-all">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/></svg>
                                <span class="font-semibold">Prestasi Saya</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function profileData() {
    return {
        loading: true,
        editMode: false,
        submitting: false,
        passwordSubmitting: false,
        profile: {},
        stats: {},
        form: {
            name: '',
            email: '',
            phone: '',
            address: ''
        },
        passwordForm: {
            current_password: '',
            new_password: '',
            new_password_confirmation: ''
        },
        
        async fetchProfile() {
            this.loading = true;
            try {
                // For now using mock data, replace with actual API call
                this.profile = {
                    name: '{{ auth()->user()->name }}',
                    email: '{{ auth()->user()->email }}',
                    phone: '081234567890',
                    address: 'Jl. Contoh No. 123, Jakarta',
                    created_at: '{{ auth()->user()->created_at }}'
                };
                
                this.stats = {
                    total_bookings: 25,
                    total_attended: 20,
                    total_achievements: 5
                };
                
                this.resetForm();
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal memuat profil', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        resetForm() {
            this.form = { ...this.profile };
        },
        
        async saveProfile() {
            this.submitting = true;
            try {
                // Replace with actual API call
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                this.profile = { ...this.form };
                this.editMode = false;
                showToast('Profil berhasil diperbarui', 'success');
            } catch (error) {
                console.error('Error:', error);
                showToast(error.message || 'Gagal menyimpan profil', 'error');
            } finally {
                this.submitting = false;
            }
        },
        
        async changePassword() {
            if (this.passwordForm.new_password !== this.passwordForm.new_password_confirmation) {
                showToast('Konfirmasi password tidak cocok', 'error');
                return;
            }
            
            this.passwordSubmitting = true;
            try {
                // Replace with actual API call
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                this.passwordForm = {
                    current_password: '',
                    new_password: '',
                    new_password_confirmation: ''
                };
                showToast('Password berhasil diubah', 'success');
            } catch (error) {
                console.error('Error:', error);
                showToast(error.message || 'Gagal mengubah password', 'error');
            } finally {
                this.passwordSubmitting = false;
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
