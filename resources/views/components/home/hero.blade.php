<section id="beranda"
    class="relative min-h-screen overflow-hidden flex items-end justify-center pb-32 sm:pb-40">

    <!-- BACKGROUND IMAGE (ZOOM ANIMATION) -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('asset/img/hero.png') }}"
            alt="FocusOnex Archery"
            class="w-full h-full object-cover animate-slow-zoom"
            style="object-position: center 40%;">
    </div>

    <!-- GRADIENT OVERLAY -->
    <div class="absolute inset-0 z-10 bg-gradient-to-b from-black/60 via-black/40 to-[#1b2659]"></div>

    <!-- CONTENT -->
    <div class="relative z-20 max-w-4xl px-4 sm:px-6 text-center">

        <p class="text-gray-100 text-base sm:text-lg md:text-xl leading-relaxed max-w-3xl px-2">
            {!! __('home.hero_subtitle') !!}
        </p>

        <div class="mt-8 sm:mt-10 flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 px-4 max-w-xl mx-auto sm:max-w-none">
            <!-- PRIMARY BUTTON -->
            <a href="{{ route('login') }}"
                class="relative w-full sm:w-auto px-6 sm:px-8 md:px-10 py-3
                    bg-[#1a307b]/60 backdrop-blur-md border border-white/20 
                    text-white font-bold rounded-full text-sm sm:text-base md:text-lg
                    hover:bg-[#1a307b]/80 hover:scale-105 transition-all duration-300 shadow-xl text-center
                    overflow-hidden group">
                <!-- SHINE EFFECT -->
                <span class="absolute inset-0 w-full h-full 
                            bg-gradient-to-r from-transparent via-white/30 to-transparent
                            -translate-x-full group-hover:translate-x-full 
                            transition-transform duration-700 ease-in-out skew-x-12">
                </span>
                {{ __('home.register_now') }}
            </a>

            <!-- SECONDARY BUTTON -->
            <a href="{{ route('program') }}"
                class="relative w-full sm:w-auto px-6 sm:px-8 md:px-10 py-3
                    bg-white/20 backdrop-blur-md border border-white/30 
                    text-white font-bold rounded-full text-sm sm:text-base md:text-lg
                    hover:bg-white/30 hover:scale-105 transition-all duration-300 shadow-xl text-center
                    overflow-hidden group">
                <!-- SHINE EFFECT -->
                <span class="absolute inset-0 w-full h-full 
                            bg-gradient-to-r from-transparent via-white/30 to-transparent
                            -translate-x-full group-hover:translate-x-full 
                            transition-transform duration-700 ease-in-out skew-x-12">
                </span>
                {{ __('home.view_program') }}
            </a>
        </div>
    </div>

    <!-- SCROLL -->
    <div class="absolute bottom-12 left-1/2 -translate-x-1/2 z-20 text-white animate-bounce">
        <div class="flex flex-col items-center">
            <span class="text-sm font-medium mb-2">{{ __('home.scroll') }}</span>
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 opacity-75"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </div>

</section>