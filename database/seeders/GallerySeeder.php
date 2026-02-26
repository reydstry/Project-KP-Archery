<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galleries = [
            // Training
            [
                'title' => 'Latihan Rutin Mingguan',
                'description' => 'Sesi latihan rutin mingguan untuk member',
                'category' => 'training',
                'photo_path' => 'galleries/training-1.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'Latihan Teknik Dasar',
                'description' => 'Pelatihan teknik dasar panahan untuk pemula',
                'category' => 'training',
                'photo_path' => 'galleries/training-2.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'Latihan Target Practice',
                'description' => 'Latihan menembak target jarak jauh',
                'category' => 'training',
                'photo_path' => 'galleries/training-3.jpg',
                'is_active' => true,
            ],
            
            // Competition
            [
                'title' => 'Seleksi Kejurnas Provinsi',
                'description' => 'Seleksi atlet untuk kejuaraan nasional tingkat provinsi',
                'category' => 'competition',
                'photo_path' => 'galleries/competition-1.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'Kompetisi Regional',
                'description' => 'Kompetisi panahan tingkat regional',
                'category' => 'competition',
                'photo_path' => 'galleries/competition-2.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'Penyerahan Penghargaan',
                'description' => 'Penyerahan penghargaan kepada atlet berprestasi',
                'category' => 'competition',
                'photo_path' => 'galleries/competition-3.jpg',
                'is_active' => true,
            ],
            
            // Group Selfie
            [
                'title' => 'Balikpapan Timur Event',
                'description' => 'Foto bersama setelah event di Balikpapan Timur',
                'category' => 'group_selfie',
                'photo_path' => 'galleries/group-1.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'Kejuaraan Nasional',
                'description' => 'Foto bersama tim di kejuaraan nasional',
                'category' => 'group_selfie',
                'photo_path' => 'galleries/group-2.jpg',
                'is_active' => true,
            ],
            [
                'title' => 'Event 17 Agustus',
                'description' => 'Perayaan kemerdekaan bersama klub',
                'category' => 'group_selfie',
                'photo_path' => 'galleries/group-3.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($galleries as $gallery) {
            Gallery::create($gallery);
        }
    }
}
