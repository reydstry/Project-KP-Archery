<?php

namespace Database\Seeders;

use App\Enums\StatusMember;
use App\Enums\UserRoles;
use App\Models\Coach;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  public function run(): void
  {
    $admin = User::updateOrCreate(
      ['email' => 'admin@panahan.com'],
      [
        'name' => 'Admin Club Panahan',
        'password' => Hash::make('password'),
        'role' => UserRoles::ADMIN,
        'phone' => '081234567890',
      ]
    );

    $coachUser = User::updateOrCreate(
      ['email' => 'coach@panahan.com'],
      [
        'name' => 'Coach Budi',
        'password' => Hash::make('password'),
        'role' => UserRoles::COACH,
        'phone' => '081234567891',
      ]
    );

    Coach::updateOrCreate(
      ['user_id' => $coachUser->id],
      [
        'name' => $coachUser->name,
        'phone' => $coachUser->phone,
      ]
    );

    $memberUser = User::updateOrCreate(
      ['email' => 'member@panahan.com'],
      [
        'name' => 'Member Dummy',
        'password' => Hash::make('password'),
        'role' => UserRoles::MEMBER,
        'phone' => '089685105076',
      ]
    );

    Member::updateOrCreate(
      ['user_id' => $memberUser->id],
      [
        'registered_by' => $admin->id,
        'name' => $memberUser->name,
        'phone' => $memberUser->phone,
        'is_self' => true,
        'status' => StatusMember::STATUS_ACTIVE,
        'is_active' => true,
      ]
    );

    $this->command->info('✅ Dummy users berhasil dibuat / diupdate!');
  }
}