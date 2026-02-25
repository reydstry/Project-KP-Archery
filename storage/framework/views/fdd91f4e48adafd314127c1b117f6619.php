<!-- Schedule Section -->
<section class="relative min-h-screen py-24 sm:py-32 
bg-gradient-to-b from-[#1b2659] to-[#0f172a] overflow-hidden">

    <!-- Background decorative blur -->
    <div class="absolute top-10 right-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 left-10 w-96 h-96 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">

        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                <?php echo e(__('program.schedule_title')); ?>

            </h2>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto">
                <?php echo e(__('program.schedule_subtitle')); ?>

            </p>
        </div>

        <?php
            $schedules = [
                [
                    'time'    => 'PAGI',
                    'color'   => 'red',
                    'sessions' => [
                        ['name' => 'SESI 1', 'time' => '07:30 - 09:00', 'badge' => 'Weekend Only'],
                        ['name' => 'SESI 2', 'time' => '09:00 - 10:30', 'badge' => null],
                        ['name' => 'SESI 3', 'time' => '10:30 - 12:00', 'badge' => null],
                    ]
                ],
                [
                    'time'    => 'SORE',
                    'color'   => 'red',
                    'sessions' => [
                        ['name' => 'SESI 1', 'time' => '13:30 - 15:00', 'badge' => null],
                        ['name' => 'SESI 2', 'time' => '15:00 - 16:30', 'badge' => null],
                        ['name' => 'SESI 3', 'time' => '16:30 - 18:00', 'badge' => null],
                    ]
                ],
                [
                    'time'    => 'MALAM',
                    'color'   => 'red',
                    'sessions' => [
                        ['name' => 'SESI 1', 'time' => '19:30 - 21:00', 'badge' => 'By Confirm'],
                    ]
                ],
            ];
        ?>

        <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
            <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="relative group">
                <!-- Glow -->
                <div class="absolute inset-0 bg-<?php echo e($schedule['color']); ?>-500/20 rounded-3xl blur-2xl scale-105 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <!-- Card -->
                <div class="relative h-full bg-white/5 backdrop-blur-md border border-white/20 rounded-2xl overflow-hidden
                            shadow-xl shadow-black/30 hover:shadow-2xl hover:shadow-black/60
                            transition-all duration-300 hover:-translate-y-2">

                    <!-- Shine -->
                    <span class="absolute inset-0 w-full h-full 
                                bg-gradient-to-r from-transparent via-white/10 to-transparent
                                -translate-x-full group-hover:translate-x-full 
                                transition-transform duration-700 ease-in-out skew-x-12 pointer-events-none z-10">
                    </span>

                    <!-- Card Header -->
                    <div class="bg-<?php echo e($schedule['color']); ?>-500/20 border-b border-white/10 px-6 py-5 flex items-center justify-center text-center">
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-white leading-tight"><?php echo e($schedule['time']); ?></h3>
                            <p class="text-white/40 text-xs mt-1"><?php echo e(count($schedule['sessions'])); ?> sesi tersedia</p>
                        </div>
                    </div>

                    <!-- Sessions -->
                    <div class="p-5 space-y-3">
                        <?php $__currentLoopData = $schedule['sessions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center gap-4 bg-white/5 border border-white/10 rounded-xl px-4 py-3">

                            <!-- Time info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-white font-semibold text-sm"><?php echo e($session['name']); ?></span>
                                    <?php if($session['badge']): ?>
                                    <span class="text-xs bg-<?php echo e($schedule['color']); ?>-500/30
                                                px-2 py-0.5 rounded-full font-semibold text-white">
                                        <?php echo e($session['badge']); ?>

                                    </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-white/50 text-xs mt-0.5"><?php echo e($session['time']); ?></p>
                            </div>

                            <!-- Clock icon -->
                            <svg class="w-4 h-4 text-white/30 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    </div>
</section><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/program/schedule-section.blade.php ENDPATH**/ ?>