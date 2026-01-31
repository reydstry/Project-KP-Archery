@props(['id', 'image', 'title', 'date', 'category', 'categoryColor' => 'blue', 'excerpt', 'alt' => ''])

<div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
    <div class="relative">
        <img src="{{ $image }}" 
             alt="{{ $alt ?: $title }}"
             class="w-full h-56 object-cover">
        <div class="absolute top-3 left-3">
            <span class="px-3 py-1.5 bg-{{ $categoryColor }}-600 text-white text-xs font-semibold rounded-full shadow-lg">
                {{ $category }}
            </span>
        </div>
    </div>

    <div class="p-6">
        <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-500 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>{{ $date }}</span>
        </div>

        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 line-clamp-2">
            {{ $title }}
        </h3>

        <p class="text-gray-600 text-xs sm:text-sm mb-4 line-clamp-3">
            {{ $excerpt }}
        </p>

        <button onclick="viewBerita({{ $id }})"
                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium text-sm">
            Baca Selengkapnya
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
</div>
