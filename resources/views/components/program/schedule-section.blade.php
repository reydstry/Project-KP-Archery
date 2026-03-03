<!-- Schedule Section -->
<section class="relative min-h-screen py-24 sm:py-32 
bg-gradient-to-b from-[#1b2659] to-[#0f172a] overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">

        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                {{ __('program.schedule_title') }}
            </h2>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto">
                {{ __('program.schedule_subtitle') }}
            </p>
        </div>

        @php
            $schedules = [
                [
                    'time'    => 'PAGI',
                    'color'   => 'red',
                    'sessions' => [
                        ['name' => 'SESI 1', 'time' => '07:30 - 09:00', 'badge' => 'Weekend Only'],
                        ['name' => 'SESI 2', 'time' => '09:00 - 10:30', 'badge' => null],
                        ['name' => 'SESI 3', 'time' => '10:30 - 12:00', 'badge' => null],
                    ]
                ],
                [
                    'time'    => 'SORE',
                    'color'   => 'red',
                    'sessions' => [
                        ['name' => 'SESI 1', 'time' => '13:30 - 15:00', 'badge' => null],
                        ['name' => 'SESI 2', 'time' => '15:00 - 16:30', 'badge' => null],
                        ['name' => 'SESI 3', 'time' => '16:30 - 18:00', 'badge' => null],
                    ]
                ],
                [
                    'time'    => 'MALAM',
                    'color'   => 'red',
                    'sessions' => [
                        ['name' => 'SESI 1', 'time' => '19:30 - 21:00', 'badge' => 'By Confirm'],
                    ]
                ],
            ];
        @endphp

        <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
            @foreach($schedules as $schedule)
            <div class="relative group">
                <div class="liquid-glass relative h-full transition-transform duration-500 hover:scale-105"
                        style="box-shadow: 0 8px 32px rgba(0,0,0,0.25);"
                        onmouseenter="this.classList.add('is-hovered')"
                        onmouseleave="this.classList.remove('is-hovered')">

                    <!-- Shine -->
                    <span class="shine"></span>

                    <!-- Card Header -->
                    <div class="bg-{{ $schedule['color'] }}-500/20 border-b border-white/10 px-6 py-5 flex items-center justify-center text-center">
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-white leading-tight">{{ $schedule['time'] }}</h3>
                            <p class="text-white/40 text-xs mt-1">{{ count($schedule['sessions']) }} sesi tersedia</p>
                        </div>
                    </div>

                    <!-- Sessions -->
                    <div class="p-5 space-y-3">
                        @foreach($schedule['sessions'] as $index => $session)
                        <div class="flex items-center gap-4 bg-white/5 border border-white/10 rounded-xl px-4 py-3">

                            <!-- Time info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-white font-semibold text-sm">{{ $session['name'] }}</span>
                                    @if($session['badge'])
                                    <span class="text-xs bg-{{ $schedule['color'] }}-500/30
                                                px-2 py-0.5 rounded-full font-semibold text-white">
                                        {{ $session['badge'] }}
                                    </span>
                                    @endif
                                </div>
                                <p class="text-white/50 text-xs mt-0.5">{{ $session['time'] }}</p>
                            </div>

                            <!-- Clock icon -->
                            <svg class="w-4 h-4 text-white/30 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>