<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
use App\Models\User;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parent = User::create([
            'name' => 'Ibu Siti',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password'),
            'role' => UserRoles::MEMBER,
            'phone' => '081234567892',
        ]);

        $memberAdult = User::create([
            'name' => 'Andi Pratama',
            'email' => 'andi@gmail.com',
            'password' => Hash::make('password'),
            'role' => UserRoles::MEMBER,
            'phone' => '081234567893',
        ]);

        Member::create([
            'user_id' => $parent->id,
            'registered_by' => $parent->id,
            'name' => 'Andi (Anak)',
            'phone' => null,
            'is_self' => false, // Ini anak
            'is_active' => true,
        ]);

        Member::create([
            'user_id' => $memberAdult->id,
            'registered_by' => $memberAdult->id,
            'name' => 'Andi Pratama',
            'phone' => '081234567893',
            'is_self' => true, // Daftar sendiri
            'is_active' => true,
        ]);

        $this->command->info('✅ Members berhasil dibuat!');
    }
}
