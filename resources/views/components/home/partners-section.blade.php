<section class="py-20 bg-white overflow-hidden">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">
                Di Dukung Oleh
            </h2>
            <p class="text-lg text-gray-600">
                Lebih dari 8 partner telah mempercayai kami untuk program pelatihan panahan mereka
            </p>
        </div>

        <!-- Partners Logo Slider -->
        <div class="relative">
            <div class="flex animate-scroll">
                @php
                    $partners = [
                        'pertamina.png',
                        'JNE.png',
                        'Backwood_Horse_Riding.jpg',
                        'perpani_indonesia.png',
                        'Pesantren_Dhiya.png',
                        'Sekolah_Alam_Balikpapan.png',
                        'TK_AL_Auliya_Balikpapan.png',
                        'YAYASAN_ISTQAMAH.png'
                    ];
                @endphp
                
                <!-- First Set -->
                @foreach($partners as $index => $logo)
                <div class="flex-shrink-0 mx-8 transition-all duration-300">
                    <img src="{{ asset('asset/img/partners/' . $logo) }}" 
                         alt="Partner {{ $index + 1 }}" 
                         class="h-16 md:h-20 object-contain">
                </div>
                @endforeach
                
                <!-- Duplicate Set for Seamless Loop -->
                @foreach($partners as $index => $logo)
                <div class="flex-shrink-0 mx-8 transition-all duration-300">
                    <img src="{{ asset('asset/img/partners/' . $logo) }}" 
                         alt="Partner {{ $index + 1 }}" 
                         class="h-16 md:h-20 object-contain">
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
