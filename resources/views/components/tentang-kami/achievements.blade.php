<!-- Achievements Section -->
<section class="relative py-24 sm:py-32 bg-gradient-to-b from-[#1b2659] to-[#0f172a] overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">

        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                {{ __('about.achievements_title') }}
            </h2>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto px-4">
                {{ __('about.achievements_subtitle') }}
            </p>
        </div>

        @php
            use App\Models\Achievement;
            
            // Fetch member achievements from database, grouped by member (limit 10 for carousel)
            $memberAchievements = Achievement::query()
                ->where('type', 'member')
                ->whereNotNull('member_id')
                ->with('member')
                ->orderBy('date', 'desc')
                ->get()
                ->groupBy('member_id')
                ->map(function($achievements) {
                    $member = $achievements->first()->member;
                    if (!$member) return null;
                    
                    return [
                        'name' => $member->name,
                        'photo' => $achievements->first()->photo_url ?? asset('asset/img/default-avatar.png'),
                        'awards' => $achievements->map(function($achievement) {
                            // Determine medal based on title keywords
                            $medal = '🏆';
                            $title = strtolower($achievement->title);
                            
                            if (str_contains($title, 'juara 1') || str_contains($title, 'gold') || str_contains($title, '1st place') || str_contains($title, 'first place')) {
                                $medal = '🥇';
                            } elseif (str_contains($title, 'juara 2') || str_contains($title, 'silver') || str_contains($title, '2nd place') || str_contains($title, 'second place')) {
                                $medal = '🥈';
                            } elseif (str_contains($title, 'juara 3') || str_contains($title, 'bronze') || str_contains($title, '3rd place') || str_contains($title, 'third place')) {
                                $medal = '🥉';
                            }
                            
                            return [
                                'medal' => $medal,
                                'title' => $achievement->title
                            ];
                        })->toArray()
                    ];
                })
                ->filter() // Remove null values
                ->take(10); // Limit to 10 members (show 3, scroll for more)
        @endphp

        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8 mb-12">
            @foreach($achievements as $achievement)
            <div class="relative group">
                <!-- Glow -->
                <div class="absolute inset-0 bg-red-500/20 rounded-3xl blur-2xl scale-105 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <!-- Card -->
                <div class="relative h-full bg-white/5 backdrop-blur-[2px] border border-white/20 rounded-2xl overflow-hidden
                            shadow-xl shadow-black/30 hover:shadow-2xl hover:shadow-black/60
                            transition-all duration-300 hover:-translate-y-2">

                    <!-- Shine -->
                    <span class="absolute inset-0 w-full h-full 
                                bg-gradient-to-r from-transparent via-white/10 to-transparent
                                -translate-x-full group-hover:translate-x-full 
                                transition-transform duration-700 ease-in-out skew-x-12 pointer-events-none">
                    </span>

                                <!-- Image -->
                                <div class="h-48 overflow-hidden bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center">
                                    @if($achievement['photo'])
                                    <img src="{{ $achievement['photo'] }}" 
                                         alt="{{ $achievement['name'] }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                    <div class="text-center">
                                        <svg class="w-20 h-20 text-white/30 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                        <p class="text-white/50 text-xs">{{ substr($achievement['name'], 0, 1) }}</p>
                                    </div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="p-5">
                                    <div class="text-center">
                                        <h4 class="font-bold text-white text-base">{{ $achievement['name'] }}</h4>
                                        <p class="text-white/50 text-xs mb-4">Atlet FocusOnex</p>
                                    </div>

                        <div class="flex justify-center">
                            <!-- Divider -->
                            <div class="w-25 h-px bg-red-500/70 mb-4"></div>
                        </div>
         
                        <!-- Awards -->
                        <div class="space-y-3">
                            @foreach($achievement['awards'] as $award)
                            <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-xl px-4 py-2.5">
                                <span class="text-xl">{{ $award['medal'] }}</span>
                                <span class="text-white/80 text-xs sm:text-sm leading-snug">{{ $award['title'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

                <!-- Right Arrow -->
                @if($memberAchievements->count() > 3)
                <button @click="scrollAchievements(1)" 
                        class="absolute right-0 top-1/2 -translate-y-1/2 z-20 w-10 h-10 sm:w-12 sm:h-12 bg-yellow-500/80 hover:bg-yellow-500 text-white rounded-full shadow-xl hover:scale-110 transition-all duration-200 flex items-center justify-center translate-x-1/2">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                @endif
            </div>
        </div>
        @endif

    </div>
</section>