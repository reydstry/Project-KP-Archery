<!-- Facilities Section -->
<section class="relative py-24 sm:py-32 bg-gradient-to-b from-[#0f172a] via-[#1b2659] to-[#273576] overflow-hidden">

    <!-- Background decorative blur -->
    <div class="absolute top-10 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">

        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                <?php echo e(__('about.facilities_main_title')); ?>

            </h2>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto">
                <?php echo e(__('about.facilities_main_subtitle')); ?>

            </p>
        </div>

        <?php
            $facilities = [
                ['image' => 'section.jpg', 'title' => __('about.facility_1_title'), 'description' => __('about.facility_1_desc')],
                ['image' => 'section.jpg', 'title' => __('about.facility_2_title'), 'description' => __('about.facility_2_desc')],
                ['image' => 'section.jpg', 'title' => __('about.facility_3_title'), 'description' => __('about.facility_3_desc')],
                ['image' => 'section.jpg', 'title' => __('about.facility_4_title'), 'description' => __('about.facility_4_desc')],
            ];
        ?>

        <!-- Facility Cards -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="relative group">
                <!-- Glow -->
                <div class="absolute inset-0 bg-red-500/20 rounded-3xl blur-2xl scale-105 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <!-- Card -->
                <div class="relative h-full bg-white/5 backdrop-blur-[2px] border border-white/20 rounded-2xl overflow-hidden
                            shadow-xl shadow-black/30 hover:shadow-2xl hover:shadow-black/60
                            transition-all duration-300 hover:-translate-y-2">

                    <!-- Shine -->
                    <span class="absolute inset-0 w-full h-full 
                                bg-gradient-to-r from-transparent via-white/10 to-transparent
                                -translate-x-full group-hover:translate-x-full 
                                transition-transform duration-700 ease-in-out skew-x-12 pointer-events-none z-10">
                    </span>

                    <!-- Image -->
                    <div class="h-48 overflow-hidden">
                        <img src="<?php echo e(asset('asset/img/facilities/' . $facility['image'])); ?>" 
                             alt="<?php echo e($facility['title']); ?>" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        
                    </div>

                    <!-- Content -->
                    <div class="p-5">
                        <h3 class="text-base sm:text-lg font-bold text-white mb-2"><?php echo e($facility['title']); ?></h3>
                        
                        <!-- Divider -->
                        <div class="w-8 h-0.5 bg-red-500/60 rounded-full mb-3"></div>
                        <p class="text-white/70 text-sm leading-relaxed"><?php echo e($facility['description']); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Stats -->
        <div class="grid sm:grid-cols-3 gap-6 max-w-3xl mx-auto">
            <?php
                $stats = [
                    ['value' => '2000m²', 'label' => __('about.stats_total_area'), 'color' => '[#a72320]'],
                    ['value' => '30+',    'label' => __('about.stats_targets'),    'color' => '[#a72320]'],
                    ['value' => '100+',   'label' => __('about.stats_equipment'),  'color' => '[#a72320]'],
                ];
            ?>

            <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="relative group">
                <div class="absolute inset-0 bg-red-500/20 rounded-3xl blur-2xl scale-105 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative bg-white/5 backdrop-blur-[2px] border border-white/20 rounded-2xl p-6 text-center
                            shadow-xl shadow-black/20 hover:scale-105 transition-all duration-300 overflow-hidden">
                    <!-- Shine -->
                    <span class="absolute inset-0 w-full h-full 
                                bg-gradient-to-r from-transparent via-white/10 to-transparent
                                -translate-x-full group-hover:translate-x-full 
                                transition-transform duration-700 ease-in-out skew-x-12 pointer-events-none z-10">
                    </span>
                    <div class="text-3xl sm:text-4xl font-bold text-white mb-1"><?php echo e($stat['value']); ?></div>
                    <div class="w-8 h-0.5 bg-<?php echo e($stat['color']); ?>-400/60 rounded-full mx-auto"></div>
                    <div class="text-white/60 text-sm"><?php echo e($stat['label']); ?></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    </div>
</section><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/tentang-kami/facilities.blade.php ENDPATH**/ ?>