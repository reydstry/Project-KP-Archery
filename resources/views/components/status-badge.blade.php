@props([
    'label',
    'tone' => 'slate',
])

@php
    $toneMap = [
        'slate' => 'bg-slate-100 text-slate-700 border-slate-200',
        'blue' => 'bg-blue-100 text-blue-700 border-blue-200',
        'green' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'amber' => 'bg-amber-100 text-amber-700 border-amber-200',
        'red' => 'bg-red-100 text-red-700 border-red-200',
    ];
@endphp

<span {{ $attributes->class([
    'inline-flex items-center px-2 py-1 rounded-md border text-[11px] font-semibold whitespace-nowrap',
    $toneMap[$tone] ?? $toneMap['slate'],
]) }}>
    {{ $label }}
</span>
