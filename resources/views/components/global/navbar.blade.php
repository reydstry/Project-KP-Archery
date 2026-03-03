<nav id="navbar"
class="liquid-navbar">
    <div class="w-full">
        <div class="flex items-center justify-between h-14 px-4">

            <!-- Logo Desktop -->
            <a href="/" class="hidden md:flex items-center rounded-full px-2 py-1">
                <img src="{{ asset('asset/img/logowhite.png') }}" alt="FocusOnex Archery" 
                class="h-7 w-auto ">
            </a>

            <!-- Logo Mobile - Centered -->
            <a href="/" class="logo-bubble flex md:hidden items-center absolute left-1/2 transform -translate-x-1/2
            ">
                <img src="{{ asset('asset/img/logowhite.png') }}" alt="FocusOnex Archery" class="h-7 w-auto">
            </a>

            <!-- Desktop Menu -->
            <div id="nav-menu" class="hidden md:flex items-center space-x-8 relative">
                <a href="{{ route('beranda') }}" 
                class="nav-link {{ request()->routeIs('beranda') ? 'active' : '' }}">{{ __('nav.home') }}</a>
                <a href="{{ route('tentang-kami') }}"
                class="nav-link {{ request()->routeIs('tentang-kami') ? 'active' : '' }}">{{ __('nav.about') }}</a>
                <a href="{{ route('program') }}" 
                class="nav-link {{ request()->routeIs('program') ? 'active' : '' }}">{{ __('nav.program') }}</a>
                <a href="{{ route('galeri') }}" class="nav-link {{ request()->routeIs('galeri') ? 'active' : '' }}">{{ __('nav.gallery') }}</a>
                <a href="{{ route('kontak') }}" class="nav-link {{ request()->routeIs('kontak') ? 'active' : '' }}">{{ __('nav.contact') }}</a>

                <div id="nav-bubble"
                class=" absolute h-10 rounded-full pointer-events-none">
                </div>

            </div>

            <!-- Right Desktop -->
            <div class="hidden md:flex items-center gap-3">

            <!-- Language Switch -->
                <div id="lang-switch" class="lang-switch flex items-center gap-1 p-1">
                    <div id="lang-bubble"
                    class="absolute h-10 rounded-full pointer-events-none">
                    </div>
                
                    <a href="{{ route('language.switch', 'id') }}"
                       class="lang-link text-xs {{ app()->getLocale() == 'id' ? 'lang-active' : '' }}">
                        ID
                    </a>
                    <a href="{{ route('language.switch', 'en') }}"
                       class="lang-link text-xs {{ app()->getLocale() == 'en' ? 'lang-active' : '' }}">
                        EN
                    </a>

                    
                </div>

                <!-- Login Button -->
                <a href="/login"
                    class="liquid-btn flex items-center gap-2 px-5 py-2
                        text-white font-semibold text-sm"
                        style="box-shadow: 0 8px 32px rgba(0,0,0,0.25);"
                        onmouseenter="this.classList.add('is-hovered')"
                        onmouseleave="this.classList.remove('is-hovered')">
                    <!-- Shine -->
                    <span class="shine"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd"/>
                    </svg>
                    {{ __('nav.login') }}
                </a>
            </div>

            <!-- Mobile Button -->
            <button id="mobile-menu-button" class="md:hidden text-white pl-2">
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

        if (menuBtn && !menuBtn.hasAttribute('data-listener')) {
            menuBtn.onclick = function() { mobileMenu.classList.remove('hidden'); };
            menuBtn.setAttribute('data-listener', 'true');
        }
        if (closeBtn && !closeBtn.hasAttribute('data-listener')) {
            closeBtn.onclick = function() { mobileMenu.classList.add('hidden'); };
            closeBtn.setAttribute('data-listener', 'true');
        }
    })();
</script>
<script>
    (function () {
        let lastScroll = 0;
        const navbar = document.getElementById("navbar");

        window.addEventListener("scroll", function () {
            let currentScroll = window.pageYOffset;

            // Kalau di paling atas → selalu tampil
            if (currentScroll <= 0) {
                navbar.style.transform = "translateY(0)";
                return;
            }

            // Scroll ke bawah → sembunyikan
            if (currentScroll > lastScroll) {
                navbar.style.transform = "translateY(-150%)";
            } 
            // Scroll ke atas → tampilkan
            else {
                navbar.style.transform = "translateY(0)";
            }

            lastScroll = currentScroll;
        });
    })();
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const bubble = document.getElementById("nav-bubble");
    const menu = document.getElementById("nav-menu");
    const links = document.querySelectorAll(".nav-link");

    let activeLink = document.querySelector(".nav-link.active");

    function moveBubble(el) {
        const rect = el.getBoundingClientRect();
        const parentRect = menu.getBoundingClientRect();

        bubble.style.width = rect.width + "px";
        bubble.style.left = (rect.left - parentRect.left) + "px";
    }

    // Set posisi awal ke active
    if (activeLink) {
        moveBubble(activeLink);
    }

    // Hover behavior (menyatu, bukan bikin baru)
    links.forEach(link => {
        link.addEventListener("mouseenter", () => {
            moveBubble(link);
            bubble.classList.add("is-hovered");
        });
    });

    // Balik ke active kalau keluar area menu
    menu.addEventListener("mouseleave", () => {
        if (activeLink) moveBubble(activeLink);
    });

    // Responsive fix kalau resize
    window.addEventListener("resize", () => {
        if (activeLink) moveBubble(activeLink);
    });

});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const bubble = document.getElementById("lang-bubble");
    const langSwitch = document.getElementById("lang-switch");
    const links = document.querySelectorAll(".lang-link");

    let activeLink = document.querySelector(".lang-link.lang-active");

    function moveBubble(el) {
        const rect = el.getBoundingClientRect();
        const parentRect = langSwitch.getBoundingClientRect();

        bubble.style.width = rect.width + "px";
        bubble.style.left = (rect.left - parentRect.left) + "px";
    }

    // Set posisi awal ke active
    if (activeLink) {
        moveBubble(activeLink);
    }

    // Hover behavior (menyatu, bukan bikin baru)
    links.forEach(link => {
        link.addEventListener("mouseenter", () => {
            moveBubble(link);
            bubble.classList.add("is-hovered");
        });
    });

    // Balik ke active kalau keluar area menu
    langSwitch.addEventListener("mouseleave", () => {
        if (activeLink) moveBubble(activeLink);
    });

    // Responsive fix kalau resize
    window.addEventListener("resize", () => {
        if (activeLink) moveBubble(activeLink);
    });

});
</script>

