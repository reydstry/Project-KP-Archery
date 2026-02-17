@extends('admin.app')

@section('title', 'Packages')
@section('subtitle', 'Manage membership packages')

@section('content')
<div x-data="packagesData()" x-init="loadPackages()" class="space-y-6">
    
    <!-- Header Actions -->
    <div class="card-animate flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-4">
        <div class="flex-1 w-full">
            <input type="search" x-model="search" placeholder="Search packages..." 
                   class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
        </div>
        <button @click="openAddModal()" 
                class="w-full sm:w-auto px-4 py-2 sm:px-6 sm:py-3 text-sm bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all whitespace-nowrap shrink-0">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Package
            </span>
        </button>
    </div>

    <!-- Packages Table - Desktop View -->
    <div class="card-animate hidden md:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" style="animation-delay: 0.1s">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Durasi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Sesi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-if="loading">
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">Loading...</td>
                        </tr>
                    </template>
                    <template x-if="!loading && filteredPackages.length === 0">
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">No packages found</td>
                        </tr>
                    </template>
                    <template x-for="package in filteredPackages" :key="package.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-800" x-text="package.name"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 text-sm" x-text="package.description || '-'"></td>
                            <td class="px-6 py-4 text-slate-600 text-sm">
                                Rp <span x-text="Number(package.price).toLocaleString('id-ID')"></span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 text-sm" x-text="package.duration_days + ' hari'"></td>
                            <td class="px-6 py-4 text-slate-600 text-sm" x-text="package.session_count"></td>
                            <td class="px-6 py-4">
                                <span :class="package.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                      class="px-3 py-1 rounded-full text-xs font-semibold">
                                    <span x-text="package.is_active ? 'Active' : 'Inactive'"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEditModal(package)" 
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
    
    <!-- Mobile Card View -->
    <div class="md:hidden space-y-3">
        <template x-if="loading">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 text-center text-slate-400">Loading...</div>
        </template>
        <template x-if="!loading && filteredPackages.length === 0">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 text-center text-slate-400">No packages found</div>
        </template>
        <template x-for="package in filteredPackages" :key="package.id">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <div class="space-y-3">
                    <!-- Package Name -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-slate-900 text-sm mb-2" x-text="package.name"></h3>
                            <p class="text-xs text-slate-600 line-clamp-2" x-text="package.description || 'No description'"></p>
                        </div>
                        <span :class="package.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                              class="px-2 py-1 rounded-full text-[10px] font-semibold whitespace-nowrap">
                            <span x-text="package.is_active ? 'Active' : 'Inactive'"></span>
                        </span>
                    </div>
                    
                    <!-- Package Details -->
                    <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-100">
                        <div class="text-center">
                            <div class="text-[10px] text-slate-500 mb-1">Harga</div>
                            <div class="text-xs font-semibold text-slate-900">
                                Rp <span x-text="Number(package.price).toLocaleString('id-ID')"></span>
                            </div>
                        </div>
                        <div class="text-center border-x border-slate-100">
                            <div class="text-[10px] text-slate-500 mb-1">Durasi</div>
                            <div class="text-xs font-semibold text-slate-900" x-text="package.duration_days + ' hari'"></div>
                        </div>
                        <div class="text-center">
                            <div class="text-[10px] text-slate-500 mb-1">Sesi</div>
                            <div class="text-xs font-semibold text-slate-900" x-text="package.session_count"></div>
                        </div>
                    </div>
                    
                    <!-- Action Button -->
                    <button @click="openEditModal(package)" 
                        class="w-full px-3 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg text-xs font-semibold hover:shadow-lg transition-all">
                        Edit Package
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
                    <template x-if="editingPackage">
                        <button type="button" @click="togglePackageActive()" :disabled="togglingActive"
                                class="flex-1 px-4 py-3 text-white rounded-xl font-semibold hover:shadow-lg transition disabled:opacity-50"
                                :class="(editingPackage?.is_active === false) ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-red-500 to-red-600'">
                            <span x-text="(editingPackage?.is_active === false) ? (togglingActive ? 'Activating...' : 'Activate') : (togglingActive ? 'Deactivating...' : 'Deactivate')"></span>
                        </button>
                    </template>
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
        togglingActive: false,
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
            if (this.loading) return; // Prevent multiple simultaneous loads
            
            this.loading = true;
            try {
                const response = await API.get('/admin/packages');
                
                // Validate response
                if (!response || typeof response !== 'object') {
                    throw new Error('Invalid response from server');
                }
                
                if (!Array.isArray(response.data)) {
                    console.warn('Packages data is not an array:', response.data);
                    this.packages = [];
                } else {
                    this.packages = response.data;
                }
            } catch (error) {
                console.error('Failed to load packages:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to load packages data';
                showToast(errorMsg, 'error');
                this.packages = [];
            } finally {
                this.loading = false;
            }
        },
        
        openAddModal() {
            this.editingPackage = null;
            this.form = { name: '', description: '', price: '', duration_days: '', session_count: '' };
            this.saving = false; // Reset saving state
            this.showModal = true;
        },
        
        openEditModal(pkg) {
            // Validate package object
            if (!pkg || !pkg.id) {
                showToast('Invalid package data', 'error');
                return;
            }
            
            this.editingPackage = pkg;
            this.form = {
                name: pkg.name || '',
                description: pkg.description || '',
                price: pkg.price || '',
                duration_days: pkg.duration_days || '',
                session_count: pkg.session_count || ''
            };
            this.saving = false; // Reset saving state
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
            this.editingPackage = null;
        },

        async togglePackageActive() {
            if (!this.editingPackage || !this.editingPackage.id) {
                showToast('Invalid package data', 'error');
                return;
            }
            
            if (this.togglingActive) {
                showToast('Toggle in progress...', 'warning');
                return;
            }

            this.togglingActive = true;
            try {
                const isInactive = this.editingPackage.is_active === false;
                let response;
                
                if (isInactive) {
                    response = await API.post(`/admin/packages/${this.editingPackage.id}/restore`);
                    showToast('✓ ' + (response.message || 'Package activated successfully'), 'success');
                } else {
                    response = await API.delete(`/admin/packages/${this.editingPackage.id}`);
                    showToast('✓ ' + (response.message || 'Package deactivated successfully'), 'success');
                }

                await this.loadPackages();
                this.editingPackage = this.packages.find(p => String(p.id) === String(this.editingPackage.id)) || this.editingPackage;
            } catch (error) {
                console.error('Failed to toggle package:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to update package status';
                showToast(errorMsg, 'error');
            } finally {
                this.togglingActive = false;
            }
        },
        
        async savePackage() {
            // Prevent double-submit
            if (this.saving) {
                showToast('Saving in progress...', 'warning');
                return;
            }
            
            // Validate form
            if (!this.form.name || this.form.name.trim() === '') {
                showToast('Package name is required', 'error');
                return;
            }
            
            if (this.form.name.trim().length < 3) {
                showToast('Package name must be at least 3 characters', 'error');
                return;
            }
            
            if (!this.form.description || this.form.description.trim() === '') {
                showToast('Description is required', 'error');
                return;
            }
            
            if (!this.form.price || this.form.price === '') {
                showToast('Price is required', 'error');
                return;
            }
            
            const price = parseFloat(this.form.price);
            if (isNaN(price) || price <= 0) {
                showToast('Price must be a valid positive number', 'error');
                return;
            }
            
            if (!this.form.duration_days || this.form.duration_days === '') {
                showToast('Duration is required', 'error');
                return;
            }
            
            const duration = parseInt(this.form.duration_days);
            if (isNaN(duration) || duration <= 0) {
                showToast('Duration must be a valid positive number', 'error');
                return;
            }
            
            if (!this.form.session_count || this.form.session_count === '') {
                showToast('Session count is required', 'error');
                return;
            }
            
            const sessions = parseInt(this.form.session_count);
            if (isNaN(sessions) || sessions <= 0) {
                showToast('Session count must be a valid positive number', 'error');
                return;
            }
            
            this.saving = true;
            try {
                let response;
                
                if (this.editingPackage) {
                    // Validate editing package
                    if (!this.editingPackage.id) {
                        throw new Error('Invalid package ID');
                    }
                    
                    response = await API.put(`/admin/packages/${this.editingPackage.id}`, this.form);
                    
                    // Validate response
                    if (!response || !response.data) {
                        throw new Error('Invalid response from server');
                    }
                    
                    const index = this.packages.findIndex(p => p.id === this.editingPackage.id);
                    if (index > -1) {
                        this.packages[index] = response.data;
                    } else {
                        console.warn('Package not found in list, reloading...');
                        await this.loadPackages();
                    }
                    
                    showToast('✓ Package updated successfully', 'success');
                } else {
                    response = await API.post('/admin/packages', this.form);
                    
                    // Validate response
                    if (!response || !response.data) {
                        throw new Error('Invalid response from server');
                    }
                    
                    this.packages.unshift(response.data);
                    showToast('✓ Package added successfully', 'success');
                }
                
                this.closeModal();
            } catch (error) {
                console.error('Failed to save package:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to save package';
                showToast(errorMsg, 'error');
            } finally {
                this.saving = false;
            }
        },
        
        confirmDelete(pkg) {
            // Validate package
            if (!pkg || !pkg.id) {
                showToast('Invalid package data', 'error');
                return;
            }
            
            this.packageToDelete = pkg;
            this.showDeleteConfirm = true;
        },
        
        async deletePackage() {
            // Validate package
            if (!this.packageToDelete || !this.packageToDelete.id) {
                showToast('Invalid package data', 'error');
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
                await API.delete(`/admin/packages/${this.packageToDelete.id}`);
                this.packages = this.packages.filter(p => p.id !== this.packageToDelete.id);
                showToast('✓ Package deleted successfully', 'success');
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