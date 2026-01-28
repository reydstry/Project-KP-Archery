<?php

namespace Database\Seeders;

use App\Models\TrainingSession;
use Illuminate\Database\Seeder;

class TrainingSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sessions = [
            [
                'name' => 'Sesi 1',
                'start_time' => '07:30',
                'end_time' => '09:00',
            ],
            [
                'name' => 'Sesi 2',
                'start_time' => '09:00',
                'end_time' => '10:30',
            ],
            [
                'name' => 'Sesi 3',
                'start_time' => '10:30',
                'end_time' => '12:00',
            ],
            [
                'name' => 'Sesi 4',
                'start_time' => '13:30',
                'end_time' => '15:00',
            ],
            [
                'name' => 'Sesi 5',
                'start_time' => '15:00',
                'end_time' => '16:30',
            ],
            [
                'name' => 'Sesi 6',
                'start_time' => '16:30',
                'end_time' => '18:00',
            ]
        ];

        foreach ($sessions as $sessionData) {
            TrainingSession::query()->firstOrCreate($sessionData);
        }
    }
}