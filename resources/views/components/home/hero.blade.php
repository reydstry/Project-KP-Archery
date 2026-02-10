<section id="beranda"
    class="relative min-h-screen overflow-hidden flex items-center justify-center">

    <!-- BACKGROUND IMAGE (ZOOM ANIMATION) -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('asset/img/hero.png') }}"
            alt="FocusOnex Archery"
            class="w-full h-full object-cover animate-slow-zoom"
            style="object-position: center 40%;">
    </div>

    <!-- GRADIENT OVERLAY -->
    <div class="absolute inset-0 z-10 bg-gradient-to-b from-black/60 via-black/40 to-black/60"></div>

    <!-- CONTENT -->
    <div class="relative z-20 max-w-4xl px-4 sm:px-6 text-center">

        <h1 class="text-3xl sm:text-4xl md:text-6xl lg:text-7xl font-extrabold text-white leading-tight">
            Temukan
            <span class="block text-blue-500 mt-2 sm:mt-3">
                Fokus Sejatimu
            </span>
        </h1>

        <p class="mt-6 sm:mt-8 text-gray-100 text-base sm:text-lg md:text-xl leading-relaxed max-w-3xl mx-auto px-2">
            Belajar panahan dengan instruktur bersertifikat.<br class="hidden sm:block">
            Dari pemula hingga atlet profesional, kami siap membimbingmu.
        </p>

        <div class="mt-8 sm:mt-10 flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 px-4 max-w-xl mx-auto sm:max-w-none">
            <!-- PRIMARY BUTTON -->
            <a href="{{ route('login') }}"
                class="w-full sm:w-auto px-6 sm:px-8 md:px-10 py-3 sm:py-3.5 md:py-4 bg-gradient-to-r from-blue-500 to-red-500 text-white font-bold rounded-full text-sm sm:text-base md:text-lg
                       hover:from-blue-600 hover:to-red-600 hover:scale-105 transition-all duration-300 shadow-xl text-center">
                Daftar Sekarang
            </a>

            <!-- SECONDARY BUTTON -->
            <a href="{{ route('program') }}"
                class="w-full sm:w-auto px-6 sm:px-8 md:px-10 py-3 sm:py-3.5 md:py-4 bg-white text-gray-800 font-bold rounded-full text-sm sm:text-base md:text-lg
                       hover:bg-gray-100 hover:scale-105 transition-all duration-300 shadow-xl text-center">
                Lihat Program
            </a>
        </div>
    </div>

    <!-- SCROLL -->
    <div class="absolute bottom-12 left-1/2 -translate-x-1/2 z-20 text-white animate-bounce">
        <div class="flex flex-col items-center">
            <span class="text-sm font-medium mb-2">Scroll</span>
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 opacity-75"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </div>

</section>
