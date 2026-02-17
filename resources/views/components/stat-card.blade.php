@props([
    'title',
    'tone' => 'blue',
])

@php
    $toneMap = [
        'blue' => 'text-[#1a307b] bg-[#1a307b]/10 border-[#1a307b]/20',
        'red' => 'text-[#d12823] bg-[#d12823]/10 border-[#d12823]/20',
        'green' => 'text-emerald-700 bg-emerald-100 border-emerald-200',
        'amber' => 'text-amber-700 bg-amber-100 border-amber-200',
    ];
@endphp

<div {{ $attributes->class(['bg-white border border-slate-200 rounded-xl p-4']) }}>
    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $title }}</p>
    <div class="mt-2 flex items-end justify-between gap-3">
        <span class="px-2 py-1 rounded-md text-xs font-semibold border {{ $toneMap[$tone] ?? $toneMap['blue'] }}">
            {{ ucfirst($tone) }}
        </span>
    </div>
    {{ $slot }}
</div>
