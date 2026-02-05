@props(['image', 'title', 'date', 'alt' => ''])

<div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 bg-white">
    <img src="{{ $image }}" 
         alt="{{ $alt ?: $title }}"
         class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300">
    
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        <div class="absolute bottom-0 left-0 right-0 p-4">
            <h3 class="text-white font-semibold text-base sm:text-lg mb-1">{{ $title }}</h3>
        </div>
    </div>
</div>
