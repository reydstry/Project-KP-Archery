<!-- Header -->
<section class="relative min-h-screen py-32 
    bg-gradient-to-b from-[#16213a] to-[#1b2659] overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">

        <!-- Section Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                <?php echo e(__('gallery.header_title')); ?>

            </h1>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto">
                <?php echo e(__('gallery.header_subtitle')); ?>

            </p>
        </div>

        <!-- Tab Navigation -->
        <div class="flex justify-center mb-10">
            <div class="tab-switch" id="tab-switch">
                <div id="tab-bubble"></div>

                <button class="tab-link tab-active" data-tab="latihan"
                    onmouseenter="document.getElementById('tab-bubble').classList.add('is-hovered')"
                    onmouseleave="document.getElementById('tab-bubble').classList.remove('is-hovered')">
                    <?php echo e(__('gallery.tab_training')); ?>

                </button>

                <button class="tab-link" data-tab="kompetisi"
                    onmouseenter="document.getElementById('tab-bubble').classList.add('is-hovered')"
                    onmouseleave="document.getElementById('tab-bubble').classList.remove('is-hovered')">
                    <?php echo e(__('gallery.tab_competition')); ?>

                </button>

                <button class="tab-link" data-tab="event"
                    onmouseenter="document.getElementById('tab-bubble').classList.add('is-hovered')"
                    onmouseleave="document.getElementById('tab-bubble').classList.remove('is-hovered')">
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
                    <div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300"
                         onmouseenter="this.classList.add('is-hovered')"
                         onmouseleave="this.classList.remove('is-hovered')">
                        <span class="shine"></span>
                        <img :src="item.photo_url"
                             :alt="item.title"
                             class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">

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
                    <div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300"
                         onmouseenter="this.classList.add('is-hovered')"
                         onmouseleave="this.classList.remove('is-hovered')">
                        <span class="shine"></span>
                        <img :src="item.photo_url"
                             :alt="item.title"
                             class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">

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
                    <div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300"
                         onmouseenter="this.classList.add('is-hovered')"
                         onmouseleave="this.classList.remove('is-hovered')">
                        <span class="shine"></span>
                        <img :src="item.photo_url"
                             :alt="item.title"
                             class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">

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

    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const bubble   = document.getElementById("tab-bubble");
    const links    = document.querySelectorAll(".tab-link");
    const contents = document.querySelectorAll(".tab-content");

    function moveBubble(el) {
        const switchEl   = document.getElementById("tab-switch");
        const switchRect = switchEl.getBoundingClientRect();
        const linkRect   = el.getBoundingClientRect();

        bubble.style.width = linkRect.width + "px";
        bubble.style.left  = (linkRect.left - switchRect.left) + "px";
    }

    // Init bubble ke tab aktif
    const activeLink = document.querySelector(".tab-link.tab-active");
    if (activeLink) {
        requestAnimationFrame(() => moveBubble(activeLink));
    }

    links.forEach(link => {
        link.addEventListener("mouseenter", () => moveBubble(link));

        link.addEventListener("mouseleave", () => {
            const active = document.querySelector(".tab-link.tab-active");
            if (active) moveBubble(active);
        });

        link.addEventListener("click", () => {
            links.forEach(l => l.classList.remove("tab-active"));
            link.classList.add("tab-active");

            const target = link.dataset.tab;
            contents.forEach(c => c.classList.add("hidden"));
            document.getElementById("content-" + target)?.classList.remove("hidden");

            moveBubble(link);
        });
    });
});
</script><?php /**PATH C:\laragon\www\Project\club-panahan\resources\views/components/galeri/hero-section.blade.php ENDPATH**/ ?>