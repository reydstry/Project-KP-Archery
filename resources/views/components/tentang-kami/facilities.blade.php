<!-- Facilities Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-3">{{ __('about.facilities_main_title') }}</h2>
            <p class="text-gray-600">{{ __('about.facilities_main_subtitle') }}</p>
        </div>

        @php
            $facilities = [
                [
                    'image' => 'section.jpg',
                    'title' => __('about.facility_1_title'),
                    'description' => __('about.facility_1_desc')
                ],
                [
                    'image' => 'section.jpg',
                    'title' => __('about.facility_2_title'),
                    'description' => __('about.facility_2_desc')
                ],
                [
                    'image' => 'section.jpg',
                    'title' => __('about.facility_3_title'),
                    'description' => __('about.facility_3_desc')
                ],
                [
                    'image' => 'section.jpg',
                    'title' => __('about.facility_4_title'),
                    'description' => __('about.facility_4_desc')
                ]
            ];
        @endphp

        <div class="grid md:grid-cols-3 gap-6 mb-10">
            @foreach($facilities as $facility)
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                <div class="h-56 overflow-hidden">
                    <img src="{{ asset('asset/img/facilities/' . $facility['image']) }}" alt="{{ $facility['title'] }}" 
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
                <div class="text-gray-600 text-sm">{{ __('about.stats_total_area') }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-6 text-center border border-gray-200">
                <div class="text-4xl font-bold text-gray-900 mb-1">30+</div>
                <div class="text-gray-600 text-sm">{{ __('about.stats_targets') }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-6 text-center border border-gray-200">
                <div class="text-4xl font-bold text-gray-900 mb-1">100+</div>
                <div class="text-gray-600 text-sm">{{ __('about.stats_equipment') }}</div>
            </div>
        </div>
    </div>
</section>
