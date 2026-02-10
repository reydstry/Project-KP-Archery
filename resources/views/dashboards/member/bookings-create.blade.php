@extends('dashboards.member._layout')

@section('title', 'Booking Sesi Baru')
@section('subtitle', 'Pilih jadwal dan coach untuk sesi latihan')

@section('content')
<div x-data="bookingCreateData()" x-init="init()">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('member.bookings') }}" 
           class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 font-semibold transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Loading State -->
    <div x-show="loading" class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <!-- Main Content -->
    <div x-show="!loading" x-cloak class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Booking Form -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Step 1: Select Active Package -->
            <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold">
                        1
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Pilih Paket</h3>
                        <p class="text-sm text-slate-600">Pilih paket aktif untuk booking</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <template x-for="pkg in activePackages" :key="pkg.id">
                        <div @click="selectPackage(pkg)"
                             class="p-4 border-2 rounded-xl cursor-pointer transition-all hover:shadow-md"
                             :class="selectedPackage?.id === pkg.id ? 'border-blue-500 bg-blue-50' : 'border-slate-200 hover:border-blue-300'">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold"
                                             x-text="pkg.member?.name.charAt(0).toUpperCase()">
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800" x-text="pkg.package?.name"></p>
                                            <p class="text-sm text-slate-500" x-text="'Member: ' + pkg.member?.name"></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm">
                                        <span class="text-slate-600">
                                            <span x-text="(pkg.total_sessions - pkg.used_sessions)"></span> sesi tersisa
                                        </span>
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-md text-xs font-medium">
                                            Aktif
                                        </span>
                                    </div>
                                </div>
                                <div x-show="selectedPackage?.id === pkg.id" class="text-blue-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="activePackages.length === 0" class="text-center py-8 text-slate-400">
                    <p>Tidak ada paket aktif. Hubungi admin untuk aktivasi paket.</p>
                </div>
            </div>

            <!-- Step 2: Select Session -->
            <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6" style="animation-delay: 0.1s">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white font-bold">
                        2
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Pilih Sesi</h3>
                        <p class="text-sm text-slate-600">Pilih jadwal sesi latihan yang tersedia</p>
                    </div>
                </div>

                <div x-show="!selectedPackage" class="text-center py-8 text-slate-400">
                    <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    <p>Pilih paket terlebih dahulu</p>
                </div>

                <div x-show="selectedPackage && sessions.length > 0" class="space-y-3">
                    <template x-for="session in sessions" :key="session.id">
                        <div @click="selectSession(session)"
                             class="p-4 border-2 rounded-xl cursor-pointer transition-all hover:shadow-md"
                             :class="selectedSession?.id === session.id ? 'border-green-500 bg-green-50' : 'border-slate-200 hover:border-green-300'">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800" x-text="formatDate(session.session_date)"></p>
                                            <p class="text-sm text-slate-600 flex items-center gap-1">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span x-text="session.session_time"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm">
                                        <div class="flex items-center gap-2 text-slate-600">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                            <span x-text="session.coach_name"></span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-md text-xs font-medium">
                                                <span x-text="session.current_participants"></span>/<span x-text="session.max_participants"></span> peserta
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="selectedSession?.id === session.id" class="text-green-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="selectedPackage && sessions.length === 0" class="text-center py-8 text-slate-400">
                    <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    <p>Tidak ada sesi tersedia saat ini</p>
                </div>
            </div>
        </div>

        <!-- Booking Summary Sidebar -->
        <div class="lg:col-span-1">
            <div class="card-animate bg-gradient-to-br from-slate-800 via-slate-700 to-slate-800 rounded-2xl shadow-lg p-6 text-white sticky top-6" style="animation-delay: 0.2s">
                <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                    Ringkasan Booking
                </h3>

                <div class="space-y-4">
                    <!-- Package Info -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-xs text-slate-300 mb-2">Paket & Member</p>
                        <div x-show="selectedPackage">
                            <p class="font-semibold mb-1" x-text="selectedPackage?.package?.name"></p>
                            <div class="flex items-center gap-2 text-sm text-slate-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                <span x-text="selectedPackage?.member?.name"></span>
                            </div>
                            <p class="text-xs text-slate-300 mt-2">
                                <span x-text="(selectedPackage?.total_sessions - selectedPackage?.used_sessions)"></span> sesi tersisa
                            </p>
                        </div>
                        <p x-show="!selectedPackage" class="text-slate-400 italic text-sm">Belum dipilih</p>
                    </div>

                    <!-- Session Info -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-xs text-slate-300 mb-2">Jadwal Sesi</p>
                        <div x-show="selectedSession">
                            <p class="font-semibold mb-1" x-text="formatDate(selectedSession?.session_date)"></p>
                            <div class="flex items-center gap-4 text-sm">
                                <div class="flex items-center gap-1.5 text-slate-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span x-text="selectedSession?.session_time"></span>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-white/20">
                                <div class="flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                    <span x-text="selectedSession?.coach_name"></span>
                                </div>
                            </div>
                        </div>
                        <p x-show="!selectedSession" class="text-slate-400 italic text-sm">Belum dipilih</p>
                    </div>

                    <!-- Action Button -->
                    <button @click="submitBooking"
                            :disabled="!canSubmit || submitting"
                            class="w-full px-6 py-3.5 bg-white text-slate-800 rounded-xl font-bold hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                        <span x-show="!submitting">Konfirmasi Booking</span>
                        <span x-show="submitting">Memproses...</span>
                    </button>

                    <p x-show=paketubmit" class="text-xs text-slate-300 text-center">
                        Pilih member dan sesi terlebih dahulu
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function bookingCreateData() {
    return {
        activePackages: [],
        sessions: [],
        selectedPackage: null,
        selectedSession: null,
        submitting: false,
        
        get canSubmit() {
            return this.selectedPackage
            return this.selectedMember && this.selectedSession;
        },
        
        async init() {
            await Promise.ActivePackages(),
                this.fetchSessions()
            ]);
            this.loading = false;
        },
        
        async fetchActivePackages() {
            try {
                const response = await API.get('/member/dashboard');
                // Get active package from dashboard
                if (response.quota) {
                    // Create mock package structure for selection
                    this.activePackages = [{
                        id: 1, // This will be fetched from real API later
                        member: { name: response.member.name },
                        package: { name: response.quota.package_name },
                        total_sessions: response.quota.total_sessions,
                        used_sessions: response.quota.used_sessions,
                // TODO: Member needs endpoint to browse available training sessions
                // For now using mock data
                this.sessions = [];
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal memuat data sesi', 'error');
            }
        },
        
        selectPackage(pkg) {
            this.selectedPackage = pkg
        async fetchSessions() {
            try {
                const data = await API.get('/member/training-sessions');
                this.sessions = data.sessions || [];
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal memuat data sesi', 'error');
            }
        },
        
        selectMember(member) {
            this.selectedMember = member;Package
        },
        
        selectSession(session) {
            this.selectedSession = session;
        },
        
        async submitBooking() {
            if (!this.canSubmit) return;
            
            this.submitting = true;
            try {
                await API.post('/member/bookings', {
                    member_package_id: this.selectedMember.id,
                    training_session_id: this.selectedSession.id
                });
                
                showToast('Booking berhasil dibuat!', 'success');
                
                setTimeout(() => {
                    window.location.href = '{{ route("member.bookings") }}';
                }, 1500);
            } catch (error) {
                console.error('Error:', error);
                showToast(error.message || 'Gagal membuat booking', 'error');
               this.submitting = false;
            }
        },
        
        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { 
                weekday: 'long',
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
