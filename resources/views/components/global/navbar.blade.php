<nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md backdrop-blur-sm bg-opacity-95 transition-all duration-300">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-20">

            <!-- Logo -->
            <a href="/" class="flex items-center">
                <img src="{{ asset('asset/img/logofocus.png') }}" alt="FocusOnex Archery" class="h-20 w-auto">
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('beranda') }}" class="nav-link">Beranda</a>
                <a href="{{ route('tentang-kami') }}" class="nav-link">Tentang Kami</a>
                <a href="{{ route('program') }}" class="nav-link">Program</a>
                <a href="{{ route('galeri') }}" class="nav-link">Galeri</a>
                <a href="{{ route('kontak') }}" class="nav-link">Kontak</a>
            </div>

            <!-- Right Desktop -->
            <div class="hidden md:flex items-center gap-4">

                <!-- Language Dropdown -->
                <div class="relative">
                    <button id="lang-toggle"
                        class="flex items-center gap-2 px-3 py-2 bg-gray-100 rounded-md hover:bg-gray-200 transition">
                        <span>ID</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="lang-menu"
                        class="hidden absolute right-0 mt-2 w-24 bg-white shadow-lg rounded-md">
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">ID</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">EN</a>
                    </div>
                </div>

                <!-- Login (NETRAL) -->
                <a href="/login"
                    class="flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd"/>
                    </svg>
                    Login
                </a>
            </div>

            <!-- Mobile Button -->
            <button id="mobile-menu-button" class="md:hidden text-gray-700">
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
        <img src="{{ asset('asset/img/logofocus.png') }}" alt="FocusOnex Archery" class="h-16 w-auto">
        <button id="close-menu" class="text-gray-700 hover:text-gray-900">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <nav class="flex flex-col gap-6 text-lg font-medium">
        <a href="{{ route('beranda') }}" class="text-gray-700 hover:text-gray-900">Beranda</a>
        <a href="{{ route('tentang-kami') }}" class="text-gray-700 hover:text-gray-900">Tentang Kami</a>
        <a href="{{ route('program') }}" class="text-gray-700 hover:text-gray-900">Program</a>
        <a href="{{ route('galeri') }}" class="text-gray-700 hover:text-gray-900">Galeri</a>
        <a href="{{ route('kontak') }}" class="text-gray-700 hover:text-gray-900">Kontak</a>
    </nav>

    <div class="mt-10 flex flex-col gap-4">
        
        <a href="/login"
            class="w-full text-center py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition">
            Login
        </a>

        <div class="flex justify-center gap-3">
            <button class="px-4 py-2 bg-gray-800 text-white rounded-md">ID</button>
            <button class="px-4 py-2 bg-gray-100 rounded-md">EN</button>
        </div>
    </div>
</div>

<!-- SCRIPT -->
<script>
    // Mobile menu: only assign event listeners if not already set
    (function() {
        var menuBtn = document.getElementById('mobile-menu-button');
        var closeBtn = document.getElementById('close-menu');
        var mobileMenu = document.getElementById('mobile-menu');
        if (menuBtn && mobileMenu && !menuBtn.hasAttribute('data-listener')) {
            menuBtn.onclick = function() { mobileMenu.classList.remove('hidden'); };
            menuBtn.setAttribute('data-listener', 'true');
        }
        if (closeBtn && mobileMenu && !closeBtn.hasAttribute('data-listener')) {
            closeBtn.onclick = function() { mobileMenu.classList.add('hidden'); };
            closeBtn.setAttribute('data-listener', 'true');
        }
        // Language dropdown: only assign event listeners if not already set
        var langToggle = document.getElementById('lang-toggle');
        var langMenu = document.getElementById('lang-menu');
        if (langToggle && langMenu && !langToggle.hasAttribute('data-listener')) {
            langToggle.onclick = function() { langMenu.classList.toggle('hidden'); };
            langToggle.setAttribute('data-listener', 'true');
        }
    })();
</script>

<style>
    .nav-link {
        @apply text-gray-700 hover:text-gray-900 font-medium transition;
    }
</style>
