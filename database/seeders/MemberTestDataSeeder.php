<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Attendance;
use App\Models\Coach;
use App\Models\Member;
use App\Models\MemberPackage;
use App\Models\Package;
use App\Models\SessionBooking;
use App\Models\SessionTime;
use App\Models\TrainingSession;
use App\Models\User;
use App\Enums\UserRoles;
use App\Enums\StatusMember;
use App\Enums\TrainingSessionStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🎯 Starting Member Test Data Seeding...');

        // 1. Create Member User
        $this->command->info('👤 Creating Member User...');
        $memberUser = User::firstOrCreate(
            ['email' => 'member@archery.test'],
            [
                'name' => 'John Member',
                'email' => 'member@archery.test',
                'password' => Hash::make('password'),
                'role' => UserRoles::MEMBER,
                'email_verified_at' => now(),
            ]
        );
        $this->command->info("✅ Member User created: {$memberUser->email}");

        // 2. Create Coach User & Profile
        $this->command->info('👨‍🏫 Creating Coach...');
        $coachUser = User::firstOrCreate(
            ['email' => 'coach.test@archery.test'],
            [
                'name' => 'Coach Michael',
                'email' => 'coach.test@archery.test',
                'password' => Hash::make('password'),
                'role' => UserRoles::COACH,
                'email_verified_at' => now(),
            ]
        );

        $coach = Coach::firstOrCreate(
            ['user_id' => $coachUser->id],
            [
                'user_id' => $coachUser->id,
                'name' => 'Coach Michael',
                'phone' => '081234567890',
                'specialization' => 'Recurve & Compound',
                'experience_years' => 10,
                'is_active' => true,
            ]
        );
        $this->command->info("✅ Coach created: {$coach->name}");

        // 3. Create Member Profile (Self)
        $this->command->info('🏹 Creating Member Profile...');
        $member = Member::firstOrCreate(
            [
                'user_id' => $memberUser->id,
                'is_self' => true,
            ],
            [
                'user_id' => $memberUser->id,
                'registered_by' => $memberUser->id,
                'name' => 'John Member',
                'phone' => '081234567891',
                'is_self' => true,
                'status' => StatusMember::STATUS_APPROVED,
                'is_active' => true,
            ]
        );
        $this->command->info("✅ Member Profile created: {$member->name}");

        // 4. Create Child Member
        $this->command->info('👶 Creating Child Member...');
        $childMember = Member::firstOrCreate(
            [
                'user_id' => $memberUser->id,
                'is_self' => false,
                'name' => 'Jane Junior',
            ],
            [
                'user_id' => $memberUser->id,
                'registered_by' => $memberUser->id,
                'name' => 'Jane Junior',
                'phone' => '081234567892',
                'is_self' => false,
                'status' => StatusMember::STATUS_APPROVED,
                'is_active' => true,
            ]
        );
        $this->command->info("✅ Child Member created: {$childMember->name}");

        // 5. Create Packages
        $this->command->info('📦 Creating Packages...');
        $packages = [
            [
                'name' => 'Paket Basic - 8 Sesi',
                'description' => 'Paket latihan basic untuk pemula',
                'price' => 500000,
                'sessions' => 8,
                'duration_days' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Paket Intermediate - 12 Sesi',
                'description' => 'Paket latihan intermediate untuk member reguler',
                'price' => 700000,
                'sessions' => 12,
                'duration_days' => 45,
                'is_active' => true,
            ],
            [
                'name' => 'Paket Premium - 20 Sesi',
                'description' => 'Paket latihan premium dengan coaching intensif',
                'price' => 1000000,
                'sessions' => 20,
                'duration_days' => 60,
                'is_active' => true,
            ],
        ];

        $createdPackages = [];
        foreach ($packages as $pkg) {
            $package = Package::firstOrCreate(
                ['name' => $pkg['name']],
                $pkg
            );
            $createdPackages[] = $package;
            $this->command->info("✅ Package created: {$package->name}");
        }

        // 6. Create Active Member Package
        $this->command->info('💳 Creating Active Member Package...');
        $memberPackage = MemberPackage::firstOrCreate(
            [
                'member_id' => $member->id,
                'package_id' => $createdPackages[1]->id, // Intermediate package
            ],
            [
                'member_id' => $member->id,
                'package_id' => $createdPackages[1]->id,
                'total_sessions' => $createdPackages[1]->sessions,
                'used_sessions' => 3, // Already used 3 sessions
                'start_date' => now()->subDays(10),
                'end_date' => now()->addDays(35),
                'is_active' => true,
            ]
        );
        $this->command->info("✅ Active Member Package created with {$memberPackage->used_sessions}/{$memberPackage->total_sessions} sessions used");

        // 7. Create Session Times
        $this->command->info('⏰ Creating Session Times...');
        $sessionTimes = [
            ['name' => 'Pagi (08:00 - 10:00)', 'start_time' => '08:00:00', 'end_time' => '10:00:00'],
            ['name' => 'Siang (10:00 - 12:00)', 'start_time' => '10:00:00', 'end_time' => '12:00:00'],
            ['name' => 'Sore (16:00 - 18:00)', 'start_time' => '16:00:00', 'end_time' => '18:00:00'],
            ['name' => 'Malam (18:00 - 20:00)', 'start_time' => '18:00:00', 'end_time' => '20:00:00'],
        ];

        $createdSessionTimes = [];
        foreach ($sessionTimes as $st) {
            $sessionTime = SessionTime::firstOrCreate(
                ['name' => $st['name']],
                $st
            );
            $createdSessionTimes[] = $sessionTime;
            $this->command->info("✅ Session Time created: {$sessionTime->name}");
        }

        // 8. Create Training Sessions (Past, Today, Future)
        $this->command->info('📅 Creating Training Sessions...');

        // Past sessions (for attendance history)
        for ($i = 10; $i >= 1; $i--) {
            $sessionTime = $createdSessionTimes[array_rand($createdSessionTimes)];
            $session = TrainingSession::create([
                'coach_id' => $coach->id,
                'session_time_id' => $sessionTime->id,
                'date' => now()->subDays($i),
                'max_participants' => 10,
                'location' => 'Lapangan Utama',
                'description' => "Training session " . now()->subDays($i)->format('d M Y'),
                'status' => TrainingSessionStatus::STATUS_COMPLETED,
            ]);
            $this->command->info("✅ Past Training Session: {$session->date->format('Y-m-d')} - {$sessionTime->name}");
        }

        // Today's session
        $todaySession = TrainingSession::create([
            'coach_id' => $coach->id,
            'session_time_id' => $createdSessionTimes[0]->id,
            'date' => now(),
            'max_participants' => 10,
            'location' => 'Lapangan Utama',
            'description' => 'Morning training session today',
            'status' => TrainingSessionStatus::STATUS_OPEN,
        ]);
        $this->command->info("✅ Today's Training Session created");

        // Future sessions (for booking)
        for ($i = 1; $i <= 7; $i++) {
            foreach (array_slice($createdSessionTimes, 0, 2) as $sessionTime) {
                $session = TrainingSession::create([
                    'coach_id' => $coach->id,
                    'session_time_id' => $sessionTime->id,
                    'date' => now()->addDays($i),
                    'max_participants' => 10,
                    'location' => 'Lapangan Utama',
                    'description' => "Training session " . now()->addDays($i)->format('d M Y') . " - " . $sessionTime->name,
                    'status' => TrainingSessionStatus::STATUS_OPEN,
                ]);
                $this->command->info("✅ Future Training Session: {$session->date->format('Y-m-d')} - {$sessionTime->name}");
            }
        }

        // 9. Create Session Bookings with Attendance
        $this->command->info('📝 Creating Session Bookings & Attendance...');

        $pastSessions = TrainingSession::where('status', TrainingSessionStatus::STATUS_COMPLETED)
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        foreach ($pastSessions as $index => $session) {
            $booking = SessionBooking::create([
                'member_package_id' => $memberPackage->id,
                'training_session_id' => $session->id,
                'status' => 'confirmed',
                'notes' => 'Regular training session',
            ]);

            // Create attendance
            $isPresent = $index < 4; // 4 present, 1 absent
            Attendance::create([
                'session_booking_id' => $booking->id,
                'status' => $isPresent ? 'present' : 'absent',
                'validated_at' => $session->date,
                'notes' => $isPresent ? 'Attended and performed well' : 'Did not attend',
            ]);

            $this->command->info("✅ Booking & Attendance created for session: {$session->date->format('Y-m-d')} - " . ($isPresent ? 'PRESENT' : 'ABSENT'));
        }

        // Create upcoming booking
        $upcomingSession = TrainingSession::where('status', TrainingSessionStatus::STATUS_OPEN)
            ->where('date', '>', now())
            ->orderBy('date', 'asc')
            ->first();

        if ($upcomingSession) {
            SessionBooking::create([
                'member_package_id' => $memberPackage->id,
                'training_session_id' => $upcomingSession->id,
                'status' => 'confirmed',
                'notes' => 'Looking forward to this session!',
            ]);
            $this->command->info("✅ Upcoming Booking created for: {$upcomingSession->date->format('Y-m-d')}");
        }

        // 10. Create Achievements
        $this->command->info('🏆 Creating Achievements...');
        $achievements = [
            [
                'member_id' => $member->id,
                'type' => 'member',
                'title' => 'Juara 1 Kompetisi Regional 2025',
                'description' => 'Meraih juara pertama pada kompetisi panahan regional tingkat provinsi kategori recurve bow',
                'date' => now()->subMonths(2),
                'photo_path' => null,
            ],
            [
                'member_id' => $member->id,
                'type' => 'member',
                'title' => 'Best Scorer Tournament',
                'description' => 'Mendapat penghargaan scorer terbaik dengan total 680 poin dari 720',
                'date' => now()->subMonths(4),
                'photo_path' => null,
            ],
            [
                'member_id' => $member->id,
                'type' => 'member',
                'title' => 'Juara 3 Championship 2024',
                'description' => 'Meraih juara ketiga pada kejuaraan nasional panahan',
                'date' => now()->subMonths(8),
                'photo_path' => null,
            ],
            [
                'member_id' => $member->id,
                'type' => 'member',
                'title' => 'Most Improved Archer',
                'description' => 'Penghargaan untuk archer dengan peningkatan performa terbaik dalam 6 bulan',
                'date' => now()->subMonths(6),
                'photo_path' => null,
            ],
        ];

        foreach ($achievements as $ach) {
            $achievement = Achievement::create($ach);
            $this->command->info("✅ Achievement created: {$achievement->title}");
        }

        $this->command->info('');
        $this->command->info('🎉 Member Test Data Seeding Completed!');
        $this->command->info('');
        $this->command->info('📋 Summary:');
        $this->command->info("   Member User: member@archery.test / password");
        $this->command->info("   Coach User: coach.test@archery.test / password");
        $this->command->info("   Member Profile: {$member->name}");
        $this->command->info("   Child Member: {$childMember->name}");
        $this->command->info("   Active Package: {$memberPackage->package->name}");
        $this->command->info("   Quota: {$memberPackage->used_sessions}/{$memberPackage->total_sessions} sessions used");
        $this->command->info("   Remaining: " . ($memberPackage->total_sessions - $memberPackage->used_sessions) . " sessions");
        $this->command->info("   Training Sessions: " . TrainingSession::count() . " total");
        $this->command->info("   Bookings: " . SessionBooking::count() . " total");
        $this->command->info("   Achievements: " . Achievement::where('member_id', $member->id)->count() . " total");
        $this->command->info('');
        $this->command->info('🚀 You can now login and test the Member Dashboard!');
    }
}
