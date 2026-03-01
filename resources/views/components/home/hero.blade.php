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
                class="liquid-btn btn-red flex items-center gap-2 px-6 sm:px-8 py-3 text-white font-bold text-sm sm:text-base md:text-lg text-center"
                style="box-shadow: 0 8px 32px rgba(0,0,0,0.25);"
                onmouseenter="this.classList.add('is-hovered')"
                onmouseleave="this.classList.remove('is-hovered')">
                <span class="shine"></span>
                {{ __('home.register_now') }}
            </a>

            <!-- SECONDARY BUTTON -->
            <a href="{{ route('program') }}"
                class="liquid-btn btn-white flex items-center gap-2 px-6 sm:px-8 py-3 text-white font-bold text-sm sm:text-base md:text-lg text-center"
                style="box-shadow: 0 8px 32px rgba(0,0,0,0.25);"
                onmouseenter="this.classList.add('is-hovered')"
                onmouseleave="this.classList.remove('is-hovered')">
                <span class="shine"></span>
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