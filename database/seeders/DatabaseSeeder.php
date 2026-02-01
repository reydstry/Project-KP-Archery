<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);

        $this->command->info('✅ Seeder berhasil dijalankan!');
        $this->command->info('');
        $this->command->info('📧 Login Credentials:');
        $this->command->info('Admin: admin@panahan.com / password');
        $this->command->info('Coach: coach@panahan.com / password');
        $this->command->info('Member: member@panahan.com / password');
    }
}