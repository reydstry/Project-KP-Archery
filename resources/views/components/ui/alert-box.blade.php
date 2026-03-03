@props([
    'type' => 'info',
    'title' => null,
])

@php
    $classes = [
        'info' => 'bg-blue-50 border-blue-200 text-blue-900',
        'success' => 'bg-emerald-50 border-emerald-200 text-emerald-900',
        'warning' => 'bg-amber-50 border-amber-200 text-amber-900',
        'error' => 'bg-red-50 border-red-200 text-red-900',
    ];
@endphp

<div {{ $attributes->class(['border rounded-xl p-4', $classes[$type] ?? $classes['info']]) }}>
    @if($title)
        <p class="text-sm font-bold">{{ $title }}</p>
    @endif
    <div class="text-sm mt-1">{{ $slot }}</div>
</div>
