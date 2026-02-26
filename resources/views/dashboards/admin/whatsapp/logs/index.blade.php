@extends('layouts.admin')

@section('title', 'Log Broadcast')
@section('subtitle', 'Riwayat pengiriman broadcast event WhatsApp')

@php
    $statusClasses = [
        'pending' => 'bg-slate-100 text-slate-700',
        'processing' => 'bg-yellow-100 text-yellow-700',
        'completed' => 'bg-emerald-100 text-emerald-700',
        'failed' => 'bg-red-100 text-red-700',
    ];
@endphp

@section('content')
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-[#1a307b]">
                <tr class="text-left text-white">
                    <th class="px-4 py-3 font-semibold text-center">No</th>
                    <th class="px-4 py-3 font-semibold text-center">Tanggal</th>
                    <th class="px-4 py-3 font-semibold text-center">Judul</th>
                    <th class="px-4 py-3 font-semibold text-center">Target</th>
                    <th class="px-4 py-3 font-semibold text-center">Success</th>
                    <th class="px-4 py-3 font-semibold text-center">Failed</th>
                    <th class="px-4 py-3 font-semibold text-center">Status</th>
                    <th class="px-4 py-3 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($broadcasts as $broadcast)
                    <tr class="text-slate-700">
                        <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-center">{{ $broadcast->event_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-center">{{ $broadcast->title }}</td>
                        <td class="px-4 py-3 text-center">{{ $broadcast->total_target }}</td>
                        <td class="px-4 py-3 text-emerald-700 text-center">{{ $broadcast->total_success }}</td>
                        <td class="px-4 py-3 text-red-700 text-center">{{ $broadcast->total_failed }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$broadcast->status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst($broadcast->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('admin.whatsapp.logs.show', $broadcast) }}" class="text-[#1a307b] font-semibold hover:underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-slate-500">
                            Belum ada data broadcast.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
