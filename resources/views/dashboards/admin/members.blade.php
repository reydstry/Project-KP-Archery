@extends('layouts.admin')

@section('title', 'Members')
@section('subtitle', 'Manage archery club members')

@section('content')
<div x-data="membersData()" x-init="loadMembers()" class="space-y-6">
    
    <!-- Header Actions -->
    <div class="card-animate flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
        <div class="flex-1 w-full">
            <input type="search" x-model="search" placeholder="Search members..." 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
        </div>
        <button @click="openAddModal()" 
                class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all whitespace-nowrap shrink-0">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Member
            </span>
        </button>
    </div>

    <!-- Members Table -->
    <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" style="animation-delay: 0.1s">
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
                                            class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition">
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

    <!-- Add/Edit Modal -->
    <div x-show="showModal" x-cloak @click.self="closeModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div @click.away="closeModal()" 
             class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="sticky top-0 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
                <h3 class="text-lg font-bold" x-text="editingMember ? 'Edit Member' : 'Add New Member'"></h3>
                <button @click="closeModal()" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form @submit.prevent="saveMember()" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Name *</label>
                    <input type="text" x-model="form.name" required
                           class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Phone</label>
                    <input type="tel" x-model="form.phone"
                           class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                </div>
                <div class="flex gap-4 pt-4">
                    <template x-if="editingMember">
                        <button type="button" @click="confirmToggle(editingMember)"
                                class="flex-1 px-4 py-3 text-white rounded-xl font-semibold hover:shadow-lg transition"
                                :class="(editingMember?.status === 'inactive' || editingMember?.is_active === false) ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-red-500 to-red-600'">
                            <span x-text="(editingMember?.status === 'inactive' || editingMember?.is_active === false) ? 'Activate' : 'Deactivate'"></span>
                        </button>
                    </template>
                    <button type="button" @click="closeModal()" 
                            class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit" :disabled="saving"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-semibold hover:shadow-lg transition disabled:opacity-50">
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
                        :class="(memberToToggle?.status === 'inactive' || memberToToggle?.is_active === false) ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-red-500 to-red-600'">
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
            <div class="sticky top-0 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
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
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-100">
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

                        <!-- Statistics Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- This Week -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-5 border border-green-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h5 class="text-sm font-semibold text-slate-700">This Week</h5>
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                </div>
                                <p class="text-3xl font-bold text-green-700" x-text="statistics?.training_count_this_week || 0"></p>
                                <p class="text-xs text-green-600 mt-1">training sessions</p>
                            </div>

                            <!-- This Month -->
                            <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-5 border border-blue-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h5 class="text-sm font-semibold text-slate-700">This Month</h5>
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                </div>
                                <p class="text-3xl font-bold text-blue-700" x-text="statistics?.training_count_this_month || 0"></p>
                                <p class="text-xs text-blue-600 mt-1">training sessions</p>
                            </div>

                            <!-- This Year -->
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-5 border border-purple-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h5 class="text-sm font-semibold text-slate-700">This Year</h5>
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                </div>
                                <p class="text-3xl font-bold text-purple-700" x-text="statistics?.training_count_this_year || 0"></p>
                                <p class="text-xs text-purple-600 mt-1">training sessions</p>
                            </div>

                            <!-- Week Streak -->
                            <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-xl p-5 border border-orange-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h5 class="text-sm font-semibold text-slate-700">Week Streak</h5>
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z"/></svg>
                                </div>
                                <p class="text-3xl font-bold text-orange-700" x-text="statistics?.week_streak || 0"></p>
                                <p class="text-xs text-orange-600 mt-1">consecutive weeks</p>
                            </div>
                        </div>

                        <!-- Total Trainings -->
                        <div class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-xl p-5 border border-slate-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="text-sm font-semibold text-slate-700 mb-1">Total Trainings</h5>
                                    <p class="text-2xl font-bold text-slate-800" x-text="statistics?.total_trainings || 0"></p>
                                </div>
                                <div class="w-12 h-12 bg-slate-200 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
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
            this.loading = true;
            try {
                const response = await API.get('/admin/members');
                this.members = response.data || [];
            } catch (error) {
                console.error('Failed to load members:', error);
                showToast('Failed to load members', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        openAddModal() {
            this.editingMember = null;
            this.form = { name: '', phone: '' };
            this.showModal = true;
        },
        
        openEditModal(member) {
            this.editingMember = member;
            this.form = {
                name: member.name,
                phone: member.phone || ''
            };
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
            this.editingMember = null;
            this.form = { name: '', phone: '' };
        },

        async viewMemberDetails(member) {
            this.memberDetails = member;
            this.statistics = null;
            this.showDetailsModal = true;
            this.loadingDetails = true;
            
            try {
                const response = await API.get(`/admin/members/${member.id}`);
                this.memberDetails = response.data;
                this.statistics = response.statistics || {};
            } catch (error) {
                console.error('Failed to load member details:', error);
                showToast('Failed to load member details', 'error');
            } finally {
                this.loadingDetails = false;
            }
        },

        closeDetailsModal() {
            this.showDetailsModal = false;
            this.memberDetails = null;
            this.statistics = null;
        },
        
        async saveMember() {
            this.saving = true;
            try {
                if (this.editingMember) {
                    const response = await API.put(`/admin/members/${this.editingMember.id}`, this.form);
                    const index = this.members.findIndex(m => m.id === this.editingMember.id);
                    if (index > -1) this.members[index] = response.data;
                    showToast('Member updated successfully', 'success');
                } else {
                    const response = await API.post('/admin/members', this.form);
                    this.members.unshift(response.data);
                    showToast('Member added successfully', 'success');
                }
                this.closeModal();
            } catch (error) {
                console.error('Failed to save member:', error);
                showToast(error.message || 'Failed to save member', 'error');
            } finally {
                this.saving = false;
            }
        },
        
        confirmToggle(member) {
            this.memberToToggle = member;
            this.showToggleConfirm = true;
        },
        
        async toggleMember() {
            if (!this.memberToToggle) return;
            
            this.toggling = true;
            try {
                const isInactive = this.memberToToggle.status === 'inactive' || this.memberToToggle.is_active === false;
                if (isInactive) {
                    await API.post(`/admin/members/${this.memberToToggle.id}/restore`);
                    showToast('Member activated successfully', 'success');
                } else {
                    await API.delete(`/admin/members/${this.memberToToggle.id}`);
                    showToast('Member deactivated successfully', 'success');
                }

                await this.loadMembers();

                if (this.editingMember && this.memberToToggle && String(this.editingMember.id) === String(this.memberToToggle.id)) {
                    this.editingMember = this.members.find(m => String(m.id) === String(this.memberToToggle.id)) || this.editingMember;
                }

                this.showToggleConfirm = false;
                this.memberToToggle = null;
            } catch (error) {
                console.error('Failed to toggle member:', error);
                showToast(error.message || 'Failed to update member status', 'error');
            } finally {
                this.toggling = false;
            }
        }
    }
}
</script>
@endpush
@endsection
