{{-- ============================================================
     Toko Perlengkapan Panahan — Section
     Provides: bow, arrow, target, aksesoris panahan
     Consistent with the hero dark-navy theme of tentang-kami
     ============================================================ --}}
<section class="relative min-h-screen py-24 sm:py-32 bg-gradient-to-b from-[#16213a] to-[#1b2659] overflow-hidden">

    <!-- Decorative blurs -->
    <!-- <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div> -->

    <div class="container mx-auto px-6 relative z-10">

        {{-- Section Header --}}
        <div class="text-center mb-14 sm:mb-20">
            
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white leading-tight">
                {{ __('about.store_title') }}<br>
            </h2>
            <p class="mt-5 text-base sm:text-lg text-white/60 max-w-2xl mx-auto leading-relaxed">
                {{ __('about.store_desc') }}
            </p>
        </div>

        {{-- Product Cards Grid --}}
        @php
            $products = [
                [
                    'name'  => 'Busur (Bow)',
                    'desc'  => 'Busur recurve & compound untuk semua level — pemula hingga kompetisi.',
                    'icon'  => 'M7 2 M7 2 C15 6 15 18 7 22 M7 2 L7 22 M4 12 L20 12 M20 12 L17 10 M20 12 L17 14',
                    'color' => 'emerald',
                    'bg'    => 'bg-red-500/20',
                    'text'  => 'text-white',
                    'dot'   => 'bg-red-500/20',
                    'items' => ['Recurve', 'Compound', 'Standarbow & Barebow'],
                ],
                [
                    'name'  => 'Anak Panah (Arrow)',
                    'desc'  => 'Anak panah karbon dan aluminium, presisi tinggi untuk latihan dan turnamen.',
                    'icon'  => 'M17 8l4 4m0 0l-4 4m4-4H3',
                    'color' => 'blue',
                    'bg'    => 'bg-red-500/20',
                    'text'  => 'text-white',
                    'dot'   => 'bg-red-500/20',
                    'items' => ['Karbon full-length', 'Aluminium alloy', 'Spine, point & nock'],
                ],
                [
                    'name'  => 'Aksesoris',
                    'desc'  => 'Lengkapi setup Anda — dari arm guard hingga sight dan stabilizer.',
                    'icon'  => 'M9 4 L13 5 L13 18 Q13 20 11 20 Q9 20 9 18 Z M13 7 C16 8 16 17 13 18 M10 4 L10 2 M12 5 L12 3 M9 2 L10 3 L11 2 M11 3 L12 4 L13 3 ',
                    'color' => 'purple',
                    'bg'    => 'bg-red-500/20',
                    'text'  => 'text-white',
                    'dot'   => 'bg-red-500/20',
                    'items' => ['Arm guard & chest guard', 'Sight & clicker', 'Stabilizer & rest'],
                ],
            ];
        @endphp

        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8 max-w-5xl mx-auto">
            @foreach($products as $product)
            <div class="relative group">
                <!-- Card -->
                <div class="liquid-glass relative h-full transition-transform duration-500 hover:scale-105"
                        style="box-shadow: 0 8px 32px rgba(0,0,0,0.25);"
                        onmouseenter="this.classList.add('is-hovered')"
                        onmouseleave="this.classList.remove('is-hovered')">

                    <!-- Shine -->
                    <span class="shine"></span>

                    {{-- Card header --}}
                    <div class="{{ $product['bg'] }} border-b border-white/10 px-6 py-5 flex items-center gap-3">
                        <div class="w-11 h-11 backdrop-blur-sm border border-white/20 rounded-2xl 
                                    flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $product['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $product['icon'] }}"/>
                            </svg>
                        </div>
                        <h3 class="text-sm sm:text-base font-bold text-white leading-tight">{{ $product['name'] }}</h3>
                    </div>

                    {{-- Description --}}
                    <div class="px-6 pt-4 pb-2">
                        <p class="text-white/60 text-sm leading-relaxed">{{ $product['desc'] }}</p>
                    </div>

                    {{-- Item list --}}
                    <ul class="px-6 pb-6 mt-3 space-y-2 flex-1">
                        @foreach($product['items'] as $item)
                        <li class="flex items-center gap-2 text-xs text-white/70">
                            <span class="w-1.5 h-1.5 rounded-full {{ $product['dot'] }} flex-shrink-0"></span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
