<!-- Hero Section -->
<section class="relative min-h-screen bg-gradient-to-b 
from-[#0f172a] via-[#1b2659] 
to-[#273576] flex items-center pt-15 overflow-hidden">

    <!-- Background decorative blur -->
    <div class="absolute top-20 left-10 w-72 h-72
     bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96
     bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">

            <!-- LEFT: Text -->
            <div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-6 text-white leading-tight">
                    <?php echo e(__('about.hero_main_title')); ?>

                </h1>

                <div class="space-y-4 text-sm sm:text-base leading-relaxed text-gray-300 text-justify">
                    <p><?php echo e(__('about.hero_paragraph_1')); ?></p>
                    <p><?php echo e(__('about.hero_paragraph_2')); ?></p>
                    <p><?php echo e(__('about.hero_paragraph_3')); ?></p>
                </div>
            </div>

            <!-- RIGHT: Image Card -->
            <div class="relative">
                <!-- Glow behind image -->
                <div class="absolute inset-0 bg-blue-500/20 rounded-3xl blur-2xl scale-105 pointer-events-none"></div>

                <!-- Image -->
                <div class="relative rounded-2xl overflow-hidden border border-white/20 shadow-2xl shadow-black/50">
                    <img src="<?php echo e(asset('asset/img/hero-section.png')); ?>" 
                         alt="Sejak 2017" 
                         class="w-full h-[300px] sm:h-[380px] md:h-[450px] object-cover">

                    <!-- Gradient overlay bottom -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>

                    <!-- Caption Glass Card -->
                    <div class="absolute bottom-4 left-4 right-4
                                bg-white/10 backdrop-blur-[2px] border border-white/20 
                                rounded-xl px-5 py-4">
                        <h3 class="text-lg sm:text-xl font-bold text-white">
                            <?php echo e(__('about.hero_since_2017')); ?>

                        </h3>
                        <p class="text-white/70 text-xs sm:text-sm mt-1">
                            <?php echo e(__('about.hero_serving_dedication')); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/tentang-kami/hero-section.blade.php ENDPATH**/ ?>