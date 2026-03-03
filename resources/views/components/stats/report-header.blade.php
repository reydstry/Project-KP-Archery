@props([
    'title',
    'subtitle' => null,
])

<div {{ $attributes->class(['bg-white border border-slate-200 rounded-xl p-4 sm:p-5']) }}>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h3 class="text-base sm:text-lg font-semibold text-slate-800">{{ $title }}</h3>
            @if($subtitle)
                <p class="text-xs sm:text-sm text-slate-500 mt-1">{{ $subtitle }}</p>
            @endif
        </div>

        @isset($actions)
            <div class="flex items-center gap-2">{{ $actions }}</div>
        @endisset
    </div>
</div>
