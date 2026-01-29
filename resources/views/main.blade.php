<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FocusOnex Archery - Temukan Fokus Sejatimu</title>
    <meta name="description" content="Pusat pelatihan panahan profesional dengan instruktur bersertifikat. Program untuk pemula hingga atlet kompetitif.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slowZoom {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(1.1);
            }
        }
        
        @keyframes scroll-left {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }
        
        @keyframes float-rotate {
            0%, 100% {
                transform: translateY(0) rotate(0deg) scale(1);
            }
            25% {
                transform: translateY(-10px) rotate(5deg) scale(1.05);
            }
            50% {
                transform: translateY(-5px) rotate(-5deg) scale(1.1);
            }
            75% {
                transform: translateY(-10px) rotate(3deg) scale(1.05);
            }
        }
        
        .animate-fade-in-down {
            animation: fadeInDown 1s ease-out;
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out;
        }
        
        .animate-slow-zoom {
            animation: slowZoom 20s ease-in-out infinite alternate;
        }
        
        .animate-scroll {
            animation: scroll-left 30s linear infinite;
        }
        
        .animate-float-rotate {
            animation: float-rotate 4s ease-in-out infinite;
        }
        
        .animation-delay-200 {
            animation-delay: 0.2s;
            opacity: 0;
            animation-fill-mode: forwards;
        }
        
        .animation-delay-400 {
            animation-delay: 0.4s;
            opacity: 0;
            animation-fill-mode: forwards;
        }
        
        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #2563eb;
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body class="font-sans antialiased">
    @include('components.navbar')
    @include('components.hero')
    @include('components.program-section')
    @include('components.partners-section')
    @include('components.cta-section')
    @include('components.footer')
</body>
</html>
