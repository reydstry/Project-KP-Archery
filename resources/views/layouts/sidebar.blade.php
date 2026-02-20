@php
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
@endphp

<nav class="p-3 sm:p-4 space-y-3" x-data="{ openGroups: {{ \Illuminate\Support\Js::from($defaultOpen) }} }">
    @foreach ($menuGroups as $group)
        @php
            $groupKey = \Illuminate\Support\Str::slug($group['title']);
            $isSingleItemGroup = count($group['items']) === 1;
            $singleItem = $isSingleItemGroup ? $group['items'][0] : null;
            $groupActive = collect($group['items'])->contains(fn (array $item) => $isItemActive($item));
        @endphp
        <section class="space-y-1">
            @if ($isSingleItemGroup)
                @php
                    $active = $singleItem ? $isItemActive($singleItem) : false;
                @endphp
                <a
                    href="{{ route($singleItem['route']) }}"
                    @click="if(isMobile) sidebarOpen = false"
                    class="flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl text-[13px] lg:text-sm font-semibold transition-all {{ $active ? 'brand-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >
                    <span class="truncate">{{ $singleItem['label'] }}</span>
                </a>
            @else
                <button
                    type="button"
                    class="w-full flex items-center justify-between px-3 lg:px-4 py-1.5 text-[11px] uppercase tracking-wide font-semibold {{ $groupActive ? 'text-white' : 'text-slate-300' }}"
                    @click="openGroups['{{ $groupKey }}'] = !openGroups['{{ $groupKey }}']"
                >
                    <span>{{ $group['title'] }}</span>
                    <svg class="w-4 h-4 transition-transform" :class="openGroups['{{ $groupKey }}'] ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div class="space-y-1" x-show="openGroups['{{ $groupKey }}']" x-collapse>
                    @foreach ($group['items'] as $item)
                        @php
                            $active = $isItemActive($item);
                        @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            @click="if(isMobile) sidebarOpen = false"
                            class="flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl text-[13px] lg:text-sm font-semibold transition-all {{ $active ? 'brand-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                        >
                            <span class="w-1.5 h-1.5 rounded-full {{ $active ? 'bg-white' : 'bg-slate-500' }}"></span>
                            <span class="truncate">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>
    @endforeach
</nav>
