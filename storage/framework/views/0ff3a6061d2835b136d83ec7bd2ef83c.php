<section class="py-12 sm:py-16 md:py-20 bg-gradient-to-b from-[#16213a] to-[#1b2659] overflow-hidden">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-20 sm:mb-22 md:mb-24">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-3 sm:mb-4">
                <?php echo e(__('home.partners_title')); ?>

            </h2>
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
                <div class="flex-shrink-0 mx-8 transition-all duration-300 shadow-xl hover:scale-110 hover:shadow-2xl rounded-lg p-4 bg-white">
                    <img src="<?php echo e(asset('asset/img/partners/' . $logo)); ?>" 
                         alt="Partner <?php echo e($index + 1); ?>" 
                         class="h-16 md:h-20 object-contain">
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <!-- Duplicate Set for Seamless Loop -->
                <?php $__currentLoopData = $partners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $logo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex-shrink-0 mx-8 transition-all duration-300 shadow-xl hover:scale-110 hover:shadow-2xl rounded-lg p-4 bg-white">
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
        transform: translateX(-50%);
    }
}

/* Desktop */
.animate-marquee {
    display: flex;
    width: max-content;
    animation: marquee 20s linear infinite;
}

/* Mobile = lebih cepat */
@media (max-width: 768px) {
    .animate-marquee {
        animation-duration: 30s;
    }
}
</style><?php /**PATH C:\laragon\www\Project\club-panahan\resources\views/components/home/partners-section.blade.php ENDPATH**/ ?>