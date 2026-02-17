@extends('admin.app')

@section('title', 'News')
@section('subtitle', 'Manage club news and announcements')

@section('content')
<div x-data="newsData()" x-init="loadNews()" class="space-y-6">
    
    <!-- Header Actions -->
    <div class="card-animate flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-4">
        <div class="flex-1 w-full sm:max-w-md">
                 <input type="search" x-model="search" placeholder="Search news..." 
                     class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none transition">
        </div>
        <button @click="openAddModal()" 
            class="w-full sm:w-auto px-4 py-2 sm:px-6 sm:py-3 text-sm bg-[#1a307b] text-white rounded-xl font-semibold hover:bg-[#152866] transition-all whitespace-nowrap shrink-0">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add News
            </span>
        </button>
    </div>

    <!-- News List -->
    <div class="space-y-4">
        <template x-if="loading">
            <div class="text-center py-12 text-slate-400">Loading...</div>
        </template>
        <template x-if="!loading && filteredNews.length === 0">
            <div class="text-center py-12 text-slate-400">No news found</div>
        </template>
        <template x-for="article in filteredNews" :key="article.id">
            <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg transition-all overflow-hidden">
                <div class="p-4 sm:p-6">
                    <div class="flex items-start gap-3 sm:gap-6">
                        <template x-if="article.photo_url">
                            <img :src="article.photo_url" :alt="article.title" 
                                 class="w-20 h-16 sm:w-32 sm:h-24 object-cover rounded-xl shrink-0">
                        </template>
                        <template x-if="!article.photo_url">
                            <div class="w-20 h-16 sm:w-32 sm:h-24 bg-[#1a307b]/10 rounded-xl shrink-0 flex items-center justify-center">
                                <svg class="w-8 h-8 sm:w-12 sm:h-12 text-[#1a307b]/50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/></svg>
                            </div>
                        </template>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 sm:gap-4 mb-1 sm:mb-2">
                                <h3 class="font-bold text-slate-800 text-sm sm:text-lg" x-text="article.title"></h3>
                                <div class="text-[10px] sm:text-xs text-slate-500 shrink-0" x-text="formatDate(article.publish_date)"></div>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-600 line-clamp-2" x-text="article.content"></p>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 sm:px-6 sm:py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button @click="openEditModal(article)" 
                            class="px-3 py-1.5 sm:px-4 sm:py-2 text-[#1a307b] hover:bg-[#1a307b]/10 rounded-lg font-medium text-xs sm:text-sm transition">
                        Edit
                    </button>
                    <button @click="confirmDelete(article)" 
                            class="px-3 py-1.5 sm:px-4 sm:py-2 text-red-600 hover:bg-red-50 rounded-lg font-medium text-xs sm:text-sm transition">
                        Delete
                    </button>
                </div>
            </div>
        </template>
    </div>

    <!-- Add/Edit Modal -->
    <div x-show="showModal" x-cloak @click.self="closeModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div @click.away="closeModal()" 
             class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full my-8"
             x-transition>
            <div class="sticky top-0 bg-[#1a307b] text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
                <h3 class="text-lg font-bold" x-text="editingNews ? 'Edit News' : 'Add New News'"></h3>
                <button @click="closeModal()" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form @submit.prevent="saveNews()" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Title *</label>
                          <input type="text" x-model="form.title" required
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Content *</label>
                    <textarea x-model="form.content" required rows="6"
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Publish Date *</label>
                          <input type="date" x-model="form.publish_date" required
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Photo</label>
                          <input type="file" @change="handlePhotoChange" accept="image/*" ref="photoInput"
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none">
                    <p class="text-xs text-slate-500 mt-1">Optional: Upload an image (max 5MB, formats: jpg, png, gif, webp)</p>
                    
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
            <h3 class="text-lg font-bold text-slate-800 mb-2">Delete News?</h3>
            <p class="text-slate-600 mb-6">This action cannot be undone.</p>
            <div class="flex gap-3">
                <button @click="showDeleteConfirm = false" 
                        class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button @click="deleteNews()" :disabled="deleting"
                    class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-semibold hover:shadow-lg transition disabled:opacity-50">
                    <span x-text="deleting ? 'Deleting...' : 'Delete'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function newsData() {
    return {
        news: [],
        search: '',
        loading: false,
        saving: false,
        deleting: false,
        showModal: false,
        showDeleteConfirm: false,
        editingNews: null,
        newsToDelete: null,
        photoFile: null,
        photoPreview: null,
        form: {
            title: '',
            content: '',
            publish_date: ''
        },
        
        get filteredNews() {
            if (!this.search) return this.news;
            const query = this.search.toLowerCase();
            return this.news.filter(n => 
                n.title.toLowerCase().includes(query) ||
                n.content.toLowerCase().includes(query)
            );
        },
        
        formatDate(dateString) {
            if (!dateString) return 'No date';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' });
        },
        
        async loadNews() {
            if (this.loading) return; // Prevent multiple simultaneous loads
            
            this.loading = true;
            try {
                const response = await API.get('/admin/news');
                
                // Validate response
                if (!response || typeof response !== 'object') {
                    throw new Error('Invalid response from server');
                }
                
                if (!Array.isArray(response.data)) {
                    console.warn('News data is not an array:', response.data);
                    this.news = [];
                } else {
                    this.news = response.data;
                }
            } catch (error) {
                console.error('Failed to load news:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to load news data';
                showToast(errorMsg, 'error');
                this.news = [];
            } finally {
                this.loading = false;
            }
        },
        
        openAddModal() {
            this.editingNews = null;
            const today = new Date().toISOString().split('T')[0];
            this.form = { title: '', content: '', publish_date: today, photo_path: '' };
            this.photoFile = null;
            this.photoPreview = null;
            this.saving = false; // Reset saving state
            this.showModal = true;
        },
        
        openEditModal(article) {
            // Validate article object
            if (!article || !article.id) {
                showToast('Invalid article data', 'error');
                return;
            }
            
            this.editingNews = article;
            this.form = {
                title: article.title || '',
                content: article.content || '',
                publish_date: article.publish_date || '',
                photo_path: article.photo_path || ''
            };
            this.photoFile = null;
            this.photoPreview = null;
            this.saving = false; // Reset saving state
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
            this.editingNews = null;
            this.photoFile = null;
            this.photoPreview = null;
        },

        handlePhotoChange(event) {
            const file = event.target.files?.[0];
            if (!file) return;
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                showToast('Only JPG, PNG, and GIF images are allowed', 'error');
                if (this.$refs.photoInput) {
                    this.$refs.photoInput.value = '';
                }
                return;
            }
            
            // Validate file size (max 5MB)
            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                showToast('Image size must be less than 5MB', 'error');
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
                showToast('Failed to read image file', 'error');
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
        
        async saveNews() {
            // Prevent double-submit
            if (this.saving) {
                showToast('Saving in progress...', 'warning');
                return;
            }
            
            // Validate form
            if (!this.form.title || this.form.title.trim() === '') {
                showToast('Title is required', 'error');
                return;
            }
            
            if (this.form.title.trim().length < 5) {
                showToast('Title must be at least 5 characters', 'error');
                return;
            }
            
            if (!this.form.content || this.form.content.trim() === '') {
                showToast('Content is required', 'error');
                return;
            }
            
            if (this.form.content.trim().length < 10) {
                showToast('Content must be at least 10 characters', 'error');
                return;
            }
            
            if (!this.form.publish_date || this.form.publish_date.trim() === '') {
                showToast('Publish date is required', 'error');
                return;
            }
            
            // Validate date format
            const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
            if (!dateRegex.test(this.form.publish_date)) {
                showToast('Invalid date format', 'error');
                return;
            }
            
            this.saving = true;
            try {
                const formData = new FormData();
                formData.append('title', this.form.title.trim());
                formData.append('content', this.form.content.trim());
                formData.append('publish_date', this.form.publish_date);
                
                if (this.photoFile) {
                    formData.append('photo', this.photoFile);
                }

                let result;
                if (this.editingNews) {
                    // Validate editing news
                    if (!this.editingNews.id) {
                        throw new Error('Invalid news ID');
                    }
                    
                    formData.append('_method', 'PUT');
                    result = await API.post(`/admin/news/${this.editingNews.id}`, formData);
                    
                    // Validate response
                    if (!result || !result.data) {
                        throw new Error('Invalid response from server');
                    }
                    
                    const index = this.news.findIndex(n => n.id === this.editingNews.id);
                    if (index > -1) {
                        this.news[index] = result.data;
                    } else {
                        console.warn('News not found in list, reloading...');
                        await this.loadNews();
                    }
                    
                    showToast('✓ News updated successfully', 'success');
                } else {
                    result = await API.post('/admin/news', formData);
                    
                    // Validate response
                    if (!result || !result.data) {
                        throw new Error('Invalid response from server');
                    }
                    
                    this.news.unshift(result.data);
                    showToast('✓ News added successfully', 'success');
                }
                
                this.closeModal();
            } catch (error) {
                console.error('Failed to save news:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to save news';
                showToast(errorMsg, 'error');
            } finally {
                this.saving = false;
            }
        },
        
        confirmDelete(article) {
            // Validate article
            if (!article || !article.id) {
                showToast('Invalid article data', 'error');
                return;
            }
            
            this.newsToDelete = article;
            this.showDeleteConfirm = true;
        },
        
        async deleteNews() {
            // Validate news
            if (!this.newsToDelete || !this.newsToDelete.id) {
                showToast('Invalid article data', 'error');
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
                await API.delete(`/admin/news/${this.newsToDelete.id}`);
                this.news = this.news.filter(n => n.id !== this.newsToDelete.id);
                showToast('✓ News deleted successfully', 'success');
                this.showDeleteConfirm = false;
                this.newsToDelete = null;
            } catch (error) {
                console.error('Failed to delete news:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to delete news';
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