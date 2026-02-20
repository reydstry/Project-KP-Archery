@props([
    'headers' => [],
    'responsive' => true,
])

<div {{ $attributes->class(['bg-white border border-slate-200 rounded-xl overflow-hidden']) }}>
    <div class="{{ $responsive ? 'overflow-x-auto' : '' }}">
        <table class="min-w-full text-sm">
            @if(!empty($headers))
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        @foreach($headers as $header)
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-600">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            <tbody class="divide-y divide-slate-100">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
