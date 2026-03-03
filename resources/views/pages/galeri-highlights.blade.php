@extends('layouts.main')

@section('title', __('gallery.highlights_page_title') . ' - FocusOneX Archery')

@section('content')
{{-- $items and $activeType passed from GalleryPageController@highlights --}}

<div class="relative min-h-screen py-24 sm:py-28 bg-gradient-to-b from-[#1b2659] to-[#16213a]">
    <div class="container mx-auto px-6">
        <div class="mb-8 sm:mb-10">
            <a href="{{ route('galeri') }}" class="inline-flex items-center gap-2 text-white/70 hover:text-white text-sm mb-5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span>{{ __('gallery.back_to_gallery') }}</span>
            </a>

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-3">
                {{ __('gallery.highlights_page_title') }}
            </h1>
            <p class="text-white/70 max-w-2xl">
                {{ __('gallery.highlights_page_subtitle') }}
            </p>
        </div>

        <div class="flex flex-wrap gap-3 mb-8">
            <a href="{{ route('galeri.highlights', ['type' => 'all']) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ $activeType === 'all' ? 'bg-white text-[#1b2659]' : 'bg-white/10 text-white/80 hover:bg-white/20' }}">
                {{ __('gallery.filter_all') }}
            </a>
            <a href="{{ route('galeri.highlights', ['type' => 'news']) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ $activeType === 'news' ? 'bg-sky-500 text-white' : 'bg-white/10 text-white/80 hover:bg-white/20' }}">
                {{ __('gallery.type_news') }}
            </a>
            <a href="{{ route('galeri.highlights', ['type' => 'achievement']) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ $activeType === 'achievement' ? 'bg-yellow-500 text-white' : 'bg-white/10 text-white/80 hover:bg-white/20' }}">
                {{ __('gallery.type_achievement') }}
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 sm:gap-7">
            @forelse($items as $item)
                <article class="liquid-glass relative h-full flex flex-col transition-all duration-300 hover:-translate-y-1" style="box-shadow: 0 8px 32px rgba(0,0,0,0.3);"
                         onmouseenter="this.classList.add('is-hovered')"
                         onmouseleave="this.classList.remove('is-hovered')">
                    <span class="shine"></span>

                    <div class="absolute top-4 right-4 z-20 px-3 py-1 rounded-full text-xs font-bold shadow-lg flex items-center gap-1 {{ $item['type'] === 'news' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white' : 'bg-gradient-to-r from-yellow-500 to-amber-600 text-white' }}">
                        <span>{{ $item['badge_icon'] }}</span>
                        <span>{{ $item['badge'] }}</span>
                    </div>

                    <div class="relative h-52 overflow-hidden rounded-t-[1rem] shrink-0">
                        <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-transparent to-transparent"></div>
                    </div>

                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-center justify-between gap-2 text-white/45 text-xs mb-3">
                            <span>{{ $item['date'] }}</span>
                            @if(!empty($item['member']))
                                <span class="truncate max-w-[130px] text-yellow-300/90">{{ $item['member'] }}</span>
                            @endif
                        </div>

                        <h2 class="text-lg font-bold text-white mb-3 line-clamp-2 leading-snug">{{ $item['title'] }}</h2>
                        <p class="text-white/65 text-sm leading-relaxed line-clamp-3 mb-5 flex-1">{{ $item['excerpt'] }}</p>

                        <a href="{{ $item['url'] }}" class="inline-flex items-center gap-2 text-sm font-semibold {{ $item['type'] === 'news' ? 'text-sky-300 hover:text-sky-200' : 'text-yellow-300 hover:text-yellow-200' }} transition-colors">
                            <span>{{ __('gallery.read_more') }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-white/10 bg-white/5 text-white/70 px-6 py-12 text-center">
                    {{ __('gallery.empty_highlights') }}
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
