@props([
    'type' => 'button',
])

<button
    type="{{ $type }}"
    {{ $attributes->class([
        'inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-[#1a307b] text-white text-sm font-semibold hover:bg-[#162a69] disabled:opacity-50 disabled:cursor-not-allowed transition',
    ]) }}
>
    {{ $slot }}
</button>
