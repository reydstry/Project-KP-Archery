@props([
    'title',
    'tone' => 'blue',
])

<div {{ $attributes->class(['bg-white border border-slate-200 rounded-xl p-4']) }}>
    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $title }}</p>
    {{ $slot }}
</div>
