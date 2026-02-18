 @extends('admin.app')

@section('title', 'Coaches')
@section('subtitle', 'Manage archery club coaches')

@section('content')
<div x-data="coachesData()" x-init="loadCoaches()" class="space-y-6">
    
    <!-- Header Actions -->
    <div class="card-animate flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-4">
        <div class="flex-1 w-full">
                 <input type="search" x-model="search" placeholder="Search coaches..." 
                     class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none transition">
        </div>
        <button @click="openAddModal()" 
            class="w-full sm:w-auto px-4 py-2 sm:px-6 sm:py-3 text-sm bg-[#1a307b] text-white rounded-xl font-semibold hover:bg-[#152866] transition-all whitespace-nowrap shrink-0">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Coach
            </span>
        </button>
    </div>

    <!-- Coaches Table - Desktop View -->
    <div class="card-animate hidden md:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" style="animation-delay: 0.1s">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-if="loading">
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-slate-400">Loading...</td>
                        </tr>
                    </template>
                    <template x-if="!loading && filteredCoaches.length === 0">
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-slate-400">No coaches found</td>
                        </tr>
                    </template>
                    <template x-for="coach in filteredCoaches" :key="coach.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-800" x-text="coach.name"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600" x-text="coach.phone || '-'"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="viewCoachDetails(coach)" 
                                            class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition"
                                            title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </button>
                                        <button @click="openEditModal(coach)" 
                                            class="p-2 text-[#1a307b] hover:bg-[#1a307b]/10 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-3" style="animation-delay: 0.1s">
        <template x-if="loading">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 text-center text-slate-400">
                Loading...
            </div>
        </template>
        <template x-if="!loading && filteredCoaches.length === 0">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 text-center text-slate-400">
                No coaches found
            </div>
        </template>
        <template x-for="coach in filteredCoaches" :key="coach.id">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <div class="space-y-3">
                    <!-- Coach Name -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-[#1a307b] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                                <h3 class="font-semibold text-slate-900 text-sm truncate" x-text="coach.name"></h3>
                            </div>
                            
                            <!-- Email -->
                            <div class="flex items-center gap-2 text-xs text-slate-600 mb-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                                </svg>
                                <span class="truncate" x-text="coach.email"></span>
                            </div>
                            
                            <!-- Phone -->
                            <div class="flex items-center gap-2 text-xs text-slate-600">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                                </svg>
                                <span x-text="coach.phone || 'No phone'"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-2 pt-2 border-t border-slate-100">
                        <button @click="viewCoachDetails(coach)" 
                            class="flex-1 px-3 py-2 bg-white border border-[#1a307b] text-[#1a307b] rounded-lg text-xs font-semibold hover:bg-[#1a307b]/5 transition-colors">
                            Details
                        </button>
                        <button @click="openEditModal(coach)" 
                            class="flex-1 px-3 py-2 bg-[#1a307b] text-white rounded-lg text-xs font-semibold hover:bg-[#152866] transition-colors">
                            Edit
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Add/Edit Modal -->
    <div x-show="showModal" x-cloak @click.self="closeModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div @click.away="closeModal()" 
             class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto"
             x-transition>
            <div class="sticky top-0 bg-[#1a307b] text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
                <h3 class="text-lg font-bold" x-text="editingCoach ? 'Edit Coach' : 'Add New Coach'"></h3>
                <button @click="closeModal()" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form @submit.prevent="saveCoach()" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Name *</label>
                          <input type="text" x-model="form.name" required
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email *</label>
                          <input type="email" x-model="form.email" required
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Phone</label>
                          <input type="tel" x-model="form.phone"
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none">
                </div>
                <div x-show="!editingCoach">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password *</label>
                          <input type="password" x-model="form.password" :required="!editingCoach"
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none">
                </div>
                <div x-show="!editingCoach">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm Password *</label>
                          <input type="password" x-model="form.password_confirmation" :required="!editingCoach"
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none">
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" @click="closeModal()" 
                            class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition">
                        Cancel
                    </button>
                        <button type="submit" :disabled="saving"
                            class="flex-1 px-4 py-3 bg-[#1a307b] text-white rounded-xl font-semibold hover:bg-[#152866] transition disabled:opacity-50">
                        <span x-text="saving ? 'Saving...' : 'Save'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation -->
    <div x-show="showDeleteConfirm" x-cloak @click.self="showDeleteConfirm = false"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Delete Coach?</h3>
            <p class="text-slate-600 mb-6">This action cannot be undone.</p>
            <div class="flex gap-3">
                <button @click="showDeleteConfirm = false" 
                        class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button @click="deleteCoach()" :disabled="deleting"
                        class="flex-1 px-4 py-3 bg-linear-to-r from-red-500 to-red-600 text-white rounded-xl font-semibold hover:shadow-lg transition disabled:opacity-50">
                    <span x-text="deleting ? 'Deleting...' : 'Delete'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Coach Details Modal -->
    <div x-show="showDetailsModal" x-cloak @click.self="closeDetailsModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div @click.away="closeDetailsModal()" 
             class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="sticky top-0 bg-linear-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
                <h3 class="text-lg font-bold">Coach Teaching Statistics</h3>
                <button @click="closeDetailsModal()" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6">
                <template x-if="loadingDetails">
                    <div class="text-center py-12">
                        <div class="animate-spin w-8 h-8 border-4 border-green-500 border-t-transparent rounded-full mx-auto"></div>
                        <p class="text-slate-500 mt-4">Loading statistics...</p>
                    </div>
                </template>
                
                <template x-if="!loadingDetails && coachDetails">
                    <div class="space-y-6">
                        <!-- Coach Info -->
                        <div class="bg-linear-to-r from-green-50 to-emerald-50 rounded-xl p-5 border border-green-100">
                            <h4 class="text-xl font-bold text-slate-800 mb-2" x-text="coachDetails.name"></h4>
                            <div class="flex flex-wrap gap-4 text-sm">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                    <span x-text="coachDetails.email"></span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                    <span x-text="coachDetails.phone || 'No phone'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- This Week -->
                            <div class="bg-linear-to-br from-green-50 to-emerald-50 rounded-xl p-5 border border-green-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h5 class="text-sm font-semibold text-slate-700">This Week</h5>
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                </div>
                                <p class="text-3xl font-bold text-green-700" x-text="statistics?.teaching_count_this_week || 0"></p>
                                <p class="text-xs text-green-600 mt-1">sessions taught</p>
                            </div>

                            <!-- This Month -->
                            <div class="bg-linear-to-br from-blue-50 to-cyan-50 rounded-xl p-5 border border-blue-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h5 class="text-sm font-semibold text-slate-700">This Month</h5>
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                </div>
                                <p class="text-3xl font-bold text-blue-700" x-text="statistics?.teaching_count_this_month || 0"></p>
                                <p class="text-xs text-blue-600 mt-1">sessions taught</p>
                            </div>

                            <!-- This Year -->
                            <div class="bg-linear-to-br from-purple-50 to-pink-50 rounded-xl p-5 border border-purple-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h5 class="text-sm font-semibold text-slate-700">This Year</h5>
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                </div>
                                <p class="text-3xl font-bold text-purple-700" x-text="statistics?.teaching_count_this_year || 0"></p>
                                <p class="text-xs text-purple-600 mt-1">sessions taught</p>
                            </div>

                            <!-- Week Streak -->
                            <div class="bg-linear-to-br from-orange-50 to-red-50 rounded-xl p-5 border border-orange-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h5 class="text-sm font-semibold text-slate-700">Week Streak</h5>
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z"/></svg>
                                </div>
                                <p class="text-3xl font-bold text-orange-700" x-text="statistics?.week_streak || 0"></p>
                                <p class="text-xs text-orange-600 mt-1">consecutive weeks</p>
                            </div>
                        </div>

                        <!-- Total Sessions Taught -->
                        <div class="bg-linear-to-r from-slate-50 to-slate-100 rounded-xl p-5 border border-slate-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="text-sm font-semibold text-slate-700 mb-1">Total Sessions Taught</h5>
                                    <p class="text-2xl font-bold text-slate-800" x-text="statistics?.total_sessions_taught || 0"></p>
                                </div>
                                <div class="w-12 h-12 bg-slate-200 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function coachesData() {
    return {
        coaches: [],
        search: '',
        loading: false,
        saving: false,
        deleting: false,
        showModal: false,
        showDeleteConfirm: false,
        showDetailsModal: false,
        loadingDetails: false,
        editingCoach: null,
        coachToDelete: null,
        coachDetails: null,
        statistics: null,
        form: {
            name: '',
            email: '',
            phone: '',
            password: '',
            password_confirmation: ''
        },
        
        get filteredCoaches() {
            if (!this.search) return this.coaches;
            const query = this.search.toLowerCase();
            return this.coaches.filter(c => 
                c.name.toLowerCase().includes(query) ||
                c.email.toLowerCase().includes(query)
            );
        },
        
        async loadCoaches() {
            if (this.loading) return; // Prevent multiple simultaneous loads
            
            this.loading = true;
            try {
                const response = await API.get('/admin/coaches');
                
                // Validate response
                if (!response || typeof response !== 'object') {
                    throw new Error('Invalid response from server');
                }
                
                if (!Array.isArray(response.data)) {
                    console.warn('Coaches data is not an array:', response.data);
                    this.coaches = [];
                } else {
                    this.coaches = response.data;
                }
            } catch (error) {
                console.error('Failed to load coaches:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to load coaches data';
                showToast(errorMsg, 'error');
                this.coaches = [];
            } finally {
                this.loading = false;
            }
        },
        
        openAddModal() {
            this.editingCoach = null;
            this.form = { name: '', email: '', phone: '', password: '', password_confirmation: '' };
            this.saving = false; // Reset saving state
            this.showModal = true;
        },
        
        openEditModal(coach) {
            // Validate coach object
            if (!coach || !coach.id) {
                showToast('Invalid coach data', 'error');
                return;
            }
            
            this.editingCoach = coach;
            this.form = {
                name: coach.name || '',
                email: coach.email || '',
                phone: coach.phone || '',
                password: '',
                password_confirmation: ''
            };
            this.saving = false; // Reset saving state
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
            this.editingCoach = null;
        },

        async viewCoachDetails(coach) {
            // Validate coach
            if (!coach || !coach.id) {
                showToast('Invalid coach data', 'error');
                return;
            }
            
            this.coachDetails = coach;
            this.statistics = null;
            this.showDetailsModal = true;
            this.loadingDetails = true;
            
            try {
                const response = await API.get(`/admin/coaches/${coach.id}`);
                
                // Validate response
                if (!response || !response.data) {
                    throw new Error('Invalid response from server');
                }
                
                this.coachDetails = response.data;
                this.statistics = response.statistics || {};
            } catch (error) {
                console.error('Failed to load coach details:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to load coach details';
                showToast(errorMsg, 'error');
                
                // Close modal on error
                this.showDetailsModal = false;
            } finally {
                this.loadingDetails = false;
            }
        },

        closeDetailsModal() {
            this.showDetailsModal = false;
            this.coachDetails = null;
            this.statistics = null;
        },
        
        async saveCoach() {
            // Prevent double-submit
            if (this.saving) {
                showToast('Saving in progress...', 'warning');
                return;
            }
            
            // Validate form
            if (!this.form.name || this.form.name.trim() === '') {
                showToast('Name is required', 'error');
                return;
            }
            
            if (this.form.name.trim().length < 3) {
                showToast('Name must be at least 3 characters', 'error');
                return;
            }
            
            if (!this.form.email || this.form.email.trim() === '') {
                showToast('Email is required', 'error');
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(this.form.email)) {
                showToast('Invalid email format', 'error');
                return;
            }
            
            // Password validation for new coach
            if (!this.editingCoach) {
                if (!this.form.password || this.form.password.trim() === '') {
                    showToast('Password is required for new coach', 'error');
                    return;
                }
                
                if (this.form.password.length < 8) {
                    showToast('Password must be at least 8 characters', 'error');
                    return;
                }
                
                if (this.form.password !== this.form.password_confirmation) {
                    showToast('Password confirmation does not match', 'error');
                    return;
                }
            }
            
            // Password validation for edit (only if password is provided)
            if (this.editingCoach && this.form.password && this.form.password.trim() !== '') {
                if (this.form.password.length < 8) {
                    showToast('Password must be at least 8 characters', 'error');
                    return;
                }
                
                if (this.form.password !== this.form.password_confirmation) {
                    showToast('Password confirmation does not match', 'error');
                    return;
                }
            }
            
            // Phone validation (optional)
            if (this.form.phone && this.form.phone.trim() !== '') {
                const phoneRegex = /^[0-9\s\-\+\(\)]+$/;
                if (!phoneRegex.test(this.form.phone)) {
                    showToast('Invalid phone number format', 'error');
                    return;
                }
            }
            
            this.saving = true;
            try {
                let response;
                
                if (this.editingCoach) {
                    // Validate editing coach
                    if (!this.editingCoach.id) {
                        throw new Error('Invalid coach ID');
                    }
                    
                    response = await API.put(`/admin/coaches/${this.editingCoach.id}`, this.form);
                    
                    // Validate response
                    if (!response || !response.data) {
                        throw new Error('Invalid response from server');
                    }
                    
                    const index = this.coaches.findIndex(c => c.id === this.editingCoach.id);
                    if (index > -1) {
                        this.coaches[index] = response.data;
                    } else {
                        console.warn('Coach not found in list, reloading...');
                        await this.loadCoaches();
                    }
                    
                    showToast('✓ Coach updated successfully', 'success');
                } else {
                    response = await API.post('/admin/coaches', this.form);
                    
                    // Validate response
                    if (!response || !response.data) {
                        throw new Error('Invalid response from server');
                    }
                    
                    this.coaches.unshift(response.data);
                    showToast('✓ Coach added successfully', 'success');
                }
                
                this.closeModal();
            } catch (error) {
                console.error('Failed to save coach:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to save coach';
                showToast(errorMsg, 'error');
            } finally {
                this.saving = false;
            }
        },
        
        confirmDelete(coach) {
            // Validate coach
            if (!coach || !coach.id) {
                showToast('Invalid coach data', 'error');
                return;
            }
            
            this.coachToDelete = coach;
            this.showDeleteConfirm = true;
        },
        
        async deleteCoach() {
            // Validate coach
            if (!this.coachToDelete || !this.coachToDelete.id) {
                showToast('Invalid coach data', 'error');
                this.showDeleteConfirm = false;
                return;
            }
            
            // Prevent double-submit
            if (this.deleting) {
                showToast('Deletion in progress...', 'warning');
                return;
            }
            
            this.deleting = true;
            try {
                await API.delete(`/admin/coaches/${this.coachToDelete.id}`);
                this.coaches = this.coaches.filter(c => c.id !== this.coachToDelete.id);
                showToast('✓ Coach deleted successfully', 'success');
                this.showDeleteConfirm = false;
                this.coachToDelete = null;
            } catch (error) {
                console.error('Failed to delete coach:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to delete coach';
                showToast(errorMsg, 'error');
            } finally {
                this.deleting = false;
            }
        }
    }
}
</script>
@endpush
@endsection