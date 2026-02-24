<!-- Schedule Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-3"><?php echo e(__('program.schedule_section_title')); ?></h2>
        </div>

        <?php
            $schedules = [
                [
                    'time' => 'PAGI',
                    'color' => 'blue',
                    'bg' => 'bg-gradient-to-r from-blue-500 to-blue-600',
                    'sessions' => [
                        ['name' => 'SESI 1', 'time' => '07:30 - 09:00', 'badge' => 'Weekend Only'],
                        ['name' => 'SESI 2', 'time' => '09:00 - 10:30', 'badge' => null],
                        ['name' => 'SESI 3', 'time' => '10:30 - 12:00', 'badge' => null]
                    ]
                ],
                [
                    'time' => 'SORE',
                    'color' => 'blue',
                    'bg' => 'bg-gradient-to-r from-blue-600 to-blue-700',
                    'sessions' => [
                        ['name' => 'SESI 1', 'time' => '13:30 - 15:00', 'badge' => null],
                        ['name' => 'SESI 2', 'time' => '15:00 - 16:30', 'badge' => null],
                        ['name' => 'SESI 3', 'time' => '16:30 - 18:00', 'badge' => null]
                    ]
                ],
                [
                    'time' => 'MALAM (By Confirm)',
                    'color' => 'blue',
                    'bg' => 'bg-gradient-to-r from-blue-700 to-blue-800',
                    'sessions' => [
                        ['name' => 'SESI 1', 'time' => '19:30 - 21:00', 'badge' => null]
                    ]
                ]
            ];
        ?>

        <div class="grid md:grid-cols-3 gap-6">
            <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="border-2 border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                <div class="<?php echo e($schedule['bg']); ?> text-white p-4">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-bold text-lg"><?php echo e($schedule['time']); ?></span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <?php $__currentLoopData = $schedule['sessions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border-l-4 <?php echo e('border-' . $schedule['color'] . '-500'); ?> pl-4">
                        <div class="flex items-center justify-between">
                            <div class="font-bold text-gray-900"><?php echo e($session['name']); ?></div>
                            <?php if($session['badge']): ?>
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-semibold"><?php echo e($session['badge']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="text-gray-600 text-sm"><?php echo e($session['time']); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/program/schedule-section.blade.php ENDPATH**/ ?>