<!-- Hero Section -->
<section class="relative bg-white py-12 sm:py-16 mt-20">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-start">
            <div>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 sm:mb-6 text-gray-900"><?php echo e(__('about.hero_main_title')); ?></h1>
                <div class="space-y-3 sm:space-y-4 text-sm sm:text-base leading-relaxed text-gray-700 text-justify">
                    <p>
                        <?php echo e(__('about.hero_paragraph_1')); ?>

                    </p>
                    <p>
                        <?php echo e(__('about.hero_paragraph_2')); ?>

                    </p>
                    <p>
                        <?php echo e(__('about.hero_paragraph_3')); ?>

                    </p>
                </div>
            </div>
            <div class="relative mt-8 md:mt-1">
                <div class="rounded-xl sm:rounded-2xl overflow-hidden shadow-lg">
                    <img src="<?php echo e(asset('asset/img/hero-section.png')); ?>" alt="Sejak 2017" 
                         class="w-full h-[250px] sm:h-[300px] md:h-[350px] object-cover">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4 sm:p-6">
                        <h3 class="text-xl sm:text-2xl font-bold mb-1 text-white"><?php echo e(__('about.hero_since_2017')); ?></h3>
                        <p class="text-white text-xs sm:text-sm"><?php echo e(__('about.hero_serving_dedication')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/tentang-kami/hero-section.blade.php ENDPATH**/ ?>