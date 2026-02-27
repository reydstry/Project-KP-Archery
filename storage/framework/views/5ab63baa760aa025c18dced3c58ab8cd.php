<nav id="navbar"
class="fixed top-4 left-6 right-6 z-50
bg-white/35 backdrop-blur-[2px] border border-white/30 rounded-full
shadow-md transition-all duration-300">
    <div class="w-full">
        <div class="flex items-center justify-between h-14 relative px-2 md:px-4 lg:px-4">

            <!-- Logo Desktop -->
            <a href="/" class="hidden md:flex items-center rounded-full px-2 py-1">
                <img src="<?php echo e(asset('asset/img/logofocus.png')); ?>" alt="FocusOnex Archery" 
                class="h-7 w-auto ">
            </a>

            <!-- Logo Mobile - Centered -->
            <a href="/" class="flex md:hidden items-center absolute left-1/2 transform -translate-x-1/2
            ">
                <img src="<?php echo e(asset('asset/img/logofocus.png')); ?>" alt="FocusOnex Archery" class="h-7 w-auto">
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="<?php echo e(route('beranda')); ?>" 
                class="nav-link <?php echo e(request()->routeIs('beranda') ? 'active' : ''); ?>"><?php echo e(__('nav.home')); ?></a>
                <a href="<?php echo e(route('tentang-kami')); ?>"
                class="nav-link <?php echo e(request()->routeIs('tentang-kami') ? 'active' : ''); ?>"><?php echo e(__('nav.about')); ?></a>
                <a href="<?php echo e(route('program')); ?>" 
                class="nav-link <?php echo e(request()->routeIs('program') ? 'active' : ''); ?>"><?php echo e(__('nav.program')); ?></a>
                <a href="<?php echo e(route('galeri')); ?>" class="nav-link <?php echo e(request()->routeIs('galeri') ? 'active' : ''); ?>"><?php echo e(__('nav.gallery')); ?></a>
                <a href="<?php echo e(route('kontak')); ?>" class="nav-link <?php echo e(request()->routeIs('kontak') ? 'active' : ''); ?>"><?php echo e(__('nav.contact')); ?></a>
            </div>

            <!-- Right Desktop -->
            <div class="hidden md:flex items-center gap-3">

                <!-- Language Dropdown -->
                <div class="relative">  
                    <button id="lang-toggle"
                        class="flex items-center gap-2 px-3 py-1.5
                              bg-[#1a307b]/70 backdrop-blur-sm border border-white/20
                           text-white font-semibold text-sm rounded-full
                           hover:bg-[#1a307b]/90 hover:scale-105 transition-all duration-300">
                        <span><?php echo e(strtoupper(app()->getLocale())); ?></span>
                        <svg id="lang-chevron" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div id="lang-menu"
                        class="absolute right-0 mt-2 p-1 overflow-hidden shadow-xl
                            bg-[#1a307b]/90 backdrop-blur-[2px] border border-white/20 rounded-2xl
                               opacity-0 scale-95 pointer-events-none
                               transition-all duration-200 ease-out origin-top-right">
                        <a href="<?php echo e(route('language.switch', 'id')); ?>" 
                           class="flex items-center justify-center px-4 py-2.5 text-sm text-white hover:bg-white/30 rounded-2xl transition-colors
                                  <?php echo e(app()->getLocale() == 'id' ? 'bg-white/30 font-bold' : 'font-medium'); ?>">
                            ID
                        </a>
                        <a href="<?php echo e(route('language.switch', 'en')); ?>" 
                           class="flex items-center justify-center px-4 py-2.5 text-sm text-white hover:bg-white/30 rounded-2xl transition-colors
                                  <?php echo e(app()->getLocale() == 'en' ? 'bg-white/30 font-bold' : 'font-medium'); ?>">
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
                    <?php echo e(__('nav.login')); ?>

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
        <img src="<?php echo e(asset('asset/img/logofocus.png')); ?>" alt="FocusOnex Archery" class="h-7 w-auto">
        <button id="close-menu" class="text-gray-700 hover:text-gray-900">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <nav class="flex flex-col gap-6 text-lg font-medium">
        <a href="<?php echo e(route('beranda')); ?>" class="text-gray-700 hover:text-[#1a307b] transition-colors"><?php echo e(__('nav.home')); ?></a>
        <a href="<?php echo e(route('tentang-kami')); ?>" class="text-gray-700 hover:text-[#1a307b] transition-colors"><?php echo e(__('nav.about')); ?></a>
        <a href="<?php echo e(route('program')); ?>" class="text-gray-700 hover:text-[#1a307b] transition-colors"><?php echo e(__('nav.program')); ?></a>
        <a href="<?php echo e(route('galeri')); ?>" class="text-gray-700 hover:text-[#1a307b] transition-colors"><?php echo e(__('nav.gallery')); ?></a>
        <a href="<?php echo e(route('kontak')); ?>" class="text-gray-700 hover:text-[#1a307b] transition-colors"><?php echo e(__('nav.contact')); ?></a>
    </nav>

    <div class="mt-10 flex flex-col gap-4">
        <a href="/login"
            class="w-full text-center py-3 bg-[#1a307b] text-white font-bold rounded-full hover:bg-[#1a307b]/90 transition">
            <?php echo e(__('nav.login')); ?>

        </a>
        <div class="flex justify-center gap-3">
            <a href="<?php echo e(route('language.switch', 'id')); ?>" 
               class="px-5 py-2 rounded-full font-semibold transition <?php echo e(app()->getLocale() == 'id' ? 'bg-[#1a307b] text-white' : 'bg-gray-100 text-gray-700'); ?>">
               🇮🇩 ID
            </a>
            <a href="<?php echo e(route('language.switch', 'en')); ?>" 
               class="px-5 py-2 rounded-full font-semibold transition <?php echo e(app()->getLocale() == 'en' ? 'bg-[#1a307b] text-white' : 'bg-gray-100 text-gray-700'); ?>">
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
</script><?php /**PATH C:\laragon\www\Project-KP-Archery\resources\views/components/global/navbar.blade.php ENDPATH**/ ?>