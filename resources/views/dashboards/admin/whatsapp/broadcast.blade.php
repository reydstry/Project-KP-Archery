@extends('layouts.admin')

@section('title', 'Broadcast Event')
@section('subtitle', 'Kirim broadcast event ke seluruh member aktif melalui queue')

@section('content')
<div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 text-red-700 px-4 py-3 text-sm">
            <p class="font-semibold mb-1">Validasi gagal:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.whatsapp.broadcast.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="title" class="block text-sm font-semibold text-slate-700 mb-1">Judul Event</label>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title') }}"
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a307b]"
                placeholder="Contoh: Latihan Gabungan Mingguan"
                required
            >
        </div>

        <div>
            <label for="event_date" class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Event</label>
            <input
                type="date"
                id="event_date"
                name="event_date"
                value="{{ old('event_date') }}"
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a307b]"
                required
            >
        </div>

        <div>
            <label for="message" class="block text-sm font-semibold text-slate-700 mb-1">Pesan Broadcast</label>
            <textarea
                id="message"
                name="message"
                rows="6"
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a307b]"
                placeholder="Tulis pesan yang akan dikirim ke semua member aktif"
                required
            >{{ old('message') }}</textarea>
        </div>

        <div class="pt-2 flex justify-end">
            <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-xl brand-btn text-sm font-semibold transition">
                Kirim Broadcast
            </button>
        </div>
    </form>
</div>
@endsection
