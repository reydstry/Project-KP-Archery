<!-- Achievements Section -->
<section class="relative py-24 sm:py-32 bg-gradient-to-b from-[#273576] to-[#0f172a] overflow-hidden">

    <!-- Background decorative blur -->
    <div class="absolute top-10 right-10 w-72 h-72 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 left-10 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">

        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                <?php echo e(__('about.achievements_title')); ?>

            </h2>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto px-4">
                <?php echo e(__('about.achievements_subtitle')); ?>

            </p>
        </div>

        <?php
            use App\Models\Achievement;
            
            // Fetch member achievements from database, grouped by member (limit 10 for carousel)
            $memberAchievements = Achievement::query()
                ->where('type', 'member')
                ->whereNotNull('member_id')
                ->with('member')
                ->orderBy('date', 'desc')
                ->get()
                ->groupBy('member_id')
                ->map(function($achievements) {
                    $member = $achievements->first()->member;
                    if (!$member) return null;
                    
                    return [
                        'name' => $member->name,
                        'photo' => $achievements->first()->photo_url ?? asset('asset/img/default-avatar.png'),
                        'awards' => $achievements->map(function($achievement) {
                            // Determine medal based on title keywords
                            $medal = '🏆';
                            $title = strtolower($achievement->title);
                            
                            if (str_contains($title, 'juara 1') || str_contains($title, 'gold') || str_contains($title, '1st place') || str_contains($title, 'first place')) {
                                $medal = '🥇';
                            } elseif (str_contains($title, 'juara 2') || str_contains($title, 'silver') || str_contains($title, '2nd place') || str_contains($title, 'second place')) {
                                $medal = '🥈';
                            } elseif (str_contains($title, 'juara 3') || str_contains($title, 'bronze') || str_contains($title, '3rd place') || str_contains($title, 'third place')) {
                                $medal = '🥉';
                            }
                            
                            return [
                                'medal' => $medal,
                                'title' => $achievement->title
                            ];
                        })->toArray()
                    ];
                })
                ->filter() // Remove null values
                ->take(10); // Limit to 10 members (show 3, scroll for more)
        ?>

        <?php if($memberAchievements->isEmpty()): ?>
        <div class="text-center py-12">
            <p class="text-white/60 text-lg">Belum ada prestasi member yang tersedia saat ini.</p>
        </div>
        <?php else: ?>
        <!-- Carousel Container with Alpine.js -->
        <div x-data="{ 
            scrollAchievements(direction) {
                const container = $refs.achievementsContainer;
                const scrollAmount = container.offsetWidth * 0.8;
                container.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
            }
        }">
            <!-- Header with View All Button -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-white mb-1">Prestasi Member Terbaik</h3>
                    <p class="text-gray-400 text-sm">Atlet-atlet berprestasi FocusOneX</p>
                </div>
                <a href="<?php echo e(route('galeri')); ?>" 
                   class="group flex items-center gap-2 px-4 py-2 bg-yellow-500/20 hover:bg-yellow-500/30 border border-yellow-400/30 rounded-xl text-yellow-300 hover:text-yellow-200 font-semibold text-sm transition-all duration-200">
                    <span>Lihat Semua</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <!-- Carousel -->
            <div class="relative">
                <!-- Left Arrow -->
                <?php if($memberAchievements->count() > 3): ?>
                <button @click="scrollAchievements(-1)" 
                        class="absolute left-0 top-1/2 -translate-y-1/2 z-20 w-10 h-10 sm:w-12 sm:h-12 bg-yellow-500/80 hover:bg-yellow-500 text-white rounded-full shadow-xl hover:scale-110 transition-all duration-200 flex items-center justify-center -translate-x-1/2">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <?php endif; ?>

                <!-- Scrollable Container -->
                <div x-ref="achievementsContainer" 
                     class="flex gap-6 sm:gap-8 overflow-x-auto scrollbar-hide snap-x snap-mandatory scroll-smooth pb-4"
                     style="scrollbar-width: none; -ms-overflow-style: none;">
                    <?php $__currentLoopData = $memberAchievements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $achievement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex-shrink-0 w-full sm:w-[calc(50%-16px)] lg:w-[calc(33.333%-22px)] snap-start">
                        <div class="relative group h-full">
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
                                            transition-transform duration-700 ease-in-out skew-x-12 pointer-events-none">
                                </span>

                                <!-- Image -->
                                <div class="h-48 overflow-hidden bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center">
                                    <?php if($achievement['photo']): ?>
                                    <img src="<?php echo e($achievement['photo']); ?>" 
                                         alt="<?php echo e($achievement['name']); ?>" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <?php else: ?>
                                    <div class="text-center">
                                        <svg class="w-20 h-20 text-white/30 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                        <p class="text-white/50 text-xs"><?php echo e(substr($achievement['name'], 0, 1)); ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Content -->
                                <div class="p-5">
                                    <div class="text-center">
                                        <h4 class="font-bold text-white text-base"><?php echo e($achievement['name']); ?></h4>
                                        <p class="text-white/50 text-xs mb-4">Atlet FocusOnex</p>
                                    </div>

                                    <div class="flex justify-center">
                                        <!-- Divider -->
                                        <div class="w-25 h-px bg-red-500/70 mb-4"></div>
                                    </div>
             
                                    <!-- Awards -->
                                    <div class="space-y-3">
                                        <?php $__currentLoopData = $achievement['awards']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $award): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-xl px-4 py-2.5">
                                            <span class="text-xl"><?php echo e($award['medal']); ?></span>
                                            <span class="text-white/80 text-xs sm:text-sm leading-snug"><?php echo e($award['title']); ?></span>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Right Arrow -->
                <?php if($memberAchievements->count() > 3): ?>
                <button @click="scrollAchievements(1)" 
                        class="absolute right-0 top-1/2 -translate-y-1/2 z-20 w-10 h-10 sm:w-12 sm:h-12 bg-yellow-500/80 hover:bg-yellow-500 text-white rounded-full shadow-xl hover:scale-110 transition-all duration-200 flex items-center justify-center translate-x-1/2">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/tentang-kami/achievements.blade.php ENDPATH**/ ?>