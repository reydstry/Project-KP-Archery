@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Overview statistik dan aktivitas club')

@section('content')
<div x-data="dashboardData()" x-init="loadData()" class="space-y-6">
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card-animate bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Members</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2" x-text="stats.total_members || 0"></h3>
                    <p class="text-xs text-slate-400 mt-1">
                        <span class="text-amber-600 font-medium" x-text="stats.pending_members || 0"></span> pending
                    </p>
                </div>
                <div class="p-3 bg-blue-50 rounded-xl">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
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

        <div class="card-animate bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow" style="animation-delay: 0.3s">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Achievements</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2" x-text="stats.total_achievements || 0"></h3>
                    <p class="text-xs text-slate-400 mt-1">Total records</p>
                </div>
                <div class="p-3 bg-amber-50 rounded-xl">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Pending Members -->
    <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm" style="animation-delay: 0.4s">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800">Pending Member Approvals</h3>
            <a href="{{ route('admin.members') }}" class="text-sm text-blue-500 hover:text-blue-600 font-medium">View All →</a>
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
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl flex items-center justify-center text-white font-bold shrink-0">
                            <span x-text="member.name.charAt(0).toUpperCase()"></span>
                        </div>
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

</div>

@push('scripts')
<script>
function dashboardData() {
    return {
        stats: {},
        recentPendingMembers: [],
        
        async loadData() {
            try {
                const data = await API.get('/admin/dashboard');
                this.stats = data.statistics || {};
                this.recentPendingMembers = data.recent?.pending_members || [];
            } catch (error) {
                console.error('Failed to load dashboard data:', error);
                showToast('Failed to load dashboard data', 'error');
            }
        }
    }
}
</script>
@endpush
@endsection
