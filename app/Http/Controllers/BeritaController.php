<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Display the specified berita.
     * 
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Data dummy untuk UI (nanti akan diganti dengan database)
        $beritaData = [
            1 => [
                'id' => 1,
                'title' => 'Tim FocusOneX Raih Juara 1 Kompetisi Regional 2026',
                'date' => '20 Januari 2026',
                'author' => 'Admin FocusOneX',
                'image' => asset('asset/img/latarbelakanglogin.jpeg'),
                'content' => '
                    <h2>Prestasi Membanggakan di Kompetisi Regional</h2>
                    <p>Tim panahan FocusOneX berhasil meraih juara pertama dalam kompetisi regional yang diselenggarakan di Balikpapan pada 20 Januari 2026. Prestasi membanggakan ini merupakan hasil dari latihan intensif dan dedikasi tinggi para atlet.</p>
                    
                    <h3>Kompetisi yang Ketat</h3>
                    <p>Kompetisi regional tahun ini diikuti oleh lebih dari 150 atlet dari berbagai klub panahan di Kalimantan. Tim FocusOneX berhasil unggul dengan poin tertinggi dan konsistensi yang luar biasa sepanjang pertandingan.</p>
                    
                    <h3>Persiapan Matang</h3>
                    <p>Keberhasilan ini tidak lepas dari persiapan matang yang dilakukan selama 3 bulan terakhir. Para atlet menjalani program latihan khusus yang dirancang oleh pelatih profesional kami, dengan fokus pada akurasi, konsistensi, dan mental bertanding.</p>
                    
                    <h3>Ucapan Terima Kasih</h3>
                    <p>FocusOneX mengucapkan terima kasih kepada seluruh atlet, pelatih, dan orang tua yang telah mendukung persiapan tim. Prestasi ini merupakan hasil kerja keras bersama dan menjadi motivasi untuk terus berprestasi di tingkat yang lebih tinggi.</p>
                    
                    <h3>Target Selanjutnya</h3>
                    <p>Dengan pencapaian ini, tim FocusOneX akan mempersiapkan diri untuk mengikuti kompetisi nasional yang akan diselenggarakan pada bulan Maret 2026. Kami optimis dapat memberikan yang terbaik dan membawa nama baik Balikpapan di tingkat nasional.</p>
                ',
                'gallery' => [
                    asset('asset/img/latarbelakanglogin.jpeg'),
                    asset('asset/img/latarbelakanglogin.jpeg'),
                    asset('asset/img/latarbelakanglogin.jpeg'),
                ],
                'tags' => ['Kompetisi', 'Prestasi', 'Regional', 'Juara 1']
            ],
            2 => [
                'id' => 2,
                'title' => 'Atlet FocusOneX Terpilih sebagai Atlet Terbaik 2026',
                'date' => '15 Januari 2026',
                'author' => 'Admin FocusOneX',
                'image' => asset('asset/img/latarbelakanglogin.jpeg'),
                'content' => '
                    <h2>Penghargaan Atlet Terbaik</h2>
                    <p>Salah satu atlet FocusOneX berhasil mendapat penghargaan sebagai atlet terbaik dalam ajang kompetisi nasional tahun ini. Penghargaan ini diberikan berdasarkan konsistensi prestasi dan dedikasi dalam olahraga panahan.</p>
                    
                    <h3>Profil Atlet</h3>
                    <p>Atlet yang meraih penghargaan ini telah bergabung dengan FocusOneX sejak 2 tahun yang lalu. Dengan latihan rutin dan bimbingan pelatih profesional, prestasi demi prestasi berhasil diraih sepanjang tahun 2025-2026.</p>
                    
                    <h3>Perjalanan Karir</h3>
                    <p>Dimulai dari program pemula, atlet ini menunjukkan perkembangan yang sangat pesat. Dalam waktu singkat, berhasil naik ke program advanced dan mulai mengikuti kompetisi-kompetisi tingkat regional hingga nasional.</p>
                    
                    <h3>Kunci Kesuksesan</h3>
                    <p>Menurut atlet tersebut, kunci kesuksesan adalah disiplin, latihan konsisten, dan mental yang kuat. Dukungan dari keluarga, pelatih, dan rekan-rekan di FocusOneX juga menjadi faktor penting dalam meraih prestasi.</p>
                    
                    <h3>Inspirasi untuk Atlet Muda</h3>
                    <p>Prestasi ini diharapkan dapat menjadi inspirasi bagi atlet-atlet muda lainnya untuk terus berlatih dan mengembangkan kemampuan. FocusOneX berkomitmen untuk terus membina atlet-atlet berbakat dan membantu mereka meraih prestasi terbaik.</p>
                ',
                'gallery' => [
                    asset('asset/img/latarbelakanglogin.jpeg'),
                    asset('asset/img/latarbelakanglogin.jpeg'),
                ],
                'tags' => ['Atlet', 'Prestasi', 'Penghargaan', 'Nasional']
            ],
        ];
        
        // Check if berita exists
        if (!isset($beritaData[$id])) {
            abort(404, 'Berita tidak ditemukan');
        }
        
        $berita = $beritaData[$id];
        
        return view('pages.berita-detail', compact('berita'));
    }
}
