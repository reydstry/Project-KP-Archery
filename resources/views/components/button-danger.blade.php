@props([
    'type' => 'button',
])

<button
    type="{{ $type }}"
    {{ $attributes->class([
        'inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-[#d12823] text-white text-sm font-semibold hover:bg-[#b8231f] disabled:opacity-50 disabled:cursor-not-allowed transition',
    ]) }}
>
    {{ $slot }}
</button>
