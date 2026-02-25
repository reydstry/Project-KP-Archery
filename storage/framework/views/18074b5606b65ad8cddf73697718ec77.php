<section class="py-16 bg-gradient-to-b from-[#1b2659] to-[#0f172a] overflow-hidden relative min-h-screen">
    
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

        <?php
            $packages = [
                [
                    'color' => 'red',
                    'title' => 'Paket 1',
                    'price' => 'Rp 200.000',
                    'period' => '(4 X / Bulan)'
                ],
                [
                    'color' => 'red',
                    'title' => 'Paket 2',
                    'price' => 'Rp 400.000',
                    'period' => '(10 X / Bulan)'
                ]
            ];
        ?>

        <!-- Packages Cards -->
        <div class="grid md:grid-cols-2 gap-8 max-w-3xl mx-auto mb-16">
            <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="relative group">
                <!-- Glow -->
                <div class="absolute inset-0 bg-<?php echo e($package['color']); ?>-500/20 rounded-3xl blur-2xl scale-105 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

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
                    <h3 class="text-white/60 text-sm font-semibold tracking-widest uppercase mb-2">
                        <?php echo e($package['title']); ?>

                    </h3>

                    <!-- Card Body -->
                    <div class="text-4xl font-bold text-white mb-2">
                        <?php echo e($package['price']); ?>

                    </div>
                    <p class="text-white/70 text-sm">
                        <?php echo e($package['period']); ?>

                    </p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Registration Fee -->
        <div class="relative group max-w-lg mx-auto">
            <!-- Glow -->
            <div class="absolute inset-0 bg-red-500/20 rounded-3xl blur-2xl scale-105 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
            <div class="relative bg-white/5 backdrop-blur-[2px] border border-white/20 rounded-2xl p-8 text-center
                        shadow-xl shadow-black/30 overflow-hidden hover:scale-105">
                
                <!-- Shine -->
                <span class="absolute inset-0 w-full h-full 
                            bg-gradient-to-r from-transparent via-white/10 to-transparent
                            -translate-x-full group-hover:translate-x-full 
                            transition-transform duration-700 ease-in-out skew-x-12 pointer-events-none">
                </span>
                <p class="text-white/60 text-sm font-semibold tracking-widest uppercase mb-2">
                    Registration Fee
                </p>
                <p class="text-4xl sm:text-5xl font-bold text-white mb-2">
                    Rp 200.000
                </p>
            </div>
        </div>
    </div>
</section>
<?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/program/package-section.blade.php ENDPATH**/ ?>