<!-- Hero Section -->
<section class="bg-gradient-to-br from-gray-50 to-white py-20 mt-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">{{ __('program.hero_page_title') }}</h1>
            <p class="text-gray-600">{{ __('program.hero_page_subtitle') }}</p>
        </div>

        @php
            $programs = [
                [
                    'color' => 'blue',
                    'bg' => 'bg-gradient-to-br from-blue-800 to-blue-700',
                    'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="7" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="11" fill="none" stroke="currentColor" stroke-width="2"/></svg>',
                    'title' => __('program.program_club_title'),
                    'description' => __('program.program_club_desc'),
                    'features' => [
                        __('program.program_club_feature_1'),
                        __('program.program_club_feature_2'),
                        __('program.program_club_feature_3'),
                        __('program.program_club_feature_4')
                    ]
                ],
                [
                    'color' => 'blue',
                    'bg' => 'bg-gradient-to-br from-blue-700 to-blue-600',
                    'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>',
                    'title' => __('program.program_competency_title'),
                    'description' => __('program.program_competency_desc'),
                    'features' => [
                        __('program.program_competency_feature_1'),
                        __('program.program_competency_feature_2'),
                        __('program.program_competency_feature_3'),
                        __('program.program_competency_feature_4')
                    ]
                ],
                [
                    'color' => 'blue',
                    'bg' => 'bg-gradient-to-br from-blue-600 to-blue-500',
                    'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>',
                    'title' => __('program.program_coaching_title'),
                    'description' => __('program.program_coaching_desc'),
                    'features' => [
                        __('program.program_coaching_feature_1'),
                        __('program.program_coaching_feature_2'),
                        __('program.program_coaching_feature_3'),
                        __('program.program_coaching_feature_4')
                    ]
                ],
                [
                    'color' => 'blue',
                    'bg' => 'bg-gradient-to-br from-blue-500 to-blue-400',
                    'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4.5 8.5 12 15l7.5-6.5L12 2zm0 13L4.5 8.5V17L12 22l7.5-5V8.5L12 15z" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>',
                    'title' => __('program.program_outbound_title'),
                    'description' => __('program.program_outbound_desc'),
                    'features' => [
                        __('program.program_outbound_feature_1'),
                        __('program.program_outbound_feature_2'),
                        __('program.program_outbound_feature_3'),
                        __('program.program_outbound_feature_4')
                    ]
                ]
            ];
        @endphp

        <div class="grid md:grid-cols-4 gap-6 mb-8">
            @foreach($programs as $program)
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow">
                <!-- Header dengan warna -->
                <div class="{{ $program['bg'] }} text-white px-6 py-5 flex items-center">
                    <div class="mr-3">
                        {!! $program['icon'] !!}
                    </div>
                    <h3 class="text-base font-bold leading-tight">{{ $program['title'] }}</h3>
                </div>
                
                <!-- Body dengan background putih -->
                <div class="p-6 bg-gray-50">
                    <p class="text-sm text-gray-700 mb-5 leading-relaxed">{{ $program['description'] }}</p>
                    <ul class="space-y-2.5">
                        @foreach($program['features'] as $feature)
                        <li class="flex items-start text-sm text-gray-700">
                            <svg class="w-4 h-4 mr-2.5 flex-shrink-0 mt-0.5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Registration Fee -->
        <div class="bg-blue-600 text-white rounded-xl p-6 text-center shadow-lg">
            <h3 class="text-2xl font-bold mb-2">{{ __('program.registration_fee') }}</h3>
            <p class="text-3xl font-bold">Rp 200.000,-</p>
        </div>
    </div>
</section>
