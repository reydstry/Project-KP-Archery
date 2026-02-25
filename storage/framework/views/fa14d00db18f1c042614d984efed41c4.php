<div class="relative py-24 sm:py-32 bg-gradient-to-b from-[#0f172a] to-[#1b2659] overflow-hidden">

    <div class="absolute top-10 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                <?php echo e(__('contact.testimonials_title')); ?>

            </h2>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto">
                <?php echo e(__('contact.testimonials_subtitle')); ?>

            </p>
        </div>

        <?php
            $testimonials = [
                ['name' => 'Sarah Johnson',  'role' => 'Peserta Program Advanced',  'stars' => 4, 'text' => 'Slate helps you see how many more days you need to work to reach your financial goal for the month and year.'],
                ['name' => 'Michael Chen',   'role' => 'Peserta Program Kompetisi', 'stars' => 5, 'text' => 'Pelatihan yang sangat profesional dengan fasilitas lengkap. Instruktur sangat berpengalaman dan sabar dalam mengajar.'],
                ['name' => 'Rina Wijaya',    'role' => 'Peserta Program Pemula',    'stars' => 5, 'text' => 'Sebagai pemula, saya sangat terbantu dengan metode pengajaran yang mudah dipahami. Dalam 3 bulan sudah bisa memanah dengan baik!'],
                ['name' => 'David Kusuma',   'role' => 'Peserta Les Privat',        'stars' => 4, 'text' => 'Les privat sangat membantu untuk meningkatkan teknik secara cepat. Jadwal yang fleksibel juga cocok untuk saya yang sibuk bekerja.'],
                ['name' => 'Lisa Permata',   'role' => 'Peserta Program Menengah',  'stars' => 5, 'text' => 'Lingkungan latihan yang nyaman dan supportif. Sesama peserta juga sangat ramah dan saling membantu. Highly recommended!'],
                ['name' => 'Ahmad Rizki',    'role' => 'Peserta Program Advanced',  'stars' => 4, 'text' => 'Program advanced benar-benar menantang dan meningkatkan skill saya ke level profesional. Sangat puas dengan hasilnya!'],
            ];
        ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="relative group">
                <div class="absolute inset-0 bg-yellow-500/10 rounded-3xl blur-2xl scale-105 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <div class="relative h-full bg-white/5 backdrop-blur-md border border-white/20 rounded-2xl p-6
                            shadow-xl shadow-black/30 hover:shadow-2xl hover:shadow-black/60
                            transition-all duration-300 hover:-translate-y-2 overflow-hidden">

                    <!-- Shine -->
                    <span class="absolute inset-0 w-full h-full 
                                bg-gradient-to-r from-transparent via-white/10 to-transparent
                                -translate-x-full group-hover:translate-x-full 
                                transition-transform duration-700 ease-in-out skew-x-12 pointer-events-none z-10">
                    </span>

                    <!-- Quote icon -->
                    <div class="text-yellow-400/30 text-6xl font-serif leading-none mb-2">"</div>

                    <!-- Text -->
                    <p class="text-white/70 text-sm leading-relaxed mb-5 italic"><?php echo e($t['text']); ?></p>

                    <!-- Divider -->
                    <div class="w-full h-px bg-white/10 mb-4"></div>

                    <!-- Profile + Stars -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="absolute inset-0 bg-yellow-500/30 rounded-full blur-md scale-110 pointer-events-none"></div>
                                <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($t['name'])); ?>&size=80&background=1a307b&color=fff"
                                     alt="<?php echo e($t['name']); ?>"
                                     class="relative w-10 h-10 rounded-full border-2 border-white/20">
                            </div>
                            <div>
                                <p class="text-white font-semibold text-sm"><?php echo e($t['name']); ?></p>
                                <p class="text-white/40 text-xs"><?php echo e($t['role']); ?></p>
                            </div>
                        </div>
                        <!-- Stars -->
                        <div class="flex gap-0.5">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                            <svg class="w-4 h-4 <?php echo e($i <= $t['stars'] ? 'text-yellow-400' : 'text-white/20'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/kontak/testimonials.blade.php ENDPATH**/ ?>