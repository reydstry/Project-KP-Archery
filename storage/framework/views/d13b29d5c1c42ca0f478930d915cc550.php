<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('subtitle', 'Overview statistik dan aktivitas club'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="dashboardData()" x-init="loadData()" class="space-y-5">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <!-- Total Members -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Members</p>
                <p class="text-3xl font-bold text-slate-800 mt-0.5 leading-none" x-text="stats.total_members ?? '—'"></p>
                <div class="flex items-center gap-1.5 mt-1.5">
                    <p class="text-xs text-slate-400 truncate">
                        <span class="text-red-500 font-semibold" x-text="stats.pending_members ?? 0"></span>
                        menunggu persetujuan
                    </p>
                </div>
            </div>
            <div class="w-12 h-12 bg-[#1a307b] rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>

        <!-- Total Coaches -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
            
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Coaches</p>
                <p class="text-3xl font-bold text-slate-800 mt-0.5 leading-none" x-text="stats.total_coaches ?? '—'"></p>
                <div class="flex items-center gap-1.5 mt-1.5">
                    <p class="text-xs text-slate-400">Active trainers</p>
                </div>
            </div>

            <div class="w-12 h-12 bg-[#1a307b] rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>

        <!-- Packages -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Packages</p>
                <p class="text-3xl font-bold text-slate-800 mt-0.5 leading-none" x-text="stats.total_packages ?? '—'"></p>
                <div class="flex items-center gap-1.5 mt-1.5">
                    <p class="text-xs text-slate-400">Available plans</p>
                </div>
            </div>
            <div class="w-12 h-12 bg-[#1a307b] rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">

        <!-- Pending Members — 2/5 -->
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-6 bg-[#1a307b]">
                <div class="flex items-center gap-2.5">
                    <h3 class="text-sm font-bold text-white">Pending Approvals</h3>
                    <span class="text-xs bg-white/5 text-white border border-white/20 
                                 px-2 py-0.5 rounded-full font-semibold"
                          x-text="recentPendingMembers.length"></span>
                </div>
                <a href="<?php echo e(route('admin.members')); ?>" 
                   class="text-xs text-slate-400 hover:text-white transition-colors flex items-center gap-1 font-medium">
                    Lihat semua
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <!-- List -->
            <div class="p-4">
                <template x-if="recentPendingMembers.length === 0">
                    <div class="text-center py-10">
                        <div class="w-12 h-12 bg-slate-100 shadow-2xl rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-400 font-medium">Tidak ada pending</p>
                        <p class="text-xs text-slate-300 mt-0.5">Semua member sudah disetujui</p>
                    </div>
                </template>

                <div class="space-y-2">
                    <template x-for="member in recentPendingMembers" :key="member.id">
                        <div class="flex items-center gap-3 p-3 rounded-xl border shadow-lg hover:shadow-xl hover:scale-[1.01]
                                    hover:bg-slate-50 border-slate-200 transition-all duration-200 cursor-pointer group">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-800 truncate" x-text="member.name"></p>
                                <p class="text-xs text-slate-400 truncate" x-text="member.phone || 'No phone'"></p>
                            </div>
                            <span class="flex-shrink-0 text-xs font-bold text-red-600 bg-red-50
                                         border border-red-200 px-2 py-0.5 rounded-lg">
                                Pending
                            </span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Today's Schedule — 3/5 -->
        <div class="lg:col-span-3 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-[18px] bg-[#1a307b]">
                <div class="flex items-center gap-2.5">
                    <h3 class="text-sm font-bold text-white">Jadwal Hari Ini</h3>
                    <span class="text-xs bg-white/10 text-white border border-white/20
                                 px-2 py-0.5 rounded-full font-semibold"
                          x-text="todaySessions.length"></span>
                </div>
                <span class="text-xs text-white bg-white/5 border border-white/20
                             px-3 py-2 rounded-full font-medium">
                    <?php echo e(now()->translatedFormat('l, d M Y')); ?>

                </span>
            </div>

            <!-- Sessions -->
            <div class="p-4 space-y-2">
                <template x-if="todaySessions.length === 0">
                    <div class="text-center py-14">
                        <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500 font-medium">Tidak ada jadwal hari ini</p> 
                    </div>
                </template>

                <template x-for="slot in todaySessions" :key="slot.id">
                <div 
                    class="rounded-xl border transition-all duration-300 overflow-hidden
                        shadow-lg hover:shadow-xl hover:scale-[1.01] cursor-pointer"

                         :class="expanded === slot.id
                             ? 'border-[#1a307b]/30
                             : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50/50'">

                        <!-- Slot Row -->
                        <div @click="expanded = expanded === slot.id ? null : slot.id"
                             class="flex items-center gap-3 px-4 py-3.5 cursor-pointer">

                            <!-- Time Badge -->
                            <div class="flex-shrink-0 text-center bg-slate-100 rounded-xl px-3 py-2 min-w-[68px]"
                                 :class="expanded === slot.id ? 'bg-[#1a307b]/10' : ''">
                                <p class="text-xs font-bold text-slate-700 leading-none"
                                   x-text="slot.session_time?.start_time?.slice(0,5) || '--:--'"></p>
                                <div class="w-full h-px bg-slate-300 my-1"
                                     :class="expanded === slot.id ? 'bg-[#1a307b]/30' : ''"></div>
                                <p class="text-xs text-slate-400 leading-none"
                                   x-text="slot.session_time?.end_time?.slice(0,5) || '--:--'"></p>
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-800 truncate"
                                   x-text="slot.session_time?.session_name || 'Training Session'"></p>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-xs text-slate-400 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span x-text="`${slot.coaches?.length || 0} coach`"></span>
                                    </span>
                                    <span class="text-xs text-slate-400 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span x-text="`${slot.total_attendance || 0}/${slot.capacity || 0}`"></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Capacity bar -->
                            <div class="flex-shrink-0 w-16 hidden sm:block">
                                <div class="h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500"
                                         :class="((slot.total_attendance || 0) / (slot.capacity || 1)) >= 0.8
                                             ? 'bg-red-400' : 'bg-emerald-400'"
                                         :style="`width: ${Math.min(100, ((slot.total_attendance || 0) / (slot.capacity || 1)) * 100)}%`">
                                    </div>
                                </div>
                                <p class="text-[10px] text-slate-400 text-right mt-0.5"
                                   x-text="`${Math.round(((slot.total_attendance || 0) / (slot.capacity || 1)) * 100)}%`"></p>
                            </div>

                            <!-- Chevron -->
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform duration-200"
                                 :class="expanded === slot.id ? 'rotate-180 text-[#1a307b]' : ''"
                                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        <!-- Expanded Detail -->
                        <div x-show="expanded === slot.id" x-collapse>
                            <div class="border-t border-slate-200 px-4 pt-4 pb-3 grid grid-cols-2 gap-4">

                                <!-- Coaches -->
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5">
                                        Coach (<span x-text="slot.coaches?.length || 0"></span>)
                                    </p>
                                    <template x-if="!slot.coaches || slot.coaches.length === 0">
                                        <p class="text-xs text-slate-400 italic">Belum ada coach</p>
                                    </template>
                                    <div class="space-y-1.5">
                                        <template x-for="coach in slot.coaches" :key="coach.id">
                                            <div class="flex items-center gap-2 px-2.5 py-2 rounded-lg bg-[#1a307b] border border-slate-200">
                                                <span class="text-xs text-white font-medium truncate" x-text="coach.name"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Members -->
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5">
                                        Member (<span x-text="slot.members?.length || 0"></span>)
                                    </p>
                                    <template x-if="!slot.members || slot.members.length === 0">
                                        <p class="text-xs text-slate-400 italic">Belum ada data</p>
                                    </template>
                                    <div class="space-y-1.5 max-h-36 overflow-y-auto pr-1">
                                        <template x-for="member in slot.members" :key="member.id">
                                            <div class="flex items-center gap-2 px-2.5 py-2 rounded-lg bg-[#1a307b] border border-slate-200">
                                                <span class="text-xs text-white font-medium truncate" x-text="member.name"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="px-4 pb-4 flex justify-end">
                                <a :href="`/admin/sessions/${slot.training_session_id}/edit`"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                                          bg-[#1a307b] hover:bg-[#162a69] text-white
                                          text-xs font-semibold transition-all shadow-sm shadow-[#1a307b]/20">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                    </svg>
                                    Edit Session
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

</div>

<?php $__env->startPush('scripts'); ?>
<script>
function dashboardData() {
    return {
        stats: {},
        recentPendingMembers: [],
        todaySessions: [],
        expanded: null,

        async loadData() {
            try {
                const data = await API.get('/admin/dashboard');
                if (!data || typeof data !== 'object') throw new Error('Invalid response');
                this.stats = data.statistics || {};
                this.recentPendingMembers = Array.isArray(data.recent?.pending_members)
                    ? data.recent.pending_members : [];
                this.todaySessions = Array.isArray(data.today_sessions)
                    ? data.today_sessions : [];
            } catch (error) {
                console.error('Dashboard load error:', error);
                showToast(error?.message || 'Gagal memuat data', 'error');
                this.stats = {};
                this.recentPendingMembers = [];
                this.todaySessions = [];
            }
        },
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Project\club-panahan\resources\views/dashboards/admin/dashboard/dashboard.blade.php ENDPATH**/ ?>