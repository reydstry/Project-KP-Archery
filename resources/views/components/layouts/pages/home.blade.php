<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>FocusOnex Archery - Temukan Fokus Sejatimu</title>
        <meta name="description" content="Pusat pelatihan panahan profesional dengan instruktur bersertifikat. Program untuk pemula hingga atlet kompetitif.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Navbar Component -->
        @include('components.navbar')

        <!-- Hero Section -->
        @include('components.hero')

        <!-- Program Section -->
        @include('components.program-section')

        <!-- Partners Section -->
        @include('components.partners-section')

        <!-- CTA Section -->
        @include('components.cta-section')

        <!-- Footer -->
        @include('components.footer')
    </body>
</html>
