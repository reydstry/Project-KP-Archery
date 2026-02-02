@extends('layouts.main')

@section('title', 'Galeri - FocusOneX Archery')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-12 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Galeri Dokumentasi</h1>
            <p class="text-base md:text-lg text-gray-600">Dokumentasi kegiatan latihan, kompetisi, dan event FocusOneX Archery</p>
        </div>

        <!-- Tabs Navigation -->
        <div class="mb-10">
            <div class="flex flex-wrap justify-center gap-2 sm:gap-4 border-b-2 border-gray-200">
                <x-galeri.tab-button tab="latihan" :active="true">Latihan</x-galeri.tab-button>
                <x-galeri.tab-button tab="kompetisi">Kompetisi</x-galeri.tab-button>
                <x-galeri.tab-button tab="event">Event</x-galeri.tab-button>
            </div>
        </div>

        <!-- Tab Content: Latihan -->
        <div id="content-latihan" class="tab-content">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                
                <x-galeri.card 
                    :image="asset('asset/img/latarbelakanglogin.jpeg')"
                    title="Latihan Rutin Mingguan"
                    date="25 Januari 2026"
                    alt="Latihan Rutin" />

                <x-galeri.card 
                    :image="asset('asset/img/latarbelakanglogin.jpeg')"
                    title="Latihan Teknik Dasar"
                    date="20 Januari 2026"
                    alt="Latihan Teknik" />

                <x-galeri.card 
                    :image="asset('asset/img/latarbelakanglogin.jpeg')"
                    title="Latihan Mental & Fokus"
                    date="18 Januari 2026"
                    alt="Latihan Mental" />

            </div>
        </div>

        <!-- Tab Content: Kompetisi -->
        <div id="content-kompetisi" class="tab-content hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                
                <x-galeri.card 
                    :image="asset('asset/img/latarbelakanglogin.jpeg')"
                    title="Kompetisi Regional"
                    date="15 Januari 2026"
                    alt="Kompetisi Regional" />

                <x-galeri.card 
                    :image="asset('asset/img/latarbelakanglogin.jpeg')"
                    title="Turnamen Nasional"
                    date="10 Januari 2026"
                    alt="Turnamen Nasional" />

            </div>
        </div>

        <!-- Tab Content: Event -->
        <div id="content-event" class="tab-content hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                
                <x-galeri.card 
                    :image="asset('asset/img/latarbelakanglogin.jpeg')"
                    title="Workshop Pemula"
                    date="5 Januari 2026"
                    alt="Workshop Pemula" />

            </div>
        </div>

    </div>
</div>

<!-- Berita Section -->
<div class="bg-white py-16">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Berita Header -->
        <div class="mb-12 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Berita & Prestasi</h2>
            <p class="text-base md:text-lg text-gray-600">Update terkini tentang prestasi dan kegiatan FocusOneX Archery</p>
        </div>

        <!-- Berita Content -->
        <div id="content-berita">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <x-galeri.news-card 
                    :id="1"
                    :image="asset('asset/img/latarbelakanglogin.jpeg')"
                    title="Tim FocusOneX Raih Juara 1 Kompetisi Regional 2026"
                    date="20 Januari 2026"
                    category="Kemenangan"
                    categoryColor="blue"
                    excerpt="Tim panahan FocusOneX berhasil meraih juara pertama dalam kompetisi regional yang diselenggarakan di Balikpapan. Prestasi membanggakan ini..."
                    alt="Juara 1 Kompetisi Regional" />

                <x-galeri.news-card 
                    :id="2"
                    :image="asset('asset/img/latarbelakanglogin.jpeg')"
                    title="Atlet FocusOneX Terpilih sebagai Atlet Terbaik 2026"
                    date="15 Januari 2026"
                    category="Prestasi"
                    categoryColor="green"
                    excerpt="Salah satu atlet FocusOneX berhasil mendapat penghargaan sebagai atlet terbaik dalam ajang kompetisi nasional tahun ini..."
                    alt="Atlet Terbaik" />

            </div>
        </div>

    </div>
</div>

<script>
// Tab Switching
function switchTab(tab) {
    // Hide all content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-600', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    });
    
    // Show selected content
    document.getElementById('content-' + tab).classList.remove('hidden');
    
    // Add active state to selected button
    const activeButton = document.getElementById('tab-' + tab);
    activeButton.classList.add('active', 'border-blue-600', 'text-blue-600');
    activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
}

// View Berita Function
function viewBerita(id) {
    alert('Halaman detail berita akan dibuat oleh backend developer.\nBerita ID: ' + id);
}

// Initialize first tab
document.addEventListener('DOMContentLoaded', function() {
    switchTab('latihan');
});
</script>

<style>
.tab-button.active {
    @apply border-blue-600 text-blue-600;
}

.tab-button:not(.active) {
    @apply border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
