@extends('layouts.admin')

@section('title', 'News')
@section('subtitle', 'Manage club news and announcements')

@section('content')
<div x-data="newsData()" x-init="loadNews()" class="space-y-6">
    
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div class="flex-1 max-w-md">
            <input type="search" x-model="search" placeholder="Search news..." 
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
        </div>
        <button @click="openAddModal()" 
                class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all">
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
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
                <div class="p-6">
                    <div class="flex items-start gap-6">
                        <template x-if="article.photo_path">
                            <img :src="article.photo_path" :alt="article.title" 
                                 class="w-32 h-24 object-cover rounded-xl shrink-0">
                        </template>
                        <template x-if="!article.photo_path">
                            <div class="w-32 h-24 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl shrink-0 flex items-center justify-center">
                                <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/></svg>
                            </div>
                        </template>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4 mb-2">
                                <h3 class="font-bold text-slate-800 text-lg" x-text="article.title"></h3>
                                <div class="text-xs text-slate-500 shrink-0" x-text="formatDate(article.publish_date)"></div>
                            </div>
                            <p class="text-sm text-slate-600 line-clamp-2" x-text="article.content"></p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button @click="openEditModal(article)" 
                            class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg font-medium text-sm transition">
                        Edit
                    </button>
                    <button @click="confirmDelete(article)" 
                            class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg font-medium text-sm transition">
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
            <div class="sticky top-0 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
                <h3 class="text-lg font-bold" x-text="editingNews ? 'Edit News' : 'Add New News'"></h3>
                <button @click="closeModal()" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form @submit.prevent="saveNews()" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Title *</label>
                    <input type="text" x-model="form.title" required
                           class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Content *</label>
                    <textarea x-model="form.content" required rows="6"
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Publish Date *</label>
                    <input type="date" x-model="form.publish_date" required
                           class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Photo URL</label>
                    <input type="url" x-model="form.photo_path" placeholder="https://..."
                           class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                    <p class="text-xs text-slate-500 mt-1">Optional: Enter image URL</p>
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
        form: {
            title: '',
            content: '',
            publish_date: '',
            photo_path: ''
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
            this.loading = true;
            try {
                const response = await API.get('/admin/news');
                this.news = response.data || [];
            } catch (error) {
                console.error('Failed to load news:', error);
                showToast('Failed to load news', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        openAddModal() {
            this.editingNews = null;
            const today = new Date().toISOString().split('T')[0];
            this.form = { title: '', content: '', publish_date: today, photo_path: '' };
            this.showModal = true;
        },
        
        openEditModal(article) {
            this.editingNews = article;
            this.form = {
                title: article.title,
                content: article.content,
                publish_date: article.publish_date,
                photo_path: article.photo_path || ''
            };
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
            this.editingNews = null;
        },
        
        async saveNews() {
            this.saving = true;
            try {
                if (this.editingNews) {
                    const response = await API.put(`/admin/news/${this.editingNews.id}`, this.form);
                    const index = this.news.findIndex(n => n.id === this.editingNews.id);
                    if (index > -1) this.news[index] = response.data;
                    showToast('News updated successfully', 'success');
                } else {
                    const response = await API.post('/admin/news', this.form);
                    this.news.unshift(response.data);
                    showToast('News added successfully', 'success');
                }
                this.closeModal();
            } catch (error) {
                console.error('Failed to save news:', error);
                showToast(error.message || 'Failed to save news', 'error');
            } finally {
                this.saving = false;
            }
        },
        
        confirmDelete(article) {
            this.newsToDelete = article;
            this.showDeleteConfirm = true;
        },
        
        async deleteNews() {
            if (!this.newsToDelete) return;
            
            this.deleting = true;
            try {
                await API.delete(`/admin/news/${this.newsToDelete.id}`);
                this.news = this.news.filter(n => n.id !== this.newsToDelete.id);
                showToast('News deleted successfully', 'success');
                this.showDeleteConfirm = false;
                this.newsToDelete = null;
            } catch (error) {
                console.error('Failed to delete news:', error);
                showToast(error.message || 'Failed to delete news', 'error');
            } finally {
                this.deleting = false;
            }
        }
    }
}
</script>
@endpush
@endsection