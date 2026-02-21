<?php
    $menuGroups = [
        [
            'title' => 'Dashboard',
            'items' => [
                [
                    'label' => 'Dashboard',
                    'route' => 'dashboard',
                    'patterns' => ['dashboard'],
                ],
            ],
        ],
        [
            'title' => 'Manajemen Pengguna',
            'items' => [
                [
                    'label' => 'Member',
                    'route' => 'admin.members',
                    'patterns' => ['admin.members'],
                ],
                [
                    'label' => 'Coach',
                    'route' => 'admin.coaches',
                    'patterns' => ['admin.coaches'],
                ],
            ],
        ],
        [
            'title' => 'Paket',
            'items' => [
                [
                    'label' => 'Master Paket',
                    'route' => 'admin.packages',
                    'patterns' => ['admin.packages'],
                ],
                [
                    'label' => 'Assign Paket',
                    'route' => 'admin.member-packages',
                    'patterns' => ['admin.member-packages'],
                ],
            ],
        ],
        [
            'title' => 'Training',
            'items' => [
                [
                    'label' => 'Training Session',
                    'route' => 'admin.sessions.index',
                    'patterns' => ['admin.sessions.*'],
                ],
                [
                    'label' => 'Slot & Coach Assignment',
                    'route' => 'admin.training.slots',
                    'patterns' => ['admin.training.slots'],
                ],
                [
                    'label' => 'Attendance Management',
                    'route' => 'admin.training.attendance',
                    'patterns' => ['admin.training.attendance'],
                ],
            ],
        ],
        [
            'title' => 'WhatsApp',
            'items' => [
                [
                    'label' => 'Broadcast Event',
                    'route' => 'admin.whatsapp.broadcast.create',
                    'patterns' => ['admin.whatsapp.broadcast.*'],
                ],
                [
                    'label' => 'Log Broadcast',
                    'route' => 'admin.whatsapp.logs.index',
                    'patterns' => ['admin.whatsapp.logs.*'],
                ],
            ],
        ],
        [
            'title' => 'Laporan',
            'items' => [
                [
                    'label' => 'Rekap Bulanan',
                    'route' => 'admin.reports.monthly',
                    'patterns' => ['admin.reports.monthly'],
                ],
            ],
        ],
        [
            'title' => 'Website',
            'items' => [
                [
                    'label' => 'News',
                    'route' => 'admin.news',
                    'patterns' => ['admin.news'],
                ],
                [
                    'label' => 'Achievements',
                    'route' => 'admin.achievements',
                    'patterns' => ['admin.achievements'],
                ],
            ],
        ],
    ];

    $isItemActive = static function (array $item): bool {
        return collect($item['patterns'] ?? [])->contains(fn (string $pattern) => request()->routeIs($pattern));
    };

    $defaultOpen = collect($menuGroups)
        ->mapWithKeys(function (array $group) use ($isItemActive) {
            $groupKey = \Illuminate\Support\Str::slug($group['title']);
            $groupActive = collect($group['items'])->contains(fn (array $item) => $isItemActive($item));

            return [$groupKey => $groupActive];
        })
        ->all();
?>

<nav class="p-3 sm:p-4 space-y-3" x-data="{ openGroups: <?php echo e(\Illuminate\Support\Js::from($defaultOpen)); ?> }">
    <?php $__currentLoopData = $menuGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $groupKey = \Illuminate\Support\Str::slug($group['title']);
            $isSingleItemGroup = count($group['items']) === 1;
            $singleItem = $isSingleItemGroup ? $group['items'][0] : null;
            $groupActive = collect($group['items'])->contains(fn (array $item) => $isItemActive($item));
        ?>
        <section class="space-y-1">
            <?php if($isSingleItemGroup): ?>
                <?php
                    $active = $singleItem ? $isItemActive($singleItem) : false;
                ?>
                <a
                    href="<?php echo e(route($singleItem['route'])); ?>"
                    @click="if(isMobile) sidebarOpen = false"
                    class="flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl text-[13px] lg:text-sm font-semibold transition-all <?php echo e($active ? 'brand-active' : 'text-slate-300 hover:bg-white/10 hover:text-white'); ?>"
                >
                    <span class="truncate"><?php echo e($singleItem['label']); ?></span>
                </a>
            <?php else: ?>
                <button
                    type="button"
                    class="w-full flex items-center justify-between px-3 lg:px-4 py-1.5 text-[11px] uppercase tracking-wide font-semibold <?php echo e($groupActive ? 'text-white' : 'text-slate-300'); ?>"
                    @click="openGroups['<?php echo e($groupKey); ?>'] = !openGroups['<?php echo e($groupKey); ?>']"
                >
                    <span><?php echo e($group['title']); ?></span>
                    <svg class="w-4 h-4 transition-transform" :class="openGroups['<?php echo e($groupKey); ?>'] ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div class="space-y-1" x-show="openGroups['<?php echo e($groupKey); ?>']" x-collapse>
                    <?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $active = $isItemActive($item);
                        ?>
                        <a
                            href="<?php echo e(route($item['route'])); ?>"
                            @click="if(isMobile) sidebarOpen = false"
                            class="flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl text-[13px] lg:text-sm font-semibold transition-all <?php echo e($active ? 'brand-active' : 'text-slate-300 hover:bg-white/10 hover:text-white'); ?>"
                        >
                            <span class="w-1.5 h-1.5 rounded-full <?php echo e($active ? 'bg-white' : 'bg-slate-500'); ?>"></span>
                            <span class="truncate"><?php echo e($item['label']); ?></span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </section>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</nav>
<?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>