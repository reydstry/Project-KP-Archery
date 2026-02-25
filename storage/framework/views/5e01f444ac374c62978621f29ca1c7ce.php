<?php $__env->startSection('title', 'Training Session'); ?>
<?php $__env->startSection('subtitle', 'Kelola tanggal sesi dan status tanpa slot/attendance'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="trainingSessionsPage()" x-init="init()">

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-5 py-3.5  bg-[#1a307b] border-b border-slate-100">
            <svg class="w-4 h-4 text-white" fill="white" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            <h3 class="text-sm font-semibold text-white">Filter</h3>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                <select x-model="filters.status" 
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm 
                               bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1a307b]/20 
                               focus:border-[#1a307b] transition-all text-slate-700">
                    <option value="">Semua Status</option>
                    <option value="open">Scheduled / Ongoing</option>
                    <option value="closed">Completed</option>
                    <option value="canceled">Canceled</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Tanggal Mulai</label>
                <input type="date" x-model="filters.start_date" 
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm 
                              bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1a307b]/20 
                              focus:border-[#1a307b] transition-all text-slate-700">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Tanggal Selesai</label>
                <input type="date" x-model="filters.end_date" 
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm 
                              bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1a307b]/20 
                              focus:border-[#1a307b] transition-all text-slate-700">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">&nbsp;</label>
                <div class="flex gap-2">
                    <button @click="loadSessions()" :disabled="loading" 
                            class="flex-1 px-4 py-2.5 rounded-xl bg-[#1a307b] text-white text-sm font-semibold 
                                   disabled:opacity-60 hover:bg-[#162a69] transition-all shadow-sm 
                                   flex items-center justify-center gap-2">
                        <svg class="w-3.5 h-3.5" :class="loading ? 'animate-spin' : ''"
                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                  d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                        Cari
                    </button>
                    <button @click="resetFilters()" :disabled="loading" title="Reset"
                            class="px-3 py-2.5 rounded-xl border bg-[#1a307b] border-slate-200 text-white
                                   hover:bg-[#162a69] disabled:opacity-60 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Section -->
    <div class="space-y-6">
        <!-- Stats Section -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <!-- Total -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">
                        Total Session
                    </p>
                    <p class="text-2xl font-bold text-[#1a307b] mt-1"
                    x-text="sessions.length"></p>
                </div>

                <div class="w-10 h-10 bg-[#1a307b] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>

            <!-- Scheduled -->
            <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">
                        Scheduled
                    </p>
                    <p class="text-2xl font-bold text-[#1a307b] mt-1"
                    x-text="sessions.filter(s => s.status === 'open').length"></p>
                </div>

                <div class="w-10 h-10 bg-[#1a307b] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Completed -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">
                        Completed
                    </p>
                    <p class="text-2xl font-bold text-[#1a307b] mt-1"
                    x-text="sessions.filter(s => s.status === 'closed').length"></p>
                </div>

                <div class="w-10 h-10 bg-[#1a307b] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>



    <!-- Desktop Table -->
    <div class="hidden lg:block bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-[#1a307b]">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                </svg>
                <h3 class="text-sm font-semibold text-white">Daftar Session</h3>
            </div>
            <div class="flex">
                <a href="<?php echo e(route('admin.sessions.create')); ?>" 
                class="inline-flex items-center justify-center w-full gap-2 px-4 py-3 rounded-2xl 
                        bg-white hover:bg-white/90 text-[#1a307b] text-xs font-semibold 
                        shadow-md hover:shadow-lg transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" 
                        viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Session
                </a>
            </div>
        </div>

        <!-- Column Header -->
        <div class="grid grid-cols-12 bg-slate-50/80 border-b border-slate-200 px-5 py-2.5">
            <div class="col-span-1 text-xs font-bold text-slate-800 uppercase tracking-wider pl-1">No</div>
            <div class="col-span-2 text-xs font-bold text-slate-800 uppercase tracking-wider text-center">Tanggal</div>
            <div class="col-span-6 text-xs font-bold text-slate-800 uppercase tracking-wider text-center">Status</div>
            <div class="col-span-3 text-xs font-bold text-slate-800 uppercase tracking-wider pl-[160px]">Aksi</div>
        </div>

        <!-- Loading -->
        <template x-if="loading">
            <div class="py-24 flex flex-col items-center gap-4">
                <div class="w-10 h-10 border-[3px] border-slate-200 border-t-[#1a307b] rounded-full animate-spin"></div>
                <p class="text-sm text-slate-400 font-medium">Memuat data...</p>
            </div>
        </template>

        <!-- Empty -->
        <template x-if="!loading && sessions.length === 0">
            <div class="py-24 flex flex-col items-center gap-3">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-slate-500 font-semibold text-sm">Belum ada session</p>
                <a href="<?php echo e(route('admin.sessions.create')); ?>" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1a307b] 
                          text-white text-sm font-semibold hover:bg-[#162a69] transition-all mt-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Session
                </a>
            </div>
        </template>

        <!-- Rows -->
        <template x-for="(session, index) in sessions" :key="session.id">
            <div class="grid grid-cols-12 items-center px-5 py-3.5 border-b border-slate-100 last:border-0 
                        hover:bg-slate-50/60 transition-colors group">

                <!-- No -->
                <div class="col-span-1">
                    <span class="w-7 h-7 flex items-center justify-center rounded-lg text-xs font-bold
                                 text-slate-800"
                          x-text="index + 1"></span>
                </div>

                <!-- Date -->
                <div class="col-span-2 text-center">
                    <p class="text-sm font-semibold text-slate-800" x-text="formatDate(session.date)"></p>
                </div>

                <!-- Status -->
                <div class="col-span-6 text-center">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold border"
                          :class="{
                              'bg-emerald-50 text-emerald-700 border-emerald-200': session.status === 'open',
                              'bg-slate-100 text-slate-500 border-slate-200': session.status === 'closed',
                              'bg-red-50 text-red-600 border-red-200': session.status === 'canceled'
                          }">
                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                              :class="{
                                  'bg-emerald-500 animate-pulse': session.status === 'open',
                                  'bg-slate-400': session.status === 'closed',
                                  'bg-red-500': session.status === 'canceled'
                              }"></span>
                        <span x-text="statusLabel(session.status)"></span>
                    </span>
                </div>

                <!-- Actions: 2 quick + dropdown -->
                <div class="col-span-3 flex items-center justify-end gap-1.5">

                    <!-- Quick: Edit -->
                    <a :href="`/admin/sessions/${session.id}/edit`"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg 
                              border border-slate-200 bg-white text-xs font-semibold text-slate-600 
                              hover:bg-slate-50 transition-all shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                        </svg>
                        Edit
                    </a>

                    <!-- Quick: Hapus -->
                    <button @click="deleteSession(session)"
                            :disabled="submittingIds.includes(session.id)"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg 
                                   border border-red-200 bg-red-50 text-xs font-semibold text-red-600 
                                   disabled:opacity-50 hover:bg-red-100 transition-all shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                        </svg>
                        Hapus
                    </button>

                    <!-- Dropdown: More Actions -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                                class="w-8 h-8 flex items-center justify-center rounded-lg 
                                       border border-slate-200 bg-white text-slate-500 
                                       hover:bg-slate-50 hover:text-slate-700 transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                            </svg>
                        </button>

                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 top-full mt-1.5 w-44 bg-white border border-slate-200 
                                    rounded-xl shadow-lg shadow-slate-200/60 z-20 overflow-hidden">
                            
                            <div class="px-3 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Lainnya</p>
                            </div>

                            <div class="p-1">
                                <a :href="`/admin/training/slots?session=${session.id}`"
                                   @click="open = false"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold 
                                          text-slate-600 hover:bg-[#1a307b]/5 hover:text-[#1a307b] transition-colors">
                                    <svg class="w-3.5 h-3.5 text-[#1a307b]/60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Slot & Coach
                                </a>
                                <a :href="`/admin/sessions/${session.id}/attendance`"
                                   @click="open = false"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold 
                                          text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                    <svg class="w-3.5 h-3.5 text-emerald-500/70" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    Attendance
                                </a>
                                <button @click="changeStatus(session, nextStatus(session.status)); open = false"
                                        :disabled="submittingIds.includes(session.id)"
                                        class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold 
                                               text-slate-600 hover:bg-amber-50 hover:text-amber-700 
                                               disabled:opacity-50 transition-colors">
                                    <svg class="w-3.5 h-3.5 text-amber-500/70" :class="submittingIds.includes(session.id) ? 'animate-spin' : ''"
                                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                    </svg>
                                    Ubah Status
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </template>
    </div>

    <!-- Mobile Cards -->
    <div class="lg:hidden space-y-3">
        <div class="flex items-center justify-between px-1">
            <p class="text-sm font-semibold text-slate-600">Daftar Session</p>
            <span class="text-xs text-slate-400 bg-white border border-slate-200 px-2.5 py-1 rounded-lg font-medium"
                  x-text="`${sessions.length} data`"></span>
        </div>

        <template x-if="loading">
            <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center">
                <div class="w-10 h-10 border-[3px] border-slate-200 border-t-[#1a307b] rounded-full animate-spin mx-auto mb-3"></div>
                <p class="text-sm text-slate-400">Memuat session...</p>
            </div>
        </template>

        <template x-if="!loading && sessions.length === 0">
            <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center">
                <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-slate-500 font-semibold text-sm">Belum ada session</p>
            </div>
        </template>

        <template x-for="(session, index) in sessions" :key="session.id">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                
                <!-- Card Row: info + badge + dropdown -->
                <div class="flex items-center gap-3 px-4 py-3.5">
                    <div class="w-9 h-9 bg-[#1a307b]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-[#1a307b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800 truncate" x-text="formatDate(session.date)"></p>
                        <p class="text-xs text-slate-400" x-text="`ID #${session.id}`"></p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold border flex-shrink-0"
                          :class="{
                              'bg-emerald-50 text-emerald-700 border-emerald-200': session.status === 'open',
                              'bg-slate-100 text-slate-500 border-slate-200': session.status === 'closed',
                              'bg-red-50 text-red-600 border-red-200': session.status === 'canceled'
                          }">
                        <span class="w-1.5 h-1.5 rounded-full"
                              :class="{
                                  'bg-emerald-500 animate-pulse': session.status === 'open',
                                  'bg-slate-400': session.status === 'closed',
                                  'bg-red-500': session.status === 'canceled'
                              }"></span>
                        <span x-text="statusLabel(session.status)"></span>
                    </span>

                    <!-- Mobile Dropdown -->
                    <div class="relative flex-shrink-0" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                                class="w-8 h-8 flex items-center justify-center rounded-lg 
                                       border border-slate-200 text-slate-400 hover:bg-slate-50 transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                            </svg>
                        </button>
                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 top-full mt-1.5 w-44 bg-white border border-slate-200 
                                    rounded-xl shadow-lg z-20 overflow-hidden">
                            <div class="p-1">
                                <a :href="`/admin/sessions/${session.id}/edit`"
                                   class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-xs font-semibold 
                                          text-slate-600 hover:bg-slate-50 transition-colors">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                    </svg>
                                    Edit Session
                                </a>
                                <a :href="`/admin/training/slots?session=${session.id}`"
                                   class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-xs font-semibold 
                                          text-slate-600 hover:bg-[#1a307b]/5 hover:text-[#1a307b] transition-colors">
                                    <svg class="w-3.5 h-3.5 text-[#1a307b]/50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Slot & Coach
                                </a>
                                <a :href="`/admin/sessions/${session.id}/attendance`"
                                   class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-xs font-semibold 
                                          text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                    <svg class="w-3.5 h-3.5 text-emerald-500/60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    Attendance
                                </a>
                                <button @click="changeStatus(session, nextStatus(session.status)); open = false"
                                        class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-xs font-semibold 
                                               text-slate-600 hover:bg-amber-50 hover:text-amber-700 transition-colors">
                                    <svg class="w-3.5 h-3.5 text-amber-500/60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                    </svg>
                                    Ubah Status
                                </button>
                                <div class="border-t border-slate-100 my-1"></div>
                                <button @click="deleteSession(session); open = false"
                                        class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-xs font-semibold 
                                               text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                    Hapus Session
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Modals (Delete / Success / Error) — sama seperti sebelumnya -->
    <!-- Delete -->
    <div x-show="showDeleteConfirm" x-cloak @click.self="showDeleteConfirm = false"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-sm w-full"
             x-show="showDeleteConfirm"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
            <div class="text-center">
                <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <svg class="h-7 w-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Hapus Session?</h3>
                <p class="text-slate-500 text-sm mb-6 leading-relaxed"
                   x-text="sessionToDelete ? `Yakin menghapus Session #${sessionToDelete.id}? Tindakan ini tidak bisa dibatalkan.` : ''"></p>
                <div class="flex gap-3">
                    <button @click="showDeleteConfirm = false"
                            class="flex-1 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-all">
                        Batal
                    </button>
                    <button @click="confirmDeleteSession()"
                            class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-red-500/20 active:scale-95">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success -->
    <div x-show="showSuccessModal" x-cloak @click.self="closeSuccessModal()"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-sm w-full"
             x-show="showSuccessModal"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
            <div class="text-center">
                <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <svg class="h-7 w-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Berhasil!</h3>
                <p class="text-slate-500 text-sm mb-6 leading-relaxed" x-text="successMessage"></p>
                <button @click="closeSuccessModal()"
                        class="w-full px-4 py-2.5 bg-[#1a307b] hover:bg-[#152866] text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-[#1a307b]/20 active:scale-95">
                    Oke, Mengerti
                </button>
            </div>
        </div>
    </div>

    <!-- Error -->
    <div x-show="showErrorModal" x-cloak @click.self="closeErrorModal()"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-sm w-full"
             x-show="showErrorModal"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
            <div class="text-center">
                <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <svg class="h-7 w-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Terjadi Kesalahan!</h3>
                <p class="text-slate-500 text-sm mb-6 leading-relaxed" x-text="errorMessage"></p>
                <button @click="closeErrorModal()"
                        class="w-full px-4 py-2.5 bg-[#1a307b] hover:bg-[#152866] text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-[#1a307b]/20 active:scale-95">
                    Oke, Mengerti
                </button>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function trainingSessionsPage() {
    return {
        sessions: [],
        loading: false,
        submittingIds: [],
        showSuccessModal: false,
        showErrorModal: false,
        showDeleteConfirm: false,
        sessionToDelete: null,
        successMessage: '',
        errorMessage: '',
        filters: { status: '', start_date: '', end_date: '' },
        async init() { await this.loadSessions(); },
        async loadSessions() {
            this.loading = true;
            try {
                const params = new URLSearchParams();
                if (this.filters.status) params.set('status', this.filters.status);
                if (this.filters.start_date) params.set('start_date', this.filters.start_date);
                if (this.filters.end_date) params.set('end_date', this.filters.end_date);
                const suffix = params.toString() ? `?${params.toString()}` : '';
                const result = await window.API.get(`/admin/training-sessions${suffix}`);
                this.sessions = Array.isArray(result?.data)
                    ? result.data.map(item => ({ id: item.id, date: item.date, status: item.status || 'open' }))
                    : [];
            } catch (error) {
                this.showErrorMessage(error?.message || 'Gagal memuat training sessions.');
            } finally {
                this.loading = false;
            }
        },
        resetFilters() {
            this.filters = { status: '', start_date: '', end_date: '' };
            this.loadSessions();
        },
        statusLabel(s) {
            return { open: 'Scheduled', closed: 'Completed', canceled: 'Cancelled' }[s] || s;
        },
        nextStatus(s) {
            return { open: 'closed', closed: 'canceled', canceled: 'open' }[s] || 'open';
        },
        formatDate(dateString) {
            if (!dateString) return '-';
            return new Date(dateString).toLocaleDateString('id-ID', {
                weekday: 'long', day: '2-digit', month: 'long', year: 'numeric'
            });
        },
        showSuccessMessage(msg) { this.successMessage = msg; this.showSuccessModal = true; },
        closeSuccessModal() { this.showSuccessModal = false; this.successMessage = ''; },
        showErrorMessage(msg) { this.errorMessage = msg; this.showErrorModal = true; },
        closeErrorModal() { this.showErrorModal = false; this.errorMessage = ''; },
        async changeStatus(session, status) {
            if (!session?.id || !status) return;
            this.submittingIds = [...this.submittingIds, session.id];
            try {
                await window.API.patch(`/admin/training-sessions/${session.id}/status`, { status });
                this.showSuccessMessage('Status session berhasil diubah.');
                await this.loadSessions();
            } catch (error) {
                this.showErrorMessage(error?.message || 'Gagal mengubah status session.');
            } finally {
                this.submittingIds = this.submittingIds.filter(id => id !== session.id);
            }
        },
        async deleteSession(session) {
            if (!session?.id) return;
            this.sessionToDelete = session;
            this.showDeleteConfirm = true;
        },
        async confirmDeleteSession() {
            if (!this.sessionToDelete?.id) return;
            this.showDeleteConfirm = false;
            this.submittingIds = [...this.submittingIds, this.sessionToDelete.id];
            try {
                const response = await window.API.delete(`/admin/training-sessions/${this.sessionToDelete.id}`);
                this.showSuccessMessage(response?.message || 'Session berhasil dihapus.');
                await this.loadSessions();
            } catch (error) {
                this.showErrorMessage(error?.message || 'Gagal menghapus session.');
            } finally {
                this.submittingIds = this.submittingIds.filter(id => id !== this.sessionToDelete.id);
                this.sessionToDelete = null;
            }
        },
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Project\club-panahan\resources\views/dashboards/admin/training/training-sessions.blade.php ENDPATH**/ ?>