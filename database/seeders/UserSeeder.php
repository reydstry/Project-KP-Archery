<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  public function run():void
  {
    User::create([
        'name' => 'Admin Club Panahan',
        'email' => 'admin@panahan.com',
        'password' => Hash::make('password'),
        'role' => UserRoles::ADMIN,
        'phone' => '081234567890',
    ]);

    User::create([
        'name' => 'Coach Budi',
        'email' => 'coach@panahan.com',
        'password' => Hash::make('password'),
        'role' => UserRoles::COACH,
        'phone' => '081234567891',
    ]);

    $this->command->info('✅ Users berhasil dibuat!');
  }
}