<!-- Berita Section -->
<div class="bg-white py-12 sm:py-14 md:py-16">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Berita Header -->
        <div class="mb-8 sm:mb-10 md:mb-12 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2 sm:mb-3">Berita & Prestasi</h2>
            <p class="text-sm sm:text-base md:text-lg text-gray-600 px-4">Update terkini tentang prestasi dan kegiatan FocusOneX Archery</p>
        </div>

        <!-- Berita Content -->
        <div id="content-berita">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                
                <x-galeri.news-card 
                    :id="1"
                    :image="asset('asset/img/latarbelakanglogin.jpeg')"
                    title="Tim FocusOneX Raih Juara 1 Kompetisi Regional 2026"
                    date="20 Januari 2026"
                    excerpt="Tim panahan FocusOneX berhasil meraih juara pertama dalam kompetisi regional yang diselenggarakan di Balikpapan. Prestasi membanggakan ini..."
                    alt="Juara 1 Kompetisi Regional" />

                <x-galeri.news-card 
                    :id="2"
                    :image="asset('asset/img/latarbelakanglogin.jpeg')"
                    title="Atlet FocusOneX Terpilih sebagai Atlet Terbaik 2026"
                    date="15 Januari 2026"
                    excerpt="Salah satu atlet FocusOneX berhasil mendapat penghargaan sebagai atlet terbaik dalam ajang kompetisi nasional tahun ini..."
                    alt="Atlet Terbaik" />

            </div>
        </div>

    </div>
</div>
