@props([
    'title' => null,
    'maxWidth' => 'max-w-lg',
])

<div {{ $attributes->class(['fixed inset-0 z-50']) }} x-cloak>
    <div class="absolute inset-0 bg-black/50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="w-full {{ $maxWidth }} bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden">
            @if($title)
                <div class="px-5 py-4 border-b border-slate-200">
                    <h3 class="text-base font-bold text-slate-900">{{ $title }}</h3>
                </div>
            @endif

            <div class="px-5 py-4">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
