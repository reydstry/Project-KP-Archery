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
<<<<<<< HEAD
                    <img src="{{ asset('asset/img/facilities/' . $facility['image']) }}" alt="{{ $facility['title'] }}" 
=======
                    <img src="{{ asset('asset/img/' . $facility['image']) }}" alt="{{ $facility['title'] }}" 
>>>>>>> pages-program
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
