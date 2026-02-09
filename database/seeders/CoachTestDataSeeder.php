<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coach;
use App\Models\SessionTime;
use App\Models\TrainingSession;
use App\Models\TrainingSessionSlot;
use App\Models\SessionBooking;
use App\Models\Attendance;
use App\Models\MemberPackage;
use App\Enums\StatusMember;
use Carbon\Carbon;

class CoachTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🎯 Seeding coach test data...');

        // Create Training Sessions with Slots and Bookings
        $firstCoach = Coach::first();
        $sessionTimes = SessionTime::all();
        
        if (!$firstCoach) {
            $this->command->warn('⚠️  No coaches found. Please run AdminTestDataSeeder first.');
            return;
        }

        if ($sessionTimes->isEmpty()) {
            $this->command->warn('⚠️  No session times found. Please run AdminTestDataSeeder first.');
            return;
        }

        // Create training sessions for today, yesterday, and 3 upcoming days
        $dates = [
            Carbon::now()->subDay(), // Yesterday
            Carbon::now(), // Today
            Carbon::now()->addDay(), // Tomorrow
            Carbon::now()->addDays(2), // Day after tomorrow
            Carbon::now()->addDays(3), // 3 days from now
        ];

        foreach ($dates as $date) {
            // Create a training session (day header)
            $trainingSession = TrainingSession::firstOrCreate(
                [
                    'coach_id' => $firstCoach->id,
                    'date' => $date->format('Y-m-d'),
                ],
                [
                    'coach_id' => $firstCoach->id,
                    'date' => $date->format('Y-m-d'),
                    'status' => 'open',
                ]
            );

            // Create 6 slots for this day (one for each session time)
            $capacities = [12, 15, 10, 14, 16, 12]; // Different capacities for each slot
            
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

                // Create some bookings for past and today's slots
                if ($date->lte(Carbon::now())) {
                    // For yesterday and today, create some bookings
                    $bookingCount = rand(3, min(8, $slot->max_participants)); // 3-8 bookings per slot
                    
                    // Get members with active packages
                    $memberPackages = MemberPackage::where('is_active', true)
                        ->with('member.user')
                        ->inRandomOrder()
                        ->limit($bookingCount)
                        ->get();

                    foreach ($memberPackages as $memberIndex => $memberPackage) {
                        if ($memberPackage->member && $memberPackage->member->user) {
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
                                ]
                            );

                            // For yesterday's bookings, create attendance records
                            if ($date->lt(Carbon::now()) && $booking->exists) {
                                // 80% attendance rate (4 out of 5 attended)
                                $attended = ($memberIndex % 5) !== 4;
                                
                                Attendance::firstOrCreate(
                                    [
                                        'session_booking_id' => $booking->id,
                                    ],
                                    [
                                        'session_booking_id' => $booking->id,
                                        'status' => $attended ? 'present' : 'absent',
                                        'validated_by' => $firstCoach->user_id,
                                        'validated_at' => $date->copy()->setTime(22, 0),
                                        'notes' => $attended ? 'Present' : 'Absent without notice',
                                    ]
                                );
                            }
                        }
                    }
                }

                // For future sessions, create some bookings too (confirmed bookings)
                if ($date->gt(Carbon::now())) {
                    $futureBookingCount = rand(2, min(5, $slot->max_participants)); // 2-5 bookings for future
                    $futureMemberPackages = MemberPackage::where('is_active', true)
                        ->with('member.user')
                        ->inRandomOrder()
                        ->limit($futureBookingCount)
                        ->get();

                    foreach ($futureMemberPackages as $futurePackage) {
                        if ($futurePackage->member && $futurePackage->member->user) {
                            SessionBooking::firstOrCreate(
                                [
                                    'training_session_slot_id' => $slot->id,
                                    'member_package_id' => $futurePackage->id,
                                ],
                                [
                                    'training_session_slot_id' => $slot->id,
                                    'member_package_id' => $futurePackage->id,
                                    'booked_by' => $futurePackage->member->user->id,
                                    'status' => 'confirmed',
                                ]
                            );
                        }
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
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}
