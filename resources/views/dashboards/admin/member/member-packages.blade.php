@extends('layouts.admin')

@section('title', 'Member Packages')
@section('subtitle', 'Manage member package assignments')

@section('content')
<div x-data="memberPackagesData()" x-init="init()" class="space-y-6">
    <!-- Header Actions -->
    <div class="card-animate flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
        <div class="flex-1 w-full">
            <input type="search" x-model="search" placeholder="Search by member name..." 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
        </div>
    </div>

    <!-- Member Packages Table - Desktop View -->
    <div class="card-animate hidden lg:block bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" style="animation-delay: 0.3s">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="pl-14 py-6 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Member</th>
                        <th class="pr-6 py-6 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Package</th>
                        <th class="px-6 py-6 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Sessions</th>
                        <th class="px-6 py-6 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-6 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-6 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <template x-if="loading">
                        <tr><td colspan="7" class="px-6 py-12 text-center text-slate-400">Loading...</td></tr>
                    </template>
                    <template x-if="!loading && filteredRows.length === 0">
                        <tr><td colspan="7" class="px-6 py-12 text-center text-slate-400">No member packages found</td></tr>
                    </template>
                    <template x-for="mp in filteredRows" :key="mp.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="pl-11 py-6 text-left">
                                <div>
                                    <p class="font-semibold text-slate-800" x-text="mp.member?.name"></p>
                                </div>
                            </td>
                            <td class="pr-6 py-6 text-center">
                                <div>
                                    <p class="font-semibold text-slate-800" x-text="mp.package?.name || 'No Package'"></p>
                                </div>
                            </td>
                            <td class="px-6 py-6 ">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-full transition-all" 
                                             :style="'width: ' + (mp.total_sessions > 0 ? ((mp.used_sessions / mp.total_sessions) * 100) : 0) + '%'""></div>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-700" x-text="mp.used_sessions + '/' + mp.total_sessions"></span>
                                </div>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <template x-if="mp.start_date && mp.end_date">
                                    <div class="text-sm">
                                        <p class="text-slate-700" x-text="mp.start_date ? new Date(mp.start_date).toLocaleDateString('id-ID') : '-' "></p>
                                        <p class="text-slate-500">s.d</p>
                                        <p class="text-slate-700" x-text="mp.end_date ? new Date(mp.end_date).toLocaleDateString('id-ID') : '-' "></p>
                                    </div>
                                </template>
                                <template x-if="!mp.start_date || !mp.end_date">
                                    <span class="text-sm text-slate-400">-</span>
                                </template>
                                
                            </td>
                            <td class="px-6 py-6 text-center">
                                <template x-if="mp.member?.status === 'pending'">
                                    <span class="inline-flex items-center px-3 py-1 min-w-[80px] rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-2"></span>
                                        Pending
                                    </span>
                                </template>
                                <template x-if="mp.member?.status !== 'pending' && mp.package_id && mp.is_active && mp.end_date && new Date(mp.end_date) >= new Date()">
                                    <span class="inline-flex items-center px-3 py-1 min-w-[80px] rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>
                                        Active
                                    </span>
                                </template>
                                <template x-if="mp.member?.status !== 'pending' && !(mp.package_id && mp.is_active && mp.end_date && new Date(mp.end_date) >= new Date())">
                                    <span class="inline-flex items-center px-3 py-1 min-w-[80px] rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-2"></span>
                                        Expired
                                    </span>
                                </template>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button @click="viewDetails(mp)" 
                                        class="text-indigo-600 hover:text-indigo-800 font-medium text-sm transition">
                                    Manage
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
        <template x-if="!loading && filteredRows.length === 0">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 text-center text-slate-400">
                No member packages found
            </div>
        </template>
        <template x-for="mp in filteredRows" :key="mp.id">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 space-y-4">
                <!-- Member Info -->
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="font-bold text-slate-800 text-lg" x-text="mp.member?.name"></p>
                        <p class="text-sm text-slate-500" x-text="mp.member?.phone"></p>
                    </div>
                    <template x-if="mp.member?.status === 'pending'">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-2"></span>
                            Pending
                        </span>
                    </template>
                    <template x-if="mp.member?.status !== 'pending' && mp.package_id && mp.is_active && mp.end_date && new Date(mp.end_date) >= new Date()">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>
                            Active
                        </span>
                    </template>
                    <template x-if="mp.member?.status !== 'pending' && !(mp.package_id && mp.is_active && mp.end_date && new Date(mp.end_date) >= new Date())">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-2"></span>
                            Expired
                        </span>
                    </template>
                </div>

                <!-- Package Info -->
                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-4">
                    <p class="font-semibold text-slate-800" x-text="mp.package?.name || 'No Package'"></p>
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
                             :style="'width: ' + (mp.total_sessions > 0 ? ((mp.used_sessions / mp.total_sessions) * 100) : 0) + '%'""></div>
                    </div>
                </div>

                <!-- Period -->
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-slate-500 text-xs mb-1">Start Date</p>
                        <p class="font-medium text-slate-800" x-text="mp.start_date ? new Date(mp.start_date).toLocaleDateString('id-ID') : '-' "></p>
                    </div>
                    <div>
                        <p class="text-slate-500 text-xs mb-1">End Date</p>
                        <p class="font-medium text-slate-800" x-text="mp.end_date ? new Date(mp.end_date).toLocaleDateString('id-ID') : '-' "></p>
                    </div>
                </div>
                <!-- Action -->
                <button @click="viewDetails(mp)" 
                        class="w-full px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                    Manage
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
                        <template x-for="pkg in activePackages" :key="pkg.id">
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
                <h3 class="text-lg font-bold">Member Details</h3>
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
                            <template x-if="selectedMemberPackage?.member?.status === 'pending'">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-2"></span>
                                    Pending
                                </span>
                            </template>
                            <template x-if="selectedMemberPackage?.member?.status !== 'pending' && selectedMemberPackage?.package_id && selectedMemberPackage?.is_active && selectedMemberPackage?.end_date && new Date(selectedMemberPackage?.end_date) >= new Date()">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>
                                    Active
                                </span>
                            </template>
                            <template x-if="selectedMemberPackage?.member?.status !== 'pending' && !(selectedMemberPackage?.package_id && selectedMemberPackage?.is_active && selectedMemberPackage?.end_date && new Date(selectedMemberPackage?.end_date) >= new Date())">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-2"></span>
                                    Expired
                                </span>
                            </template>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Validated At</p>
                            <p class="font-semibold text-slate-800" x-text="selectedMemberPackage?.validated_at ? new Date(selectedMemberPackage.validated_at).toLocaleDateString('id-ID') : '-'"></p>
                        </div>
                    </div>
                </div>

                <!-- Package Info -->
                <template x-if="selectedMemberPackage?.package_id">
                    <div class="bg-indigo-50 rounded-xl p-4">
                        <h4 class="font-bold text-indigo-900 mb-3">Package Information</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-indigo-600">Package Name</p>
                                <p class="font-semibold text-indigo-900" x-text="selectedMemberPackage?.package?.name || '-' "></p>
                            </div>
                            <div>
                                <p class="text-sm text-indigo-600">Price</p>
                                <p class="font-semibold text-indigo-900">Rp <span x-text="Number(selectedMemberPackage?.package?.price || 0).toLocaleString('id-ID')"></span></p>
                            </div>
                            <div>
                                <p class="text-sm text-indigo-600">Duration</p>
                                <p class="font-semibold text-indigo-900" x-text="selectedMemberPackage?.package?.duration_days ? (selectedMemberPackage.package.duration_days + ' days') : '-' "></p>
                            </div>
                            <div>
                                <p class="text-sm text-indigo-600">Total Sessions</p>
                                <p class="font-semibold text-indigo-900" x-text="selectedMemberPackage?.total_sessions"></p>
                            </div>
                            <div>
                                <p class="text-sm text-green-600">Start Date</p>
                                <p class="font-semibold text-green-900" x-text="selectedMemberPackage?.start_date ? new Date(selectedMemberPackage.start_date).toLocaleDateString('id-ID') : '-'">
                            </div>
                            <div>
                                <p class="text-sm text-green-600">End Date</p>
                                <p class="font-semibold text-green-900" x-text="selectedMemberPackage?.end_date ? new Date(selectedMemberPackage.end_date).toLocaleDateString('id-ID') : '-'">
                            </div>
                        </div>

                        <h4 class="font-bold text-green-900 mb-3 mt-3">Usage Information</h4>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm text-green-700">Sessions Used</span>
                                    <span class="text-sm font-bold text-green-900" x-text="selectedMemberPackage?.used_sessions + ' / ' + selectedMemberPackage?.total_sessions"></span>
                                </div>
                                <div class="w-full bg-green-200 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-green-500 to-green-600 h-full transition-all" 
                                         :style="'width: ' + (selectedMemberPackage?.total_sessions > 0 ? ((selectedMemberPackage.used_sessions / selectedMemberPackage.total_sessions) * 100) : 0) + '%'""></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template x-if="!selectedMemberPackage?.package_id">
                    <div class="bg-indigo-50 rounded-xl p-4">
                        <h4 class="font-bold text-indigo-900 mb-1">Package Information</h4>
                        <p class="text-sm text-indigo-700">No package assigned.</p>
                    </div>
                </template>

                <!-- Usage Info -->
                <template x-if="selectedMemberPackage?.package_id">
                    
                </template>

                <!-- Actions -->
                <div class="flex justify-end gap-3">
                    <button @click="toggleMemberActiveFromDetails()"
                            :disabled="togglingMember"
                            class="px-5 py-2.5 border border-slate-200 text-slate-700 rounded-xl font-semibold hover:bg-slate-50 transition disabled:opacity-50">
                        <span x-text="(selectedMemberPackage?.member?.status === 'inactive' || selectedMemberPackage?.member?.is_active === false) ? (togglingMember ? 'Activating...' : 'Activate') : (togglingMember ? 'Deactivating...' : 'Deactivate')"></span>
                    </button>

                    <button @click="openAssignFromDetails()"
                            class="px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all disabled:opacity-50">
                        <span x-text="selectedMemberPackage?.package_id ? 'Update Package' : 'Assign Package'"></span>
                    </button>
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
            togglingMember: false,
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

                    // if current selection is inactive, force user to pick an active package
                    if (this.assignForm?.package_id) {
                        const selected = this.packages.find(p => String(p.id) === String(this.assignForm.package_id));
                        if (selected && selected.is_active === false) {
                            this.assignForm.package_id = '';
                            this.selectedPackageInfo = null;
                        }
                    }
                } catch (error) {
                    console.error('Failed to load packages:', error);
                    window.showToast('Failed to load packages: ' + error.message, 'error');
                }
            },

            get activePackages() {
                return (this.packages || []).filter(p => p?.is_active !== false);
            },

            get displayRows() {
                const byMemberId = new Map();

                for (const mp of (this.memberPackages || [])) {
                    const key = String(mp.member_id);
                    const existing = byMemberId.get(key);
                    if (!existing || (Number(mp.id) > Number(existing.id))) {
                        byMemberId.set(key, mp);
                    }
                }

                return (this.members || []).map(member => {
                    const mp = byMemberId.get(String(member.id));
                    if (mp) {
                        // Always prefer canonical member data from /admin/members
                        // so inactive/active status is always up-to-date.
                        mp.member = member;

                        if (mp.package_id) {
                            mp.package = (this.packages || []).find(p => String(p.id) === String(mp.package_id)) || mp.package;
                        }

                        return mp;
                    }

                    return {
                        id: `member-${member.id}`,
                        member_id: member.id,
                        member,
                        package_id: null,
                        package: null,
                        total_sessions: 0,
                        used_sessions: 0,
                        start_date: null,
                        end_date: null,
                        is_active: false,
                    };
                });
            },

            get filteredRows() {
                if (!this.search) return this.displayRows;
                const searchLower = this.search.toLowerCase();
                return this.displayRows.filter(mp =>
                    (mp.member?.name || '').toLowerCase().includes(searchLower) ||
                    (mp.member?.phone || '').toLowerCase().includes(searchLower) ||
                    (mp.package?.name || '').toLowerCase().includes(searchLower)
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
                this.selectedPackageInfo = this.activePackages.find(p => p.id == this.assignForm.package_id);
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
            },

            async toggleMemberActiveFromDetails() {
                const mp = this.selectedMemberPackage;
                const memberId = mp?.member_id;
                if (!memberId || this.togglingMember) return;

                this.togglingMember = true;
                try {
                    const isInactive = mp?.member?.status === 'inactive' || mp?.member?.is_active === false;
                    if (isInactive) {
                        await window.API.post(`/admin/members/${memberId}/restore`);
                        window.showToast('Member activated successfully', 'success');
                    } else {
                        await window.API.delete(`/admin/members/${memberId}`);
                        window.showToast('Member deactivated successfully', 'success');
                    }

                    await this.loadMembers();
                    await this.loadMemberPackages();

                    // refresh selectedMemberPackage from latest computed rows
                    const refreshed = this.displayRows.find(r => String(r.member_id) === String(memberId));
                    this.selectedMemberPackage = refreshed || this.selectedMemberPackage;
                } catch (error) {
                    window.showToast('Failed to update member status: ' + (error.message || 'Unknown error'), 'error');
                } finally {
                    this.togglingMember = false;
                }
            },

            openAssignFromDetails() {
                const mp = this.selectedMemberPackage;
                if (!mp?.member_id) return;

                if (mp?.member?.status === 'inactive' || mp?.member?.is_active === false) {
                    window.showToast('Member inactive akan diaktifkan otomatis saat package di-assign', 'info');
                }

                // Close details first to avoid stacked modals
                this.showDetailsModal = false;

                const today = new Date().toISOString().split('T')[0];
                const startDate = mp.start_date
                    ? new Date(mp.start_date).toISOString().split('T')[0]
                    : today;

                this.showAssignModal = true;
                this.assignForm = {
                    member_id: mp.member_id,
                    package_id: (mp.package?.is_active === false) ? '' : (mp.package_id || ''),
                    start_date: startDate,
                };

                this.selectedPackageInfo = this.activePackages.find(p => p.id == this.assignForm.package_id) || null;
            }
        };
    }
</script>
@endsection
