<!-- Instructor Section -->
<section class="relative py-24 sm:py-32 bg-gradient-to-b from-[#16213a] to-[#1b2659] overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">

        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                {{ __('program.instructors_title') }}
            </h2>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto">
                {{ __('program.instructors_subtitle') }}
            </p>
        </div>

        @php
            $instructorPoints = [
                [
                    'title' => 'Bersertifikat Resmi',
                    'description' => 'Setiap instruktur telah mengantongi sertifikat kepelatinan panahan resmi dari lembaga yang diakui.',
                    'color' => 'red',
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                ],
                [
                    'title' => 'Atlet Aktif Perpani Balikpapan',
                    'description' => 'Tetap berkompetisi dan menghadirkan kemampuan terkini untuk memberikan pengalaman pelatihan terbaik.',
                    'color' => 'red',
                    'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
                ],
                [
                    'title' => 'Juara Berbagai Kompetisi',
                    'description' => 'Meraih prestasi di tingkat daerah hingga nasional: POPDA, PORNAS, PORDPROV, PON, KEJURNAS, dan berbagai turnamen Open.',
                    'color' => 'red',
                    'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
                ],
            ];
        @endphp

        <!-- Cards -->
        <div class="flex flex-col gap-6 max-w-3xl mx-auto">
            @foreach($instructorPoints as $index => $point)
            <div class="relative group">
                <div class="liquid-glass wide relative p-6 transition-transform duration-300 hover:scale-105"
                    style="box-shadow: 0 8px 32px rgba(0,0,0,0.25);"
                    onmouseenter="this.classList.add('is-hovered')"
                    onmouseleave="this.classList.remove('is-hovered')">

                    <!-- Shine -->
                    <span class="shine"></span>

                    <div class="flex items-center gap-5">

                        <!-- Left: Number + Icon -->
                        <div class="flex flex-col items-center gap-2 flex-shrink-0">
                            <div class="w-14 h-14 bg-{{ $point['color'] }}-500/20 backdrop-blur-sm border border-white/20 
                                        rounded-2xl flex items-center justify-center
                                        shadow-lg shadow-{{ $point['color'] }}-500/20">
                                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $point['icon'] }}"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Vertical divider -->
                        <div class="w-px h-16 bg-white/10 flex-shrink-0"></div>

                        <!-- Right: Text -->
                        <div class="flex-1">
                            <h3 class="text-base sm:text-lg font-bold text-white mb-1.5 leading-tight">
                                {{ $point['title'] }}
                            </h3>
                            <div class="w-8 h-0.5 bg-{{ $point['color'] }}-400/60 rounded-full mb-2"></div>
                            <p class="text-white/60 text-sm leading-relaxed">
                                {{ $point['description'] }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>