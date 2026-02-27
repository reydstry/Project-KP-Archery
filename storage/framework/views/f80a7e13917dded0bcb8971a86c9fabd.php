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
            use App\Models\News;
            use App\Models\Achievement;
            
            // Fetch 3 latest published news from database
            $newsItems = News::query()
                ->published()
                ->orderBy('publish_date', 'desc')
                ->orderBy('id', 'desc')
                ->limit(3)
                ->get()
                ->map(function($news) {
                    return [
                        'id'      => $news->id,
                        'type'    => 'news',
                        'image'   => $news->photo_url ?? asset('asset/img/latarbelakanglogin.jpeg'),
                        'title'   => $news->title,
                        'date'    => $news->publish_date->format('d F Y'),
                        'excerpt' => \Illuminate\Support\Str::limit(strip_tags($news->content), 150),
                    ];
                });

            // Fetch 3 latest published achievements from database
            $achievementItems = Achievement::query()
                ->published()
                ->with('member')
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->limit(3)
                ->get()
                ->map(function($achievement) {
                    // Determine badge based on title keywords
                    $badge = '🏆';
                    if (str_contains(strtolower($achievement->title), 'juara 1') || str_contains(strtolower($achievement->title), 'gold') || str_contains(strtolower($achievement->title), '1st place')) {
                        $badge = '🥇';
                    } elseif (str_contains(strtolower($achievement->title), 'juara 2') || str_contains(strtolower($achievement->title), 'silver') || str_contains(strtolower($achievement->title), '2nd place')) {
                        $badge = '🥈';
                    } elseif (str_contains(strtolower($achievement->title), 'juara 3') || str_contains(strtolower($achievement->title), 'bronze') || str_contains(strtolower($achievement->title), '3rd place')) {
                        $badge = '🥉';
                    }

                    return [
                        'id'      => $achievement->id,
                        'type'    => 'achievement',
                        'badge'   => $badge,
                        'image'   => $achievement->photo_url ?? asset('asset/img/latarbelakanglogin.jpeg'),
                        'title'   => $achievement->title,
                        'date'    => $achievement->date->format('d F Y'),
                        'excerpt' => \Illuminate\Support\Str::limit(strip_tags($achievement->description), 120),
                        'member'  => $achievement->type === 'member' && $achievement->member ? $achievement->member->name : 'FocusOneX Club',
                    ];
                });

            // Merge both - 3 news first, then 3 achievements
            $allItems = $newsItems->concat($achievementItems);
        ?>

        <!-- News & Achievement Combined Section -->
        <div id="content-berita">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <?php $__currentLoopData = $allItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="relative group">
                    <!-- Glow - Different color for achievements -->
                    <div class="absolute inset-0 <?php echo e($item['type'] === 'achievement' ? 'bg-yellow-500/20' : 'bg-red-500/20'); ?> rounded-3xl blur-2xl scale-105 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

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

                        <!-- Type Badge (Top Right Corner) -->
                        <?php if($item['type'] === 'achievement'): ?>
                        <div class="absolute top-4 right-4 z-20 bg-gradient-to-r from-yellow-500 to-amber-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg flex items-center gap-1">
                            <span><?php echo e($item['badge']); ?></span>
                            <span>Achievement</span>
                        </div>
                        <?php else: ?>
                        <div class="absolute top-4 right-4 z-20 bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                            </svg>
                            <span>News</span>
                        </div>
                        <?php endif; ?>

                        <!-- Image -->
                        <div class="relative h-52 overflow-hidden">
                            <img src="<?php echo e($item['image']); ?>"
                                 alt="<?php echo e($item['title']); ?>"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        </div>

                        <!-- Content -->
                        <div class="p-5">
                            <!-- Date & Member Info -->
                            <div class="flex items-center justify-between gap-2 text-white/40 text-xs mb-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span><?php echo e($item['date']); ?></span>
                                </div>
                                <?php if($item['type'] === 'achievement' && isset($item['member'])): ?>
                                <div class="flex items-center gap-1 text-yellow-400/80">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                    </svg>
                                    <span class="truncate max-w-[120px]"><?php echo e($item['member']); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Title -->
                            <h3 class="text-base sm:text-lg font-bold text-white mb-2 line-clamp-2 leading-snug">
                                <?php echo e($item['title']); ?>

                            </h3>

                            <!-- Divider -->
                            <div class="w-8 h-0.5 <?php echo e($item['type'] === 'achievement' ? 'bg-yellow-400/60' : 'bg-red-400/60'); ?> rounded-full mb-3"></div>

                            <!-- Excerpt -->
                            <p class="text-white/60 text-xs sm:text-sm mb-5 line-clamp-3 leading-relaxed">
                                <?php echo e($item['excerpt']); ?>

                            </p>

                            <!-- Read more -->
                            <?php if($item['type'] === 'achievement'): ?>
                            <a href="<?php echo e(route('achievement.detail', $item['id'])); ?>"
                                    class="inline-flex items-center gap-2 text-yellow-400 hover:text-yellow-300
                                           font-semibold text-sm transition-colors duration-200 group/btn">
                                <?php echo e(__('gallery.read_more')); ?>

                                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform duration-200"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <?php else: ?>
                            <a href="<?php echo e(route('news.detail', $item['id'])); ?>"
                                    class="inline-flex items-center gap-2 text-red-400 hover:text-red-300
                                           font-semibold text-sm transition-colors duration-200 group/btn">
                                <?php echo e(__('gallery.read_more')); ?>

                                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform duration-200"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        </div>

    </div>
</div>
<?php /**PATH C:\laragon\www\Project-KP-Archery\resources\views/components/galeri/news-section.blade.php ENDPATH**/ ?>