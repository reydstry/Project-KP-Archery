<!-- Why Choose Us Section -->
<section class="relative py-24 sm:py-32 bg-gradient-to-b from-[#16213a] to-[#1b2659] overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">

        <!-- Section Header -->
        <div class="text-center mb-16 sm:mb-20">
            
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white">
                {{ __('about.why_choose_title') }}
            </h2>
        </div>

        <!-- Cards -->
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8 max-w-5xl mx-auto">
            @php
                $cards = [
                    [
                        'title_key' => 'about.why_professional_title',
                        'desc_key'  => 'about.why_professional_desc',
                        'icon'      => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                    ],
                    [
                        'title_key' => 'about.why_safety_title',
                        'desc_key'  => 'about.why_safety_desc',
                        'icon'      => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                    [
                        'title_key' => 'about.why_community_title',
                        'desc_key'  => 'about.why_community_desc',
                        'icon'      => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                    ],
                ];
            @endphp

            @foreach($cards as $card)
            <div class="relative group">
                <!-- Card -->
                <div class="liquid-glass relative h-full transition-transform duration-500 hover:scale-105"
                        style="box-shadow: 0 8px 32px rgba(0,0,0,0.25);"
                        onmouseenter="this.classList.add('is-hovered')"
                        onmouseleave="this.classList.remove('is-hovered')">

                    <!-- Shine -->
                    <span class="shine"></span>

                    
                    <div class="bg-red-500/20 border-b
                     border-white/10 px-6 py-5 flex items-center gap-3">
                        <!-- Icon -->
                        <div class="w-11 h-11 backdrop-blur-sm border border-white/20 rounded-2xl 
                                    flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                            </svg>
                        </div>

                        <!-- Title -->
                        <h3 class="text-sm sm:text-base font-bold text-white leading-tight">
                            {{ __($card['title_key']) }}
                        </h3>
                    </div>

                    <!-- Description -->
                    <p class="text-white/70 text-sm leading-relaxed p-6">
                        {{ __($card['desc_key']) }}
                    </p>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>