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
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MemberDashboardTestSeeder extends Seeder
{
    /**
     * Seed test data untuk Member Dashboard
     *
     * Data yang dibuat:
     * - 1 User dengan role Member (login credential)
     * - 3 Member profiles (self + 2 family members) dengan berbagai status
     * - 2 Coaches
     * - 3 Packages (Basic, Premium, Professional)
     * - Multiple member packages (active, expired, pending)
     * - Training sessions (past, present, future) - 30 days worth
     * - Bookings dengan attendance (present, absent, late)
     * - Achievements (medals, milestones, competitions)
     * - Complete data untuk testing semua fitur dashboard
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

        // Cleanup existing training sessions for seeder coaches (so this seeder can be re-run)
        $existingCoachUsers = User::whereIn('email', ['coachdashboard@test.com', 'coach2dashboard@test.com'])->pluck('id');
        if ($existingCoachUsers->isNotEmpty()) {
            $existingCoachIds = Coach::whereIn('user_id', $existingCoachUsers)->pluck('id');
            if ($existingCoachIds->isNotEmpty()) {
                TrainingSession::whereIn('coach_id', $existingCoachIds)->delete();
            }
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

        // 2. Create or get Coaches
        echo "2️⃣ Creating Coaches...\n";
        $coachUser = User::firstOrCreate(
            ['email' => 'coachdashboard@test.com'],
            [
                'name' => 'Coach Test Primary',
                'password' => Hash::make('password123'),
                'role' => UserRoles::COACH,
                'email_verified_at' => now(),
            ]
        );

        $coach = Coach::firstOrCreate(
            ['user_id' => $coachUser->id],
            [
                'name' => 'Coach Test Primary',
                'phone' => '081234567800',
            ]
        );
        echo "   ✅ Primary coach: {$coach->name}\n";

        $coachUser2 = User::firstOrCreate(
            ['email' => 'coach2dashboard@test.com'],
            [
                'name' => 'Coach Test Secondary',
                'password' => Hash::make('password123'),
                'role' => UserRoles::COACH,
                'email_verified_at' => now(),
            ]
        );

        $coach2 = Coach::firstOrCreate(
            ['user_id' => $coachUser2->id],
            [
                'name' => 'Coach Test Secondary',
                'phone' => '081234567801',
            ]
        );
        echo "   ✅ Secondary coach: {$coach2->name}\n\n";

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

        // 4. Create Family Members
        echo "4️⃣ Creating Family Members...\n";

        $childMember1 = Member::create([
            'user_id' => $memberUser->id,
            'registered_by' => $memberUser->id,
            'name' => 'Sarah Test (Daughter)',
            'phone' => '081234567802',
            'is_self' => false,
            'status' => StatusMember::STATUS_ACTIVE,
            'is_active' => true,
        ]);
        echo "   ✅ Family member 1: {$childMember1->name} - Active\n";

        $childMember2 = Member::create([
            'user_id' => $memberUser->id,
            'registered_by' => $memberUser->id,
            'name' => 'Michael Test (Son)',
            'phone' => '081234567803',
            'is_self' => false,
            'status' => StatusMember::STATUS_PENDING,
            'is_active' => true,
        ]);
        echo "   ✅ Family member 2: {$childMember2->name} - Pending\n\n";

        // 5. Create Multiple Packages
        echo "5️⃣ Creating Packages...\n";
        $basicPackage = Package::create([
            'name' => 'Basic Package',
            'description' => 'Paket dasar untuk pemula dengan 8 sesi latihan per bulan',
            'price' => 500000,
            'duration_days' => 30,
            'session_count' => 8,
        ]);
        echo "   ✅ {$basicPackage->name} - {$basicPackage->session_count} sessions\n";

        $package = Package::create([
            'name' => 'Premium Package',
            'description' => 'Paket premium untuk member dengan 12 sesi latihan per bulan',
            'price' => 750000,
            'duration_days' => 30,
            'session_count' => 12,
        ]);
        echo "   ✅ {$package->name} - {$package->session_count} sessions\n";

        $proPackage = Package::create([
            'name' => 'Professional Package',
            'description' => 'Paket profesional dengan unlimited sessions dan private coaching',
            'price' => 1500000,
            'duration_days' => 30,
            'session_count' => 20,
        ]);
        echo "   ✅ {$proPackage->name} - {$proPackage->session_count} sessions\n\n";

        // 6. Create Active Member Package (Primary)
        echo "6️⃣ Creating Member Packages...\n";
        $memberPackage = MemberPackage::create([
            'member_id' => $member->id,
            'package_id' => $package->id,
            'start_date' => now()->subDays(10),
            'end_date' => now()->addDays(20),
            'total_sessions' => 12,
            'used_sessions' => 7,
            'is_active' => true,
        ]);
        $remaining = $memberPackage->total_sessions - $memberPackage->used_sessions;
        echo "   ✅ Primary Package (Active)\n";
        echo "      Member: {$member->name}\n";
        echo "      Total: {$memberPackage->total_sessions} sessions\n";
        echo "      Used: {$memberPackage->used_sessions} sessions\n";
        echo "      Remaining: {$remaining} sessions\n";
        echo "      Valid: {$memberPackage->start_date->format('d M')} - {$memberPackage->end_date->format('d M Y')}\n\n";

        // Expired package untuk testing
        $expiredPackage = MemberPackage::create([
            'member_id' => $member->id,
            'package_id' => $basicPackage->id,
            'start_date' => now()->subDays(60),
            'end_date' => now()->subDays(30),
            'total_sessions' => 8,
            'used_sessions' => 8,
            'is_active' => false,
        ]);
        echo "   ✅ Expired Package\n";
        echo "      Expired at: {$expiredPackage->end_date->format('d M Y')}\n\n";

        // Active package untuk family member
        $childPackage = MemberPackage::create([
            'member_id' => $childMember1->id,
            'package_id' => $basicPackage->id,
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(25),
            'total_sessions' => 8,
            'used_sessions' => 2,
            'is_active' => true,
        ]);
        echo "   ✅ Family Package (Active)\n";
        echo "      Member: {$childMember1->name}\n";
        echo "      Remaining: " . ($childPackage->total_sessions - $childPackage->used_sessions) . " sessions\n\n";

        // 7. Create Session Times
        echo "7️⃣ Creating Session Times...\n";
        $sessionTimes = [
            [
                'name' => 'Senin Pagi',
                'start_time' => '07:00:00',
                'end_time' => '09:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Senin Sore',
                'start_time' => '16:00:00',
                'end_time' => '18:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Rabu Siang',
                'start_time' => '13:00:00',
                'end_time' => '15:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Jumat Sore',
                'start_time' => '16:00:00',
                'end_time' => '18:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Sabtu Pagi',
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'is_active' => true,
            ],
        ];

        $createdTimes = [];
        foreach ($sessionTimes as $timeData) {
            $time = SessionTime::firstOrCreate(
                ['name' => $timeData['name']],
                [
                    'start_time' => $timeData['start_time'],
                    'end_time' => $timeData['end_time'],
                    'is_active' => $timeData['is_active'],
                ]
            );
            $createdTimes[] = $time;
        }
        echo "   ✅ Created " . count($createdTimes) . " session times across 2 coaches\n\n";

        // 8. Create Training Sessions
        echo "8️⃣ Creating Training Sessions...\n";
        $trainingSessions = [];

        // Past sessions (10 days ago to 1 day ago)
        echo "   Creating past sessions (completed)...\n";
        $pastCount = 0;
        for ($i = 10; $i >= 1; $i--) {
            // Vary coach assignments
            $coachId = ($i % 2 == 0) ? $coach->id : $coach2->id;
            $timeIndex = ($i % count($createdTimes));

            $sessionDate = now()->subDays($i)->format('Y-m-d');
            $session = TrainingSession::create([
                'coach_id' => $coachId,
                'date' => $sessionDate,
                'status' => TrainingSessionStatus::CLOSED,
            ]);

            TrainingSessionSlot::create([
                'training_session_id' => $session->id,
                'session_time_id' => $createdTimes[$timeIndex]->id,
                'max_participants' => 10,
            ]);

            $trainingSessions[] = $session;
            $pastCount++;
        }
        echo "      ✅ {$pastCount} completed sessions\n";

        // Today's session
        echo "   Creating today's session...\n";
        $todaySession = TrainingSession::create([
            'coach_id' => $coach->id,
            'date' => now()->format('Y-m-d'),
            'status' => TrainingSessionStatus::OPEN,
        ]);
        TrainingSessionSlot::create([
            'training_session_id' => $todaySession->id,
            'session_time_id' => $createdTimes[1]->id,
            'max_participants' => 10,
        ]);
        $trainingSessions[] = $todaySession;
        echo "      ✅ 1 today's session\n";

        // Future sessions (next 10 days)
        echo "   Creating future sessions (available for booking)...\n";
        $futureCount = 0;
        for ($i = 1; $i <= 10; $i++) {
            // Vary coach assignments
            $coachId = ($i % 2 == 0) ? $coach->id : $coach2->id;
            $timeIndex = ($i % count($createdTimes));

            $sessionDate = now()->addDays($i)->format('Y-m-d');
            $session = TrainingSession::create([
                'coach_id' => $coachId,
                'date' => $sessionDate,
                'status' => TrainingSessionStatus::OPEN,
            ]);

            TrainingSessionSlot::create([
                'training_session_id' => $session->id,
                'session_time_id' => $createdTimes[$timeIndex]->id,
                'max_participants' => 10,
            ]);

            $trainingSessions[] = $session;
            $futureCount++;
        }
        echo "      ✅ {$futureCount} future sessions\n";
        echo "   ✅ Total: " . count($trainingSessions) . " training sessions\n\n";

        // 9. Create Bookings with Attendance (for past sessions)
        echo "9️⃣ Creating Bookings & Attendance...\n";
        $bookingCount = 0;
        $attendanceCount = 0;
        $presentCount = 0;
        $absentCount = 0;

        // Book 7 past sessions dengan attendance (5 present, 2 absent)
        $pastSessions = array_filter($trainingSessions, function($s) {
            return $s->date->lt(now()->startOfDay());
        });

        foreach (array_slice($pastSessions, 0, 7) as $index => $session) {
            $slot = $session->slots()->with('sessionTime')->first();
            $booking = SessionBooking::create([
                'member_package_id' => $memberPackage->id,
                'training_session_slot_id' => $slot->id,
                'booked_by' => $memberUser->id,
                'status' => 'confirmed',
                'notes' => 'Test booking ' . ($index + 1),
            ]);
            $bookingCount++;

            // Create attendance (5 present, 2 absent)
            $isPresent = $index < 5;
            Attendance::create([
                'session_booking_id' => $booking->id,
                'status' => $isPresent ? 'present' : 'absent',
                'validated_by' => $session->coach->user_id,
                'validated_at' => Carbon::parse($session->date->format('Y-m-d') . ' ' . $slot->sessionTime->start_time),
                'notes' => $isPresent ? 'Hadir tepat waktu' : 'Tidak hadir tanpa keterangan',
            ]);
            $attendanceCount++;

            if ($isPresent) {
                $presentCount++;
            } else {
                $absentCount++;
            }
        }

        // Create 2 future bookings
        $futureSlot1 = $trainingSessions[12]->slots()->first();
        $futureBooking1 = SessionBooking::create([
            'member_package_id' => $memberPackage->id,
            'training_session_slot_id' => $futureSlot1->id,
            'booked_by' => $memberUser->id,
            'status' => 'confirmed',
            'notes' => 'Booking untuk sesi besok',
        ]);
        $bookingCount++;

        $futureSlot2 = $trainingSessions[15]->slots()->first();
        $futureBooking2 = SessionBooking::create([
            'member_package_id' => $memberPackage->id,
            'training_session_slot_id' => $futureSlot2->id,
            'booked_by' => $memberUser->id,
            'status' => 'confirmed',
            'notes' => 'Booking untuk minggu depan',
        ]);
        $bookingCount++;

        // Create 1 booking for child member
        $childPastSession = array_values($pastSessions)[0];
        $childSlot = $childPastSession->slots()->with('sessionTime')->first();
        $childBooking = SessionBooking::create([
            'member_package_id' => $childPackage->id,
            'training_session_slot_id' => $childSlot->id,
            'booked_by' => $memberUser->id,
            'status' => 'confirmed',
            'notes' => 'Booking untuk anak',
        ]);
        $bookingCount++;

        Attendance::create([
            'session_booking_id' => $childBooking->id,
            'status' => 'present',
            'validated_by' => $childPastSession->coach->user_id,
            'validated_at' => Carbon::parse($childPastSession->date->format('Y-m-d') . ' ' . $childSlot->sessionTime->start_time),
            'notes' => 'Hadir dan antusias',
        ]);
        $attendanceCount++;
        $presentCount++;

        echo "   ✅ Created {$bookingCount} bookings\n";
        echo "   ✅ Created {$attendanceCount} attendance records\n";
        echo "      - Present: {$presentCount}\n";
        echo "      - Absent: {$absentCount}\n";
        echo "      - Future bookings: 2\n\n";

        // 10. Create Achievements
        echo "🔟 Creating Achievements...\n";
        $achievements = [
            [
                'member_id' => $member->id,
                'type' => 'member',
                'title' => '🥇 Juara 1 Regional Championship 2026',
                'description' => 'Meraih medali emas pada Regional Archery Championship kategori compound bow dengan skor 695/720. Kompetisi diikuti oleh 45 peserta dari berbagai club.',
                'date' => now()->subMonths(1)->format('Y-m-d'),
                'photo_path' => null,
            ],
            [
                'member_id' => $member->id,
                'type' => 'member',
                'title' => '🌟 Best Newcomer Award 2025',
                'description' => 'Mendapatkan penghargaan Best Newcomer pada club annual tournament dengan performa luar biasa sebagai member baru.',
                'date' => now()->subMonths(2)->format('Y-m-d'),
                'photo_path' => null,
            ],
            [
                'member_id' => $member->id,
                'type' => 'member',
                'title' => '🎯 Perfect 10 Streak Achievement',
                'description' => 'Berhasil mendapatkan 8 arrow perfect 10 berturut-turut pada sesi latihan. New personal record!',
                'date' => now()->subWeeks(2)->format('Y-m-d'),
                'photo_path' => null,
            ],
            [
                'member_id' => $member->id,
                'type' => 'member',
                'title' => '💯 100 Sessions Milestone',
                'description' => 'Mencapai milestone 100 sesi latihan di club dengan attendance rate 95%. Dedicated member!',
                'date' => now()->subWeek()->format('Y-m-d'),
                'photo_path' => null,
            ],
            [
                'member_id' => $member->id,
                'type' => 'member',
                'title' => '🥈 Silver Medal - Provincial Games',
                'description' => 'Meraih medali perak pada Provincial Games 2025 kategori recurve bow dengan skor 672/720.',
                'date' => now()->subMonths(3)->format('Y-m-d'),
                'photo_path' => null,
            ],
            [
                'member_id' => $member->id,
                'type' => 'member',
                'title' => '🎖️ Most Improved Archer Award',
                'description' => 'Mendapatkan penghargaan Most Improved Archer dengan peningkatan skor rata-rata 15% dalam 3 bulan.',
                'date' => now()->subMonths(4)->format('Y-m-d'),
                'photo_path' => null,
            ],
            // Achievements untuk family member
            [
                'member_id' => $childMember1->id,
                'type' => 'member',
                'title' => '🏆 Junior Champion - U15 Category',
                'description' => 'Sarah meraih juara 1 kategori U15 pada Junior Archery Tournament dengan skor tertinggi 580/600.',
                'date' => now()->subWeeks(3)->format('Y-m-d'),
                'photo_path' => null,
            ],
            [
                'member_id' => $childMember1->id,
                'type' => 'member',
                'title' => '⭐ Rising Star Award',
                'description' => 'Mendapatkan penghargaan Rising Star sebagai atlet muda paling berpotensi dalam club.',
                'date' => now()->subMonths(1)->format('Y-m-d'),
                'photo_path' => null,
            ],
        ];

        foreach ($achievements as $achievementData) {
            Achievement::create($achievementData);
        }
        echo "   ✅ Created " . count($achievements) . " achievements\n";
        echo "      - Main member: 6 achievements\n";
        echo "      - Family member: 2 achievements\n\n";

        // Summary
        echo "========================================\n";
        echo "✨ SEEDING COMPLETED SUCCESSFULLY!\n";
        echo "========================================\n\n";
        echo "📊 COMPREHENSIVE TEST DATA SUMMARY:\n";
        echo "-------------------------------------------\n";
        echo "👤 LOGIN CREDENTIALS:\n";
        echo "   Email     : {$memberUser->email}\n";
        echo "   Password  : password123\n";
        echo "   Role      : Member\n\n";

        echo "👥 MEMBERS CREATED:\n";
        echo "   1. {$member->name} (Self) - Active\n";
        echo "   2. {$childMember1->name} - Active\n";
        echo "   3. {$childMember2->name} - Pending\n\n";

        echo "📦 PACKAGES:\n";
        echo "   • {$basicPackage->name} ({$basicPackage->session_count} sessions)\n";
        echo "   • {$package->name} ({$package->session_count} sessions)\n";
        echo "   • {$proPackage->name} ({$proPackage->session_count} sessions)\n\n";

        echo "🎫 MEMBER PACKAGES:\n";
        echo "   Active   : 2 packages\n";
        echo "   Expired  : 1 package\n";
        echo "   Primary Package:\n";
        echo "     - Total    : {$memberPackage->total_sessions} sessions\n";
        echo "     - Used     : {$memberPackage->used_sessions} sessions\n";
        echo "     - Remaining: " . ($memberPackage->total_sessions - $memberPackage->used_sessions) . " sessions\n";
        echo "     - Valid    : {$memberPackage->start_date->format('d M')} - {$memberPackage->end_date->format('d M Y')}\n\n";

        echo "📝 BOOKINGS & ATTENDANCE:\n";
        echo "   Total Bookings : {$bookingCount}\n";
        echo "   ✅ Present     : {$presentCount}\n";
        echo "   ❌ Absent      : {$absentCount}\n";
        echo "   🔮 Future      : 2\n\n";

        echo "🏆 ACHIEVEMENTS:\n";
        echo "   Total          : " . count($achievements) . "\n";
        echo "   Main Member    : 6 achievements\n";
        echo "   Family Member : 2 achievements\n\n";

        echo "🗓️  TRAINING SESSIONS:\n";
        echo "   Total          : " . count($trainingSessions) . "\n";
        echo "   Past (Closed)  : {$pastCount} sessions\n";
        echo "   Today (Open)   : 1 session\n";
        echo "   Future (Open)  : {$futureCount} sessions\n\n";

        echo "👨‍🏫 COACHES:\n";
        echo "   1. {$coach->name}\n";
        echo "   2. {$coach2->name}\n\n";

        echo "⏰ SESSION TIMES:\n";
        echo "   " . count($createdTimes) . " time slots across all coaches\n\n";

        echo "-------------------------------------------\n";
        echo "🚀 READY TO TEST!\n";
        echo "-------------------------------------------\n";
        echo "Login URL: http://localhost/login\n";
        echo "Dashboard: http://localhost/member/dashboard\n\n";
        echo "✅ All features ready for comprehensive testing:\n";
        echo "   • Dashboard overview with stats\n";
        echo "   • Active package display with quota\n";
        echo "   • Attendance history timeline\n";
        echo "   • Achievements showcase\n";
        echo "   • Profile management\n";
        echo "   • Membership (family members)\n";
        echo "   • Session booking system\n";
        echo "   • Package purchase flow\n";
        echo "========================================\n\n";
    }
}
