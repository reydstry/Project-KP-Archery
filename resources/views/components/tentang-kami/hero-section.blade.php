<!-- Hero Section -->
<section class="relative min-h-screen bg-gradient-to-b 
from-[#1b2659] to-[#16213a] flex items-center pt-15 overflow-hidden">


    <div class="container mx-auto px-6 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">

            <!-- LEFT: Text -->
            <div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-6 text-white leading-tight">
                    {{ __('about.hero_main_title') }}
                </h1>

                <div class="space-y-4 text-sm sm:text-base leading-relaxed text-gray-300 text-justify">
                    <p>{{ __('about.hero_paragraph_1') }}</p>
                    <p>{{ __('about.hero_paragraph_2') }}</p>
                    <p>{{ __('about.hero_paragraph_3') }}</p>
                </div>
            </div>

            <!-- RIGHT: Image Card -->
            <div class="relative">
                <!-- Glow behind image -->
                <div class="absolute inset-0 bg-blue-500/20 rounded-3xl blur-2xl scale-105 pointer-events-none"></div>

                <!-- Image -->
                <div class="relative rounded-2xl overflow-hidden border border-white/20 shadow-2xl shadow-black/50">
                    <img src="{{ asset('asset/img/hero-section.png') }}" 
                         alt="Sejak 2017" 
                         class="w-full h-[300px] sm:h-[380px] md:h-[450px] object-cover">

                    <!-- Gradient overlay bottom -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                </div>
            </div>
        </div>
    </div>
</section>