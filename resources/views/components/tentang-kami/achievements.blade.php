<!-- Achievements Section -->
<section class="relative py-24 sm:py-32 bg-gradient-to-b from-[#273576] to-[#0f172a] overflow-hidden">

    <!-- Background decorative blur -->
    <div class="absolute top-10 right-10 w-72 h-72 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 left-10 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">

        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                {{ __('about.achievements_title') }}
            </h2>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto px-4">
                {{ __('about.achievements_subtitle') }}
            </p>
        </div>

        @php
            $achievements = [
                [
                    'name' => 'Rizky Ata',
                    'photo' => 'test.png',
                    'awards' => [
                        ['medal' => '🥇', 'title' => 'Juara 1 Kejuaraan Nasional 2024'],
                        ['medal' => '🥈', 'title' => 'Juara 2 Turnamen Internal']
                    ]
                ],
                [
                    'name' => 'Faisal',
                    'photo' => 'test.png',
                    'awards' => [
                        ['medal' => '🥇', 'title' => 'Juara 1 Kejuaraan Nasional 2024'],
                        ['medal' => '🥉', 'title' => 'Juara 3 Turnamen Internal']
                    ]
                ],
                [
                    'name' => 'Yanto',
                    'photo' => 'test.png',
                    'awards' => [
                        ['medal' => '🥇', 'title' => 'Juara 1 Kejuaraan Nasional 2024'],
                        ['medal' => '🥈', 'title' => 'Juara 2 Turnamen Internal']
                    ]
                ],
            ];
        @endphp

        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8 mb-12">
            @foreach($achievements as $achievement)
            <div class="relative group">
                <!-- Card -->
                <div class="liquid-glass relative h-full transition-transform duration-500 hover:scale-105"
                        style="box-shadow: 0 8px 32px rgba(0,0,0,0.25);"
                        onmouseenter="this.classList.add('is-hovered')"
                        onmouseleave="this.classList.remove('is-hovered')">

                    <!-- Shine -->
                    <span class="shine"></span>

                    <!-- Image -->
                    <div class="h-48 overflow-hidden">
                        <img src="{{ asset('asset/img/achievements/' . $achievement['photo']) }}" 
                             alt="{{ $achievement['name'] }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        
                    </div>

                    <!-- Content -->
                    <div class="p-5">
                        <div class="text-center">
                            <h4 class="font-bold text-white text-base">{{ $achievement['name'] }}</h4>
                            <p class="text-white/50 text-xs mb-4">Atlet FocusOnex</p>
                        </div>

                        <div class="flex justify-center">
                            <!-- Divider -->
                            <div class="w-25 h-px bg-red-500/70 mb-4"></div>
                        </div>
         
                        <!-- Awards -->
                        <div class="space-y-3">
                            @foreach($achievement['awards'] as $award)
                            <div class="liquid-glass relative h-full transition-transform duration-500 px-4 py-2.5"
                                style="box-shadow: 0 8px 32px rgba(0,0,0,0.25);" 

                                <!-- Shine -->
                                <span class="shine"></span>

                                <span class="text-xl">{{ $award['medal'] }}</span>
                                <span class="text-white/80 text-xs sm:text-sm leading-snug">{{ $award['title'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>