<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Package::create([
            'name' => 'Paket Bulanan 8x',
            'description' => 'Latihan 8 kali dalam sebulan',
            'price' => 200000,
            'duration_days' => 30,
            'session_count' => 8,
        ]);

        Package::create([
            'name' => 'Paket Bulanan 12x',
            'description' => 'Latihan 12 kali dalam sebulan',
            'price' => 280000,
            'duration_days' => 30,
            'session_count' => 12,
        ]);

        $this->command->info('✅ Packages berhasil dibuat!');
    }
}
