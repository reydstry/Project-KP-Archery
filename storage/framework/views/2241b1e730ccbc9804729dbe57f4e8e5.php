<!-- Testimonials Section -->
<section class="py-12 sm:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8 sm:mb-10 md:mb-12">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2 sm:mb-3"><?php echo e(__('about.achievements_title')); ?></h2>
            <p class="text-sm sm:text-base text-gray-600 px-4"><?php echo e(__('about.achievements_subtitle')); ?></p>
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
                ]
            ];
        ?>

        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-5 sm:gap-6">
            <?php $__currentLoopData = $achievements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $achievement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white border border-gray-200 rounded-lg p-5 sm:p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-4 sm:mb-5">
                    <img src="<?php echo e(asset('asset/img/achievements/' . $achievement['photo'])); ?>" alt="<?php echo e($achievement['name']); ?>" 
                         class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover mr-3">
                    <div>
                        <h4 class="font-bold text-gray-900"><?php echo e($achievement['name']); ?></h4>
                    </div>
                </div>
                <div class="space-y-2 text-gray-700 text-sm">
                    <?php $__currentLoopData = $achievement['awards']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $award): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start">
                        <span class="text-xl mr-2"><?php echo e($award['medal']); ?></span>
                        <span><?php echo e($award['title']); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/tentang-kami/achievements.blade.php ENDPATH**/ ?>