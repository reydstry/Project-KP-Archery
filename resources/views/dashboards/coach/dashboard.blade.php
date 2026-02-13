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
                    <div class="rounded-xl border border-slate-100 hover:shadow-lg transition-all duration-300 overflow-hidden" x-data="{ expanded: false }">
                        <!-- Main slot card - clickable to expand -->
                        <div @click="expanded = !expanded" class="flex items-center justify-between p-5 bg-gradient-to-r from-slate-50 to-blue-50 cursor-pointer">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/>
                                        <circle cx="12" cy="12" r="6"/>
                                        <circle cx="12" cy="12" r="2"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-lg" x-text="session.session_time?.session_name || 'Training Session'"></h4>
                                    <p class="text-slate-600 text-sm">
                                        <span x-text="session.session_time?.start_time || '00:00'"></span> - 
                                        <span x-text="session.session_time?.end_time || '00:00'"></span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <p class="text-slate-600 text-sm mb-1">Kapasitas</p>
                                    <p class="font-bold text-slate-800 text-lg">
                                        <span class="text-blue-600" x-text="session.total_bookings || 0"></span> / 
                                        <span x-text="session.capacity || 0"></span>
                                    </p>
                                </div>
                                <button @click.stop="expanded = !expanded" class="p-2 hover:bg-white rounded-lg transition">
                                    <svg class="w-5 h-5 text-slate-600 transition-transform" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Expanded content: coaches and members -->
                        <div x-show="expanded" x-collapse class="border-t border-slate-200">
                            <div class="p-5 bg-white grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Coaches column -->
                                <div>
                                    <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Coach(es)
                                    </h4>
                                    <template x-if="!session.coaches || session.coaches.length === 0">
                                        <p class="text-sm text-slate-500 italic">No coaches assigned</p>
                                    </template>
                                    <div class="space-y-2">
                                        <template x-for="coach in session.coaches" :key="coach.id">
                                            <div class="flex items-center gap-2 text-sm text-slate-700 bg-gradient-to-r from-green-50 to-emerald-50 px-3 py-2 rounded-lg border border-green-100">
                                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <span x-text="coach.name"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Members column -->
                                <div>
                                    <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Members (<span x-text="session.members?.length || 0"></span>)
                                    </h4>
                                    <template x-if="!session.members || session.members.length === 0">
                                        <p class="text-sm text-slate-500 italic">No bookings yet</p>
                                    </template>
                                    <div class="space-y-2 max-h-60 overflow-y-auto">
                                        <template x-for="member in session.members" :key="member.id">
                                            <div class="flex items-center gap-2 text-sm text-slate-700 bg-gradient-to-r from-blue-50 to-indigo-50 px-3 py-2 rounded-lg border border-blue-100">
                                                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center shrink-0">
                                                    <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <span class="truncate" x-text="member.name"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Action buttons in expanded state -->
                            <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                                <a :href="`/coach/sessions/${session.training_session_id}/edit`" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition shadow-lg">View Details</a>
                            </div>
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
            }
        }
    }
</script>
@endpush
