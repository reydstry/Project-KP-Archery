@extends('layouts.admin')

@section('title', 'Gallery')
@section('subtitle', 'Manage photo galleries')

@section('content')
<div x-data="galleryData()" x-init="loadGalleries()" class="space-y-6">
    
    <!-- Header Actions -->
    <div class="card-animate flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-4">
        <div class="flex-1 w-full sm:max-w-md">
            <input type="search" x-model="search" placeholder="Search galleries..." 
                class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none transition">
        </div>
        <select x-model="filterCategory" 
            class="w-full sm:w-auto px-3 py-2 sm:px-4 sm:py-3 text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none transition">
            <option value="">All Categories</option>
            <option value="training">Training</option>
            <option value="competition">Competition</option>
            <option value="group_selfie">Group Selfie</option>
        </select>
        <button @click="openAddModal()" 
            class="w-full sm:w-auto px-4 py-2 sm:px-6 sm:py-3 text-sm bg-[#1a307b] text-white rounded-xl font-semibold hover:bg-[#152866] transition-all whitespace-nowrap shrink-0">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Photo
            </span>
        </button>
    </div>

    <!-- Gallery Grid -->
    <div class="space-y-4">
        <template x-if="loading">
            <div class="text-center py-12 text-slate-400">Loading...</div>
        </template>
        <template x-if="!loading && filteredGalleries.length === 0">
            <div class="text-center py-12 text-slate-400">No galleries found</div>
        </template>
        
        <!-- Grid Layout -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <template x-for="item in filteredGalleries" :key="item.id">
                <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg transition-all overflow-hidden">
                    <!-- Image -->
                    <div class="aspect-[4/3] relative overflow-hidden bg-slate-100">
                        <img :src="item.photo_url" :alt="item.title" 
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        
                        <!-- Status Badge -->
                        <div class="absolute top-2 right-2">
                            <span :class="item.is_active ? 'bg-green-500' : 'bg-red-500'" 
                                  class="px-2 py-1 text-xs text-white rounded-lg font-medium">
                                <span x-text="item.is_active ? 'Active' : 'Inactive'"></span>
                            </span>
                        </div>
                        
                        <!-- Category Badge -->
                        <div class="absolute top-2 left-2">
                            <span class="px-2 py-1 text-xs bg-white/90 backdrop-blur-sm text-slate-700 rounded-lg font-medium capitalize" 
                                  x-text="formatCategory(item.category)"></span>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-4">
                        <h3 class="font-bold text-slate-800 text-sm truncate mb-1" x-text="item.title"></h3>
                        <p class="text-xs text-slate-500 line-clamp-2" x-text="item.description || 'No description'"></p>
                    </div>
                    
                    <!-- Actions -->
                    <div class="px-4 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button @click="openEditModal(item)" 
                            class="px-3 py-1.5 text-[#1a307b] hover:bg-[#1a307b]/10 rounded-lg font-medium text-xs transition">
                            Edit
                        </button>
                        <button @click="confirmDelete(item)" 
                            class="px-3 py-1.5 text-red-600 hover:bg-red-50 rounded-lg font-medium text-xs transition">
                            Delete
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div x-show="showModal" x-cloak @click.self="closeModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div @click.away="closeModal()" 
             class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full my-8 max-h-[90vh] overflow-y-auto"
             x-transition>
            <div class="sticky top-0 bg-[#1a307b] text-white px-6 py-4 rounded-t-2xl flex items-center justify-between z-10">
                <h3 class="text-lg font-bold" x-text="editingGallery ? 'Edit Gallery' : 'Add New Photo'"></h3>
                <button @click="closeModal()" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form @submit.prevent="saveGallery()" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Title *</label>
                    <input type="text" x-model="form.title" required
                        class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                    <textarea x-model="form.description" rows="3"
                        class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none resize-none"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Category *</label>
                    <select x-model="form.category" required
                        class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none">
                        <option value="">Select category</option>
                        <option value="training">Training</option>
                        <option value="competition">Competition</option>
                        <option value="group_selfie">Group Selfie</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="form.is_active" class="rounded">
                        <span class="text-sm text-slate-600">Active</span>
                    </label>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Photo <span x-show="!editingGallery" class="text-red-500">*</span>
                    </label>
                    <input type="file" @change="handlePhotoChange" accept="image/*" ref="photoInput"
                        :required="!editingGallery"
                        class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none">
                    <p class="text-xs text-slate-500 mt-1">Upload an image (max 5MB, formats: jpg, png, gif, webp)</p>
                    
                    <!-- Image Preview -->
                    <div x-show="photoPreview" class="mt-3">
                        <img :src="photoPreview" class="w-full h-48 object-cover rounded-xl border border-slate-200">
                        <button type="button" @click="removePhoto()" 
                            class="mt-2 text-sm text-red-600 hover:text-red-700 font-medium">
                            Remove photo
                        </button>
                    </div>
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
            <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus Galeri?</h3>
            <p class="text-slate-600 mb-6">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button @click="showDeleteConfirm = false" 
                    class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button @click="deleteGallery()" :disabled="deleting"
                    class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-semibold hover:shadow-lg transition disabled:opacity-50">
                    <span x-text="deleting ? 'Menghapus...' : 'Hapus'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div x-show="showSuccessModal" x-cloak @click.self="closeSuccessModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 max-w-md w-full mx-4 transform"
             x-show="showSuccessModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            <div class="text-center">
                <!-- Success Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                
                <!-- Title -->
                <h3 class="text-xl font-bold text-slate-900 mb-2">Berhasil Disimpan!</h3>
                
                <!-- Message -->
                <p class="text-slate-600 mb-6" x-text="successMessage"></p>
                
                <!-- Button -->
                <button @click="closeSuccessModal()" class="w-full px-6 py-3 bg-[#1a307b] hover:bg-[#152866] text-white rounded-xl font-semibold transition-all duration-200 active:scale-95">
                    Oke, Mengerti
                </button>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div x-show="showErrorModal" x-cloak @click.self="closeErrorModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 max-w-md w-full mx-4 transform"
             x-show="showErrorModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            <div class="text-center">
                <!-- Error Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                
                <!-- Title -->
                <h3 class="text-xl font-bold text-slate-900 mb-2">Terjadi Kesalahan!</h3>
                
                <!-- Message -->
                <p class="text-slate-600 mb-6" x-text="errorMessage"></p>
                
                <!-- Button -->
                <button @click="closeErrorModal()" class="w-full px-6 py-3 bg-[#1a307b] hover:bg-[#152866] text-white rounded-xl font-semibold transition-all duration-200 active:scale-95">
                    Oke, Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function galleryData() {
    return {
        galleries: [],
        search: '',
        filterCategory: '',
        loading: false,
        saving: false,
        deleting: false,
        showModal: false,
        showDeleteConfirm: false,
        showSuccessModal: false,
        showErrorModal: false,
        successMessage: '',
        errorMessage: '',
        editingGallery: null,
        galleryToDelete: null,
        photoFile: null,
        photoPreview: null,
        form: {
            title: '',
            description: '',
            category: '',
            is_active: true
        },
        
        get filteredGalleries() {
            let filtered = this.galleries;
            
            // Filter by category
            if (this.filterCategory) {
                filtered = filtered.filter(g => g.category === this.filterCategory);
            }
            
            // Filter by search
            if (this.search) {
                const query = this.search.toLowerCase();
                filtered = filtered.filter(g => 
                    g.title.toLowerCase().includes(query) ||
                    (g.description && g.description.toLowerCase().includes(query))
                );
            }
            
            return filtered;
        },
        
        formatCategory(category) {
            return category.replace('_', ' ');
        },
        
        closeSuccessModal() {
            this.showSuccessModal = false;
            this.successMessage = '';
        },
        
        closeErrorModal() {
            this.showErrorModal = false;
            this.errorMessage = '';
        },
        
        async loadGalleries() {
            if (this.loading) return;
            
            this.loading = true;
            try {
                const response = await API.get('/admin/galleries');
                
                if (!response || typeof response !== 'object') {
                    throw new Error('Invalid response from server');
                }
                
                if (!Array.isArray(response.data)) {
                    console.warn('Gallery data is not an array:', response.data);
                    this.galleries = [];
                } else {
                    this.galleries = response.data;
                }
            } catch (error) {
                console.error('Failed to load galleries:', error);
                this.errorMessage = error?.response?.data?.message || error?.message || 'Gagal memuat data galeri. Silakan coba lagi.';
                this.showErrorModal = true;
                this.galleries = [];
            } finally {
                this.loading = false;
            }
        },
        
        openAddModal() {
            this.editingGallery = null;
            this.form = {
                title: '',
                description: '',
                category: '',
                is_active: true
            };
            this.photoFile = null;
            this.photoPreview = null;
            this.saving = false;
            this.showModal = true;
        },
        
        openEditModal(item) {
            if (!item || !item.id) {
                this.errorMessage = 'Data galeri tidak valid.';
                this.showErrorModal = true;
                return;
            }
            
            this.editingGallery = item;
            this.form = {
                title: item.title || '',
                description: item.description || '',
                category: item.category || '',
                is_active: item.is_active !== undefined ? item.is_active : true
            };
            this.photoFile = null;
            this.photoPreview = item.photo_url || null;
            this.saving = false;
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
            this.editingGallery = null;
            this.photoFile = null;
            this.photoPreview = null;
        },

        handlePhotoChange(event) {
            const file = event.target.files?.[0];
            if (!file) return;
            
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                this.errorMessage = 'Hanya gambar JPG, PNG, GIF, dan WEBP yang diperbolehkan.';
                this.showErrorModal = true;
                if (this.$refs.photoInput) {
                    this.$refs.photoInput.value = '';
                }
                return;
            }
            
            const maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
                this.errorMessage = 'Ukuran gambar harus kurang dari 5MB.';
                this.showErrorModal = true;
                if (this.$refs.photoInput) {
                    this.$refs.photoInput.value = '';
                }
                return;
            }
            
            this.photoFile = file;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.photoPreview = e.target.result;
            };
            reader.onerror = () => {
                this.errorMessage = 'Gagal membaca file gambar.';
                this.showErrorModal = true;
                this.photoFile = null;
                this.photoPreview = null;
            };
            reader.readAsDataURL(file);
        },

        removePhoto() {
            this.photoFile = null;
            this.photoPreview = null;
            if (this.$refs.photoInput) {
                this.$refs.photoInput.value = '';
            }
        },
        
        async saveGallery() {
            if (this.saving) {
                return;
            }
            
            if (!this.form.title || this.form.title.trim() === '') {
                this.errorMessage = 'Judul wajib diisi.';
                this.showErrorModal = true;
                return;
            }
            
            if (!this.form.category) {
                this.errorMessage = 'Kategori wajib dipilih.';
                this.showErrorModal = true;
                return;
            }
            
            if (!this.editingGallery && !this.photoFile) {
                this.errorMessage = 'Foto wajib diupload.';
                this.showErrorModal = true;
                return;
            }
            
            this.saving = true;
            try {
                const formData = new FormData();
                formData.append('title', this.form.title.trim());
                formData.append('description', this.form.description?.trim() || '');
                formData.append('category', this.form.category);
                formData.append('is_active', this.form.is_active ? '1' : '0');
                
                if (this.photoFile) {
                    formData.append('photo', this.photoFile);
                }

                let result;
                if (this.editingGallery) {
                    if (!this.editingGallery.id) {
                        throw new Error('Invalid gallery ID');
                    }
                    
                    formData.append('_method', 'PUT');
                    result = await API.post(`/admin/galleries/${this.editingGallery.id}`, formData);
                    
                    if (!result || !result.data) {
                        throw new Error('Invalid response from server');
                    }
                    
                    const index = this.galleries.findIndex(g => g.id === this.editingGallery.id);
                    if (index > -1) {
                        this.galleries[index] = result.data;
                    } else {
                        await this.loadGalleries();
                    }
                    
                    this.successMessage = 'Galeri berhasil diperbarui!';
                    this.showSuccessModal = true;
                } else {
                    result = await API.post('/admin/galleries', formData);
                    
                    if (!result || !result.data) {
                        throw new Error('Invalid response from server');
                    }
                    
                    this.galleries.unshift(result.data);
                    this.successMessage = 'Galeri berhasil ditambahkan!';
                    this.showSuccessModal = true;
                }
                
                this.closeModal();
            } catch (error) {
                console.error('Failed to save gallery:', error);
                this.errorMessage = error?.response?.data?.message || error?.message || 'Gagal menyimpan galeri. Silakan coba lagi.';
                this.showErrorModal = true;
            } finally {
                this.saving = false;
            }
        },
        
        confirmDelete(item) {
            if (!item || !item.id) {
                this.errorMessage = 'Data galeri tidak valid.';
                this.showErrorModal = true;
                return;
            }
            
            this.galleryToDelete = item;
            this.showDeleteConfirm = true;
        },
        
        async deleteGallery() {
            if (!this.galleryToDelete || !this.galleryToDelete.id) {
                this.errorMessage = 'Data galeri tidak valid.';
                this.showErrorModal = true;
                this.showDeleteConfirm = false;
                return;
            }
            
            if (this.deleting) {
                return;
            }
            
            this.deleting = true;
            try {
                await API.delete(`/admin/galleries/${this.galleryToDelete.id}`);
                this.galleries = this.galleries.filter(g => g.id !== this.galleryToDelete.id);
                this.successMessage = 'Galeri berhasil dihapus!';
                this.showSuccessModal = true;
                this.showDeleteConfirm = false;
                this.galleryToDelete = null;
            } catch (error) {
                console.error('Failed to delete gallery:', error);
                this.errorMessage = error?.response?.data?.message || error?.message || 'Gagal menghapus galeri. Silakan coba lagi.';
                this.showErrorModal = true;
            } finally {
                this.deleting = false;
            }
        }
    }
}
</script>
@endpush
@endsection
