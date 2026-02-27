<!-- Instructor Section -->
<section class="relative py-24 sm:py-32 bg-gradient-to-b from-[#0f172a] to-[#1b2659] overflow-hidden">

    <!-- Background decorative blur -->
    <div class="absolute top-10 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">

        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                <?php echo e(__('program.instructors_title')); ?>

            </h2>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto">
                <?php echo e(__('program.instructors_subtitle')); ?>

            </p>
        </div>

        <?php
            $instructorPoints = [
                [
                    'title' => 'Bersertifikat Resmi',
                    'description' => 'Setiap instruktur telah mengantongi sertifikat kepelatinan panahan resmi dari lembaga yang diakui.',
                    'color' => 'red',
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                ],
                [
                    'title' => 'Atlet Aktif Perpani Balikpapan',
                    'description' => 'Tetap berkompetisi dan menghadirkan kemampuan terkini untuk memberikan pengalaman pelatihan terbaik.',
                    'color' => 'red',
                    'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
                ],
                [
                    'title' => 'Juara Berbagai Kompetisi',
                    'description' => 'Meraih prestasi di tingkat daerah hingga nasional: POPDA, PORNAS, PORDPROV, PON, KEJURNAS, dan berbagai turnamen Open.',
                    'color' => 'red',
                    'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
                ],
            ];
        ?>

        <!-- Cards -->
<div class="flex flex-col gap-6 max-w-3xl mx-auto">
    <?php $__currentLoopData = $instructorPoints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="relative group">
        <!-- Glow -->
        <div class="absolute inset-0 bg-<?php echo e($point['color']); ?>-500/20 rounded-3xl blur-2xl scale-105 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

        <!-- Card -->
        <div class="relative bg-white/5 backdrop-blur-md border border-white/20 rounded-2xl p-6
                    shadow-xl shadow-black/30 hover:shadow-2xl hover:shadow-black/60
                    transition-all duration-300 hover:scale-105 overflow-hidden">

            <!-- Shine -->
            <span class="absolute inset-0 w-full h-full 
                        bg-gradient-to-r from-transparent via-white/10 to-transparent
                        -translate-x-full group-hover:translate-x-full 
                        transition-transform duration-700 ease-in-out skew-x-12 pointer-events-none z-10">
            </span>

            <div class="flex items-center gap-5">

                <!-- Left: Number + Icon -->
                <div class="flex flex-col items-center gap-2 flex-shrink-0">
                    <div class="w-14 h-14 bg-<?php echo e($point['color']); ?>-500/20 backdrop-blur-sm border border-white/20 
                                rounded-2xl flex items-center justify-center
                                shadow-lg shadow-<?php echo e($point['color']); ?>-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($point['icon']); ?>"/>
                        </svg>
                    </div>
                </div>

                <!-- Vertical divider -->
                <div class="w-px h-16 bg-white/10 flex-shrink-0"></div>

                <!-- Right: Text -->
                <div class="flex-1">
                    <h3 class="text-base sm:text-lg font-bold text-white mb-1.5 leading-tight">
                        <?php echo e($point['title']); ?>

                    </h3>
                    <div class="w-8 h-0.5 bg-<?php echo e($point['color']); ?>-400/60 rounded-full mb-2"></div>
                    <p class="text-white/60 text-sm leading-relaxed">
                        <?php echo e($point['description']); ?>

                    </p>
                </div>

            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

    </div>
</section><?php /**PATH C:\laragon\www\Project\club-panahan\resources\views/components/program/instructor-section.blade.php ENDPATH**/ ?>