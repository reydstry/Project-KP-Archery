@extends('layouts.admin')

@section('title', 'Detail Broadcast')
@section('subtitle', 'Detail hasil pengiriman WhatsApp broadcast')

@php
    $statusClasses = [
        'pending' => 'bg-slate-100 text-slate-700',
        'processing' => 'bg-yellow-100 text-yellow-700',
        'completed' => 'bg-emerald-100 text-emerald-700',
        'failed' => 'bg-red-100 text-red-700',
    ];

    $logStatusClasses = [
        'pending' => 'bg-slate-100 text-slate-700',
        'success' => 'bg-emerald-100 text-emerald-700',
        'failed' => 'bg-red-100 text-red-700',
    ];
@endphp

@section('content')
<div class="space-y-4">
    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-slate-500">Judul</p>
                <p class="font-semibold text-slate-800">{{ $broadcast->title }}</p>
            </div>
            <div>
                <p class="text-slate-500">Tanggal Event</p>
                <p class="font-semibold text-slate-800">{{ $broadcast->event_date?->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-slate-500">Dibuat Oleh</p>
                <p class="font-semibold text-slate-800">{{ $broadcast->createdBy?->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-500">Status</p>
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$broadcast->status] ?? 'bg-slate-100 text-slate-700' }}">
                    {{ ucfirst($broadcast->status) }}
                </span>
            </div>
            <div>
                <p class="text-slate-500">Target</p>
                <p class="font-semibold text-slate-800">{{ $broadcast->total_target }}</p>
            </div>
            <div>
                <p class="text-slate-500">Sukses / Gagal</p>
                <p class="font-semibold text-slate-800">{{ $broadcast->total_success }} / {{ $broadcast->total_failed }}</p>
            </div>
        </div>

        <div class="mt-4">
            <p class="text-slate-500 text-sm mb-1">Pesan</p>
            <div class="rounded-xl bg-slate-50 border border-slate-200 p-3 text-sm text-slate-700 whitespace-pre-line">{{ $broadcast->message }}</div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-slate-600">
                        <th class="px-4 py-3 font-semibold">Member</th>
                        <th class="px-4 py-3 font-semibold">Phone</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Response</th>
                        <th class="px-4 py-3 font-semibold">Sent At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($broadcast->logs as $log)
                        <tr class="text-slate-700 align-top">
                            <td class="px-4 py-3">{{ $log->member?->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $log->phone_number }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $logStatusClasses[$log->status] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 max-w-lg break-words">{{ $log->response ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $log->sent_at?->format('d M Y H:i:s') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada log pengiriman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
