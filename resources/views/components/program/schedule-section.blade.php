<!-- Schedule Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-3">Jadwal Latihan</h2>
        </div>

        @php
            $schedules = [
                [
                    'time' => 'PAGI',
                    'color' => 'blue',
                    'bg' => 'bg-gradient-to-r from-blue-500 to-blue-600',
                    'sessions' => [
                        ['name' => 'SESI 1', 'time' => '07:30 - 09:00', 'badge' => 'Weekend Only'],
                        ['name' => 'SESI 2', 'time' => '09:00 - 10:30', 'badge' => null],
                        ['name' => 'SESI 3', 'time' => '10:30 - 12:00', 'badge' => null]
                    ]
                ],
                [
                    'time' => 'SORE',
                    'color' => 'blue',
                    'bg' => 'bg-gradient-to-r from-blue-600 to-blue-700',
                    'sessions' => [
                        ['name' => 'SESI 1', 'time' => '13:30 - 15:00', 'badge' => null],
                        ['name' => 'SESI 2', 'time' => '15:00 - 16:30', 'badge' => null],
                        ['name' => 'SESI 3', 'time' => '16:30 - 18:00', 'badge' => null]
                    ]
                ],
                [
                    'time' => 'MALAM (By Confirm)',
                    'color' => 'blue',
                    'bg' => 'bg-gradient-to-r from-blue-700 to-blue-800',
                    'sessions' => [
                        ['name' => 'SESI 1', 'time' => '19:30 - 21:00', 'badge' => null]
                    ]
                ]
            ];
        @endphp

        <div class="grid md:grid-cols-3 gap-6">
            @foreach($schedules as $schedule)
            <div class="border-2 border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                <div class="{{ $schedule['bg'] }} text-white p-4">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-bold text-lg">{{ $schedule['time'] }}</span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    @foreach($schedule['sessions'] as $session)
                    <div class="border-l-4 {{ 'border-' . $schedule['color'] . '-500' }} pl-4">
                        <div class="flex items-center justify-between">
                            <div class="font-bold text-gray-900">{{ $session['name'] }}</div>
                            @if($session['badge'])
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-semibold">{{ $session['badge'] }}</span>
                            @endif
                        </div>
                        <div class="text-gray-600 text-sm">{{ $session['time'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
