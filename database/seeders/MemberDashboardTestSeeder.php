<?php

namespace Database\Seeders;

use App\Enums\StatusMember;
use App\Enums\TrainingSessionStatus;
use App\Enums\UserRoles;
use App\Models\Achievement;
use App\Models\Attendance;
use App\Models\Coach;
use App\Models\Member;
use App\Models\MemberPackage;
use App\Models\Package;
use App\Models\SessionBooking;
use App\Models\SessionTime;
use App\Models\TrainingSession;
use App\Models\TrainingSessionSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberDashboardTestSeeder extends Seeder
{
    /**
     * Seed test data untuk Member Dashboard (data sedang berjalan).
     */
    public function run(): void
    {
        $this->command->info('🎯 Seeding member dashboard test data...');

        $memberUser = User::query()->updateOrCreate(
            ['email' => 'memberdashboard@test.com'],
            [
                'name' => 'Member Dashboard Test',
                'role' => UserRoles::MEMBER->value,
                'phone' => '081200000111',
                'password' => Hash::make('password123'),
            ]
        );

        $coachUser = User::query()->updateOrCreate(
            ['email' => 'coachdashboard@test.com'],
            [
                'name' => 'Coach Dashboard Test',
                'role' => UserRoles::COACH->value,
                'phone' => '081200000222',
                'password' => Hash::make('password123'),
            ]
        );

        $coach = Coach::query()->updateOrCreate(
            ['user_id' => $coachUser->id],
            [
                'name' => $coachUser->name,
                'phone' => $coachUser->phone,
            ]
        );

        $adminUser = User::query()->firstOrCreate(
            ['email' => 'admin@clubpanahan.com'],
            [
                'name' => 'Admin Club Panahan',
                'role' => UserRoles::ADMIN->value,
                'phone' => '081234567890',
                'password' => Hash::make('admin123'),
            ]
        );

        $member = Member::query()->updateOrCreate(
            ['user_id' => $memberUser->id, 'is_self' => true],
            [
                'registered_by' => $memberUser->id,
                'name' => $memberUser->name,
                'phone' => $memberUser->phone,
                'status' => StatusMember::STATUS_ACTIVE->value,
                'is_active' => true,
            ]
        );

        $childActive = Member::query()->updateOrCreate(
            ['user_id' => $memberUser->id, 'is_self' => false, 'name' => 'Sarah Dashboard Test'],
            [
                'registered_by' => $memberUser->id,
                'phone' => '081200000333',
                'status' => StatusMember::STATUS_ACTIVE->value,
                'is_active' => true,
            ]
        );

        Member::query()->updateOrCreate(
            ['user_id' => $memberUser->id, 'is_self' => false, 'name' => 'Michael Dashboard Test'],
            [
                'registered_by' => $memberUser->id,
                'phone' => '081200000444',
                'status' => StatusMember::STATUS_PENDING->value,
                'is_active' => true,
            ]
        );

        $premiumPackage = Package::query()->updateOrCreate(
            ['name' => 'Premium Package'],
            [
                'description' => 'Paket premium untuk pengujian dashboard member',
                'price' => 900000,
                'duration_days' => 30,
                'session_count' => 12,
                'is_active' => true,
            ]
        );

        $basicPackage = Package::query()->updateOrCreate(
            ['name' => 'Basic Package'],
            [
                'description' => 'Paket basic untuk pengujian dashboard member',
                'price' => 500000,
                'duration_days' => 30,
                'session_count' => 8,
                'is_active' => true,
            ]
        );

        $memberPackage = MemberPackage::query()->updateOrCreate(
            ['member_id' => $member->id],
            [
                'package_id' => $premiumPackage->id,
                'total_sessions' => 12,
                'used_sessions' => 5,
                'start_date' => now()->subDays(9)->toDateString(),
                'end_date' => now()->addDays(21)->toDateString(),
                'is_active' => true,
                'validated_by' => $adminUser->id,
                'validated_at' => now()->subDays(9),
            ]
        );

        $childPackage = MemberPackage::query()->updateOrCreate(
            ['member_id' => $childActive->id],
            [
                'package_id' => $basicPackage->id,
                'total_sessions' => 8,
                'used_sessions' => 2,
                'start_date' => now()->subDays(4)->toDateString(),
                'end_date' => now()->addDays(26)->toDateString(),
                'is_active' => true,
                'validated_by' => $adminUser->id,
                'validated_at' => now()->subDays(4),
            ]
        );

        $sessionTimes = SessionTime::query()->orderBy('start_time')->get();
        if ($sessionTimes->isEmpty()) {
            $sessionTimes = collect([
                SessionTime::query()->firstOrCreate(
                    ['name' => 'Sesi 1'],
                    ['start_time' => '07:30:00', 'end_time' => '09:00:00', 'is_active' => true]
                ),
                SessionTime::query()->firstOrCreate(
                    ['name' => 'Sesi 2'],
                    ['start_time' => '09:00:00', 'end_time' => '10:30:00', 'is_active' => true]
                ),
                SessionTime::query()->firstOrCreate(
                    ['name' => 'Sesi 3'],
                    ['start_time' => '15:00:00', 'end_time' => '16:30:00', 'is_active' => true]
                ),
            ]);
        }

        $sessionDates = [-6, -4, -2, -1, 0, 1, 3];
        $slotsByOffset = [];

        foreach ($sessionDates as $index => $offset) {
            $date = Carbon::today()->addDays($offset);
            $status = $date->isPast() ? TrainingSessionStatus::CLOSED : TrainingSessionStatus::OPEN;

            $trainingSession = TrainingSession::query()->firstOrCreate(
                [
                    'date' => $date->toDateString(),
                    'created_by' => $coachUser->id,
                ],
                ['status' => $status]
            );

            if ($trainingSession->status !== $status) {
                $trainingSession->update(['status' => $status]);
            }

            $sessionTime = $sessionTimes[$index % $sessionTimes->count()];

            $slot = TrainingSessionSlot::query()->firstOrCreate(
                [
                    'training_session_id' => $trainingSession->id,
                    'session_time_id' => $sessionTime->id,
                ],
                [
                    'max_participants' => 10,
                ]
            );

            $slot->coaches()->syncWithoutDetaching([$coach->id]);
            $slotsByOffset[$offset] = $slot;
        }

        $pastOffsetsForAttendance = [-6, -4, -2, -1];

        foreach ($pastOffsetsForAttendance as $idx => $offset) {
            $slot = $slotsByOffset[$offset];

            $booking = SessionBooking::query()->firstOrCreate(
                [
                    'member_package_id' => $memberPackage->id,
                    'training_session_slot_id' => $slot->id,
                ],
                [
                    'booked_by' => $memberUser->id,
                    'status' => 'confirmed',
                    'notes' => 'Seeded booking for dashboard history',
                ]
            );

            $isPresent = $idx !== 2;

            Attendance::query()->firstOrCreate(
                ['session_booking_id' => $booking->id],
                [
                    'status' => $isPresent ? 'present' : 'absent',
                    'validated_by' => $coachUser->id,
                    'validated_at' => Carbon::parse($slot->trainingSession->date)->setTime(17, 0),
                    'notes' => $isPresent ? 'Present (seeded)' : 'Absent (seeded)',
                ]
            );
        }

        $futureSlot = $slotsByOffset[1];
        SessionBooking::query()->firstOrCreate(
            [
                'member_package_id' => $memberPackage->id,
                'training_session_slot_id' => $futureSlot->id,
            ],
            [
                'booked_by' => $memberUser->id,
                'status' => 'confirmed',
                'notes' => 'Seeded future booking',
            ]
        );

        $childPastSlot = $slotsByOffset[-2];
        $childBooking = SessionBooking::query()->firstOrCreate(
            [
                'member_package_id' => $childPackage->id,
                'training_session_slot_id' => $childPastSlot->id,
            ],
            [
                'booked_by' => $memberUser->id,
                'status' => 'confirmed',
                'notes' => 'Seeded child booking',
            ]
        );

        Attendance::query()->firstOrCreate(
            ['session_booking_id' => $childBooking->id],
            [
                'status' => 'present',
                'validated_by' => $coachUser->id,
                'validated_at' => Carbon::parse($childPastSlot->trainingSession->date)->setTime(17, 15),
                'notes' => 'Present (child seeded)',
            ]
        );

        $memberDataUser = User::query()->updateOrCreate(
            ['email' => 'memberdata@test.com'],
            [
                'name' => 'Member Data Test',
                'role' => UserRoles::MEMBER->value,
                'phone' => '081200000555',
                'password' => Hash::make('password123'),
            ]
        );

        $memberData = Member::query()->updateOrCreate(
            ['user_id' => $memberDataUser->id, 'is_self' => true],
            [
                'registered_by' => $memberDataUser->id,
                'name' => $memberDataUser->name,
                'phone' => $memberDataUser->phone,
                'status' => StatusMember::STATUS_ACTIVE->value,
                'is_active' => true,
            ]
        );

        $memberDataPackage = MemberPackage::query()->updateOrCreate(
            ['member_id' => $memberData->id],
            [
                'package_id' => $premiumPackage->id,
                'total_sessions' => 12,
                'used_sessions' => 4,
                'start_date' => now()->subDays(7)->toDateString(),
                'end_date' => now()->addDays(23)->toDateString(),
                'is_active' => true,
                'validated_by' => $adminUser->id,
                'validated_at' => now()->subDays(7),
            ]
        );

        $memberDataPastSlot = $slotsByOffset[-4];
        $memberDataPastBooking = SessionBooking::query()->firstOrCreate(
            [
                'member_package_id' => $memberDataPackage->id,
                'training_session_slot_id' => $memberDataPastSlot->id,
            ],
            [
                'booked_by' => $memberDataUser->id,
                'status' => 'confirmed',
                'notes' => 'Seeded member data past booking',
            ]
        );

        Attendance::query()->firstOrCreate(
            ['session_booking_id' => $memberDataPastBooking->id],
            [
                'status' => 'present',
                'validated_by' => $coachUser->id,
                'validated_at' => Carbon::parse($memberDataPastSlot->trainingSession->date)->setTime(17, 10),
                'notes' => 'Present (member data test)',
            ]
        );

        $memberDataPastSlot2 = $slotsByOffset[-1];
        $memberDataPastBooking2 = SessionBooking::query()->firstOrCreate(
            [
                'member_package_id' => $memberDataPackage->id,
                'training_session_slot_id' => $memberDataPastSlot2->id,
            ],
            [
                'booked_by' => $memberDataUser->id,
                'status' => 'confirmed',
                'notes' => 'Seeded member data second past booking',
            ]
        );

        Attendance::query()->firstOrCreate(
            ['session_booking_id' => $memberDataPastBooking2->id],
            [
                'status' => 'absent',
                'validated_by' => $coachUser->id,
                'validated_at' => Carbon::parse($memberDataPastSlot2->trainingSession->date)->setTime(17, 20),
                'notes' => 'Absent (member data test)',
            ]
        );

        $memberDataFutureSlot = $slotsByOffset[3];
        SessionBooking::query()->firstOrCreate(
            [
                'member_package_id' => $memberDataPackage->id,
                'training_session_slot_id' => $memberDataFutureSlot->id,
            ],
            [
                'booked_by' => $memberDataUser->id,
                'status' => 'confirmed',
                'notes' => 'Seeded member data future booking',
            ]
        );

        $achievements = [
            ['member_id' => $member->id, 'type' => 'member', 'title' => 'Juara 1 Regional 2026'],
            ['member_id' => $member->id, 'type' => 'member', 'title' => 'Best Improvement Award'],
            ['member_id' => $member->id, 'type' => 'member', 'title' => 'Perfect 10 Streak'],
            ['member_id' => $childActive->id, 'type' => 'member', 'title' => 'Junior Rising Star'],
        ];

        foreach ($achievements as $i => $achievement) {
            Achievement::query()->firstOrCreate(
                [
                    'member_id' => $achievement['member_id'],
                    'title' => $achievement['title'],
                ],
                [
                    'type' => $achievement['type'],
                    'description' => 'Achievement seeded for dashboard testing.',
                    'date' => now()->subDays(($i + 1) * 7)->toDateString(),
                    'photo_path' => null,
                ]
            );
        }

        $this->command->info('✅ Member dashboard test data seeded successfully!');
        $this->command->info('📧 Login: memberdashboard@test.com / password123');
        $this->command->info('📧 Login: memberdata@test.com / password123');
        $this->command->info('👨‍🏫 Coach: coachdashboard@test.com / password123');
        $this->command->info('📊 Member active package remaining sessions: ' . ($memberPackage->total_sessions - $memberPackage->used_sessions));
    }
}
