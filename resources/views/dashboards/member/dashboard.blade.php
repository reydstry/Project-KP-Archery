@extends('dashboards.member._layout')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang di member portal FocusOneX Archery')

@section('content')
<div x-data="dashboardData()" x-init="fetchDashboard()">
    
    <!-- Loading State -->
    <div x-show="loading" class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <!-- Dashboard Content -->
    <div x-show="!loading" x-cloak class="space-y-6">
        
        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Remaining Sessions -->
            <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 mb-1">Sisa Kuota</p>
                        <h3 class="text-3xl font-bold text-slate-800" x-text="data.quota?.remaining_sessions ?? 0"></h3>
                        <p class="text-xs text-slate-400 mt-1">dari <span x-text="data.quota?.total_sessions ?? 0"></span> sesi</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Total Attended -->
            <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6 hover:shadow-md transition" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 mb-1">Total Hadir</p>
                        <h3 class="text-3xl font-bold text-slate-800" x-text="data.attendance?.statistics?.total_attended ?? 0"></h3>
                        <p class="text-xs text-slate-400 mt-1">sesi latihan</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Total Absent -->
            <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6 hover:shadow-md transition" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 mb-1">Tidak Hadir</p>
                        <h3 class="text-3xl font-bold text-slate-800" x-text="data.attendance?.statistics?.total_absent ?? 0"></h3>
                        <p class="text-xs text-slate-400 mt-1">sesi latihan</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-red-100 to-red-200 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Days Remaining -->
            <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6 hover:shadow-md transition" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 mb-1">Sisa Hari Paket</p>
                        <h3 class="text-3xl font-bold text-slate-800" x-text="data.quota?.days_remaining ?? 0"></h3>
                        <p class="text-xs text-slate-400 mt-1">hari tersisa</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-100 to-amber-200 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Package Info -->
        <div x-show="data.quota" class="card-animate bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white" style="animation-delay: 0.4s">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-xl font-bold mb-1">Paket Aktif</h3>
                    <p class="text-blue-100 text-sm">Informasi paket membership Anda saat ini</p>
                </div>
                <div class="px-3 py-1 bg-white/20 rounded-lg text-sm font-semibold">
                    Active
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                    <p class="text-blue-100 text-xs mb-1">Nama Paket</p>
                    <p class="font-bold text-lg" x-text="data.quota?.package_name"></p>
                </div>
                <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                    <p class="text-blue-100 text-xs mb-1">Periode</p>
                    <p class="font-bold text-sm">
                        <span x-text="formatDate(data.quota?.start_date)"></span> - 
                        <span x-text="formatDate(data.quota?.end_date)"></span>
                    </p>
                </div>
                <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                    <p class="text-blue-100 text-xs mb-1">Sesi Terpakai</p>
                    <p class="font-bold text-lg">
                        <span x-text="data.quota?.used_sessions"></span> / <span x-text="data.quota?.total_sessions"></span>
                    </p>
                </div>
            </div>
            
            <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                <div class="flex justify-between text-sm mb-2">
                    <span>Progress Penggunaan</span>
                    <span x-text="Math.round((data.quota?.used_sessions / data.quota?.total_sessions) * 100) + '%'"></span>
                </div>
                <div class="w-full bg-white/20 rounded-full h-3">
                    <div class="bg-white h-3 rounded-full transition-all duration-500" 
                         :style="`width: ${(data.quota?.used_sessions / data.quota?.total_sessions) * 100}%`">
                    </div>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <a href="{{ route('member.bookings.create') }}" 
                   class="px-6 py-3 bg-white text-blue-600 rounded-xl font-semibold hover:shadow-lg transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Booking Sesi Baru
                </a>
            </div>
        </div>

        <!-- No Active Package Warning -->
        <div x-show="!data.quota" class="card-animate bg-amber-50 border border-amber-200 rounded-2xl p-6" style="animation-delay: 0.4s">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-amber-800 text-lg mb-1">Tidak Ada Paket Aktif</h4>
                    <p class="text-amber-700">Anda belum memiliki paket aktif. Silakan hubungi admin untuk aktivasi paket membership.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Attendance -->
            <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6" style="animation-delay: 0.5s">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                    Riwayat Kehadiran
                </h3>
                
                <div x-show="data.attendance?.history?.length > 0" class="space-y-3">
                    <template x-for="item in data.attendance?.history" :key="item.id">
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition">
                            <div class="flex-1">
                                <p class="font-semibold text-slate-800" x-text="item.session_time"></p>
                                <p class="text-sm text-slate-600" x-text="formatDate(item.session_date)"></p>
                                <p class="text-xs text-slate-500 mt-1">
                                    Coach: <span x-text="item.coach_name"></span>
                                </p>
                            </div>
                            <div>
                                <span 
                                    class="px-3 py-1 rounded-full text-xs font-bold"
                                    :class="item.attendance_status === 'present' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200'"
                                    x-text="item.attendance_status === 'present' ? 'Hadir' : 'Tidak Hadir'">
                                </span>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="!data.attendance?.history || data.attendance?.history?.length === 0" class="text-center py-12 text-slate-400">
                    <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    <p>Belum ada riwayat kehadiran</p>
                </div>
            </div>

            <!-- Achievements -->
            <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6" style="animation-delay: 0.6s">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/></svg>
                    Prestasi Terbaru
                </h3>
                
                <div x-show="data.achievements?.length > 0" class="space-y-3">
                    <template x-for="achievement in data.achievements" :key="achievement.id">
                        <div class="flex items-start p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition">
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-amber-500 rounded-xl flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-slate-800 truncate" x-text="achievement.title"></p>
                                <p class="text-sm text-slate-600 line-clamp-2" x-text="achievement.description"></p>
                                <p class="text-xs text-slate-500 mt-1" x-text="formatDate(achievement.date)"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="!data.achievements || data.achievements?.length === 0" class="text-center py-12 text-slate-400">
                    <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/></svg>
                    <p>Belum ada prestasi</p>
                </div>

                <div x-show="data.achievements?.length > 0" class="mt-4 text-center">
                    <a href="{{ route('member.achievements') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold inline-flex items-center gap-1">
                        Lihat Semua Prestasi
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function dashboardData() {
    return {
        loading: true,
        data: {},
        
        async fetchDashboard() {
            this.loading = true;
            try {
                this.data = await API.get('/member/dashboard');
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal memuat data dashboard', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        }
    }
}
</script>
@endpush
@endsection
