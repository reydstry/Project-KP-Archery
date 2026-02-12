<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminTestDataSeeder::class,
            CoachTestDataSeeder::class,
            MemberDashboardTestSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('✅ Seeder berhasil dijalankan!');

        $this->command->info('');
        $this->command->info('📧 Login Credentials:');
        $this->command->info('📧 Admin: admin@clubpanahan.com / admin123');
        $this->command->info('📧 Coach: budi.coach@clubpanahan.com / coach123');
        $this->command->info('📧 Member Dashboard Test: memberdashboard@test.com / password123');
    }
}
