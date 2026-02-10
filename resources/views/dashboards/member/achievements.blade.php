@extends('dashboards.member._layout')

@section('title', 'Prestasi Saya')
@section('subtitle', 'Koleksi pencapaian dan penghargaan Anda')

@section('content')
<div x-data="achievementsData()" x-init="fetchAchievements()">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Prestasi Saya</h2>
            <p class="text-slate-600 mt-1">
                Total <span x-text="achievements.length"></span> prestasi yang telah Anda raih
            </p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="card-animate bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm mb-1">Total Prestasi</p>
                    <h3 class="text-4xl font-bold" x-text="achievements.length"></h3>
                </div>
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/></svg>
                </div>
            </div>
        </div>

        <div class="card-animate bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white" style="animation-delay: 0.05s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm mb-1">Tahun Ini</p>
                    <h3 class="text-4xl font-bold" x-text="achievementsThisYear"></h3>
                </div>
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                </div>
            </div>
        </div>

        <div class="card-animate bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white" style="animation-delay: 0.1s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm mb-1">Kategori</p>
                    <h3 class="text-4xl font-bold" x-text="uniqueCategories"></h3>
                </div>
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                </div>
            </div>
        </div>

        <div class="card-animate bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white" style="animation-delay: 0.15s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm mb-1">Tahun Terakhir</p>
                    <h3 class="text-4xl font-bold" x-text="latestYear"></h3>
                </div>
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter by Year -->
    <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6" style="animation-delay: 0.2s">
        <div class="flex flex-wrap gap-2">
            <button @click="filterYear = null; filterAchievements()"
                    :class="filterYear === null ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-5 py-2.5 rounded-xl font-semibold transition-all">
                Semua Tahun
            </button>
            <template x-for="year in availableYears" :key="year">
                <button @click="filterYear = year; filterAchievements()"
                        :class="filterYear === year ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                        class="px-5 py-2.5 rounded-xl font-semibold transition-all"
                        x-text="year">
                </button>
            </template>
        </div>
    </div>

    <!-- Loading State -->
    <div x-show="loading" class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <!-- Achievements Grid -->
    <div x-show="!loading" x-cloak class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="(achievement, index) in filteredAchievements" :key="achievement.id">
            <div @click="openModal(achievement)"
                 class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl transition-all cursor-pointer group overflow-hidden"
                 :style="`animation-delay: ${(index % 9) * 0.05}s`">
                
                <!-- Achievement Header -->
                <div class="p-6 bg-gradient-to-br from-amber-50 via-amber-100 to-orange-100 border-b border-amber-200 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-300/30 to-orange-300/30 rounded-full -mr-16 -mt-16"></div>
                    <div class="relative z-10 flex items-start justify-between">
                        <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-amber-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/></svg>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-3 py-1.5 bg-white/80 backdrop-blur-sm text-amber-700 rounded-lg text-sm font-bold shadow-sm"
                                  x-text="new Date(achievement.date).getFullYear()">
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Achievement Content -->
                <div class="p-6">
                    <h4 class="font-bold text-slate-800 text-lg mb-2 line-clamp-2 group-hover:text-blue-600 transition"
                        x-text="achievement.title">
                    </h4>
                    
                    <p class="text-slate-600 text-sm mb-4 line-clamp-3" x-text="achievement.description"></p>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            <span x-text="formatDate(achievement.date)"></span>
                        </div>
                        <div class="text-blue-600 group-hover:translate-x-1 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="!loading && filteredAchievements.length === 0" x-cloak
         class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-16 text-center">
        <svg class="w-32 h-32 mx-auto mb-6 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/></svg>
        <h4 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Prestasi</h4>
        <p class="text-slate-500">
            <span x-show="filterYear">Tidak ada prestasi pada tahun <span x-text="filterYear"></span></span>
            <span x-show="!filterYear">Anda belum memiliki prestasi. Terus berlatih untuk meraih prestasi!</span>
        </p>
    </div>

    <!-- Achievement Detail Modal -->
    <div x-show="modalOpen" 
         x-cloak
         @click.self="closeModal()"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.stop
             class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <!-- Modal Header -->
            <div class="p-8 bg-gradient-to-br from-amber-50 via-amber-100 to-orange-100 border-b border-amber-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-amber-300/30 to-orange-300/30 rounded-full -mr-32 -mt-32"></div>
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-20 h-20 bg-gradient-to-br from-amber-400 to-amber-500 rounded-2xl flex items-center justify-center shadow-xl">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/></svg>
                        </div>
                        <button @click="closeModal()" 
                                class="w-10 h-10 bg-white/80 backdrop-blur-sm hover:bg-white rounded-xl flex items-center justify-center transition-all shadow-sm">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2" x-text="selectedAchievement?.title"></h3>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-2 text-sm text-amber-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            <span x-text="formatDate(selectedAchievement?.date)"></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-8">
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Deskripsi</h4>
                    <p class="text-slate-600 leading-relaxed" x-text="selectedAchievement?.description"></p>
                </div>

                <div x-show="selectedAchievement?.category" class="mb-6">
                    <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Kategori</h4>
                    <span class="inline-block px-4 py-2 bg-blue-100 text-blue-700 rounded-lg font-semibold border border-blue-200"
                          x-text="selectedAchievement?.category">
                    </span>
                </div>

                <div x-show="selectedAchievement?.location" class="mb-6">
                    <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Lokasi</h4>
                    <div class="flex items-center gap-2 text-slate-600">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        <span x-text="selectedAchievement?.location"></span>
                    </div>
                </div>

                <div x-show="selectedAchievement?.member_name" class="mb-6">
                    <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Member</h4>
                    <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold"
                             x-text="selectedAchievement?.member_name?.charAt(0).toUpperCase()">
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800" x-text="selectedAchievement?.member_name"></p>
                            <p class="text-sm text-slate-500">Pencapaian Member</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function achievementsData() {
    return {
        loading: true,
        achievements: [],
        filteredAchievements: [],
        filterYear: null,
        availableYears: [],
        modalOpen: false,
        selectedAchievement: null,
        
        get achievementsThisYear() {
            const currentYear = new Date().getFullYear();
            return this.achievements.filter(a => new Date(a.date).getFullYear() === currentYear).length;
        },
        
        get uniqueCategories() {
            return new Set(this.achievements.map(a => a.category).filter(Boolean)).size;
        },
        
        get latestYear() {
            if (this.achievements.length === 0) return new Date().getFullYear();
            return Math.max(...this.achievements.map(a => new Date(a.date).getFullYear()));
        },
        
        async fetchAchievements() {
            this.loading = true;
            try {
                const data = await API.get('/member/dashboard');
                this.achievements = data.achievements || [];
                this.filteredAchievements = [...this.achievements];
                this.extractYears();
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal memuat data prestasi', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        extractYears() {
            const years = [...new Set(this.achievements.map(a => new Date(a.date).getFullYear()))];
            this.availableYears = years.sort((a, b) => b - a);
        },
        
        filterAchievements() {
            if (this.filterYear === null) {
                this.filteredAchievements = [...this.achievements];
            } else {
                this.filteredAchievements = this.achievements.filter(
                    a => new Date(a.date).getFullYear() === this.filterYear
                );
            }
        },
        
        openModal(achievement) {
            this.selectedAchievement = achievement;
            this.modalOpen = true;
            document.body.style.overflow = 'hidden';
        },
        
        closeModal() {
            this.modalOpen = false;
            this.selectedAchievement = null;
            document.body.style.overflow = '';
        },
        
        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        }
    }
}
</script>
@endpush
@endsection
