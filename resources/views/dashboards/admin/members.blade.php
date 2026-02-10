@extends('layouts.admin')

@section('title', 'Members')
@section('subtitle', 'Manage archery club members')

@section('content')
<div x-data="membersData()" x-init="loadMembers()" class="space-y-6">
    
    <!-- Header Actions -->
    <div class="card-animate flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
        <div class="flex-1 w-full sm:max-w-md">
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
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-500 rounded-lg flex items-center justify-center text-white font-bold text-sm shrink-0">
                                        <span x-text="member.name.charAt(0).toUpperCase()"></span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800" x-text="member.name"></p>
                                        <p class="text-xs text-slate-500">ID: <span x-text="member.id"></span></p>
                                    </div>
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
                                    <button @click="openEditModal(member)" 
                                            class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                                    </button>
                                    <button @click="confirmDelete(member)" 
                                            class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
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

    <!-- Delete Confirmation -->
    <div x-show="showDeleteConfirm" x-cloak @click.self="showDeleteConfirm = false"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Delete Member?</h3>
            <p class="text-slate-600 mb-6">This action cannot be undone. The member will be deactivated.</p>
            <div class="flex gap-3">
                <button @click="showDeleteConfirm = false" 
                        class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button @click="deleteMember()" :disabled="deleting"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-semibold hover:shadow-lg transition disabled:opacity-50">
                    <span x-text="deleting ? 'Deleting...' : 'Delete'"></span>
                </button>
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
        deleting: false,
        showModal: false,
        showDeleteConfirm: false,
        editingMember: null,
        memberToDelete: null,
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
        
        confirmDelete(member) {
            this.memberToDelete = member;
            this.showDeleteConfirm = true;
        },
        
        async deleteMember() {
            if (!this.memberToDelete) return;
            
            this.deleting = true;
            try {
                await API.delete(`/admin/members/${this.memberToDelete.id}`);
                this.members = this.members.filter(m => m.id !== this.memberToDelete.id);
                showToast('Member deleted successfully', 'success');
                this.showDeleteConfirm = false;
                this.memberToDelete = null;
            } catch (error) {
                console.error('Failed to delete member:', error);
                showToast(error.message || 'Failed to delete member', 'error');
            } finally {
                this.deleting = false;
            }
        }
    }
}
</script>
@endpush
@endsection
