{{-- ============================================================
     Toko Perlengkapan Panahan — Section
     Provides: bow, arrow, target, aksesoris panahan
     Consistent with the hero dark-navy theme of tentang-kami
     ============================================================ --}}
<section class="relative py-24 sm:py-32 bg-gradient-to-b from-[#0f172a] via-[#16213a] to-[#1b2659] overflow-hidden">

    <!-- Decorative blurs -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">

        {{-- Section Header --}}
        <div class="text-center mb-14 sm:mb-20">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold tracking-widest uppercase mb-5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Toko Resmi
            </span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white leading-tight">
                Toko Perlengkapan<br>
                <span class="text-emerald-400">Panahan</span>
            </h2>
            <p class="mt-5 text-base sm:text-lg text-white/60 max-w-2xl mx-auto leading-relaxed">
                FocusOneX hadir tidak hanya sebagai club pelatihan — kami juga menyediakan perlengkapan panahan
                berkualitas untuk mendukung perjalanan Anda dari pemula hingga atlet nasional.
            </p>
        </div>

        {{-- Product Cards Grid --}}
        @php
            $products = [
                [
                    'name'  => 'Busur (Bow)',
                    'desc'  => 'Busur recurve & compound untuk semua level — pemula hingga kompetisi.',
                    'icon'  => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8',
                    'color' => 'emerald',
                    'bg'    => 'bg-emerald-500/20',
                    'ring'  => 'ring-emerald-500/30',
                    'text'  => 'text-emerald-300',
                    'dot'   => 'bg-emerald-400',
                    'items' => ['Recurve beginner', 'Compound mid-range', 'Barebow & Traditional'],
                ],
                [
                    'name'  => 'Anak Panah (Arrow)',
                    'desc'  => 'Anak panah karbon dan aluminium, presisi tinggi untuk latihan dan turnamen.',
                    'icon'  => 'M17 8l4 4m0 0l-4 4m4-4H3',
                    'color' => 'blue',
                    'bg'    => 'bg-blue-500/20',
                    'ring'  => 'ring-blue-500/30',
                    'text'  => 'text-blue-300',
                    'dot'   => 'bg-blue-400',
                    'items' => ['Karbon full-length', 'Aluminium alloy', 'Custom spine & nock'],
                ],
                [
                    'name'  => 'Target & Bantalan',
                    'desc'  => 'Target face standar WA & foam boss untuk latihan di rumah atau lapangan.',
                    'icon'  => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    'color' => 'amber',
                    'bg'    => 'bg-amber-500/20',
                    'ring'  => 'ring-amber-500/30',
                    'text'  => 'text-amber-300',
                    'dot'   => 'bg-amber-400',
                    'items' => ['Target face WA 40/60/80/122 cm', 'Foam boss & straw boss', 'Stand & frame'],
                ],
                [
                    'name'  => 'Aksesoris',
                    'desc'  => 'Lengkapi setup Anda — dari arm guard hingga sight dan stabilizer.',
                    'icon'  => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4',
                    'color' => 'purple',
                    'bg'    => 'bg-purple-500/20',
                    'ring'  => 'ring-purple-500/30',
                    'text'  => 'text-purple-300',
                    'dot'   => 'bg-purple-400',
                    'items' => ['Arm guard & chest guard', 'Sight & clicker', 'Stabilizer & rest'],
                ],
            ];
        @endphp

        <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="relative group flex flex-col">
                {{-- Glow hover effect --}}
                <div class="absolute inset-0 rounded-3xl blur-xl scale-110 opacity-0 group-hover:opacity-100 transition-opacity duration-500 {{ $product['bg'] }} pointer-events-none"></div>

                <div class="relative flex flex-col h-full bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl overflow-hidden
                            shadow-lg shadow-black/30 group-hover:shadow-2xl group-hover:border-white/20
                            transition-all duration-300 group-hover:-translate-y-1.5">

                    {{-- Shine sweep on hover --}}
                    <span class="absolute inset-0 w-full h-full
                                bg-gradient-to-r from-transparent via-white/8 to-transparent
                                -translate-x-full group-hover:translate-x-full
                                transition-transform duration-700 skew-x-12 pointer-events-none">
                    </span>

                    {{-- Card header --}}
                    <div class="{{ $product['bg'] }} border-b border-white/10 px-6 py-5 flex items-center gap-3">
                        <div class="w-11 h-11 ring-2 {{ $product['ring'] }} rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0 shadow-inner">
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

        {{-- CTA --}}
        <div class="mt-14 text-center">
            <div class="inline-flex flex-col sm:flex-row items-center gap-4">
                <a href="{{ route('kontak') }}"
                   class="inline-flex items-center gap-2.5 px-8 py-3.5 rounded-xl
                          bg-emerald-500 hover:bg-emerald-400 active:bg-emerald-600
                          text-white font-semibold text-sm shadow-lg shadow-emerald-500/30
                          hover:shadow-emerald-500/50 hover:-translate-y-0.5
                          transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Lihat Toko
                </a>
                <a href="{{ route('kontak') }}"
                   class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl
                          border border-white/20 text-white/70 hover:text-white hover:border-white/40
                          font-semibold text-sm transition-all duration-200 hover:-translate-y-0.5">
                    Hubungi Kami
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <p class="mt-4 text-xs text-white/40">Pembelian, konsultasi, atau custom order — kami siap membantu.</p>
        </div>

    </div>
</section>
