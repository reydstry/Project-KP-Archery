<?php

namespace Database\Seeders;

use App\Models\SessionTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MemberSeeder::class,
            PackageSeeder::class,
            SessionTimeSeeder::class,
        ]);

        $this->command->info('✅ Seeder berhasil dijalankan!');
        $this->command->info('');
        $this->command->info('📧 Login Credentials:');
        $this->command->info('Admin: admin@panahan.com / password');
        $this->command->info('Coach: coach@panahan.com / password');
        $this->command->info('Parent: siti@gmail.com / password');
        $this->command->info('Member: andi@gmail.com / password');
    }
}