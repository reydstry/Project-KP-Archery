{{--
 | status-badge component
 |
 | Usage:
 |   <x-status-badge :status="$member->status" />         — auto-maps member status
 |   <x-status-badge label="Custom" tone="green" />        — explicit label & tone
 |
 | Tones: slate | blue | green | amber | red | navy
 --}}
@props([
    'status' => null,   // 'pending' | 'active' | 'inactive'  — auto-resolves label+tone
    'label'  => null,
    'tone'   => null,
])

@php
    // Auto-resolve label and tone from member status if not explicitly provided
    if ($status !== null) {
        $statusMap = [
            'active'   => ['label' => 'Aktif',         'tone' => 'green'],
            'pending'  => ['label' => 'Menunggu',       'tone' => 'amber'],
            'inactive' => ['label' => 'Nonaktif',       'tone' => 'slate'],
            'open'     => ['label' => 'Buka',           'tone' => 'green'],
            'closed'   => ['label' => 'Tutup',          'tone' => 'slate'],
            'canceled' => ['label' => 'Dibatalkan',     'tone' => 'red'],
        ];
        $resolved = $statusMap[$status] ?? ['label' => ucfirst($status), 'tone' => 'slate'];
        $label    = $label ?? $resolved['label'];
        $tone     = $tone  ?? $resolved['tone'];
    }

    $toneMap = [
        'slate'  => 'bg-slate-100 text-slate-600 border-slate-200 ring-slate-100',
        'blue'   => 'bg-blue-50  text-blue-700  border-blue-200  ring-blue-50',
        'navy'   => 'bg-[#1a307b]/10 text-[#1a307b] border-[#1a307b]/20',
        'green'  => 'bg-emerald-50 text-emerald-700 border-emerald-200 ring-emerald-50',
        'amber'  => 'bg-amber-50  text-amber-700  border-amber-200  ring-amber-50',
        'red'    => 'bg-red-50    text-red-700    border-red-200    ring-red-50',
    ];

    $dotMap = [
        'slate'  => 'bg-slate-400',
        'blue'   => 'bg-blue-500',
        'navy'   => 'bg-[#1a307b]',
        'green'  => 'bg-emerald-500',
        'amber'  => 'bg-amber-500',
        'red'    => 'bg-red-500',
    ];

    $resolvedTone    = $toneMap[$tone ?? 'slate']  ?? $toneMap['slate'];
    $resolvedDotTone = $dotMap[$tone  ?? 'slate']  ?? $dotMap['slate'];
@endphp

<span {{ $attributes->class([
    'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border text-[11px] font-semibold tracking-wide whitespace-nowrap select-none',
    $resolvedTone,
]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $resolvedDotTone }}"></span>
    {{ $label }}
</span>

