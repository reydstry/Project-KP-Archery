@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Overview statistik dan aktivitas club')

@section('content')
<div x-data="dashboardData()" x-init="loadData()" class="space-y-6">
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6">
        <div class="card-animate bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">    
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Members</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2" x-text="stats.total_members || 0"></h3>
                    <p class="text-xs text-slate-400 mt-1">
                        <span class="text-amber-600 font-medium" x-text="stats.pending_members || 0"></span> pending
                    </p>
                </div>
                <div class="p-3 bg-[#1a307b]/10 rounded-xl">
                    <svg class="w-6 h-6 text-[#1a307b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="card-animate bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow" style="animation-delay: 0.1s">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Coaches</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2" x-text="stats.total_coaches || 0"></h3>
                    <p class="text-xs text-slate-400 mt-1">Active trainers</p>
                </div>
                <div class="p-3 bg-green-50 rounded-xl">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            </div>
        </div>

        <div class="card-animate bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow" style="animation-delay: 0.2s">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Packages</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2" x-text="stats.total_packages || 0"></h3>
                    <p class="text-xs text-slate-400 mt-1">Available plans</p>
                </div>
                <div class="p-3 bg-purple-50 rounded-xl">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Pending Members -->
    <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm" style="animation-delay: 0.4s">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800">Pending Member Approvals</h3>
            <a href="{{ route('admin.members') }}" class="text-sm text-[#1a307b] hover:text-[#152866] font-medium">View All →</a>
        </div>
        <div class="p-6">
            <template x-if="recentPendingMembers.length === 0">
                <div class="text-center py-12 text-slate-400">
                    <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    <p class="text-sm font-medium">No pending members</p>
                </div>
            </template>
            <div class="space-y-3">
                <template x-for="member in recentPendingMembers" :key="member.id">
                    <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50 transition">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-800 truncate" x-text="member.name"></p>
                            <p class="text-sm text-slate-500" x-text="member.phone || 'No phone'"></p>
                        </div>
                        <span class="px-3 py-1 text-xs font-bold text-amber-700 bg-amber-50 rounded-full border border-amber-200">Pending</span>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Today's Training Schedule -->
    <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm" style="animation-delay: 0.5s">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h3 class="font-bold text-slate-800">Jadwal Hari Ini</h3>
            <span class="px-4 py-2 bg-slate-50 text-slate-700 rounded-xl text-sm font-semibold border border-slate-200">
                {{ now()->format('l, d F Y') }}
            </span>
        </div>

        <div class="p-6">
            <template x-if="todaySessions.length === 0">
                <div class="text-center py-12 text-slate-400">
                    <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm font-medium">Tidak ada jadwal untuk hari ini</p>
                </div>
            </template>

            <template x-if="todaySessions.length > 0">
                <div class="space-y-3">
                    <template x-for="slot in todaySessions" :key="slot.id">
                        <div class="rounded-xl border border-slate-100 hover:border-slate-200 transition overflow-hidden" x-data="{ expanded: false }">
                            <!-- Main slot card - clickable to expand -->
                            <div @click="expanded = !expanded" class="flex items-center justify-between gap-4 p-4 hover:bg-slate-50 transition cursor-pointer">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-12 h-12 bg-[#1a307b] rounded-xl flex items-center justify-center text-white shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10"/>
                                            <circle cx="12" cy="12" r="6"/>
                                            <circle cx="12" cy="12" r="2"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-800 truncate" x-text="slot.session_time?.session_name || 'Training Session'"></p>
                                        <p class="text-sm text-slate-500">
                                            <span x-text="slot.session_time?.start_time || '00:00'"></span>
                                            <span> - </span>
                                            <span x-text="slot.session_time?.end_time || '00:00'"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 shrink-0">
                                    <div class="text-right">
                                        <p class="text-xs text-slate-400">Kapasitas</p>
                                        <p class="font-bold text-slate-800">
                                            <span class="text-[#1a307b]" x-text="slot.total_bookings || 0"></span>
                                            <span class="text-slate-400">/</span>
                                            <span x-text="slot.capacity || 0"></span>
                                        </p>
                                    </div>
                                    <button @click.stop="expanded = !expanded" class="p-2 hover:bg-slate-100 rounded-lg transition">
                                        <svg class="w-5 h-5 text-slate-600 transition-transform" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Expanded content: coaches and members -->
                            <div x-show="expanded" x-collapse class="border-t border-slate-100">
                                <div class="p-4 bg-slate-50 grid-cols-1 gap-4">
                                    <!-- Coaches column -->
                                    <div class="mb-4">
                                        <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Coach (<span x-text="slot.coaches?.length || 0"></span>)
                                        </h4>
                                        <template x-if="!slot.coaches || slot.coaches.length === 0">
                                            <p class="text-sm text-slate-500 italic">No coaches assigned</p>
                                        </template>
                                        <div class="space-y-2">
                                            <template x-for="coach in slot.coaches" :key="coach.id">
                                                <div class="flex items-center gap-2 text-sm text-slate-700 bg-white px-3 py-2 rounded-lg border border-slate-100">
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
                                            Member (<span x-text="slot.members?.length || 0"></span>)
                                        </h4>
                                        <template x-if="!slot.members || slot.members.length === 0">
                                            <p class="text-sm text-slate-500 italic">No bookings yet</p>
                                        </template>
                                        <div class="space-y-2 max-h-60 overflow-y-auto">
                                            <template x-for="member in slot.members" :key="member.id">
                                                <div class="flex items-center gap-2 text-sm text-slate-700 bg-white px-3 py-2 rounded-lg border border-slate-100">
                                                    <div class="w-6 h-6 bg-[#1a307b]/10 rounded-full flex items-center justify-center shrink-0">
                                                        <svg class="w-3 h-3 text-[#1a307b]" fill="currentColor" viewBox="0 0 20 20">
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
                                <div class="p-4 bg-white border-t border-slate-100 flex items-center justify-end gap-3">
                                    <a :href="`/admin/sessions/${slot.training_session_id}/edit`" class="px-4 py-2 bg-[#1a307b] hover:bg-[#152866] text-white text-sm font-semibold rounded-lg transition">Edit Session</a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>

</div>

@push('scripts')
<script>
function dashboardData() {
    return {
        stats: {},
        recentPendingMembers: [],
        todaySessions: [],
        
        async loadData() {
            try {
                const data = await API.get('/admin/dashboard');
                
                // Validate response
                if (!data || typeof data !== 'object') {
                    throw new Error('Invalid response from server');
                }
                
                this.stats = data.statistics || {};
                this.recentPendingMembers = Array.isArray(data.recent?.pending_members) 
                    ? data.recent.pending_members 
                    : [];
                this.todaySessions = Array.isArray(data.today_sessions) 
                    ? data.today_sessions 
                    : [];
            } catch (error) {
                console.error('Failed to load dashboard data:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to load dashboard data';
                showToast(errorMsg, 'error');
                
                // Set defaults on error
                this.stats = {};
                this.recentPendingMembers = [];
                this.todaySessions = [];
            }
        }
    }
}
</script>
@endpush
@endsection
