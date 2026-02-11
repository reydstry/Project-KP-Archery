@extends('layouts.coach')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang, Coach ' . auth()->user()->name . '! Berikut ringkasan aktivitas latihan Anda.')

@section('content')
<div x-data="dashboardData()" x-init="loadData()">

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Today's Sessions -->
        <div class="bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-lg p-4 sm:p-6 border border-blue-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 card-animate" style="animation-delay: 0.1s">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-slate-600 text-sm font-semibold mb-1">Today's Sessions</h3>
            <template x-if="loading">
                <p class="text-3xl font-bold text-slate-300">...</p>
            </template>
            <template x-if="!loading">
                <p class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-blue-500 bg-clip-text text-transparent" x-text="statistics.today_sessions"></p>
            </template>
        </div>

        <!-- Upcoming Sessions -->
        <div class="bg-gradient-to-br from-white to-emerald-50 rounded-2xl shadow-lg p-4 sm:p-6 border border-emerald-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 card-animate" style="animation-delay: 0.2s">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-slate-600 text-sm font-semibold mb-1">Upcoming Sessions</h3>
            <template x-if="loading">
                <p class="text-3xl font-bold text-slate-300">...</p>
            </template>
            <template x-if="!loading">
                <p class="text-4xl font-bold bg-gradient-to-r from-emerald-600 to-emerald-500 bg-clip-text text-transparent" x-text="statistics.upcoming_sessions"></p>
            </template>
        </div>

        <!-- Total Sessions -->
        <div class="bg-gradient-to-br from-white to-purple-50 rounded-2xl shadow-lg p-4 sm:p-6 border border-purple-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 card-animate" style="animation-delay: 0.3s">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-slate-600 text-sm font-semibold mb-1">Total Sessions</h3>
            <template x-if="loading">
                <p class="text-3xl font-bold text-slate-300">...</p>
            </template>
            <template x-if="!loading">
                <p class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-purple-500 bg-clip-text text-transparent" x-text="statistics.total_sessions"></p>
            </template>
        </div>
    </div>

    <!-- Today's Schedule -->
    <div class="bg-gradient-to-br from-white to-slate-50 rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-slate-100 card-animate" style="animation-delay: 0.4s">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0 mb-4 sm:mb-6">
            <h3 class="text-2xl font-bold text-slate-800">Jadwal Hari Ini</h3>
            <span class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl text-sm font-semibold shadow-lg">
                {{ now()->format('l, d F Y') }}
            </span>
        </div>

        <template x-if="loading">
            <div class="text-center py-12">
                <div class="inline-block w-8 h-8 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                <p class="text-slate-500 mt-4">Loading schedule...</p>
            </div>
        </template>

        <template x-if="!loading && todaySessions.length === 0">
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-slate-500">Tidak ada jadwal untuk hari ini</p>
            </div>
        </template>

        <template x-if="!loading && todaySessions.length > 0">
    <div class="space-y-4">
        <template x-for="(session, index) in todaySessions" :key="session.id">
            <div class="flex items-center justify-between p-5 bg-gradient-to-r from-slate-50 to-blue-50 rounded-xl border border-slate-100 hover:shadow-lg transition-all duration-300">
                <div>
                    <h4 class="font-bold text-slate-800 text-lg" x-text="session.session_time?.session_name || 'Training Session'"></h4>
                    <p class="text-slate-600 text-sm">
                        <span x-text="session.session_time?.start_time || '00:00'"></span> - 
                        <span x-text="session.session_time?.end_time || '00:00'"></span>
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <div>
                        <p class="text-slate-600 text-sm mb-1">Kapasitas</p>
                        <p class="font-bold text-slate-800 text-lg">
                            <span class="text-blue-600" x-text="session.total_bookings || 0"></span> / 
                            <span x-text="session.capacity || 0"></span>
                        </p>
                    </div>
                    <!-- Button to View Members -->
                    <button @click="viewSlotMembers(session)" 
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 hover:bg-blue-200 text-blue-600 hover:text-blue-700 transition-all duration-200 hover:shadow-lg"
                            title="Lihat daftar member">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>
</template>



        <template x-if="error">
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-red-300 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="text-red-500" x-text="error"></p>
            </div>
        </template>
    </div>

    <!-- Members List Modal -->
    <div id="membersListModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" @click="closeMembersModal()"></div>
            
            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-1" x-text="selectedSlot?.session_time?.session_name || 'Session'"></h3>
                            <p class="text-sm text-gray-600">
                                <span x-text="selectedSlot?.session_time?.start_time"></span> - 
                                <span x-text="selectedSlot?.session_time?.end_time"></span>
                            </p>
                        </div>
                        <button @click="closeMembersModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="mt-5">
                        <template x-if="loadingMembers">
                            <div class="text-center py-8">
                                <div class="inline-block w-8 h-8 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                                <p class="text-slate-500 mt-2">Loading members...</p>
                            </div>
                        </template>

                        <template x-if="!loadingMembers && slotMembers.length === 0">
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-slate-600 font-medium">Belum ada member yang booking</p>
                            </div>
                        </template>

                        <template x-if="!loadingMembers && slotMembers.length > 0">
                            <div class="max-h-96 overflow-y-auto">
                                <div class="space-y-2">
                                    <template x-for="(member, idx) in slotMembers" :key="member.id">
                                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200 hover:bg-slate-100 transition-colors">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                                    <span x-text="(idx + 1)"></span>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-slate-900" x-text="member.member_name || 'Unknown'"></p>
                                                    <p class="text-xs text-slate-500" x-text="'Member ID: ' + (member.member_id || '-')"></p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                                                    Booked
                                                </span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4">
                    <button @click="closeMembersModal()" class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-lg">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function dashboardData() {
        return {
            loading: true,
            error: null,
            coach: {},
            statistics: {
                today_sessions: 0,
                upcoming_sessions: 0,
                total_sessions: 0
            },
            todaySessions: [],
            selectedSlot: null,
            slotMembers: [],
            loadingMembers: false,

            async loadData() {
                this.loading = true;
                this.error = null;

                try {
                    const data = await API.get('/coach/dashboard');
                    
                    this.coach = data.coach || {};
                    this.statistics = data.statistics || {};
                    this.todaySessions = data.today_sessions || [];

                    showToast('Dashboard loaded successfully', 'success');
                } catch (err) {
                    this.error = err.message || 'Failed to load dashboard data';
                    showToast(this.error, 'error');
                    console.error('Dashboard error:', err);
                } finally {
                    this.loading = false;
                }
            },

            async viewSlotMembers(session) {
                this.selectedSlot = session;
                this.slotMembers = [];
                this.loadingMembers = true;
                
                document.getElementById('membersListModal').classList.remove('hidden');

                try {
                    // Get training session ID - bisa dari training_session_id atau parent session
                    const sessionId = session.training_session_id || session.session_id;
                    const slotId = session.slot_id || session.id;
                    
                    const response = await API.get(`/coach/training-sessions/${sessionId}/bookings?slot_id=${slotId}`);
                    this.slotMembers = response?.bookings || [];
                } catch (err) {
                    console.error('Failed to load members:', err);
                    showToast(err?.message || 'Failed to load members', 'error');
                    this.slotMembers = [];
                } finally {
                    this.loadingMembers = false;
                }
            },

            closeMembersModal() {
                document.getElementById('membersListModal').classList.add('hidden');
                this.selectedSlot = null;
                this.slotMembers = [];
            }
        }
    }
</script>
@endpush
