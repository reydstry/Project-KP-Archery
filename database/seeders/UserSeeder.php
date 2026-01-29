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
      User::query()->firstOrCreate(
        ['email' => 'admin@club.test'],
        [
          'name' => 'Admin',
          'role' => UserRoles::ADMIN->value,
          'email_verified_at' => now(),
          'password' => Hash::make('password'),
        ]
      );

      if (method_exists(User::class, 'factory')) {
          User::factory()->count(10)->create();
      }
  }
}