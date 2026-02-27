<!-- Hero Section -->
<section class="relative min-h-screen py-32 
bg-gradient-to-b from-[#1b2659] via-[#0f172a] to-[#16213a] overflow-hidden">

    <!-- Background decorative blur -->
    <div class="absolute top-10 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">

        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                <?php echo e(__('home.program_title')); ?>

            </h1>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto">
                <?php echo e(__('home.program_subtitle')); ?>

            </p>
        </div>

        <?php
            $programs = [
                [
                    'color' => '[#a72320]',
                    'icon' => '<svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="7" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="11" fill="none" stroke="currentColor" stroke-width="2"/></svg>',
                    'title' => __('program.program_club_title'),
                    'description' => __('program.program_club_desc'),
                    'features' => [
                        __('program.program_club_feature_1'),
                        __('program.program_club_feature_2'),
                        __('program.program_club_feature_3'),
                        __('program.program_club_feature_4')
                    ]
                ],
                [
                    'color' => '[#a72320]',
                    'icon' => '<svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>',
                    'title' => __('program.program_competency_title'),
                    'description' => __('program.program_competency_desc'),
                    'features' => [
                        __('program.program_competency_feature_1'),
                        __('program.program_competency_feature_2'),
                        __('program.program_competency_feature_3'),
                        __('program.program_competency_feature_4')
                    ]
                ],
                [
                    'color' => '[#a72320]',
                    'icon' => '<svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>',
                    'title' => __('program.program_coaching_title'),
                    'description' => __('program.program_coaching_desc'),
                    'features' => [
                        __('program.program_coaching_feature_1'),
                        __('program.program_coaching_feature_2'),
                        __('program.program_coaching_feature_3'),
                        __('program.program_coaching_feature_4')
                    ]
                ],
                [
                    'color' => '[#a72320]',
                    'icon' => '<svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4.5 8.5 12 15l7.5-6.5L12 2zm0 13L4.5 8.5V17L12 22l7.5-5V8.5L12 15z" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>',
                    'title' => __('program.program_outbound_title'),
                    'description' => __('program.program_outbound_desc'),
                    'features' => [
                        __('program.program_outbound_feature_1'),
                        __('program.program_outbound_feature_2'),
                        __('program.program_outbound_feature_3'),
                        __('program.program_outbound_feature_4')
                    ]
                ]
            ];
        ?>

        <!-- Program Cards -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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

                    <!-- Card Header -->
                    <div class="bg-<?php echo e($program['color']); ?>/40 border-b
                     border-white/10 px-6 py-5 flex items-center gap-3">
                        <div class="w-11 h-11 bg-<?php echo e($program['color']); ?>/40 backdrop-blur-sm border border-white/20 
                                    rounded-xl flex items-center justify-center text-white flex-shrink-0
                                    shadow-lg shadow-<?php echo e($program['color']); ?>/20">
                            <?php echo $program['icon']; ?>

                        </div>
                        <h3 class="text-sm sm:text-base font-bold text-white leading-tight">
                            <?php echo e($program['title']); ?>

                        </h3>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        <p class="text-white/70 text-sm leading-relaxed mb-5">
                            <?php echo e($program['description']); ?>

                        </p>

                        <!-- Divider -->
                        <div class="w-full h-px bg-white/10 mb-4"></div>

                        <!-- Features -->
                        <ul class="space-y-2.5">
                            <?php $__currentLoopData = $program['features']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="flex items-start gap-2.5 text-sm text-white/70">
                                <span class="mt-0.5 w-4 h-4 flex-shrink-0 bg-<?php echo e($program['color']); ?>-500/30 
                                             rounded-full flex items-center justify-center">
                                    <svg class="w-2.5 h-2.5 text-<?php echo e($program['color']); ?>-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <span><?php echo e($feature); ?></span>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section><?php /**PATH C:\laragon\www\Project\club-panahan\resources\views/components/home/program-section.blade.php ENDPATH**/ ?>