<?php

namespace Database\Seeders;

use App\Enums\StatusMember;
use App\Enums\UserRoles;
use App\Models\Coach;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TedSeeder extends Seeder
{
  public function run(): void
  {
    User::firstOrCreate(
        ['email' => 'admin@clubpanahan.com'],
        [
            'name' => 'Admin Club Panahan',
            'role' => UserRoles::ADMIN->value,
            'phone' => '081234567890',
            'password' => Hash::make('admin123'),
        ]
    );

    User::firstOrCreate(
      ['email' => 'coach@clubpanahan.com'],
      [
        'name' => 'Coach Budi',
        'password' => Hash::make('password'),
        'role' => UserRoles::COACH->value,
        'phone' => '081234567891',
      ]
    );

    $defaultSessionTimes = [
        ['name' => 'Sesi 1', 'start_time' => '07:30:00', 'end_time' => '09:00:00'],
        ['name' => 'Sesi 2', 'start_time' => '09:00:00', 'end_time' => '10:30:00'],
        ['name' => 'Sesi 3', 'start_time' => '10:30:00', 'end_time' => '12:00:00'],
        ['name' => 'Sesi 4', 'start_time' => '13:30:00', 'end_time' => '15:00:00'],
        ['name' => 'Sesi 5', 'start_time' => '15:00:00', 'end_time' => '16:30:00'],
        ['name' => 'Sesi 6', 'start_time' => '16:30:00', 'end_time' => '18:00:00'],
    ];

    foreach ($defaultSessionTimes as $sessionTimeData) {
        SessionTime::firstOrCreate(
            ['name' => $sessionTimeData['name']],
            array_merge($sessionTimeData, ['is_active' => true])
        );
    }

    // Create Packages
    $packages = [
        [
            'name' => 'Basic Package',
            'description' => 'Perfect for beginners who want to learn archery fundamentals',
            'price' => 500000,
            'duration_days' => 30,
            'session_count' => 8,
        ],
        [
            'name' => 'Standard Package',
            'description' => 'Ideal for intermediate archers looking to improve their skills',
            'price' => 900000,
            'duration_days' => 60,
            'session_count' => 16,
        ],
        [
            'name' => 'Premium Package',
            'description' => 'Comprehensive training for serious archers with competition goals',
            'price' => 1500000,
            'duration_days' => 90,
            'session_count' => 24,
        ],
        [
            'name' => 'Professional Package',
            'description' => 'Advanced training program for competitive archers',
            'price' => 2500000,
            'duration_days' => 180,
            'session_count' => 48,
        ],
    ];

    foreach ($packages as $packageData) {
        Package::firstOrCreate(
            ['name' => $packageData['name']],
            $packageData
        );
    }
    $this->command->info('✅ Dummy users berhasil dibuat / diupdate!');
  }
}