@extends('layouts.admin')

@section('title', 'Member Packages')
@section('subtitle', 'Manage member package assignments')

@section('content')
<div x-data="memberPackagesData()" x-init="init()" class="space-y-6">
    
    <!-- Header Actions -->
    <div class="card-animate flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
        <div class="flex-1 w-full sm:max-w-md">
            <input type="search" x-model="search" placeholder="Search by member name..." 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
        </div>
        <button @click="openAssignModal()" 
                class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all whitespace-nowrap shrink-0">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Assign Package
            </span>
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6" style="animation-delay: 0.1s">        <div class="card-animate bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Assignments</p>
                    <p class="text-3xl font-bold mt-2" x-text="memberPackages.length"></p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
            </div>
        </div>
        <div class="card-animate bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white" style="animation-delay: 0.15s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Active Packages</p>
                    <p class="text-3xl font-bold mt-2" x-text="memberPackages.filter(p => p.is_active).length"></p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="card-animate bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-6 text-white" style="animation-delay: 0.2s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm font-medium">Expired Packages</p>
                    <p class="text-3xl font-bold mt-2" x-text="memberPackages.filter(p => new Date(p.end_date) < new Date()).length"></p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="card-animate bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white" style="animation-delay: 0.25s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Sessions</p>
                    <p class="text-3xl font-bold mt-2" x-text="memberPackages.reduce((sum, p) => sum + p.total_sessions, 0)"></p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Packages Table - Desktop View -->
    <div class="card-animate hidden lg:block bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" style="animation-delay: 0.3s">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Package</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Sessions</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Validated By</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <template x-if="loading">
                        <tr><td colspan="7" class="px-6 py-12 text-center text-slate-400">Loading...</td></tr>
                    </template>
                    <template x-if="!loading && filteredMemberPackages.length === 0">
                        <tr><td colspan="7" class="px-6 py-12 text-center text-slate-400">No member packages found</td></tr>
                    </template>
                    <template x-for="mp in filteredMemberPackages" :key="mp.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-slate-800" x-text="mp.member?.name"></p>
                                    <p class="text-sm text-slate-500" x-text="mp.member?.phone"></p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-slate-800" x-text="mp.package?.name"></p>
                                    <p class="text-sm text-slate-500">Rp <span x-text="Number(mp.package?.price || 0).toLocaleString('id-ID')"></span></p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-full transition-all" 
                                             :style="'width: ' + ((mp.used_sessions / mp.total_sessions) * 100) + '%'"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-700" x-text="mp.used_sessions + '/' + mp.total_sessions"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <p class="text-slate-700" x-text="new Date(mp.start_date).toLocaleDateString('id-ID')"></p>
                                    <p class="text-slate-500">to <span x-text="new Date(mp.end_date).toLocaleDateString('id-ID')"></span></p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <template x-if="mp.is_active && new Date(mp.end_date) >= new Date()">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>
                                        Active
                                    </span>
                                </template>
                                <template x-if="mp.is_active && new Date(mp.end_date) < new Date()">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-2"></span>
                                        Expired
                                    </span>
                                </template>
                                <template x-if="!mp.is_active">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                        <span class="w-1.5 h-1.5 bg-slate-500 rounded-full mr-2"></span>
                                        Inactive
                                    </span>
                                </template>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <p class="text-slate-700" x-text="mp.validator?.name || '-'"></p>
                                    <p class="text-slate-500" x-text="mp.validated_at ? new Date(mp.validated_at).toLocaleDateString('id-ID') : '-'"></p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button @click="viewDetails(mp)" 
                                        class="text-indigo-600 hover:text-indigo-800 font-medium text-sm transition">
                                    View
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Member Packages Cards - Mobile View -->
    <div class="lg:hidden space-y-4">
        <template x-if="loading">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 text-center text-slate-400">
                Loading...
            </div>
        </template>
        <template x-if="!loading && filteredMemberPackages.length === 0">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 text-center text-slate-400">
                No member packages found
            </div>
        </template>
        <template x-for="mp in filteredMemberPackages" :key="mp.id">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 space-y-4">
                <!-- Member Info -->
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="font-bold text-slate-800 text-lg" x-text="mp.member?.name"></p>
                        <p class="text-sm text-slate-500" x-text="mp.member?.phone"></p>
                    </div>
                    <template x-if="mp.is_active && new Date(mp.end_date) >= new Date()">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>
                            Active
                        </span>
                    </template>
                    <template x-if="mp.is_active && new Date(mp.end_date) < new Date()">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-2"></span>
                            Expired
                        </span>
                    </template>
                    <template x-if="!mp.is_active">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                            <span class="w-1.5 h-1.5 bg-slate-500 rounded-full mr-2"></span>
                            Inactive
                        </span>
                    </template>
                </div>

                <!-- Package Info -->
                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-4">
                    <p class="font-semibold text-slate-800" x-text="mp.package?.name"></p>
                    <p class="text-sm text-indigo-600 font-medium mt-1">
                        Rp <span x-text="Number(mp.package?.price || 0).toLocaleString('id-ID')"></span>
                    </p>
                </div>

                <!-- Sessions Progress -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-600">Sessions</span>
                        <span class="text-sm font-bold text-slate-800" x-text="mp.used_sessions + '/' + mp.total_sessions"></span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-full transition-all" 
                             :style="'width: ' + ((mp.used_sessions / mp.total_sessions) * 100) + '%'"></div>
                    </div>
                </div>

                <!-- Period -->
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-slate-500 text-xs mb-1">Start Date</p>
                        <p class="font-medium text-slate-800" x-text="new Date(mp.start_date).toLocaleDateString('id-ID')"></p>
                    </div>
                    <div>
                        <p class="text-slate-500 text-xs mb-1">End Date</p>
                        <p class="font-medium text-slate-800" x-text="new Date(mp.end_date).toLocaleDateString('id-ID')"></p>
                    </div>
                </div>

                <!-- Validator -->
                <div class="pt-4 border-t border-slate-100">
                    <p class="text-xs text-slate-500 mb-1">Validated By</p>
                    <p class="text-sm font-medium text-slate-700" x-text="mp.validator?.name || '-'"></p>
                    <p class="text-xs text-slate-500" x-text="mp.validated_at ? new Date(mp.validated_at).toLocaleDateString('id-ID') : '-'"></p>
                </div>

                <!-- Action -->
                <button @click="viewDetails(mp)" 
                        class="w-full px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                    View Details
                </button>
            </div>
        </template>
    </div>

    <!-- Assign Package Modal -->
    <div x-show="showAssignModal" x-cloak @click.self="closeAssignModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div @click.away="closeAssignModal()" 
             class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto"
             x-transition>
            <div class="sticky top-0 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
                <h3 class="text-lg font-bold">Assign Package to Member</h3>
                <button @click="closeAssignModal()" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form @submit.prevent="assignPackage()" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Select Member *</label>
                    <select x-model="assignForm.member_id" required
                            class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                        <option value="">-- Choose Member --</option>
                        <template x-for="member in members" :key="member.id">
                            <option :value="member.id" x-text="member.name + ' (' + member.phone + ')'"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Select Package *</label>
                    <select x-model="assignForm.package_id" required
                            @change="updatePackageInfo()"
                            class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                        <option value="">-- Choose Package --</option>
                        <template x-for="pkg in packages" :key="pkg.id">
                            <option :value="pkg.id" x-text="pkg.name + ' - Rp ' + Number(pkg.price).toLocaleString('id-ID')"></option>
                        </template>
                    </select>
                </div>
                <template x-if="assignForm.package_id">
                    <div class="bg-indigo-50 p-4 rounded-xl">
                        <p class="text-sm font-semibold text-indigo-900 mb-2">Package Details:</p>
                        <div class="space-y-1 text-sm text-indigo-700">
                            <p><span class="font-medium">Duration:</span> <span x-text="selectedPackageInfo?.duration_days"></span> days</p>
                            <p><span class="font-medium">Sessions:</span> <span x-text="selectedPackageInfo?.session_count"></span> sessions</p>
                            <p><span class="font-medium">Price:</span> Rp <span x-text="Number(selectedPackageInfo?.price || 0).toLocaleString('id-ID')"></span></p>
                        </div>
                    </div>
                </template>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Start Date *</label>
                    <input type="date" x-model="assignForm.start_date" required
                           class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                </div>
                <div class="flex gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="closeAssignModal()" 
                            class="flex-1 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold transition">
                        Cancel
                    </button>
                    <button type="submit" :disabled="submitting"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:shadow-lg text-white rounded-xl font-semibold transition disabled:opacity-50">
                        <span x-show="!submitting">Assign</span>
                        <span x-show="submitting">Assigning...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Details Modal -->
    <div x-show="showDetailsModal" x-cloak @click.self="closeDetailsModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div @click.away="closeDetailsModal()" 
             class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
             x-transition>
            <div class="sticky top-0 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
                <h3 class="text-lg font-bold">Member Package Details</h3>
                <button @click="closeDetailsModal()" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-6" x-show="selectedMemberPackage">
                <!-- Member Info -->
                <div class="bg-slate-50 rounded-xl p-4">
                    <h4 class="font-bold text-slate-800 mb-3">Member Information</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-slate-500">Name</p>
                            <p class="font-semibold text-slate-800" x-text="selectedMemberPackage?.member?.name"></p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Phone</p>
                            <p class="font-semibold text-slate-800" x-text="selectedMemberPackage?.member?.phone"></p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Status</p>
                            <p class="font-semibold text-slate-800" x-text="selectedMemberPackage?.member?.status"></p>
                        </div>
                    </div>
                </div>

                <!-- Package Info -->
                <div class="bg-indigo-50 rounded-xl p-4">
                    <h4 class="font-bold text-indigo-900 mb-3">Package Information</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-indigo-600">Package Name</p>
                            <p class="font-semibold text-indigo-900" x-text="selectedMemberPackage?.package?.name"></p>
                        </div>
                        <div>
                            <p class="text-sm text-indigo-600">Price</p>
                            <p class="font-semibold text-indigo-900">Rp <span x-text="Number(selectedMemberPackage?.package?.price || 0).toLocaleString('id-ID')"></span></p>
                        </div>
                        <div>
                            <p class="text-sm text-indigo-600">Duration</p>
                            <p class="font-semibold text-indigo-900" x-text="selectedMemberPackage?.package?.duration_days + ' days'"></p>
                        </div>
                        <div>
                            <p class="text-sm text-indigo-600">Total Sessions</p>
                            <p class="font-semibold text-indigo-900" x-text="selectedMemberPackage?.total_sessions"></p>
                        </div>
                    </div>
                </div>

                <!-- Usage Info -->
                <div class="bg-green-50 rounded-xl p-4">
                    <h4 class="font-bold text-green-900 mb-3">Usage Information</h4>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-green-700">Sessions Used</span>
                                <span class="text-sm font-bold text-green-900" x-text="selectedMemberPackage?.used_sessions + ' / ' + selectedMemberPackage?.total_sessions"></span>
                            </div>
                            <div class="w-full bg-green-200 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-green-500 to-green-600 h-full transition-all" 
                                     :style="'width: ' + ((selectedMemberPackage?.used_sessions / selectedMemberPackage?.total_sessions) * 100) + '%'"></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-green-600">Start Date</p>
                                <p class="font-semibold text-green-900" x-text="selectedMemberPackage?.start_date ? new Date(selectedMemberPackage.start_date).toLocaleDateString('id-ID') : '-'"></p>
                            </div>
                            <div>
                                <p class="text-sm text-green-600">End Date</p>
                                <p class="font-semibold text-green-900" x-text="selectedMemberPackage?.end_date ? new Date(selectedMemberPackage.end_date).toLocaleDateString('id-ID') : '-'"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Validation Info -->
                <div class="bg-purple-50 rounded-xl p-4">
                    <h4 class="font-bold text-purple-900 mb-3">Validation Information</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-purple-600">Validated By</p>
                            <p class="font-semibold text-purple-900" x-text="selectedMemberPackage?.validator?.name || '-'"></p>
                        </div>
                        <div>
                            <p class="text-sm text-purple-600">Validated At</p>
                            <p class="font-semibold text-purple-900" x-text="selectedMemberPackage?.validated_at ? new Date(selectedMemberPackage.validated_at).toLocaleDateString('id-ID') : '-'"></p>
                        </div>
                        <div>
                            <p class="text-sm text-purple-600">Status</p>
                            <template x-if="selectedMemberPackage?.is_active && new Date(selectedMemberPackage?.end_date) >= new Date()">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>
                                    Active
                                </span>
                            </template>
                            <template x-if="selectedMemberPackage?.is_active && new Date(selectedMemberPackage?.end_date) < new Date()">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-2"></span>
                                    Expired
                                </span>
                            </template>
                            <template x-if="!selectedMemberPackage?.is_active">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                    <span class="w-1.5 h-1.5 bg-slate-500 rounded-full mr-2"></span>
                                    Inactive
                                </span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function memberPackagesData() {
        return {
            memberPackages: [],
            members: [],
            packages: [],
            loading: false,
            submitting: false,
            search: '',
            showAssignModal: false,
            showDetailsModal: false,
            selectedMemberPackage: null,
            selectedPackageInfo: null,
            assignForm: {
                member_id: '',
                package_id: '',
                start_date: new Date().toISOString().split('T')[0]
            },

            async init() {
                await this.loadMemberPackages();
                await this.loadMembers();
                await this.loadPackages();
            },

            async loadMemberPackages() {
                this.loading = true;
                try {
                    const data = await window.API.get('/admin/member-packages');
                    this.memberPackages = data.data || [];
                } catch (error) {
                    window.showToast('Failed to load member packages: ' + error.message, 'error');
                } finally {
                    this.loading = false;
                }
            },

            async loadMembers() {
                try {
                    const data = await window.API.get('/admin/members');
                    this.members = data.data || [];
                } catch (error) {
                    console.error('Failed to load members:', error);
                    window.showToast('Failed to load members: ' + error.message, 'error');
                }
            },

            async loadPackages() {
                try {
                    const data = await window.API.get('/admin/packages');
                    this.packages = data.data || [];
                } catch (error) {
                    console.error('Failed to load packages:', error);
                    window.showToast('Failed to load packages: ' + error.message, 'error');
                }
            },

            get filteredMemberPackages() {
                if (!this.search) return this.memberPackages;
                const searchLower = this.search.toLowerCase();
                return this.memberPackages.filter(mp => 
                    mp.member?.name?.toLowerCase().includes(searchLower) ||
                    mp.package?.name?.toLowerCase().includes(searchLower)
                );
            },

            openAssignModal() {
                this.showAssignModal = true;
                this.assignForm = {
                    member_id: '',
                    package_id: '',
                    start_date: new Date().toISOString().split('T')[0]
                };
                this.selectedPackageInfo = null;
            },

            closeAssignModal() {
                this.showAssignModal = false;
                this.assignForm = {
                    member_id: '',
                    package_id: '',
                    start_date: new Date().toISOString().split('T')[0]
                };
                this.selectedPackageInfo = null;
            },

            updatePackageInfo() {
                this.selectedPackageInfo = this.packages.find(p => p.id == this.assignForm.package_id);
            },

            async assignPackage() {
                if (this.submitting) return;
                
                this.submitting = true;
                try {
                    await window.API.post(`/admin/members/${this.assignForm.member_id}/assign-package`, {
                        package_id: this.assignForm.package_id,
                        start_date: this.assignForm.start_date
                    });
                    
                    window.showToast('Package assigned successfully', 'success');
                    this.closeAssignModal();
                    await this.loadMemberPackages();
                } catch (error) {
                    window.showToast('Failed to assign package: ' + error.message, 'error');
                } finally {
                    this.submitting = false;
                }
            },

            viewDetails(memberPackage) {
                this.selectedMemberPackage = memberPackage;
                this.showDetailsModal = true;
            },

            closeDetailsModal() {
                this.showDetailsModal = false;
                this.selectedMemberPackage = null;
            }
        };
    }
</script>
@endsection
