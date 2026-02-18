@extends('admin.app')

@section('title', 'Members')
@section('subtitle', 'Manage archery club members')

@section('content')
<div x-data="membersData()" x-init="loadMembers()" class="space-y-6">
    
    <!-- Header Actions -->
    <div class="card-animate flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-4">
        <div class="flex-1 w-full">
                 <input type="search" x-model="search" placeholder="Search members..." 
                     class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none transition">
        </div>
        <button @click="openAddModal()" 
            class="w-full sm:w-auto px-4 py-2 sm:px-6 sm:py-3 text-sm bg-[#1a307b] text-white rounded-xl font-semibold hover:bg-[#152866] transition-all whitespace-nowrap shrink-0">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Member
            </span>
        </button>
    </div>

    <!-- Members Table - Desktop View -->
    <div class="card-animate hidden md:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" style="animation-delay: 0.1s">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-if="loading">
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">Loading...</td>
                        </tr>
                    </template>
                    <template x-if="!loading && filteredMembers.length === 0">
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">No members found</td>
                        </tr>
                    </template>
                    <template x-for="member in filteredMembers" :key="member.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">                                    <div>
                                        <p class="font-semibold text-slate-800" x-text="member.name"></p>                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600" x-text="member.phone || '-'"></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-bold rounded-full"
                                      :class="{
                                          'bg-green-50 text-green-700 border border-green-200': member.status === 'active',
                                          'bg-amber-50 text-amber-700 border border-amber-200': member.status === 'pending',
                                          'bg-slate-50 text-slate-700 border border-slate-200': member.status !== 'active' && member.status !== 'pending'
                                      }"
                                      x-text="member.status || 'Unknown'"></span>
                            </td>
                            <td class="px-6 py-4 text-slate-600" x-text="member.is_self ? 'Self' : 'Child'"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="viewMemberDetails(member)" 
                                            class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition"
                                            title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </button>
                                        <button @click="openEditModal(member)" 
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

    <!-- Members Cards - Mobile View -->
    <div class="md:hidden space-y-3">
        <template x-if="loading">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 text-center text-slate-400">
                Loading...
            </div>
        </template>
        <template x-if="!loading && filteredMembers.length === 0">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 text-center text-slate-400">
                No members found
            </div>
        </template>
        <template x-for="member in filteredMembers" :key="member.id">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 space-y-3">
                <!-- Member Info -->
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-slate-800 text-sm truncate" x-text="member.name"></h3>
                        <p class="text-xs text-slate-500 mt-0.5" x-text="member.phone || '-'"></p>
                    </div>
                    <div class="ml-3">
                        <span class="px-2 py-1 text-[10px] font-bold rounded-full whitespace-nowrap"
                              :class="{
                                  'bg-green-50 text-green-700 border border-green-200': member.status === 'active',
                                  'bg-amber-50 text-amber-700 border border-amber-200': member.status === 'pending',
                                  'bg-slate-50 text-slate-700 border border-slate-200': member.status !== 'active' && member.status !== 'pending'
                              }"
                              x-text="member.status || 'Unknown'"></span>
                    </div>
                </div>

                <!-- Type -->
                <div class="flex items-center gap-2 text-xs text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span x-text="member.is_self ? 'Self' : 'Child'"></span>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 pt-2 border-t border-slate-100">
                    <button @click="viewMemberDetails(member)"
                            class="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-xs font-medium text-green-600 bg-green-50 hover:bg-green-100 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Details
                    </button>
                    <button @click="openEditModal(member)"
                            class="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-xs font-medium text-[#1a307b] bg-[#1a307b]/10 hover:bg-[#1a307b]/20 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                        Edit
                    </button>
                </div>
            </div>
        </template>
    </div>

    <!-- Add/Edit Modal -->
    <div x-show="showModal" x-cloak @click.self="closeModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div @click.away="closeModal()" 
             class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="sticky top-0 bg-[#1a307b] text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
                <h3 class="text-lg font-bold" x-text="editingMember ? 'Edit Member' : 'Add New Member'"></h3>
                <button @click="closeModal()" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form @submit.prevent="saveMember()" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Name *</label>
                          <input type="text" x-model="form.name" required
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Phone</label>
                          <input type="tel" x-model="form.phone"
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none">
                </div>
                <div class="flex gap-4 pt-4">
                    <template x-if="editingMember">
                        <button type="button" @click="confirmToggle(editingMember)"
                                class="flex-1 px-4 py-3 text-white rounded-xl font-semibold hover:shadow-lg transition"
                                :class="(editingMember?.status === 'inactive' || editingMember?.is_active === false) ? 'bg-linear-to-r from-green-500 to-green-600' : 'bg-linear-to-r from-red-500 to-red-600'">
                            <span x-text="(editingMember?.status === 'inactive' || editingMember?.is_active === false) ? 'Activate' : 'Deactivate'"></span>
                        </button>
                    </template>
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

    <!-- Activate/Deactivate Confirmation -->
    <div x-show="showToggleConfirm" x-cloak @click.self="showToggleConfirm = false"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                 :class="(memberToToggle?.status === 'inactive' || memberToToggle?.is_active === false) ? 'bg-green-50' : 'bg-red-50'">
                <template x-if="memberToToggle?.status === 'inactive' || memberToToggle?.is_active === false">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </template>
                <template x-if="memberToToggle?.status !== 'inactive' && memberToToggle?.is_active !== false">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6"/></svg>
                </template>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2"
                x-text="(memberToToggle?.status === 'inactive' || memberToToggle?.is_active === false) ? 'Activate Member?' : 'Deactivate Member?'"></h3>
            <p class="text-slate-600 mb-6"
               x-text="(memberToToggle?.status === 'inactive' || memberToToggle?.is_active === false) ? 'The member will be activated again.' : 'The member will be set to inactive.'"></p>
            <div class="flex gap-3">
                <button @click="showToggleConfirm = false" 
                        class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button @click="toggleMember()" :disabled="toggling"
                        class="flex-1 px-4 py-3 text-white rounded-xl font-semibold hover:shadow-lg transition disabled:opacity-50"
                        :class="(memberToToggle?.status === 'inactive' || memberToToggle?.is_active === false) ? 'bg-linear-to-r from-green-500 to-green-600' : 'bg-linear-to-r from-red-500 to-red-600'">
                    <span x-text="(memberToToggle?.status === 'inactive' || memberToToggle?.is_active === false) ? (toggling ? 'Activating...' : 'Activate') : (toggling ? 'Deactivating...' : 'Deactivate')"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Member Details Modal -->
    <div x-show="showDetailsModal" x-cloak @click.self="closeDetailsModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div @click.away="closeDetailsModal()" 
             class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="sticky top-0 bg-[#1a307b] text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
                <h3 class="text-lg font-bold">Member Training Statistics</h3>
                <button @click="closeDetailsModal()" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6">
                <template x-if="loadingDetails">
                    <div class="text-center py-12">
                        <div class="animate-spin w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full mx-auto"></div>
                        <p class="text-slate-500 mt-4">Loading statistics...</p>
                    </div>
                </template>
                
                <template x-if="!loadingDetails && memberDetails">
                    <div class="space-y-6">
                        <!-- Member Info -->
                        <div class="bg-linear-to-r from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-100">
                            <h4 class="text-xl font-bold text-slate-800 mb-2" x-text="memberDetails.name"></h4>
                            <div class="flex flex-wrap gap-4 text-sm">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                    <span x-text="memberDetails.phone || 'No phone'"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 text-xs font-bold rounded-full"
                                          :class="{
                                              'bg-green-50 text-green-700 border border-green-200': memberDetails.status === 'active',
                                              'bg-amber-50 text-amber-700 border border-amber-200': memberDetails.status === 'pending',
                                              'bg-slate-50 text-slate-700 border border-slate-200': memberDetails.status !== 'active' && memberDetails.status !== 'pending'
                                          }"
                                          x-text="memberDetails.status"></span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-100">
                            <h4 class="font-bold text-indigo-900 mb-3">Informasi Paket</h4>
                            <template x-if="activeMemberPackage()">
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-sm text-indigo-600">Nama Paket</p>
                                        <p class="font-semibold text-indigo-900" x-text="activeMemberPackage()?.package?.name || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-indigo-600">Harga</p>
                                        <p class="font-semibold text-indigo-900">Rp <span x-text="Number(activeMemberPackage()?.package?.price || 0).toLocaleString('id-ID')"></span></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-indigo-600">Durasi</p>
                                        <p class="font-semibold text-indigo-900" x-text="activeMemberPackage()?.package?.duration_days ? (activeMemberPackage().package.duration_days + ' hari') : '-' "></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-green-600">Tanggal Mulai</p>
                                        <p class="font-semibold text-green-900" x-text="activeMemberPackage()?.start_date ? new Date(activeMemberPackage().start_date).toLocaleDateString('id-ID') : '-' "></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-green-600">Tanggal Berakhir</p>
                                        <p class="font-semibold text-green-900" x-text="activeMemberPackage()?.end_date ? new Date(activeMemberPackage().end_date).toLocaleDateString('id-ID') : '-' "></p>
                                    </div>
                                </div>
                            </template>
                            <template x-if="!activeMemberPackage()">
                                <p class="text-sm text-slate-500">Member belum memiliki paket aktif.</p>
                            </template>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white rounded-xl p-5 border border-slate-200">
                                <p class="text-sm font-semibold text-slate-600 mb-2">Slot Terpakai</p>
                                <p class="text-3xl font-bold text-[#1a307b]" x-text="activeMemberPackage()?.used_sessions || 0"></p>
                            </div>
                            <div class="bg-white rounded-xl p-5 border border-slate-200">
                                <p class="text-sm font-semibold text-slate-600 mb-2">Sisa Slot</p>
                                <p class="text-3xl font-bold text-[#1a307b]" x-text="remainingSlots()"></p>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl p-5 border border-slate-200" x-show="activeMemberPackage()">
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-slate-600">Penggunaan Slot</span>
                                <span class="text-sm font-bold text-slate-800" x-text="`${activeMemberPackage()?.used_sessions || 0} / ${activeMemberPackage()?.total_sessions || 0}`"></span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                                <div class="bg-[#1a307b] h-full transition-all" 
                                     :style="'width: ' + ((activeMemberPackage()?.total_sessions || 0) > 0 ? (((activeMemberPackage()?.used_sessions || 0) / activeMemberPackage().total_sessions) * 100) : 0) + '%'">
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
function membersData() {
    return {
        members: [],
        search: '',
        loading: false,
        saving: false,
        toggling: false,
        showModal: false,
        showToggleConfirm: false,
        showDetailsModal: false,
        loadingDetails: false,
        editingMember: null,
        memberToToggle: null,
        memberDetails: null,
        statistics: null,
        form: {
            name: '',
            phone: ''
        },
        
        get filteredMembers() {
            if (!this.search) return this.members;
            const query = this.search.toLowerCase();
            return this.members.filter(m => 
                m.name.toLowerCase().includes(query) ||
                (m.phone && m.phone.includes(query))
            );
        },
        
        async loadMembers() {
            if (this.loading) return; // Prevent multiple simultaneous loads
            
            this.loading = true;
            try {
                const response = await API.get('/admin/members');
                
                // Validate response
                if (!response || typeof response !== 'object') {
                    throw new Error('Invalid response from server');
                }
                
                if (!Array.isArray(response.data)) {
                    console.warn('Members data is not an array:', response.data);
                    this.members = [];
                } else {
                    this.members = response.data;
                }
            } catch (error) {
                console.error('Failed to load members:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to load members data';
                showToast(errorMsg, 'error');
                this.members = []; // Set empty array on error
            } finally {
                this.loading = false;
            }
        },
        
        openAddModal() {
            this.editingMember = null;
            this.form = { name: '', phone: '' };
            this.saving = false; // Reset saving state
            this.showModal = true;
        },
        
        openEditModal(member) {
            // Validate member object
            if (!member || !member.id) {
                showToast('Invalid member data', 'error');
                return;
            }
            
            this.editingMember = member;
            this.form = {
                name: member.name || '',
                phone: member.phone || ''
            };
            this.saving = false; // Reset saving state
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
            this.editingMember = null;
            this.form = { name: '', phone: '' };
        },

        async viewMemberDetails(member) {
            // Validate member
            if (!member || !member.id) {
                showToast('Invalid member data', 'error');
                return;
            }
            
            this.memberDetails = member;
            this.statistics = null;
            this.showDetailsModal = true;
            this.loadingDetails = true;
            
            try {
                const response = await API.get(`/admin/members/${member.id}`);
                
                // Validate response
                if (!response || !response.data) {
                    throw new Error('Invalid response from server');
                }
                
                this.memberDetails = response.data;
                this.statistics = response.statistics || {};
            } catch (error) {
                console.error('Failed to load member details:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to load member details';
                showToast(errorMsg, 'error');
                
                // Close modal on error
                this.showDetailsModal = false;
            } finally {
                this.loadingDetails = false;
            }
        },

        closeDetailsModal() {
            this.showDetailsModal = false;
            this.memberDetails = null;
            this.statistics = null;
        },

        activeMemberPackage() {
            const packages = this.memberDetails?.member_packages || [];
            if (!Array.isArray(packages) || packages.length === 0) {
                return null;
            }

            const now = new Date();
            const active = packages.find((pkg) => {
                const isActive = Boolean(pkg?.is_active);
                if (!isActive) return false;
                if (!pkg?.end_date) return true;
                return new Date(pkg.end_date) >= now;
            });

            return active || packages[0] || null;
        },

        remainingSlots() {
            const pkg = this.activeMemberPackage();
            if (!pkg) return 0;
            const total = Number(pkg.total_sessions || 0);
            const used = Number(pkg.used_sessions || 0);
            return Math.max(0, total - used);
        },
        
        async saveMember() {
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
            
            if (this.form.phone && this.form.phone.trim() !== '') {
                // Basic phone validation
                const phoneRegex = /^[0-9\s\-\+\(\)]+$/;
                if (!phoneRegex.test(this.form.phone)) {
                    showToast('Invalid phone number format', 'error');
                    return;
                }
            }
            
            this.saving = true;
            try {
                let response;
                
                if (this.editingMember) {
                    // Validate editing member
                    if (!this.editingMember.id) {
                        throw new Error('Invalid member ID');
                    }
                    
                    response = await API.put(`/admin/members/${this.editingMember.id}`, this.form);
                    
                    // Validate response
                    if (!response || !response.data) {
                        throw new Error('Invalid response from server');
                    }
                    
                    const index = this.members.findIndex(m => m.id === this.editingMember.id);
                    if (index > -1) {
                        this.members[index] = response.data;
                    } else {
                        console.warn('Member not found in list, reloading...');
                        await this.loadMembers();
                    }
                    
                    showToast('✓ Member updated successfully', 'success');
                } else {
                    response = await API.post('/admin/members', this.form);
                    
                    // Validate response
                    if (!response || !response.data) {
                        throw new Error('Invalid response from server');
                    }
                    
                    this.members.unshift(response.data);
                    showToast('✓ Member added successfully', 'success');
                }
                
                this.closeModal();
            } catch (error) {
                console.error('Failed to save member:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to save member';
                showToast(errorMsg, 'error');
            } finally {
                this.saving = false;
            }
        },
        
        confirmToggle(member) {
            // Validate member
            if (!member || !member.id) {
                showToast('Invalid member data', 'error');
                return;
            }
            
            this.memberToToggle = member;
            this.showToggleConfirm = true;
        },
        
        async toggleMember() {
            // Validate member
            if (!this.memberToToggle || !this.memberToToggle.id) {
                showToast('Invalid member data', 'error');
                this.showToggleConfirm = false;
                return;
            }
            
            // Prevent double-submit
            if (this.toggling) {
                showToast('Operation in progress...', 'warning');
                return;
            }
            
            this.toggling = true;
            
            try {
                const isInactive = this.memberToToggle.status === 'inactive' || this.memberToToggle.is_active === false;
                
                if (isInactive) {
                    await API.post(`/admin/members/${this.memberToToggle.id}/restore`);
                    showToast('✓ Member activated successfully', 'success');
                } else {
                    await API.delete(`/admin/members/${this.memberToToggle.id}`);
                    showToast('✓ Member deactivated successfully', 'success');
                }

                // Reload members list
                await this.loadMembers();

                // Update editing member if it's the same
                if (this.editingMember && this.memberToToggle && String(this.editingMember.id) === String(this.memberToToggle.id)) {
                    this.editingMember = this.members.find(m => String(m.id) === String(this.memberToToggle.id)) || this.editingMember;
                }

                this.showToggleConfirm = false;
                this.memberToToggle = null;
            } catch (error) {
                console.error('Failed to toggle member:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to update member status';
                showToast(errorMsg, 'error');
            } finally {
                this.toggling = false;
            }
        }
    }
}
</script>
@endpush
@endsection
