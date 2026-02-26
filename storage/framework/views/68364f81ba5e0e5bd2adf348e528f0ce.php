<!-- Header -->
<section class="relative min-h-screen py-32 
    bg-gradient-to-b from-[#16213a] via-[#0f172a] to-[#1b2659] overflow-hidden">
    
    <!-- Background decorative blur -->
    <div class="absolute top-10 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>


    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-12">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
          <?php echo e(__('gallery.header_title')); ?>

        </h1>
        <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto">
          <?php echo e(__('gallery.header_subtitle')); ?>

        </p>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-8 sm:mb-10">
        <div class="flex flex-wrap justify-center gap-2 sm:gap-4 border-b-2 border-gray-200 px-4">
            <button onclick="switchTab('latihan')" id="tab-latihan" class="tab-button active px-6 sm:px-8 py-3 sm:py-4 text-sm sm:text-base font-semibold border-b-2 transition-colors">
                <?php echo e(__('gallery.tab_training')); ?>

            </button>
            <button onclick="switchTab('kompetisi')" id="tab-kompetisi" class="tab-button px-6 sm:px-8 py-3 sm:py-4 text-sm sm:text-base font-semibold border-b-2 transition-colors">
                <?php echo e(__('gallery.tab_competition')); ?>

            </button>
            <button onclick="switchTab('event')" id="tab-event" class="tab-button px-6 sm:px-8 py-3 sm:py-4 text-sm sm:text-base font-semibold border-b-2 transition-colors">
                <?php echo e(__('gallery.tab_group_selfie')); ?>

            </button>
        </div>
    </div>

    <!-- Tab Content: Latihan -->
<div id="content-latihan" class="tab-content" x-data="galleryTab('training')" x-init="loadGalleries()">
    <!-- Loading State -->
    <div x-show="loading" class="flex justify-center items-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
    </div>

    <!-- Empty State -->
    <div x-show="!loading && galleries.length === 0" class="text-center py-20">
        <p class="text-gray-400 text-lg">Belum ada foto latihan</p>
    </div>

    <!-- Gallery Grid -->
    <div x-show="!loading && galleries.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
        <template x-for="item in galleries" :key="item.id">
            <div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 bg-white">
                <img :src="item.photo_url" 
                     :alt="item.title"
                     class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300">
                
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-4">
                        <h3 class="text-white font-semibold text-base sm:text-lg mb-1" x-text="item.title"></h3>
                        <p x-show="item.description" class="text-white/80 text-xs sm:text-sm mt-1" x-text="item.description"></p>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

<!-- Tab Content: Kompetisi -->
<div id="content-kompetisi" class="tab-content hidden" x-data="galleryTab('competition')" x-init="loadGalleries()">
    <!-- Loading State -->
    <div x-show="loading" class="flex justify-center items-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
    </div>

    <!-- Empty State -->
    <div x-show="!loading && galleries.length === 0" class="text-center py-20">
        <p class="text-gray-400 text-lg">Belum ada foto kompetisi</p>
    </div>

    <!-- Gallery Grid -->
    <div x-show="!loading && galleries.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
        <template x-for="item in galleries" :key="item.id">
            <div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 bg-white">
                <img :src="item.photo_url" 
                     :alt="item.title"
                     class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300">
                
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-4">
                        <h3 class="text-white font-semibold text-base sm:text-lg mb-1" x-text="item.title"></h3>
                        <p x-show="item.description" class="text-white/80 text-xs sm:text-sm mt-1" x-text="item.description"></p>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

<!-- Tab Content: Event -->
<div id="content-event" class="tab-content hidden" x-data="galleryTab('group_selfie')" x-init="loadGalleries()">
    <!-- Loading State -->
    <div x-show="loading" class="flex justify-center items-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
    </div>

    <!-- Empty State -->
    <div x-show="!loading && galleries.length === 0" class="text-center py-20">
        <p class="text-gray-400 text-lg">Belum ada foto group selfie</p>
    </div>

    <!-- Gallery Grid -->
    <div x-show="!loading && galleries.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
        <template x-for="item in galleries" :key="item.id">
            <div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 bg-white">
                <img :src="item.photo_url" 
                     :alt="item.title"
                     class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300">
                
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-4">
                        <h3 class="text-white font-semibold text-base sm:text-lg mb-1" x-text="item.title"></h3>
                        <p x-show="item.description" class="text-white/80 text-xs sm:text-sm mt-1" x-text="item.description"></p>
                    </div>
                </div>
            </div>
        </template>
    </div>
    
</div>

</section><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/galeri/hero-section.blade.php ENDPATH**/ ?>