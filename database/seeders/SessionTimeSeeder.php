<?php

namespace Database\Seeders;

use App\Models\SessionTime;
use App\Models\User;
use Illuminate\Database\Seeder;

class SessionTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coach = User::where('email', 'coach@panahan.com')->first();

        if (!$coach) {
            $this->command->error('❌ Coach belum dibuat! Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        SessionTime::insert([
            [
                'day_of_week' => 'Monday',
                'start_time' => '16:00:00',
                'end_time' => '18:00:00',
                'max_capacity' => 10,
                'coach_id' => $coach->id,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day_of_week' => 'Wednesday',
                'start_time' => '16:00:00',
                'end_time' => '18:00:00',
                'max_capacity' => 10,
                'coach_id' => $coach->id,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day_of_week' => 'Friday',
                'start_time' => '16:00:00',
                'end_time' => '18:00:00',
                'max_capacity' => 10,
                'coach_id' => $coach->id,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day_of_week' => 'Saturday',
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'max_capacity' => 15,
                'coach_id' => $coach->id,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('✅ Session Times berhasil dibuat!');
    }
}