@extends('layouts.admin')

@section('title', 'Achievements')
@section('subtitle', 'Manage member and club achievements')

@section('content')
<div x-data="achievementsData()" x-init="init()" class="space-y-6">
    
    <!-- Header Actions -->
    <div class="card-animate flex flex-col lg:flex-row items-stretch lg:items-center gap-3 sm:gap-4">
        <div class="flex gap-2 sm:gap-3 flex-wrap">
            <button @click="filterType = 'all'; search = ''" 
                    :class="filterType === 'all' ? 'bg-[#1a307b] text-white' : 'bg-white text-slate-600 border border-slate-200'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition hover:shadow whitespace-nowrap">
                All
            </button>
            <button @click="filterType = 'member'; search = ''" 
                    :class="filterType === 'member' ? 'bg-[#1a307b] text-white' : 'bg-white text-slate-600 border border-slate-200'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition hover:shadow whitespace-nowrap">
                Member
            </button>
            <button @click="filterType = 'club'; search = ''" 
                    :class="filterType === 'club' ? 'bg-[#1a307b] text-white' : 'bg-white text-slate-600 border border-slate-200'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition hover:shadow whitespace-nowrap">
                Club
            </button>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 flex-1 w-full lg:max-w-xl">
                 <input type="search" x-model="search" placeholder="Search achievements..." 
                     class="flex-1 w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none transition">
                <button @click="openAddModal()" 
                    class="w-full sm:w-auto px-6 py-2 bg-[#1a307b] text-white rounded-xl font-semibold hover:bg-[#152866] transition-all whitespace-nowrap shrink-0">
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
                <template x-if="achievement.photo_url">
                    <img :src="achievement.photo_url" :alt="achievement.title" 
                         class="w-full h-48 object-cover">
                </template>
                <template x-if="!achievement.photo_url">
                    <div class="w-full h-48 bg-[#1a307b]/10 flex items-center justify-center">
                        <svg class="w-20 h-20 text-[#1a307b]/50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/></svg>
                    </div>
                </template>
                <div class="p-5 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-bold text-slate-800 text-lg leading-tight" x-text="achievement.title"></h3>
                        <span :class="achievement.type === 'member' ? 'bg-[#1a307b]/10 text-[#1a307b]' : 'bg-purple-100 text-purple-700'"
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
                <div class="px-5 py-4 bg-[#1a307b] border-t border-slate-100 flex items-center justify-end gap-2">
                        <button @click="openEditModal(achievement)" 
                            class="px-4 py-2 text-white hover:bg-white/20 rounded-lg font-medium text-sm transition">
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
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div @click.away="closeModal()" 
             class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full my-8 max-h-[90vh] overflow-y-auto"
             x-transition>
            <div class="sticky top-0 bg-[#1a307b] text-white px-6 py-4 rounded-t-2xl flex items-center justify-between z-10">
                <h3 class="text-lg font-bold" x-text="editingAchievement ? 'Edit Achievement' : 'Add New Achievement'"></h3>
                <button @click="closeModal()" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form @submit.prevent="saveAchievement()" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Achievement Type *</label>
                        <select x-model="form.type" required
                            class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none">
                        <option value="">Select type...</option>
                        <option value="member">Member Achievement</option>
                        <option value="club">Club Achievement</option>
                    </select>
                </div>
                <div x-show="form.type === 'member'">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Member *</label>
                    <div class="relative" x-data="{ dropdownOpen: false }" @click.away="dropdownOpen = false; showMemberDropdown = false">
                        <!-- Selected Member Display / Search Input -->
                        <div @click="openMemberDropdown(); dropdownOpen = true" 
                             class="w-full px-4 py-2 rounded-xl border border-slate-200 focus-within:ring-2 focus-within:ring-[#1a307b] focus-within:border-transparent cursor-pointer bg-white flex items-center justify-between">
                            <div class="flex-1">
                                <span x-show="!form.member_id && !showMemberDropdown" class="text-slate-400">Select member...</span>
                                <span x-show="form.member_id && !showMemberDropdown" x-text="selectedMemberName" class="text-slate-800"></span>
                                <input x-show="showMemberDropdown" 
                                       x-ref="memberSearchInput"
                                       x-model="memberSearchQuery" 
                                       @input="dropdownOpen = true"
                                       type="text" 
                                       placeholder="Search member..." 
                                       class="w-full outline-none bg-transparent">
                            </div>
                            <div class="flex items-center gap-1">
                                <button x-show="form.member_id" 
                                        @click.stop="clearMemberSelection()" 
                                        type="button"
                                        class="text-slate-400 hover:text-slate-600 p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        
                        <!-- Dropdown List -->
                        <div x-show="dropdownOpen && showMemberDropdown" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute z-50 w-full mt-2 bg-white rounded-xl border border-slate-200 shadow-xl max-h-60 overflow-y-auto">
                            <template x-if="filteredMembers.length === 0">
                                <div class="px-4 py-3 text-sm text-slate-500 text-center">
                                    <span x-text="memberSearchQuery ? 'No members found' : 'No members available'"></span>
                                </div>
                            </template>
                            <template x-for="member in filteredMembers" :key="member.id">
                                <div @click="selectMember(member); dropdownOpen = false" 
                                     class="px-4 py-3 hover:bg-slate-50 cursor-pointer transition border-b border-slate-100 last:border-b-0">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-sm font-semibold text-slate-800" x-text="member.name"></div>
                                            <div x-show="member.email" class="text-xs text-slate-500" x-text="member.email"></div>
                                        </div>
                                        <svg x-show="form.member_id == member.id" class="w-5 h-5 text-[#1a307b]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Title *</label>
                          <input type="text" x-model="form.title" required
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description *</label>
                    <textarea x-model="form.description" required rows="4"
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-transparent outline-none resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Date *</label>
                          <input type="date" x-model="form.date" required
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
            <h3 class="text-lg font-bold text-slate-800 mb-2">Delete Achievement?</h3>
            <p class="text-slate-600 mb-6">This action cannot be undone.</p>
            <div class="flex gap-3">
                <button @click="showDeleteConfirm = false" 
                        class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button @click="deleteAchievement()" :disabled="deleting"
                    class="flex-1 px-4 py-3 bg-linear-to-r from-red-500 to-red-600 text-white rounded-xl font-semibold hover:shadow-lg transition disabled:opacity-50">
                    <span x-text="deleting ? 'Deleting...' : 'Delete'"></span>
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
        showSuccessModal: false,
        showErrorModal: false,
        successMessage: '',
        errorMessage: '',
        editingAchievement: null,
        achievementToDelete: null,
        photoFile: null,
        photoPreview: null,
        memberSearchQuery: '',
        showMemberDropdown: false,
        form: {
            type: '',
            member_id: '',
            title: '',
            description: '',
            date: ''
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
        
        get filteredMembers() {
            if (!this.memberSearchQuery) return this.members;
            const query = this.memberSearchQuery.toLowerCase();
            return this.members.filter(m => 
                m.name.toLowerCase().includes(query) ||
                (m.email && m.email.toLowerCase().includes(query))
            );
        },
        
        get selectedMemberName() {
            if (!this.form.member_id) return '';
            const member = this.members.find(m => m.id == this.form.member_id);
            return member ? member.name : '';
        },
        
        formatDate(dateString) {
            if (!dateString) return 'No date';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' });
        },
        
        showSuccessMessage(message) {
            this.successMessage = message;
            this.showSuccessModal = true;
        },
        
        closeSuccessModal() {
            this.showSuccessModal = false;
            this.successMessage = '';
        },
        
        showErrorMessage(message) {
            this.errorMessage = message;
            this.showErrorModal = true;
        },
        
        closeErrorModal() {
            this.showErrorModal = false;
            this.errorMessage = '';
        },
        
        async init() {
            await Promise.all([this.loadAchievements(), this.loadMembers()]);
        },
        
        async loadAchievements() {
            if (this.loading) return; // Prevent multiple simultaneous loads
            
            this.loading = true;
            try {
                const response = await API.get('/admin/achievements');
                
                // Validate response
                if (!response || typeof response !== 'object') {
                    throw new Error('Invalid response from server');
                }
                
                if (!Array.isArray(response.data)) {
                    console.warn('Achievements data is not an array:', response.data);
                    this.achievements = [];
                } else {
                    this.achievements = response.data;
                }
            } catch (error) {
                console.error('Failed to load achievements:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to load achievements';
                this.showErrorMessage(errorMsg);
                this.achievements = [];
            } finally {
                this.loading = false;
            }
        },
        
        async loadMembers() {
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
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to load members';
                this.showErrorMessage(errorMsg);
                this.members = [];
            }
        },
        
        openAddModal() {
            this.editingAchievement = null;
            const today = new Date().toISOString().split('T')[0];
            this.form = { type: '', member_id: '', title: '', description: '', date: today };
            this.photoFile = null;
            this.photoPreview = null;
            this.memberSearchQuery = '';
            this.showMemberDropdown = false;
            this.saving = false; // Reset saving state
            this.showModal = true;
        },
        
        openEditModal(achievement) {
            // Validate achievement object
            if (!achievement || !achievement.id) {
                this.showErrorMessage('Invalid achievement data');
                return;
            }
            
            this.editingAchievement = achievement;
            this.form = {
                type: achievement.type || '',
                member_id: achievement.member_id || '',
                title: achievement.title || '',
                description: achievement.description || '',
                date: achievement.date || ''
            };
            this.photoFile = null;
            this.photoPreview = achievement.photo_url || null;
            this.saving = false; // Reset saving state
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
            this.editingAchievement = null;
            this.photoFile = null;
            this.photoPreview = null;
            this.memberSearchQuery = '';
            this.showMemberDropdown = false;
        },
        
        selectMember(member) {
            this.form.member_id = member.id;
            this.memberSearchQuery = '';
            this.showMemberDropdown = false;
        },
        
        openMemberDropdown() {
            this.showMemberDropdown = true;
            this.$nextTick(() => {
                const input = this.$refs.memberSearchInput;
                if (input) input.focus();
            });
        },
        
        clearMemberSelection() {
            this.form.member_id = '';
            this.memberSearchQuery = '';
        },

        handlePhotoChange(event) {
            const file = event.target.files?.[0];
            if (!file) return;
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                this.showErrorMessage('Only JPG, PNG, and GIF images are allowed');
                if (this.$refs.photoInput) {
                    this.$refs.photoInput.value = '';
                }
                return;
            }
            
            // Validate file size (max 5MB)
            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                this.showErrorMessage('Image size must be less than 5MB');
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
                this.showErrorMessage('Failed to read image file');
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
        
        async saveAchievement() {
            // Prevent double-submit
            if (this.saving) {
                this.showErrorMessage('Saving in progress...');
                return;
            }
            
            // Validate form
            if (!this.form.type || this.form.type === '') {
                this.showErrorMessage('Achievement type is required');
                return;
            }
            
            if (this.form.type === 'member' && (!this.form.member_id || this.form.member_id === '')) {
                this.showErrorMessage('Please select a member for member achievement');
                return;
            }
            
            if (!this.form.title || this.form.title.trim() === '') {
                this.showErrorMessage('Title is required');
                return;
            }
            
            if (this.form.title.trim().length < 3) {
                this.showErrorMessage('Title must be at least 3 characters');
                return;
            }
            
            if (!this.form.description || this.form.description.trim() === '') {
                this.showErrorMessage('Description is required');
                return;
            }
            
            if (this.form.description.trim().length < 10) {
                this.showErrorMessage('Description must be at least 10 characters');
                return;
            }
            
            if (!this.form.date || this.form.date === '') {
                this.showErrorMessage('Date is required');
                return;
            }
            
            // Validate date format
            const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
            if (!dateRegex.test(this.form.date)) {
                this.showErrorMessage('Invalid date format');
                return;
            }
            
            this.saving = true;
            try {
                const formData = new FormData();
                formData.append('type', this.form.type);
                formData.append('title', this.form.title.trim());
                formData.append('description', this.form.description.trim());
                formData.append('date', this.form.date);
                
                if (this.form.type === 'member' && this.form.member_id) {
                    formData.append('member_id', this.form.member_id);
                }
                
                if (this.photoFile) {
                    formData.append('photo', this.photoFile);
                }

                let result;
                if (this.editingAchievement) {
                    // Validate editing achievement
                    if (!this.editingAchievement.id) {
                        throw new Error('Invalid achievement ID');
                    }
                    
                    formData.append('_method', 'PUT');
                    result = await API.post(`/admin/achievements/${this.editingAchievement.id}`, formData);
                    
                    // Validate response
                    if (!result || !result.data) {
                        throw new Error('Invalid response from server');
                    }
                    
                    const index = this.achievements.findIndex(a => a.id === this.editingAchievement.id);
                    if (index > -1) {
                        this.achievements[index] = result.data;
                    } else {
                        console.warn('Achievement not found in list, reloading...');
                        await this.loadAchievements();
                    }
                    
                    this.showSuccessMessage('Achievement berhasil diperbarui');
                } else {
                    result = await API.post('/admin/achievements', formData);
                    
                    // Validate response
                    if (!result || !result.data) {
                        throw new Error('Invalid response from server');
                    }
                    
                    this.achievements.unshift(result.data);
                    this.showSuccessMessage('Achievement berhasil ditambahkan');
                }
                
                this.closeModal();
            } catch (error) {
                console.error('Failed to save achievement:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to save achievement';
                this.showErrorMessage(errorMsg);
            } finally {
                this.saving = false;
            }
        },
        
        confirmDelete(achievement) {
            // Validate achievement
            if (!achievement || !achievement.id) {
                this.showErrorMessage('Invalid achievement data');
                return;
            }
            
            this.achievementToDelete = achievement;
            this.showDeleteConfirm = true;
        },
        
        async deleteAchievement() {
            // Validate achievement
            if (!this.achievementToDelete || !this.achievementToDelete.id) {
                this.showErrorMessage('Invalid achievement data');
                this.showDeleteConfirm = false;
                return;
            }
            
            // Prevent double-submit
            if (this.deleting) {
                this.showErrorMessage('Deletion in progress...');
                return;
            }
            
            this.deleting = true;
            try {
                await API.delete(`/admin/achievements/${this.achievementToDelete.id}`);
                this.achievements = this.achievements.filter(a => a.id !== this.achievementToDelete.id);
                this.showSuccessMessage('Achievement berhasil dihapus');
                this.showDeleteConfirm = false;
                this.achievementToDelete = null;
            } catch (error) {
                console.error('Failed to delete achievement:', error);
                const errorMsg = error?.response?.data?.message || error?.message || 'Failed to delete achievement';
                this.showErrorMessage(errorMsg);
            } finally {
                this.deleting = false;
            }
        }
    }
}
</script>
@endpush
@endsection