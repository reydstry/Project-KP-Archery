@extends('layouts.main')

@section('title', $achievement->title . ' - FocusOneX Archery')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1b2659] via-[#0f172a] to-[#16213a]">
    
    <!-- Hero Section with Image -->
    <div class="relative h-[50vh] md:h-[60vh] overflow-hidden">
        <!-- Background Image -->
        <img src="{{ $achievement->photo_url ?? asset('asset/img/latarbelakanglogin.jpeg') }}" 
             alt="{{ $achievement->title }}"
             class="w-full h-full object-cover">
        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-[#0f172a]/80 to-transparent"></div>
        
        <!-- Content -->
        <div class="absolute bottom-0 left-0 right-0 z-10 pb-12 pt-32">
            <div class="container mx-auto px-6">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 bg-yellow-500/20 backdrop-blur-md border border-yellow-500/30 text-yellow-300 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    @php
                        $badge = '🏆';
                        if (str_contains(strtolower($achievement->title), 'juara 1') || str_contains(strtolower($achievement->title), 'gold') || str_contains(strtolower($achievement->title), '1st place')) {
                            $badge = '🥇';
                        } elseif (str_contains(strtolower($achievement->title), 'juara 2') || str_contains(strtolower($achievement->title), 'silver') || str_contains(strtolower($achievement->title), '2nd place')) {
                            $badge = '🥈';
                        } elseif (str_contains(strtolower($achievement->title), 'juara 3') || str_contains(strtolower($achievement->title), 'bronze') || str_contains(strtolower($achievement->title), '3rd place')) {
                            $badge = '🥉';
                        }
                    @endphp
                    <span class="text-2xl">{{ $badge }}</span>
                    <span>Achievement</span>
                </div>

                <!-- Title -->
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4 leading-tight">
                    {{ $achievement->title }}
                </h1>

                <!-- Meta Info -->
                <div class="flex flex-wrap items-center gap-4 text-gray-300">
                    @if($achievement->type === 'member' && $achievement->member)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                        </svg>
                        <span class="font-semibold">{{ $achievement->member->name }}</span>
                    </div>
                    <span class="text-gray-500">•</span>
                    @else
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                        <span class="font-semibold">FocusOneX Club Achievement</span>
                    </div>
                    <span class="text-gray-500">•</span>
                    @endif
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <time datetime="{{ $achievement->date->format('Y-m-d') }}">
                            {{ $achievement->date->format('d F Y') }}
                        </time>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="container mx-auto px-6 py-12 md:py-16">
        <div class="max-w-4xl mx-auto">
            
            <!-- Content Card -->
            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-8 md:p-12 shadow-2xl">
                <!-- Achievement Type Badge -->
                <div class="mb-6">
                    @if($achievement->type === 'member')
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500/20 to-purple-500/20 border border-blue-500/30 rounded-full text-sm font-semibold text-blue-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                        </svg>
                        Individual Achievement
                    </span>
                    @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-yellow-500/20 to-orange-500/20 border border-yellow-500/30 rounded-full text-sm font-semibold text-yellow-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        Club Achievement
                    </span>
                    @endif
                </div>

                <!-- Description -->
                @if($achievement->description)
                <div class="prose prose-lg prose-invert max-w-none mb-8">
                    <div class="text-white/90 leading-relaxed space-y-4">
                        {!! nl2br(e($achievement->description)) !!}
                    </div>
                </div>
                @endif

                <!-- Achievement Stats (if member achievement) -->
                @if($achievement->type === 'member' && $achievement->member)
                <div class="mt-8 pt-8 border-t border-white/10">
                    <h3 class="text-white font-bold text-xl mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        Athlete Profile
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                            <div class="text-gray-400 text-sm mb-1">Name</div>
                            <div class="text-white font-semibold text-lg">{{ $achievement->member->name }}</div>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                            <div class="text-gray-400 text-sm mb-1">Achievement Date</div>
                            <div class="text-white font-semibold text-lg">{{ $achievement->date->format('d F Y') }}</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Share Section -->
            <div class="mt-8 bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6">
                <h3 class="text-white font-bold text-lg mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    Share this achievement
                </h3>
                <div class="flex flex-wrap gap-3">
                    <button onclick="shareToWhatsApp()" class="flex-1 sm:flex-none px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        WhatsApp
                    </button>
                    <button onclick="shareToFacebook()" class="flex-1 sm:flex-none px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        Facebook
                    </button>
                    <button onclick="copyLink()" class="flex-1 sm:flex-none px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-xl font-semibold transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Copy Link
                    </button>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-8 text-center">
                <a href="{{ route('galeri') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white rounded-2xl font-semibold transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Gallery
                </a>
            </div>

        </div>
    </div>
</div>

<script>
function shareToWhatsApp() {
    const title = "{{ $achievement->title }}";
    const url = window.location.href;
    const text = encodeURIComponent(`${title}\n\n${url}`);
    window.open(`https://wa.me/?text=${text}`, '_blank');
}

function shareToFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Link copied to clipboard!');
    });
}
</script>
@endsection
