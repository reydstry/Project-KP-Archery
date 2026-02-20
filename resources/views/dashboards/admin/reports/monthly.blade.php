@extends('layouts.admin')

@section('title', 'Monthly Member Activity Report')
@section('subtitle', 'Rekap aktivitas member bulanan, kehadiran, dan slot paket')

@section('content')
<div class="space-y-4">
    <x-report-header
        title="Laporan Aktivitas Member"
        subtitle="Periode: {{ \Carbon\Carbon::createFromDate($filters['year'], $filters['month'], 1)->translatedFormat('F Y') }}"
    >
        <x-slot:actions class="flex items-center gap-2">
            <a
                href="{{ url('/api/admin/reports/export') . '?' . http_build_query(['mode' => 'monthly', 'month' => $filters['month'], 'year' => $filters['year']]) }}"
                class="inline-flex w-full items-center justify-center px-4 py-2.5 rounded-lg bg-[#1a307b] text-white text-xs sm:text-sm font-semibold hover:bg-[#1a307b]/80 transition"
            > 
                Export Excel
            </a>
        </x-slot:actions>
    </x-report-header>

    <form method="GET" action="{{ route('admin.reports.monthly') }}" class="bg-white border border-slate-200 rounded-xl p-4 grid grid-cols-1 md:grid-cols-6 gap-3">
        <x-form-input
            label="Bulan"
            name="month"
            type="number"
            min="1"
            max="12"
            value="{{ $filters['month'] }}"
        />

        <x-form-input
            label="Tahun"
            name="year"
            type="number"
            min="2020"
            max="2100"
            value="{{ $filters['year'] }}"
        />

        <div x-data="{
            open: false,
            selected: '{{ $filters['package_id'] ?? '' }}',
            packages: [
                @foreach($packages as $package)
                    {id: '{{ $package->id }}', name: '{{ $package->name }}'},
                @endforeach
            ],
            get selectedName() {
                if(this.selected === '') return 'Semua Paket';
                const pkg = this.packages.find(p => p.id == this.selected);
                return pkg ? pkg.name : this.selected;
            }
        }" class="relative w-full">

            <label class="block text-sm font-semibold text-slate-700 mb-2">Paket / Class</label>

            <button
                @click="open = !open"
                type="button"
                class="w-full px-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-800 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-[#1a307b]/30"
            >
                <span x-text="selectedName"></span>
                <svg
                    :class="{ 'rotate-180': open }"
                    class="w-4 h-4 text-slate-400 transition-transform duration-300"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <ul
                x-show="open"
                @click.outside="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="absolute mt-1 left-0 w-full bg-white border border-slate-300 rounded-lg shadow-lg z-10 max-h-60 overflow-auto"
            >
                <li @click="selected=''; open=false" class="px-3 py-2 hover:bg-slate-100 cursor-pointer">Semua Paket</li>
                <template x-for="pkg in packages" :key="pkg.id">
                    <li @click="selected=pkg.id; open=false" x-text="pkg.name" class="px-3 py-2 hover:bg-slate-100 cursor-pointer"></li>
                </template>
            </ul>

            <!-- Hidden input untuk form -->
            <input type="hidden" name="package_id" :value="selected">

        </div>


        <!-- Sort By -->
        <div x-data="{ open: false, selected: '{{ $filters['sort'] ?? 'name' }}' }" class="relative w-full">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Sort By</label>

            <button
                @click="open = !open"
                type="button"
                class="w-full px-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-800 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-[#1a307b]/30"
            >
                <span x-text="selectedLabel(selected)"></span>
                <svg
                    :class="{ 'rotate-180': open }"
                    class="w-4 h-4 text-slate-400 transition-transform duration-300"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <ul
                x-show="open"
                @click.outside="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="absolute mt-1 left-0 w-full bg-white border border-slate-300 rounded-lg shadow-lg z-10 max-h-60 overflow-auto"
            >
                <li @click="selected='name'; open=false" class="px-3 py-2 hover:bg-slate-100 cursor-pointer">Nama</li>
                <li @click="selected='package'; open=false" class="px-3 py-2 hover:bg-slate-100 cursor-pointer">Paket</li>
                <li @click="selected='attended_sessions'; open=false" class="px-3 py-2 hover:bg-slate-100 cursor-pointer">Kehadiran</li>
                <li @click="selected='remaining_slots'; open=false" class="px-3 py-2 hover:bg-slate-100 cursor-pointer">Sisa Slot</li>
            </ul>

            <input type="hidden" name="sort" :value="selected">

            <script>
                function selectedLabel(value) {
                    const labels = {
                        name: 'Nama',
                        package: 'Paket',
                        attended_sessions: 'Kehadiran',
                        remaining_slots: 'Sisa Slot'
                    };
                    return labels[value] || value;
                }
            </script>
        </div>

        <div x-data="{ open: false, selected: '{{ $filters['direction'] ?? 'asc' }}' }" class="relative w-full">
            <label for="direction" class="block text-sm font-semibold text-slate-700 mb-2">Direction</label>

            <!-- Custom select button -->
            <button
                @click="open = !open"
                type="button"
                class="w-full px-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-800 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-[#1a307b]/30"
            >
                <span x-text="selected.toUpperCase()"></span>
                <svg
                    :class="{ 'rotate-180': open }"
                    class="w-4 h-4 text-slate-400 transition-transform duration-300"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown options -->
            <ul
                x-show="open"
                @click.outside="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="absolute mt-1 w-full bg-white border border-slate-300 rounded-lg shadow-lg z-10 max-h-40 overflow-auto"
            >
                <li
                    @click="selected = 'asc'; open = false"
                    class="px-3 py-2 hover:bg-slate-100 cursor-pointer"
                >
                    ASC
                </li>
                <li
                    @click="selected = 'desc'; open = false"
                    class="px-3 py-2 hover:bg-slate-100 cursor-pointer"
                >
                    DESC
                </li>
            </ul>

            <!-- Hidden input to send selected value in form -->
            <input type="hidden" name="direction" :value="selected">
        </div>


        <div class="flex items-center">
            <button type="submit" class="w-full px-4 py-2.5 rounded-lg bg-[#1a307b] text-white text-sm font-semibold hover:opacity-95 transition">
                Terapkan Filter
            </button>
        </div>
    </form>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <x-stat-card title="Total Members">
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $summary['total_members'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Member aktif</p>
        </x-stat-card>

        <x-stat-card title="Total Sessions">
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $summary['total_sessions'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Jumlah session pada periode</p>
        </x-stat-card>

        <x-stat-card title="Members Trained">
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $summary['members_trained'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Member yang sudah latihan</p>
        </x-stat-card>

        <x-stat-card title="Average Attendance">
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ number_format($summary['average_attendance'], 1) }}%</p>
            <p class="text-xs text-slate-500 mt-1">Rata-rata kehadiran member</p>
        </x-stat-card>
    </div>

    <x-table :headers="['No', 'Nama', 'Paket', 'Kehadiran', 'Sisa Slot', 'Slot Terpakai']">
        @forelse($rows as $index => $row)
            <tr class="{{ $row['is_low_attendance'] ? 'bg-white' : '' }} text-center">
                <td class="px-4 py-3 text-slate-700">{{ $index + 1 }}</td>

                <td class="px-4 py-3">
                    <p class="text-slate-800">{{ $row['member_name'] }}</p>           
                </td>

                <td class="px-4 py-3">
                    <p class="text-slate-700">{{ $row['package_name'] }}</p>
                </td>

                <td class="px-4 py-3 text-slate-700 ">{{ $row['attended_sessions'] }}</td>

                <td class="px-4 py-3 text-slate-700">{{ $row['remaining_slots'] }} slot</td>

                <td class="px-4 py-3 text-slate-700">{{ $row['used_slots'] }} slot</td>


            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 py-10 text-center text-slate-500">
                    Data report tidak tersedia untuk filter bulan/tahun yang dipilih.
                </td>
            </tr>
        @endforelse
    </x-table>
</div>
@endsection
