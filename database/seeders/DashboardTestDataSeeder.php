<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DashboardTestDataSeeder extends Seeder
{
    /**
     * Seed all dashboard testing data in correct order.
     */
    public function run(): void
    {
        $this->call([
            AdminTestDataSeeder::class,
            CoachTestDataSeeder::class,
            MemberDashboardTestSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('✅ Dashboard test data berhasil disiapkan!');
        $this->command->info('📧 Admin: admin@clubpanahan.com / admin123');
        $this->command->info('📧 Coach: budi.coach@clubpanahan.com / coach123');
        $this->command->info('📧 Member: memberdashboard@test.com / password123');
    }
}
