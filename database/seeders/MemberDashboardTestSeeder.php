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
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberDashboardTestSeeder extends Seeder
{
    /**
     * Seed test data untuk Member Dashboard
     * 
     * Data yang dibuat:
     * - 1 User dengan role Member
     * - 1 Member profile (approved)
     * - 1 Child member
     * - 1 Coach
     * - 1 Package active dengan sisa quota
     * - Training sessions (past, present, future)
     * - Bookings dengan attendance
     * - Achievements
     */
    public function run(): void
    {
        echo "\n";
        echo "========================================\n";
        echo "🎯 MEMBER DASHBOARD TEST DATA SEEDER\n";
        echo "========================================\n\n";

        // Cleanup existing test data if exists
        $existingUser = User::where('email', 'memberdashboard@test.com')->first();
        if ($existingUser) {
            echo "🧹 Cleaning up existing test data...\n";
            // Delete will cascade to related data through foreign key constraints
            $existingUser->delete();
            echo "   ✅ Cleaned up\n\n";
        }

        // 1. Create Member User
        echo "1️⃣ Creating Member User...\n";
        $memberUser = User::firstOrCreate(
            ['email' => 'memberdashboard@test.com'],
            [
                'name' => 'Member Test User',
                'password' => Hash::make('password123'),
                'role' => UserRoles::MEMBER,
                'email_verified_at' => now(),
            ]
        );
        echo "   ✅ User created: {$memberUser->email}\n\n";

        // 2. Create or get Coach
        echo "2️⃣ Creating Coach...\n";
        $coachUser = User::firstOrCreate(
            ['email' => 'coachdashboard@test.com'],
            [
                'name' => 'Coach Dashboard Test',
                'password' => Hash::make('password123'),
                'role' => UserRoles::COACH,
                'email_verified_at' => now(),
            ]
        );

        $coach = Coach::firstOrCreate(
            ['user_id' => $coachUser->id],
            [
                'name' => 'Coach Dashboard Test',
                'phone' => '081234567800',
            ]
        );
        echo "   ✅ Coach created: {$coach->name}\n\n";

        // 3. Create Member Profile (self)
        echo "3️⃣ Creating Member Profile (Self)...\n";
        $member = Member::create([
            'user_id' => $memberUser->id,
            'registered_by' => $memberUser->id,
            'name' => 'Member Test User',
            'phone' => '081234567801',
            'is_self' => true,
            'status' => StatusMember::STATUS_ACTIVE,
            'is_active' => true,
        ]);
        echo "   ✅ Member profile: {$member->name}\n";
        echo "      Status: active\n\n";

        // 4. Create Child Member
        echo "4️⃣ Creating Child Member...\n";
        $childMember = Member::create([
            'user_id' => $memberUser->id,
            'registered_by' => $memberUser->id,
            'name' => 'Test Child',
            'phone' => '081234567802',
            'is_self' => false,
            'status' => StatusMember::STATUS_PENDING,
            'is_active' => true,
        ]);
        echo "   ✅ Child member: {$childMember->name}\n";
        echo "      Status: pending\n\n";

        // 5. Create Package
        echo "5️⃣ Creating Package...\n";
        $package = Package::create([
            'name' => 'Paket Premium 12 Sesi',
            'description' => 'Paket latihan premium untuk member dengan 12 sesi latihan per bulan',
            'price' => 750000,
            'duration_days' => 30,
            'session_count' => 12,
        ]);
        echo "   ✅ Package: {$package->name}\n";
        echo "      Sessions: {$package->session_count}\n";
        echo "      Duration: {$package->duration_days} days\n\n";

        // 6. Create Active Member Package
        echo "6️⃣ Creating Active Member Package...\n";
        $memberPackage = MemberPackage::create([
            'member_id' => $member->id,
            'package_id' => $package->id,
            'start_date' => now()->subDays(7),
            'end_date' => now()->addDays(23),
            'total_sessions' => 12,
            'used_sessions' => 5,
            'is_active' => true,
        ]);
        $remaining = $memberPackage->total_sessions - $memberPackage->used_sessions;
        echo "   ✅ Member Package Active\n";
        echo "      Total: {$memberPackage->total_sessions} sessions\n";
        echo "      Used: {$memberPackage->used_sessions} sessions\n";
        echo "      Remaining: {$remaining} sessions\n";
        echo "      Valid until: {$memberPackage->end_date->format('Y-m-d')}\n\n";

        // 7. Create Session Times
        echo "7️⃣ Creating Session Times...\n";
        $sessionTimes = [
            [
                'day_of_week' => 'monday',
                'start_time' => '07:00:00',
                'end_time' => '09:00:00'
            ],
            [
                'day_of_week' => 'wednesday',
                'start_time' => '13:00:00',
                'end_time' => '15:00:00'
            ],
            [
                'day_of_week' => 'friday',
                'start_time' => '16:00:00',
                'end_time' => '18:00:00'
            ],
        ];

        $createdTimes = [];
        foreach ($sessionTimes as $timeData) {
            $time = SessionTime::firstOrCreate(
                [
                    'day_of_week' => $timeData['day_of_week'],
                    'start_time' => $timeData['start_time'],
                ],
                [
                    'end_time' => $timeData['end_time'],
                    'max_capacity' => 10,
                    'coach_id' => $coach->user_id,
                    'is_active' => true,
                ]
            );
            $createdTimes[] = $time;
        }
        echo "   ✅ Created " . count($createdTimes) . " session times\n\n";

        // 8. Create Training Sessions
        echo "8️⃣ Creating Training Sessions...\n";
        $trainingSessions = [];
        
        // Past sessions (7 days ago to 1 day ago)
        echo "   Creating past sessions (completed)...\n";
        for ($i = 7; $i >= 1; $i--) {
            $session = TrainingSession::create([
                'coach_id' => $coach->id,
                'session_time_id' => $createdTimes[0]->id,
                'date' => now()->subDays($i)->format('Y-m-d'),
                'max_participants' => 10,
                'status' => TrainingSessionStatus::CLOSED,
            ]);
            $trainingSessions[] = $session;
        }
        echo "      ✅ {$i} completed sessions\n";

        // Today's session
        echo "   Creating today's session...\n";
        $todaySession = TrainingSession::create([
            'coach_id' => $coach->id,
            'session_time_id' => $createdTimes[1]->id,
            'date' => now()->format('Y-m-d'),
            'max_participants' => 10,
            'status' => TrainingSessionStatus::OPEN,
        ]);
        $trainingSessions[] = $todaySession;
        echo "      ✅ 1 today's session\n";

        // Future sessions (next 7 days)
        echo "   Creating future sessions (available for booking)...\n";
        for ($i = 1; $i <= 7; $i++) {
            $timeIndex = $i % 3;
            $session = TrainingSession::create([
                'coach_id' => $coach->id,
                'session_time_id' => $createdTimes[$timeIndex]->id,
                'date' => now()->addDays($i)->format('Y-m-d'),
                'max_participants' => 10,
                'status' => TrainingSessionStatus::OPEN,
            ]);
            $trainingSessions[] = $session;
        }
        echo "      ✅ 7 future sessions\n";
        echo "   ✅ Total: " . count($trainingSessions) . " training sessions\n\n";

        // 9. Create Bookings with Attendance (for past sessions)
        echo "9️⃣ Creating Bookings & Attendance...\n";
        $bookingCount = 0;
        $attendanceCount = 0;
        
        // Book 5 past sessions dengan attendance
        foreach (array_slice($trainingSessions, 0, 5) as $index => $session) {
            if ($session->date < now()->format('Y-m-d')) {
                $booking = SessionBooking::create([
                    'member_package_id' => $memberPackage->id,
                    'training_session_id' => $session->id,
                    'booked_by' => $memberUser->id,
                    'status' => 'confirmed',
                    'notes' => 'Booking test ' . ($index + 1),
                ]);
                $bookingCount++;

                // Create attendance (4 present, 1 absent)
                $isPresent = $index < 4;
                Attendance::create([
                    'session_booking_id' => $booking->id,
                    'status' => $isPresent ? 'present' : 'absent',
                    'validated_at' => $session->date . ' ' . $createdTimes[0]->start_time,
                    'notes' => $isPresent ? 'Hadir tepat waktu' : 'Tidak hadir',
                ]);
                $attendanceCount++;
            }
        }
        
        // Create 1 future booking (tomorrow)
        $futureBooking = SessionBooking::create([
            'member_package_id' => $memberPackage->id,
            'training_session_id' => $trainingSessions[9]->id,
            'booked_by' => $memberUser->id,
            'status' => 'confirmed',
            'notes' => 'Booking untuk sesi mendatang',
        ]);
        $bookingCount++;

        echo "   ✅ Created {$bookingCount} bookings\n";
        echo "   ✅ Created {$attendanceCount} attendance records\n";
        echo "      - Present: 4\n";
        echo "      - Absent: 1\n\n";

        // 10. Create Achievements
        echo "🔟 Creating Achievements...\n";
        $achievements = [
            [
                'member_id' => $member->id,
                'type' => 'member',
                'title' => 'Juara 1 Regional Championship 2026',
                'description' => 'Meraih medali emas pada Regional Archery Championship kategori compound bow dengan skor 695/720',
                'date' => now()->subMonths(1)->format('Y-m-d'),
                'photo_path' => null,
            ],
            [
                'member_id' => $member->id,
                'type' => 'member',
                'title' => 'Best Newcomer Award',
                'description' => 'Mendapatkan penghargaan Best Newcomer pada club tournament bulan lalu',
                'date' => now()->subMonths(2)->format('Y-m-d'),
                'photo_path' => null,
            ],
            [
                'member_id' => $member->id,
                'type' => 'member',
                'title' => 'Perfect 10 Streak',
                'description' => 'Berhasil mendapatkan 5 arrow perfect 10 berturut-turut pada sesi latihan',
                'date' => now()->subWeeks(2)->format('Y-m-d'),
                'photo_path' => null,
            ],
            [
                'member_id' => $member->id,
                'type' => 'member',
                'title' => '100 Sessions Milestone',
                'description' => 'Mencapai milestone 100 sesi latihan di club',
                'date' => now()->subWeek()->format('Y-m-d'),
                'photo_path' => null,
            ],
        ];

        foreach ($achievements as $achievementData) {
            Achievement::create($achievementData);
        }
        echo "   ✅ Created " . count($achievements) . " achievements\n\n";

        // Summary
        echo "========================================\n";
        echo "✨ SEEDING COMPLETED SUCCESSFULLY!\n";
        echo "========================================\n\n";
        echo "📊 SUMMARY:\n";
        echo "-------------------------------------------\n";
        echo "👤 User Email    : {$memberUser->email}\n";
        echo "🔑 Password      : password123\n";
        echo "👨‍🏫 Coach         : {$coach->name}\n";
        echo "📦 Package       : {$package->name}\n";
        echo "🎫 Total Quota   : {$memberPackage->total_sessions} sessions\n";
        echo "✅ Used          : {$memberPackage->used_sessions} sessions\n";
        echo "🎯 Remaining     : " . ($memberPackage->total_sessions - $memberPackage->used_sessions) . " sessions\n";
        echo "📅 Valid Until   : {$memberPackage->end_date->format('d M Y')}\n";
        echo "📝 Bookings      : {$bookingCount} total\n";
        echo "✅ Present       : 4\n";
        echo "❌ Absent        : 1\n";
        echo "🏆 Achievements  : " . count($achievements) . "\n";
        echo "🗓️  Training Sess : " . count($trainingSessions) . " (7 past, 1 today, 7 future)\n";
        echo "-------------------------------------------\n\n";
        echo "🚀 You can now login and test the Member Dashboard!\n";
        echo "   URL: http://localhost/member/dashboard\n";
        echo "========================================\n\n";
    }
}
