<!-- Achievements Section -->
<section class="relative py-24 sm:py-32 bg-gradient-to-b from-[#273576] to-[#0f172a] overflow-hidden">

    <!-- Background decorative blur -->
    <div class="absolute top-10 right-10 w-72 h-72 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 left-10 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">

        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                <?php echo e(__('about.achievements_title')); ?>

            </h2>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto px-4">
                <?php echo e(__('about.achievements_subtitle')); ?>

            </p>
        </div>

        <?php
            $achievements = [
                [
                    'name' => 'Rizky Ata',
                    'photo' => 'test.png',
                    'awards' => [
                        ['medal' => '🥇', 'title' => 'Juara 1 Kejuaraan Nasional 2024'],
                        ['medal' => '🥈', 'title' => 'Juara 2 Turnamen Internal']
                    ]
                ],
                [
                    'name' => 'Faisal',
                    'photo' => 'test.png',
                    'awards' => [
                        ['medal' => '🥇', 'title' => 'Juara 1 Kejuaraan Nasional 2024'],
                        ['medal' => '🥉', 'title' => 'Juara 3 Turnamen Internal']
                    ]
                ],
                [
                    'name' => 'Yanto',
                    'photo' => 'test.png',
                    'awards' => [
                        ['medal' => '🥇', 'title' => 'Juara 1 Kejuaraan Nasional 2024'],
                        ['medal' => '🥈', 'title' => 'Juara 2 Turnamen Internal']
                    ]
                ],
            ];
        ?>

        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8 mb-12">
            <?php $__currentLoopData = $achievements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $achievement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                transition-transform duration-700 ease-in-out skew-x-12 pointer-events-none">
                    </span>

                    <!-- Image -->
                    <div class="h-48 overflow-hidden">
                        <img src="<?php echo e(asset('asset/img/achievements/' . $achievement['photo'])); ?>" 
                             alt="<?php echo e($achievement['name']); ?>" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        
                    </div>

                    <!-- Content -->
                    <div class="p-5">
                        <div class="text-center">
                            <h4 class="font-bold text-white text-base"><?php echo e($achievement['name']); ?></h4>
                            <p class="text-white/50 text-xs mb-4">Atlet FocusOnex</p>
                        </div>

                        <div class="flex justify-center">
                            <!-- Divider -->
                            <div class="w-25 h-px bg-red-500/70 mb-4"></div>
                        </div>
         
                        <!-- Awards -->
                        <div class="space-y-3">
                            <?php $__currentLoopData = $achievement['awards']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $award): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-xl px-4 py-2.5">
                                <span class="text-xl"><?php echo e($award['medal']); ?></span>
                                <span class="text-white/80 text-xs sm:text-sm leading-snug"><?php echo e($award['title']); ?></span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    </div>
</section><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/tentang-kami/achievements.blade.php ENDPATH**/ ?>