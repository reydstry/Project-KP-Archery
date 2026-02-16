<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coach;
use App\Models\Member;
use App\Models\SessionTime;
use App\Models\TrainingSession;
use App\Models\TrainingSessionSlot;
use App\Models\SessionBooking;
use App\Models\Attendance;
use App\Models\MemberPackage;
use App\Enums\TrainingSessionStatus;
use Carbon\Carbon;

class CoachTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🎯 Seeding coach test data...');

        $firstCoach = Coach::first();
        $sessionTimes = SessionTime::query()->orderBy('start_time')->get();
        
        if (!$firstCoach) {
            $this->command->warn('⚠️  No coaches found. Please run AdminTestDataSeeder first.');
            return;
        }

        if ($sessionTimes->isEmpty()) {
            $this->command->warn('⚠️  No session times found. Please run AdminTestDataSeeder first.');
            return;
        }

        $activeMemberPackages = MemberPackage::query()
            ->where('is_active', true)
            ->where('end_date', '>=', now()->toDateString())
            ->whereRaw('used_sessions < total_sessions')
            ->with(['member.user'])
            ->get()
            ->filter(fn ($memberPackage) => $memberPackage->member && $memberPackage->member->user)
            ->values();

        if ($activeMemberPackages->isEmpty()) {
            $this->command->warn('⚠️  No active member packages found. Please run AdminTestDataSeeder first.');
            return;
        }

        // Data "sedang berjalan": kemarin, hari ini, +3 hari ke depan
        $dates = [
            Carbon::today()->subDay(),
            Carbon::today(),
            Carbon::today()->addDay(),
            Carbon::today()->addDays(2),
            Carbon::today()->addDays(3),
        ];

        $capacities = [12, 15, 10, 14, 16, 12];

        foreach ($dates as $date) {
            $status = $date->isPast()
                ? TrainingSessionStatus::CLOSED
                : TrainingSessionStatus::OPEN;

            $trainingSession = TrainingSession::query()->firstOrCreate([
                'date' => $date->format('Y-m-d'),
                'created_by' => $firstCoach->user_id,
            ], [
                'status' => $status,
            ]);

            if ($trainingSession->status !== $status) {
                $trainingSession->update(['status' => $status]);
            }

            foreach ($sessionTimes as $index => $sessionTime) {
                $slot = TrainingSessionSlot::firstOrCreate(
                    [
                        'training_session_id' => $trainingSession->id,
                        'session_time_id' => $sessionTime->id,
                    ],
                    [
                        'training_session_id' => $trainingSession->id,
                        'session_time_id' => $sessionTime->id,
                        'max_participants' => $capacities[$index] ?? 12,
                    ]
                );

                $slot->coaches()->syncWithoutDetaching([$firstCoach->id]);

                $isPastDate = $date->isPast();
                $targetBookings = $isPastDate ? 6 : 4;

                $selectedPackages = $activeMemberPackages
                    ->shuffle()
                    ->take(min($targetBookings, $slot->max_participants));

                foreach ($selectedPackages as $memberIndex => $memberPackage) {
                    $booking = SessionBooking::firstOrCreate(
                        [
                            'training_session_slot_id' => $slot->id,
                            'member_package_id' => $memberPackage->id,
                        ],
                        [
                            'training_session_slot_id' => $slot->id,
                            'member_package_id' => $memberPackage->id,
                            'booked_by' => $memberPackage->member->user->id,
                            'status' => 'confirmed',
                            'notes' => $isPastDate ? 'Seeded closed-session booking' : 'Seeded upcoming booking',
                        ]
                    );

                    if ($isPastDate) {
                        $isPresent = ($memberIndex % 5) !== 4;

                        Attendance::firstOrCreate(
                            [
                                'session_booking_id' => $booking->id,
                            ],
                            [
                                'session_booking_id' => $booking->id,
                                'status' => $isPresent ? 'present' : 'absent',
                                'validated_by' => $firstCoach->user_id,
                                'validated_at' => $date->copy()->setTime(17, 30),
                                'notes' => $isPresent ? 'Present (seeded)' : 'Absent (seeded)',
                            ]
                        );
                    }
                }
            }
        }

        $this->command->info('✅ Coach test data seeded successfully!');
        $this->command->info('');
        $this->command->info('📊 Coach Test Data:');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('📅 Training Sessions: ' . TrainingSession::count());
        $this->command->info('🎯 Training Slots: ' . TrainingSessionSlot::count());
        $this->command->info('📝 Bookings: ' . SessionBooking::count());
        $this->command->info('✅ Attendance Records: ' . Attendance::count());
        $this->command->info('👥 Active Members with Package: ' . Member::query()->whereHas('memberPackages', fn ($q) => $q->active())->count());
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}
