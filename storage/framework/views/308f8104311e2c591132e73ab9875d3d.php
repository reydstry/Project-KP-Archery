<!-- Berita Section -->
<div class="relative min-h-screen py-24 sm:py-32 
    bg-gradient-to-b from-[#1b2659] via-[#0f172a] to-[#16213a] overflow-hidden">
    
    <!-- Background decorative blur -->
    <div class="absolute top-10 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">
        
        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                <?php echo e(__('gallery.news_title')); ?>

            </h2>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto">
                <?php echo e(__('gallery.news_subtitle')); ?>

            </p>
        </div>

        <?php
            $newsItems = [
                [
                    'id'      => 1,
                    'image'   => asset('asset/img/latarbelakanglogin.jpeg'),
                    'title'   => 'Tim FocusOneX Raih Juara 1 Kompetisi Regional 2026',
                    'date'    => '20 Januari 2026',
                    'excerpt' => 'Tim panahan FocusOneX berhasil meraih juara pertama dalam kompetisi regional yang diselenggarakan di Balikpapan. Prestasi membanggakan ini...',
                ],
                [
                    'id'      => 2,
                    'image'   => asset('asset/img/latarbelakanglogin.jpeg'),
                    'title'   => 'Atlet FocusOneX Terpilih sebagai Atlet Terbaik 2026',
                    'date'    => '15 Januari 2026',
                    'excerpt' => 'Salah satu atlet FocusOneX berhasil mendapat penghargaan sebagai atlet terbaik dalam ajang kompetisi nasional tahun ini...',
                ],
            ];
        ?>

        <!-- Berita Cards -->
        <div id="content-berita">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <?php $__currentLoopData = $newsItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $news): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="relative group">
                    <!-- Glow -->
                    <div class="absolute inset-0 bg-red-500/20 rounded-3xl blur-2xl scale-105 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

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

                        <!-- Image -->
                        <div class="relative h-52 overflow-hidden">
                            <img src="<?php echo e($news['image']); ?>" 
                                 alt="<?php echo e($news['title']); ?>"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        </div>

                        <!-- Content -->
                        <div class="p-5">
                            <!-- Date -->
                            <div class="flex items-center gap-2 text-white/40 text-xs mb-3">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span><?php echo e($news['date']); ?></span>
                            </div>

                            <!-- Title -->
                            <h3 class="text-base sm:text-lg font-bold text-white mb-2 line-clamp-2 leading-snug">
                                <?php echo e($news['title']); ?>

                            </h3>

                            <!-- Divider -->
                            <div class="w-8 h-0.5 bg-red-400/60 rounded-full mb-3"></div>

                            <!-- Excerpt -->
                            <p class="text-white/60 text-xs sm:text-sm mb-5 line-clamp-3 leading-relaxed">
                                <?php echo e($news['excerpt']); ?>

                            </p>

                            <!-- Read more -->
                            <button onclick="viewBerita(<?php echo e($news['id']); ?>)"
                                    class="inline-flex items-center gap-2 text-red-400 hover:text-red-300 
                                           font-semibold text-sm transition-colors duration-200 group/btn">
                                <?php echo e(__('gallery.read_more')); ?>

                                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform duration-200" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        </div>

    </div>
</div><?php /**PATH C:\laragon\www\Project\club-panahan\resources\views/components/galeri/news-section.blade.php ENDPATH**/ ?>