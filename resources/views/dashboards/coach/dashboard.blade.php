@extends('layouts.coach')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang, Coach ' . auth()->user()->name . '! Berikut ringkasan aktivitas latihan Anda.')

@section('content')
<div x-data="dashboardData()" x-init="loadData()" class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card-animate bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Sesi Hari Ini</p>
            <p class="text-3xl font-bold text-[#1a307b] mt-2" x-text="statistics.today_sessions || 0"></p>
        </div>
        <div class="card-animate bg-white rounded-2xl p-5 border border-slate-200 shadow-sm" style="animation-delay: 0.1s">
            <p class="text-sm font-medium text-slate-500">Sesi Mendatang</p>
            <p class="text-3xl font-bold text-[#1a307b] mt-2" x-text="statistics.upcoming_sessions || 0"></p>
        </div>
        <div class="card-animate bg-white rounded-2xl p-5 border border-slate-200 shadow-sm" style="animation-delay: 0.2s">
            <p class="text-sm font-medium text-slate-500">Total Sesi</p>
            <p class="text-3xl font-bold text-[#1a307b] mt-2" x-text="statistics.total_sessions || 0"></p>
        </div>
    </div>

    <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm" style="animation-delay: 0.3s">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h3 class="font-bold text-slate-800">Jadwal Hari Ini</h3>
            <span class="px-4 py-2 bg-[#1a307b]/10 text-[#1a307b] rounded-xl text-sm font-semibold border border-[#1a307b]/20">
                {{ now()->format('l, d F Y') }}
            </span>
        </div>

        <div class="p-6">
            <template x-if="loading">
                <div class="text-center py-10">
                    <div class="inline-block w-8 h-8 border-4 border-slate-200 border-t-[#1a307b] rounded-full animate-spin"></div>
                    <p class="text-sm text-slate-500 mt-3">Memuat jadwal...</p>
                </div>
            </template>

            <template x-if="!loading && error">
                <div class="text-center py-10">
                    <p class="text-sm text-[#d12823]" x-text="error"></p>
                </div>
            </template>

            <template x-if="!loading && !error && todaySessions.length === 0">
                <div class="text-center py-10 text-slate-500 text-sm">Tidak ada jadwal untuk hari ini</div>
            </template>

            <template x-if="!loading && !error && todaySessions.length > 0">
                <div class="space-y-3">
                    <template x-for="slot in todaySessions" :key="slot.id">
                        <div class="rounded-xl border border-slate-200 overflow-hidden" x-data="{ expanded: false }">
                            <div @click="expanded = !expanded" class="flex items-center justify-between gap-4 p-4 hover:bg-slate-50 transition cursor-pointer">
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-800 truncate" x-text="slot.session_time?.session_name || 'Training Session'"></p>
                                    <p class="text-sm text-slate-500">
                                        <span x-text="slot.session_time?.start_time || '--:--'"></span>
                                        <span> - </span>
                                        <span x-text="slot.session_time?.end_time || '--:--'"></span>
                                    </p>
                                </div>
                                <div class="flex items-center gap-4 shrink-0">
                                    <p class="text-sm font-semibold text-slate-700">
                                        <span class="text-[#1a307b]" x-text="slot.total_attendances || 0"></span>
                                        /
                                        <span x-text="slot.capacity || 0"></span>
                                    </p>
                                    <svg class="w-5 h-5 text-slate-600 transition-transform" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <div x-show="expanded" x-collapse class="border-t border-slate-200 bg-slate-50 p-4 space-y-3">
                                <div>
                                    <p class="text-xs font-semibold text-slate-600 mb-2">Coach (<span x-text="slot.coaches?.length || 0"></span>)</p>
                                    <template x-if="!slot.coaches || slot.coaches.length === 0">
                                        <p class="text-xs text-slate-500">Belum ada coach.</p>
                                    </template>
                                    <div class="flex flex-wrap gap-1">
                                        <template x-for="coach in slot.coaches" :key="coach.id">
                                            <span class="px-2 py-1 text-xs rounded bg-[#1a307b]/10 text-[#1a307b] border border-[#1a307b]/20" x-text="coach.name"></span>
                                        </template>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold text-slate-600 mb-2">Member (<span x-text="slot.members?.length || 0"></span>)</p>
                                    <template x-if="!slot.members || slot.members.length === 0">
                                        <p class="text-xs text-slate-500">Belum ada member.</p>
                                    </template>
                                    <div class="space-y-1 max-h-40 overflow-y-auto">
                                        <template x-for="member in slot.members" :key="member.id">
                                            <p class="text-xs text-slate-700 px-2 py-1 rounded bg-white border border-slate-200" x-text="member.name"></p>
                                        </template>
                                    </div>
                                </div>

                                <div class="pt-2 flex justify-end">
                                    <a :href="`/coach/sessions/${slot.training_session_id}/edit`" class="px-4 py-2 bg-[#1a307b] hover:bg-[#152866] text-white text-sm font-semibold rounded-lg transition">Edit Session</a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
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
        statistics: {
            today_sessions: 0,
            upcoming_sessions: 0,
            total_sessions: 0,
        },
        todaySessions: [],

        async loadData() {
            this.loading = true;
            this.error = null;
            try {
                const data = await window.API.get('/coach/dashboard');
                this.statistics = data?.statistics || this.statistics;
                this.todaySessions = Array.isArray(data?.today_sessions) ? data.today_sessions : [];
            } catch (err) {
                this.error = err?.message || 'Gagal memuat data dashboard';
                window.showToast(this.error, 'error');
            } finally {
                this.loading = false;
            }
        },
    };
}
</script>
@endpush
