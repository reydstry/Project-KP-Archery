<!-- Instructor Section -->
<section class="py-16 bg-yellow-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-400 rounded-full mb-4">
                <svg class="w-8 h-8 text-yellow-900" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-3">Tim Instruktur Profesional</h2>
            <p class="text-gray-600 max-w-3xl mx-auto">
                Kami bangga memiliki tim instruktur yang tidak hanya mengajar, tetapi juga berprestasi di arena kompetisi
            </p>
        </div>

        <div class="max-w-4xl mx-auto space-y-6">
            @php
                $instructorPoints = [
                    [
                        'title' => 'Bersertifikat Resmi',
                        'description' => 'Setiap instruktur telah mengantongi sertifikat kepelatinan panahan'
                    ],
                    [
                        'title' => 'Atlet Aktif Perpani Balikpapan',
                        'description' => 'Tetap berkompetisi dan menghadirkan kemampuan untuk memberikan pengalaman terbaik'
                    ],
                    [
                        'title' => 'Juara Berbagai Kompetisi',
                        'description' => 'Meraih prestasi di tingkat daerah hingga nasional: POPDA, PORNAS, PORDPROV, PON, KEJURNAS, dan berbagai turnamen Open'
                    ]
                ];
            @endphp

            @foreach($instructorPoints as $point)
            <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-yellow-900" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $point['title'] }}</h3>
                        <p class="text-gray-600">{{ $point['description'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
