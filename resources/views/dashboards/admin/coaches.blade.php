@extends('layouts.admin')

@section('title', 'Coaches')
@section('subtitle', 'Manage archery club coaches')

@section('content')
<div x-data="coachesData()" x-init="loadCoaches()" class="space-y-6">
    
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div class="flex-1 max-w-md">
            <input type="search" x-model="search" placeholder="Search coaches..." 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition">
        </div>
        <button @click="openAddModal()" 
                class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all">
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Coach
            </span>
        </button>
    </div>

    <!-- Coaches Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-if="loading">
            <div class="col-span-full text-center py-12 text-slate-400">Loading...</div>
        </template>
        <template x-if="!loading && filteredCoaches.length === 0">
            <div class="col-span-full text-center py-12 text-slate-400">No coaches found</div>
        </template>
        <template x-for="coach in filteredCoaches" :key="coach.id">
            <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg transition-all overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-500 rounded-2xl flex items-center justify-center text-white font-bold text-xl shrink-0">
                            <span x-text="coach.name.charAt(0).toUpperCase()"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-slate-800 text-lg truncate" x-text="coach.name"></h3>
                            <p class="text-sm text-slate-500 truncate" x-text="coach.email"></p>
                            <p class="text-sm text-slate-500 mt-1" x-text="coach.phone || 'No phone'"></p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button @click="openEditModal(coach)" 
                            class="px-4 py-2 text-green-600 hover:bg-green-50 rounded-lg font-medium text-sm transition">
                        Edit
                    </button>
                    <button @click="confirmDelete(coach)" 
                            class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg font-medium text-sm transition">
                        Delete
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
             x-transition>
            <div class="sticky top-0 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
                <h3 class="text-lg font-bold" x-text="editingCoach ? 'Edit Coach' : 'Add New Coach'"></h3>
                <button @click="closeModal()" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form @submit.prevent="saveCoach()" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Name *</label>
                    <input type="text" x-model="form.name" required
                           class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email *</label>
                    <input type="email" x-model="form.email" required
                           class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Phone</label>
                    <input type="tel" x-model="form.phone"
                           class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                </div>
                <div x-show="!editingCoach">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password *</label>
                    <input type="password" x-model="form.password" :required="!editingCoach"
                           class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                </div>
                <div x-show="!editingCoach">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm Password *</label>
                    <input type="password" x-model="form.password_confirmation" :required="!editingCoach"
                           class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" @click="closeModal()" 
                            class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit" :disabled="saving"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl font-semibold hover:shadow-lg transition disabled:opacity-50">
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
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-semibold hover:shadow-lg transition disabled:opacity-50">
                    <span x-text="deleting ? 'Deleting...' : 'Delete'"></span>
                </button>
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
        editingCoach: null,
        coachToDelete: null,
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
            this.loading = true;
            try {
                const response = await API.get('/admin/coaches');
                this.coaches = response.data || [];
            } catch (error) {
                console.error('Failed to load coaches:', error);
                showToast('Failed to load coaches', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        openAddModal() {
            this.editingCoach = null;
            this.form = { name: '', email: '', phone: '', password: '', password_confirmation: '' };
            this.showModal = true;
        },
        
        openEditModal(coach) {
            this.editingCoach = coach;
            this.form = {
                name: coach.name,
                email: coach.email,
                phone: coach.phone || '',
                password: '',
                password_confirmation: ''
            };
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
            this.editingCoach = null;
        },
        
        async saveCoach() {
            this.saving = true;
            try {
                if (this.editingCoach) {
                    const response = await API.put(`/admin/coaches/${this.editingCoach.id}`, this.form);
                    const index = this.coaches.findIndex(c => c.id === this.editingCoach.id);
                    if (index > -1) this.coaches[index] = response.data;
                    showToast('Coach updated successfully', 'success');
                } else {
                    const response = await API.post('/admin/coaches', this.form);
                    this.coaches.unshift(response.data);
                    showToast('Coach added successfully', 'success');
                }
                this.closeModal();
            } catch (error) {
                console.error('Failed to save coach:', error);
                showToast(error.message || 'Failed to save coach', 'error');
            } finally {
                this.saving = false;
            }
        },
        
        confirmDelete(coach) {
            this.coachToDelete = coach;
            this.showDeleteConfirm = true;
        },
        
        async deleteCoach() {
            if (!this.coachToDelete) return;
            
            this.deleting = true;
            try {
                await API.delete(`/admin/coaches/${this.coachToDelete.id}`);
                this.coaches = this.coaches.filter(c => c.id !== this.coachToDelete.id);
                showToast('Coach deleted successfully', 'success');
                this.showDeleteConfirm = false;
                this.coachToDelete = null;
            } catch (error) {
                console.error('Failed to delete coach:', error);
                showToast(error.message || 'Failed to delete coach', 'error');
            } finally {
                this.deleting = false;
            }
        }
    }
}
</script>
@endpush
@endsection