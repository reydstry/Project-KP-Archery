<nav class="fixed top-4 left-6 right-6 z-50
bg-white/40 backdrop-blur-md border border-white/30 rounded-full
shadow-md transition-all duration-300">
    <div class="w-full">
        <div class="flex items-center justify-between h-14 relative px-2 md:px-4 lg:px-4">

            <!-- Logo Desktop -->
            <a href="/" class="hidden md:flex items-center">
                <img src="{{ asset('asset/img/logofocus.png') }}" alt="FocusOnex Archery" class="h-7 w-auto">
            </a>

            <!-- Logo Mobile - Centered -->
            <a href="/" class="flex md:hidden items-center absolute left-1/2 transform -translate-x-1/2">
                <img src="{{ asset('asset/img/logofocus.png') }}" alt="FocusOnex Archery" class="h-7 w-auto">
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('beranda') }}" class="nav-link">{{ __('nav.home') }}</a>
                <a href="{{ route('tentang-kami') }}" class="nav-link">{{ __('nav.about') }}</a>
                <a href="{{ route('program') }}" class="nav-link">{{ __('nav.program') }}</a>
                <a href="{{ route('galeri') }}" class="nav-link">{{ __('nav.gallery') }}</a>
                <a href="{{ route('kontak') }}" class="nav-link">{{ __('nav.contact') }}</a>
            </div>

            <!-- Right Desktop -->
            <div class="hidden md:flex items-center gap-3">

                <!-- Language Dropdown -->
                <div class="relative">
                    <button id="lang-toggle"
                        class="flex items-center gap-2 px-3 py-1.5
                               bg-white/20 backdrop-blur-sm border border-white/30
                               rounded-full hover:bg-white/30 transition-all duration-200 
                               text-white font-semibold text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                        </svg>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                        <svg id="lang-chevron" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div id="lang-menu"
                        class="absolute right-0 mt-2 w-20 rounded-xl overflow-hidden shadow-xl
                               border border-white/20 backdrop-blur-md bg-[#1a307b]/90
                               opacity-0 scale-95 pointer-events-none
                               transition-all duration-200 ease-out origin-top-right">
                        <a href="{{ route('language.switch', 'id') }}" 
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-white hover:bg-white/20 transition-colors
                                  {{ app()->getLocale() == 'id' ? 'bg-white/20 font-bold' : 'font-medium' }}">
                            ID
                        </a>
                        <a href="{{ route('language.switch', 'en') }}" 
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-white hover:bg-white/20 transition-colors
                                  {{ app()->getLocale() == 'en' ? 'bg-white/20 font-bold' : 'font-medium' }}">
                            EN
                        </a>
                    </div>
                </div>

                <!-- Login Button -->
                <a href="/login"
                    class="relative flex items-center gap-2 px-5 py-2
                           bg-[#1a307b]/70 backdrop-blur-sm border border-white/20
                           text-white font-semibold text-sm rounded-full
                           hover:bg-[#1a307b]/90 hover:scale-105 transition-all duration-300
                           shadow-lg overflow-hidden group">
                    <!-- Shine -->
                    <span class="absolute inset-0 w-full h-full 
                                bg-gradient-to-r from-transparent via-white/20 to-transparent
                                -translate-x-full group-hover:translate-x-full 
                                transition-transform duration-700 ease-in-out skew-x-12">
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd"/>
                    </svg>
                    {{ __('nav.login') }}
                </a>
            </div>

            <!-- Mobile Button -->
            <button id="mobile-menu-button" class="md:hidden text-white">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

        </div>
    </div>
</nav>

<!-- MOBILE MENU -->
<div id="mobile-menu" class="hidden fixed inset-0 bg-white z-50 p-6">
    <div class="flex justify-between items-center mb-8">
        <img src="{{ asset('asset/img/logofocus.png') }}" alt="FocusOnex Archery" class="h-7 w-auto">
        <button id="close-menu" class="text-gray-700 hover:text-gray-900">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <nav class="flex flex-col gap-6 text-lg font-medium">
        <a href="{{ route('beranda') }}" class="text-gray-700 hover:text-[#1a307b] transition-colors">{{ __('nav.home') }}</a>
        <a href="{{ route('tentang-kami') }}" class="text-gray-700 hover:text-[#1a307b] transition-colors">{{ __('nav.about') }}</a>
        <a href="{{ route('program') }}" class="text-gray-700 hover:text-[#1a307b] transition-colors">{{ __('nav.program') }}</a>
        <a href="{{ route('galeri') }}" class="text-gray-700 hover:text-[#1a307b] transition-colors">{{ __('nav.gallery') }}</a>
        <a href="{{ route('kontak') }}" class="text-gray-700 hover:text-[#1a307b] transition-colors">{{ __('nav.contact') }}</a>
    </nav>

    <div class="mt-10 flex flex-col gap-4">
        <a href="/login"
            class="w-full text-center py-3 bg-[#1a307b] text-white font-bold rounded-full hover:bg-[#1a307b]/90 transition">
            {{ __('nav.login') }}
        </a>
        <div class="flex justify-center gap-3">
            <a href="{{ route('language.switch', 'id') }}" 
               class="px-5 py-2 rounded-full font-semibold transition {{ app()->getLocale() == 'id' ? 'bg-[#1a307b] text-white' : 'bg-gray-100 text-gray-700' }}">
               🇮🇩 ID
            </a>
            <a href="{{ route('language.switch', 'en') }}" 
               class="px-5 py-2 rounded-full font-semibold transition {{ app()->getLocale() == 'en' ? 'bg-[#1a307b] text-white' : 'bg-gray-100 text-gray-700' }}">
               🇬🇧 EN
            </a>
        </div>
    </div>
</div>

<!-- SCRIPT -->
<script>
    (function() {
        var menuBtn = document.getElementById('mobile-menu-button');
        var closeBtn = document.getElementById('close-menu');
        var mobileMenu = document.getElementById('mobile-menu');
        var langToggle = document.getElementById('lang-toggle');
        var langMenu = document.getElementById('lang-menu');
        var langChevron = document.getElementById('lang-chevron');
        var langOpen = false;

        if (menuBtn && !menuBtn.hasAttribute('data-listener')) {
            menuBtn.onclick = function() { mobileMenu.classList.remove('hidden'); };
            menuBtn.setAttribute('data-listener', 'true');
        }
        if (closeBtn && !closeBtn.hasAttribute('data-listener')) {
            closeBtn.onclick = function() { mobileMenu.classList.add('hidden'); };
            closeBtn.setAttribute('data-listener', 'true');
        }

        if (langToggle && langMenu && !langToggle.hasAttribute('data-listener')) {
            langToggle.onclick = function(e) {
                e.stopPropagation();
                langOpen = !langOpen;
                if (langOpen) {
                    langMenu.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                    langMenu.classList.add('opacity-100', 'scale-100');
                    langChevron.style.transform = 'rotate(180deg)';
                } else {
                    langMenu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                    langMenu.classList.remove('opacity-100', 'scale-100');
                    langChevron.style.transform = 'rotate(0deg)';
                }
            };
            langToggle.setAttribute('data-listener', 'true');

            // Tutup dropdown kalau klik di luar
            document.addEventListener('click', function() {
                if (langOpen) {
                    langOpen = false;
                    langMenu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                    langMenu.classList.remove('opacity-100', 'scale-100');
                    langChevron.style.transform = 'rotate(0deg)';
                }
            });
        }
    })();
</script>