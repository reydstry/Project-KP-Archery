@extends('layouts.admin')

@section('title', 'Packages')
@section('subtitle', 'Manage membership packages')

@section('content')
<div x-data="packagesData()" x-init="loadPackages()" class="space-y-6">
    
    <!-- Header Actions -->
    <div class="card-animate flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
        <div class="flex-1 w-full sm:max-w-md">
            <input type="search" x-model="search" placeholder="Search packages..." 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
        </div>
        <button @click="openAddModal()" 
                class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all whitespace-nowrap shrink-0">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Package
            </span>
        </button>
    </div>

    <!-- Packages Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" style="animation-delay: 0.1s">
        <template x-if="loading">
            <div class="col-span-full text-center py-12 text-slate-400">Loading...</div>
        </template>
        <template x-if="!loading && filteredPackages.length === 0">
            <div class="col-span-full text-center py-12 text-slate-400">No packages found</div>
        </template>
        <template x-for="pkg in filteredPackages" :key="pkg.id">
            <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg transition-all overflow-hidden">
                <div class="p-6 space-y-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="font-bold text-slate-800 text-xl" x-text="pkg.name"></h3>
                            <p class="text-sm text-slate-500 mt-1" x-text="pkg.description"></p>
                        </div>
                        <div class="shrink-0 bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg font-bold text-lg">
                            Rp <span x-text="Number(pkg.price).toLocaleString('id-ID')"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                        <div>
                            <p class="text-xs text-slate-500">Duration</p>
                            <p class="font-bold text-slate-700" x-text="pkg.duration_days + ' days'"></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Sessions</p>
                            <p class="font-bold text-slate-700" x-text="pkg.session_count + ' sessions'"></p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button @click="openEditModal(pkg)" 
                            class="px-4 py-2 text-indigo-600 hover:bg-indigo-50 rounded-lg font-medium text-sm transition">
                        Edit
                    </button>
                    <button @click="confirmDelete(pkg)" 
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
            <div class="sticky top-0 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
                <h3 class="text-lg font-bold" x-text="editingPackage ? 'Edit Package' : 'Add New Package'"></h3>
                <button @click="closeModal()" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form @submit.prevent="savePackage()" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Package Name *</label>
                    <input type="text" x-model="form.name" required
                           class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description *</label>
                    <textarea x-model="form.description" required rows="3"
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Price (Rp) *</label>
                        <input type="number" x-model="form.price" required min="0" step="1000"
                               class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Duration (days) *</label>
                        <input type="number" x-model="form.duration_days" required min="1"
                               class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Session Count *</label>
                    <input type="number" x-model="form.session_count" required min="1"
                           class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" @click="closeModal()" 
                            class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit" :disabled="saving"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl font-semibold hover:shadow-lg transition disabled:opacity-50">
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
            <h3 class="text-lg font-bold text-slate-800 mb-2">Delete Package?</h3>
            <p class="text-slate-600 mb-6">This action cannot be undone.</p>
            <div class="flex gap-3">
                <button @click="showDeleteConfirm = false" 
                        class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button @click="deletePackage()" :disabled="deleting"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-semibold hover:shadow-lg transition disabled:opacity-50">
                    <span x-text="deleting ? 'Deleting...' : 'Delete'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function packagesData() {
    return {
        packages: [],
        search: '',
        loading: false,
        saving: false,
        deleting: false,
        showModal: false,
        showDeleteConfirm: false,
        editingPackage: null,
        packageToDelete: null,
        form: {
            name: '',
            description: '',
            price: '',
            duration_days: '',
            session_count: ''
        },
        
        get filteredPackages() {
            if (!this.search) return this.packages;
            const query = this.search.toLowerCase();
            return this.packages.filter(p => 
                p.name.toLowerCase().includes(query) ||
                p.description.toLowerCase().includes(query)
            );
        },
        
        async loadPackages() {
            this.loading = true;
            try {
                const response = await API.get('/admin/packages');
                this.packages = response.data || [];
            } catch (error) {
                console.error('Failed to load packages:', error);
                showToast('Failed to load packages', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        openAddModal() {
            this.editingPackage = null;
            this.form = { name: '', description: '', price: '', duration_days: '', session_count: '' };
            this.showModal = true;
        },
        
        openEditModal(pkg) {
            this.editingPackage = pkg;
            this.form = {
                name: pkg.name,
                description: pkg.description,
                price: pkg.price,
                duration_days: pkg.duration_days,
                session_count: pkg.session_count
            };
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
            this.editingPackage = null;
        },
        
        async savePackage() {
            this.saving = true;
            try {
                if (this.editingPackage) {
                    const response = await API.put(`/admin/packages/${this.editingPackage.id}`, this.form);
                    const index = this.packages.findIndex(p => p.id === this.editingPackage.id);
                    if (index > -1) this.packages[index] = response.data;
                    showToast('Package updated successfully', 'success');
                } else {
                    const response = await API.post('/admin/packages', this.form);
                    this.packages.unshift(response.data);
                    showToast('Package added successfully', 'success');
                }
                this.closeModal();
            } catch (error) {
                console.error('Failed to save package:', error);
                showToast(error.message || 'Failed to save package', 'error');
            } finally {
                this.saving = false;
            }
        },
        
        confirmDelete(pkg) {
            this.packageToDelete = pkg;
            this.showDeleteConfirm = true;
        },
        
        async deletePackage() {
            if (!this.packageToDelete) return;
            
            this.deleting = true;
            try {
                await API.delete(`/admin/packages/${this.packageToDelete.id}`);
                this.packages = this.packages.filter(p => p.id !== this.packageToDelete.id);
                showToast('Package deleted successfully', 'success');
                this.showDeleteConfirm = false;
                this.packageToDelete = null;
            } catch (error) {
                console.error('Failed to delete package:', error);
                showToast(error.message || 'Failed to delete package', 'error');
            } finally {
                this.deleting = false;
            }
        }
    }
}
</script>
@endpush
@endsection