<!-- Instructor Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-700 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
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
                        'description' => 'Setiap instruktur telah mengantongi sertifikat kepelatinan panahan',
                        'icon' => '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>'
                    ],
                    [
                        'title' => 'Atlet Aktif Perpani Balikpapan',
                        'description' => 'Tetap berkompetisi dan menghadirkan kemampuan untuk memberikan pengalaman terbaik',
                        'icon' => '<path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>'
                    ],
                    [
                        'title' => 'Juara Berbagai Kompetisi',
                        'description' => 'Meraih prestasi di tingkat daerah hingga nasional: POPDA, PORNAS, PORDPROV, PON, KEJURNAS, dan berbagai turnamen Open',
                        'icon' => '<path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>'
                    ]
                ];
            @endphp

            @foreach($instructorPoints as $point)
            <div class="bg-gradient-to-br from-blue-50 to-slate-50 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow border border-blue-100">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $point['icon'] !!}
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $point['title'] }}</h3>
                        <p class="text-gray-700">{{ $point['description'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
