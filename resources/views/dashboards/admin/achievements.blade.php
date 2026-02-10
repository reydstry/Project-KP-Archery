@extends('layouts.admin')

@section('title', 'Achievements')
@section('subtitle', 'Manage member and club achievements')

@section('content')
<div x-data="achievementsData()" x-init="init()" class="space-y-6">
    
    <!-- Header Actions -->
    <div class="card-animate flex flex-col lg:flex-row items-stretch lg:items-center gap-3 sm:gap-4">
        <div class="flex gap-2 sm:gap-3 flex-wrap">
            <button @click="filterType = 'all'; search = ''" 
                    :class="filterType === 'all' ? 'bg-blue-500 text-white' : 'bg-white text-slate-600 border border-slate-200'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition hover:shadow whitespace-nowrap">
                All
            </button>
            <button @click="filterType = 'member'; search = ''" 
                    :class="filterType === 'member' ? 'bg-blue-500 text-white' : 'bg-white text-slate-600 border border-slate-200'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition hover:shadow whitespace-nowrap">
                Member
            </button>
            <button @click="filterType = 'club'; search = ''" 
                    :class="filterType === 'club' ? 'bg-blue-500 text-white' : 'bg-white text-slate-600 border border-slate-200'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition hover:shadow whitespace-nowrap">
                Club
            </button>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 flex-1 w-full lg:max-w-xl">
            <input type="search" x-model="search" placeholder="Search achievements..." 
                   class="flex-1 w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
            <button @click="openAddModal()" 
                    class="w-full sm:w-auto px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all whitespace-nowrap shrink-0">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add Achievement
                </span>
            </button>
        </div>
    </div>

    <!-- Achievements Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-if="loading">
            <div class="col-span-full text-center py-12 text-slate-400">Loading...</div>
        </template>
        <template x-if="!loading && filteredAchievements.length === 0">
            <div class="col-span-full text-center py-12 text-slate-400">No achievements found</div>
        </template>
        <template x-for="achievement in filteredAchievements" :key="achievement.id">
            <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg transition-all overflow-hidden">
                <template x-if="achievement.photo_path">
                    <img :src="achievement.photo_path" :alt="achievement.title" 
                         class="w-full h-48 object-cover">
                </template>
                <template x-if="!achievement.photo_path">
                    <div class="w-full h-48 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                        <svg class="w-20 h-20 text-blue-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/></svg>
                    </div>
                </template>
                <div class="p-5 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-bold text-slate-800 text-lg leading-tight" x-text="achievement.title"></h3>
                        <span :class="achievement.type === 'member' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'"
                              class="px-3 py-1 rounded-full text-xs font-semibold shrink-0 uppercase" x-text="achievement.type"></span>
                    </div>
                    <p class="text-sm text-slate-600 line-clamp-2" x-text="achievement.description"></p>
                    <div class="flex items-center gap-4 text-xs text-slate-500 pt-2 border-t border-slate-100">
                        <span x-text="formatDate(achievement.date)"></span>
                        <template x-if="achievement.type === 'member' && achievement.member">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                <span x-text="achievement.member.name"></span>
                            </span>
                        </template>
                    </div>
                </div>
                <div class="px-5 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button @click="openEditModal(achievement)" 
                            class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg font-medium text-sm transition">
                        Edit
                    </button>
                    <button @click="confirmDelete(achievement)" 
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
                <h3 class="text-lg font-bold" x-text="editingAchievement ? 'Edit Achievement' : 'Add New Achievement'"></h3>
                <button @click="closeModal()" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form @submit.prevent="saveAchievement()" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Achievement Type *</label>
                    <select x-model="form.type" required
                            class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                        <option value="">Select type...</option>
                        <option value="member">Member Achievement</option>
                        <option value="club">Club Achievement</option>
                    </select>
                </div>
                <div x-show="form.type === 'member'">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Member *</label>
                    <select x-model="form.member_id" :required="form.type === 'member'"
                            class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                        <option value="">Select member...</option>
                        <template x-for="member in members" :key="member.id">
                            <option :value="member.id" x-text="member.name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Title *</label>
                    <input type="text" x-model="form.title" required
                           class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description *</label>
                    <textarea x-model="form.description" required rows="4"
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Date *</label>
                    <input type="date" x-model="form.date" required
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
            <h3 class="text-lg font-bold text-slate-800 mb-2">Delete Achievement?</h3>
            <p class="text-slate-600 mb-6">This action cannot be undone.</p>
            <div class="flex gap-3">
                <button @click="showDeleteConfirm = false" 
                        class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button @click="deleteAchievement()" :disabled="deleting"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-semibold hover:shadow-lg transition disabled:opacity-50">
                    <span x-text="deleting ? 'Deleting...' : 'Delete'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function achievementsData() {
    return {
        achievements: [],
        members: [],
        search: '',
        filterType: 'all',
        loading: false,
        saving: false,
        deleting: false,
        showModal: false,
        showDeleteConfirm: false,
        editingAchievement: null,
        achievementToDelete: null,
        form: {
            type: '',
            member_id: '',
            title: '',
            description: '',
            date: '',
            photo_path: ''
        },
        
        get filteredAchievements() {
            let filtered = this.achievements;
            
            if (this.filterType !== 'all') {
                filtered = filtered.filter(a => a.type === this.filterType);
            }
            
            if (this.search) {
                const query = this.search.toLowerCase();
                filtered = filtered.filter(a => 
                    a.title.toLowerCase().includes(query) ||
                    a.description.toLowerCase().includes(query) ||
                    (a.member && a.member.name.toLowerCase().includes(query))
                );
            }
            
            return filtered;
        },
        
        formatDate(dateString) {
            if (!dateString) return 'No date';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' });
        },
        
        async init() {
            await Promise.all([this.loadAchievements(), this.loadMembers()]);
        },
        
        async loadAchievements() {
            this.loading = true;
            try {
                const response = await API.get('/admin/achievements');
                this.achievements = response.data || [];
            } catch (error) {
                console.error('Failed to load achievements:', error);
                showToast('Failed to load achievements', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        async loadMembers() {
            try {
                const response = await API.get('/admin/members');
                this.members = response.data || [];
            } catch (error) {
                console.error('Failed to load members:', error);
            }
        },
        
        openAddModal() {
            this.editingAchievement = null;
            const today = new Date().toISOString().split('T')[0];
            this.form = { type: '', member_id: '', title: '', description: '', date: today, photo_path: '' };
            this.showModal = true;
        },
        
        openEditModal(achievement) {
            this.editingAchievement = achievement;
            this.form = {
                type: achievement.type,
                member_id: achievement.member_id || '',
                title: achievement.title,
                description: achievement.description,
                date: achievement.date,
                photo_path: achievement.photo_path || ''
            };
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
            this.editingAchievement = null;
        },
        
        async saveAchievement() {
            this.saving = true;
            try {
                const payload = { ...this.form };
                if (payload.type === 'club') payload.member_id = null;
                
                if (this.editingAchievement) {
                    const response = await API.put(`/admin/achievements/${this.editingAchievement.id}`, payload);
                    const index = this.achievements.findIndex(a => a.id === this.editingAchievement.id);
                    if (index > -1) this.achievements[index] = response.data;
                    showToast('Achievement updated successfully', 'success');
                } else {
                    const response = await API.post('/admin/achievements', payload);
                    this.achievements.unshift(response.data);
                    showToast('Achievement added successfully', 'success');
                }
                this.closeModal();
            } catch (error) {
                console.error('Failed to save achievement:', error);
                showToast(error.message || 'Failed to save achievement', 'error');
            } finally {
                this.saving = false;
            }
        },
        
        confirmDelete(achievement) {
            this.achievementToDelete = achievement;
            this.showDeleteConfirm = true;
        },
        
        async deleteAchievement() {
            if (!this.achievementToDelete) return;
            
            this.deleting = true;
            try {
                await API.delete(`/admin/achievements/${this.achievementToDelete.id}`);
                this.achievements = this.achievements.filter(a => a.id !== this.achievementToDelete.id);
                showToast('Achievement deleted successfully', 'success');
                this.showDeleteConfirm = false;
                this.achievementToDelete = null;
            } catch (error) {
                console.error('Failed to delete achievement:', error);
                showToast(error.message || 'Failed to delete achievement', 'error');
            } finally {
                this.deleting = false;
            }
        }
    }
}
</script>
@endpush
@endsection