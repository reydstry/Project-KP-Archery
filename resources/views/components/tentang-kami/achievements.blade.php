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
                    'photo' => 'test.png',
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
                    <img src="{{ asset('asset/img/achievements/' . $achievement['photo']) }}" alt="{{ $achievement['name'] }}" 
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
