<section class="py-16 bg-gradient-to-b from-[#1b2659] to-[#0f172a] overflow-hidden relative min-h-screen" x-data="packageData()" x-init="loadPackages()">
    
    <!-- Background decorative blur -->
    <div class="absolute top-10 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                <?php echo e(__('program.packages_title')); ?>

            </h1>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto">
                <?php echo e(__('program.packages_subtitle')); ?>

            </p>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
            <p class="text-white mt-4">Memuat paket...</p>
        </div>

        <!-- Packages Cards -->
        <div x-show="!loading && packages.length > 0" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto mb-16">
            <template x-for="(package, index) in packages" :key="package.id">
                <div class="relative group">
                    <!-- Glow -->
                    <div class="absolute inset-0 bg-red-500/20 rounded-3xl blur-2xl scale-105 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                    <!-- Card -->
                    <div class="relative bg-white/5 backdrop-blur-[2px] border border-white/20 rounded-2xl p-8 text-center
                            shadow-xl shadow-black/30 overflow-hidden transition-all duration-300 hover:scale-105">

                        <!-- Shine -->
                        <span class="absolute inset-0 w-full h-full 
                                    bg-gradient-to-r from-transparent via-white/10 to-transparent
                                    -translate-x-full group-hover:translate-x-full 
                                    transition-transform duration-700 ease-in-out skew-x-12 pointer-events-none z-10">
                        </span>

                        <!-- Card Header -->
                        <h3 class="text-white/60 text-sm font-semibold tracking-widest uppercase mb-2" x-text="package.name">
                        </h3>

                        <!-- Card Body -->
                        <div class="text-4xl font-bold text-white mb-2">
                            Rp <span x-text="formatPrice(package.price)"></span>
                        </div>
                        <p class="text-white/70 text-sm mb-3">
                            <span x-text="package.session_count"></span> sesi / <span x-text="package.duration_days"></span> hari
                        </p>
                        
                        <!-- Description -->
                        <p class="text-white/60 text-xs mt-4 line-clamp-3" x-text="package.description">
                        </p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && packages.length === 0" class="text-center py-12">
            <p class="text-white/60 text-lg">Belum ada paket tersedia saat ini.</p>
        </div>
            </div>
        </div>
    </div>
</section>

<script>
function packageData() {
    return {
        packages: [],
        loading: false,

        async loadPackages() {
            this.loading = true;
            try {
                const response = await fetch('/api/packages');
                const data = await response.json();
                this.packages = data.data || [];
            } catch (error) {
                console.error('Error loading packages:', error);
                this.packages = [];
            } finally {
                this.loading = false;
            }
        },

        formatPrice(price) {
            return new Intl.NumberFormat('id-ID').format(price);
        }
    }
}
</script>
<?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/program/package-section.blade.php ENDPATH**/ ?>