@extends('layouts.main')

@section('title', 'Tentang Kami - FocusOneX Archery')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-white py-16 mt-20">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-start">
                <div>
                    <h1 class="text-3xl font-bold mb-6 text-gray-900">Tentang FocusOneX Archery</h1>
                    <div class="space-y-4 text-sm leading-relaxed text-gray-700 text-justify">
                        <p>
                            Sejak 2017, FocusOneX Archery telah menjadi pusat pelatihan panahan terdepan di Balikpapan yang menghadirkan program komprehensif untuk semua kalangan—dari pemula yang baru ingin mencoba hingga atlet yang siap bersaing di level kompetisi.
                        </p>
                        <p>
                            Keunggulan kami terletak pada tim instruktur bersertifikat yang tidak hanya memiliki kemampuan teknis tinggi tetapi juga berkompetisi sebagai atlet Panjam Balikpapan. Dengan fasilitas modern dan pengalaman bertahun-tahun, kami siap membimbing Anda menguasai seni panahan dengan cara yang aman, menyenangkan, dan efektif.
                        </p>
                        <p>
                            Kami dipercaya oleh berbagai instansi termasuk Pertamina, SIT Al Auliya Balikpapan, dan berbagai sekolah lainnya di Balikpapan. Lebih dari 8 partner telah mempercayai kami untuk program pelatihan panahan mereka, membuktikan komitmen kami dalam memberikan pengalaman pelatihan yang berkualitas.
                        </p>
                    </div>
                </div>
                <div class="relative">
                    <div class="rounded-2xl overflow-hidden shadow-lg">
                        <img src="{{ asset('asset/img/JNE.png') }}" alt="Sejak 2017" 
                             class="w-full h-[350px] object-cover">
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-6">
                            <h3 class="text-2xl font-bold mb-1 text-white">Sejak 2017</h3>
                            <p class="text-white text-sm">Melayani dengan Dedikasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Mengapa Memilih Kami?</h2>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-600 rounded-md mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Instruktur Profesional</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Tim instruktur bersertifikat yang masih aktif bertanding sebagai atlet Panjam Balikpapan dengan berbagai prestasi di kompetisi daerah hingga nasional.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-600 rounded-md mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Keamanan Terjamin</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Protokol keselamatan ketat dan peralatan berkualitas tinggi untuk memastikan pengalaman belajar yang aman dan nyaman untuk semua peserta.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-600 rounded-md mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Komunitas Solid</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Bergabung dengan komunitas pemanah yang suportif, dari pemula hingga atlet kompetisi, dalam lingkungan yang positif dan memotivasi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Facilities Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Fasilitas Fox Archery Range</h2>
                <p class="text-gray-600">Nikmati fasilitas berkelas dunia yang mendukung perkembangan kemampuan Anda</p>
            </div>

            @php
                $facilities = [
                    [
                        'image' => 'outdoor-range.jpg',
                        'title' => 'Lapangan Outdoor Utama',
                        'description' => 'Lapangan outdoor seluas 2000m² dengan target jarak 10m hingga 70m untuk semua jenis busur'
                    ],
                    [
                        'image' => 'target-range.jpg',
                        'title' => 'Area Latihan Jarak Pendek',
                        'description' => 'Zona khusus untuk pemula dengan target jarak 10m-30m dilengkapi dengan sistem keamanan maksimal'
                    ],
                    [
                        'image' => 'competition.jpg',
                        'title' => 'Arena Kompetisi',
                        'description' => 'Arena kompetisi standar Perpani untuk latihan turnamen dan simulasi kompetisi resmi'
                    ],
                    [
                        'image' => 'competition.jpg',
                        'title' => 'Arena Kompetisi',
                        'description' => 'Arena kompetisi standar Perpani untuk latihan turnamen dan simulasi kompetisi resmi'
                    ]
                ];
            @endphp

            <div class="grid md:grid-cols-3 gap-6 mb-10">
                @foreach($facilities as $facility)
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                    <div class="h-56 overflow-hidden">
                        <img src="{{ asset('asset/img/' . $facility['image']) }}" alt="{{ $facility['title'] }}" 
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $facility['title'] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $facility['description'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            

            <!-- Stats -->
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-lg p-6 text-center border border-gray-200">
                    <div class="text-4xl font-bold text-gray-900 mb-1">2000m²</div>
                    <div class="text-gray-600 text-sm">Total Luas Fasilitas</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-6 text-center border border-gray-200">
                    <div class="text-4xl font-bold text-gray-900 mb-1">30+</div>
                    <div class="text-gray-600 text-sm">Target Panahan</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-6 text-center border border-gray-200">
                    <div class="text-4xl font-bold text-gray-900 mb-1">100+</div>
                    <div class="text-gray-600 text-sm">Peralatan Premium</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Prestasi Member</h2>
                <p class="text-gray-600">Bangga dengan Pencapaian luar biasa dari member yang telah berlatih di Focus One x Archery</p>
            </div>

            @php
                $achievements = [
                    [
                        'name' => 'Rizky Ata',
                        'photo' => 'member-1.jpg',
                        'awards' => [
                            ['medal' => '🥇', 'title' => 'Juara 1 Kejuaraan Nasional 2024'],
                            ['medal' => '🥈', 'title' => 'Juara 2 Turnamen Internal']
                        ]
                    ],
                    [
                        'name' => 'Faisal',
                        'photo' => 'member-2.jpg',
                        'awards' => [
                            ['medal' => '🥇', 'title' => 'Juara 1 Kejuaraan Nasional 2024'],
                            ['medal' => '🥉', 'title' => 'Juara 3 Turnamen Internal']
                        ]
                    ],
                    [
                        'name' => 'Yanto',
                        'photo' => 'member-3.jpg',
                        'awards' => [
                            ['medal' => '🥇', 'title' => 'Juara 1 Kejuaraan Nasional 2024'],
                            ['medal' => '🥈', 'title' => 'Juara 2 Turnamen Internal']
                        ]
                    ]
                ];
            @endphp

            <div class="grid md:grid-cols-3 gap-6">
                @foreach($achievements as $achievement)
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-5">
                        <img src="{{ asset('asset/img/' . $achievement['photo']) }}" alt="{{ $achievement['name'] }}" 
                             class="w-14 h-14 rounded-full object-cover mr-3">
                        <div>
                            <h4 class="font-bold text-gray-900">{{ $achievement['name'] }}</h4>
                        </div>
                    </div>
                    <div class="space-y-2 text-gray-700 text-sm">
                        @foreach($achievement['awards'] as $award)
                        <div class="flex items-start">
                            <span class="text-xl mr-2">{{ $award['medal'] }}</span>
                            <span>{{ $award['title'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endsection
