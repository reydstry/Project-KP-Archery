<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'FocusOneX Archery')</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    <style>
        .nav-link {
            @apply text-gray-700 hover:text-blue-600 transition-colors duration-200 font-medium;
        }
        .nav-link.active {
            @apply text-blue-600;
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white">
    
    <!-- Navbar -->
    @include('components.global.navbar')
    
    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('components.global.footer')
    
    <!-- Scripts -->
    <script>
        // Mobile menu toggle (prevent redeclaration)
        let menuToggle = window.menuToggle || document.getElementById('menu-toggle');
        let mobileMenu = window.mobileMenu || document.getElementById('mobile-menu');
        let menuClose = window.menuClose || document.getElementById('menu-close');
        window.menuToggle = menuToggle;
        window.mobileMenu = mobileMenu;
        window.menuClose = menuClose;
        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', () => {
                mobileMenu.classList.remove('hidden');
            });
        }
        if (menuClose && mobileMenu) {
            menuClose.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        }
        // Active nav link
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
