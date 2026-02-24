<section class="py-12 sm:py-16 md:py-20 bg-white overflow-hidden">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-8 sm:mb-10 md:mb-12">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-3 sm:mb-4">
                <?php echo e(__('home.partners_title')); ?>

            </h2>
            <p class="text-base sm:text-lg text-gray-600 px-4">
                <?php echo e(__('home.partners_subtitle')); ?>

            </p>
        </div>

        <!-- Partners Logo Slider -->
        <div class="relative">
            <div class="flex animate-marquee">
                <?php
                    $partners = [
                        'pertamina.png',
                        'JNE.png',
                        'Backwood_Horse_Riding.jpg',
                        'perpani_indonesia.png',
                        'Pesantren_Dhiya.png',
                        'Sekolah_Alam_Balikpapan.png',
                        'TK_AL_Auliya_Balikpapan.png',
                        'YAYASAN_ISTQAMAH.png'
                    ];
                ?>
                
                <!-- First Set -->
                <?php $__currentLoopData = $partners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $logo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex-shrink-0 mx-8 transition-all duration-300 hover:scale-110 hover:shadow-2xl rounded-lg p-4 bg-white/50 hover:bg-white">
                    <img src="<?php echo e(asset('asset/img/partners/' . $logo)); ?>" 
                         alt="Partner <?php echo e($index + 1); ?>" 
                         class="h-16 md:h-20 object-contain">
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <!-- Duplicate Set for Seamless Loop -->
                <?php $__currentLoopData = $partners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $logo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex-shrink-0 mx-8 transition-all duration-300 hover:scale-110 hover:shadow-2xl rounded-lg p-4 bg-white/50 hover:bg-white">
                    <img src="<?php echo e(asset('asset/img/partners/' . $logo)); ?>" 
                         alt="Partner <?php echo e($index + 1); ?>" 
                         class="h-16 md:h-20 object-contain">
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>
<style>
@keyframes marquee {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-300%);
    }
}

/* Desktop */
.animate-marquee {
    animation: marquee 100s linear infinite;
}

/* Mobile = lebih cepat */
@media (max-width: 768px) {
    .animate-marquee {
        animation-duration: 50s;
    }
}
</style><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/home/partners-section.blade.php ENDPATH**/ ?>