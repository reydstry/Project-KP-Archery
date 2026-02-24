<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $__env->yieldContent('title', 'FocusOneX Archery'); ?></title>
    
    <!-- Tailwind CSS -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <!-- Additional Styles -->
    <style>
        .nav-link {
            @apply text-gray-700 hover:text-blue-600 transition-colors duration-200 font-medium;
        }
        .nav-link.active {
            @apply text-blue-600;
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="font-sans antialiased bg-white">
    
    <!-- Navbar -->
    <?php echo $__env->make('components.global.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Main Content -->
    <main class="min-h-screen">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    
    <!-- Footer -->
    <?php echo $__env->make('components.global.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
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
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/layouts/main.blade.php ENDPATH**/ ?>