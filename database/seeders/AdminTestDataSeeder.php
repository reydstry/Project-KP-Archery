<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;
use App\Models\Package;
use App\Models\MemberPackage;
use App\Models\News;
use App\Models\Achievement;
use App\Models\Coach;
use App\Enums\UserRoles;
use App\Enums\StatusMember;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@clubpanahan.com'],
            [
                'name' => 'Admin Club Panahan',
                'role' => UserRoles::ADMIN->value,
                'phone' => '081234567890',
                'password' => Hash::make('admin123'),
            ]
        );

        // Create Coach Users
        $coaches = [
            [
                'name' => 'Budi Gunawan',
                'email' => 'budi.coach@clubpanahan.com',
                'phone' => '081234567891',
                'role' => UserRoles::COACH->value,
                'password' => Hash::make('coach123'),
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.coach@clubpanahan.com',
                'phone' => '081234567892',
                'role' => UserRoles::COACH->value,
                'password' => Hash::make('coach123'),
            ],
            [
                'name' => 'Andi Pratama',
                'email' => 'andi.coach@clubpanahan.com',
                'phone' => '081234567893',
                'role' => UserRoles::COACH->value,
                'password' => Hash::make('coach123'),
            ],
        ];

        foreach ($coaches as $coachData) {
            User::firstOrCreate(
                ['email' => $coachData['email']],
                $coachData
            );
        }

        // Create Packages
        $packages = [
            [
                'name' => 'Basic Package',
                'description' => 'Perfect for beginners who want to learn archery fundamentals',
                'price' => 500000,
                'duration_days' => 30,
                'session_count' => 8,
            ],
            [
                'name' => 'Standard Package',
                'description' => 'Ideal for intermediate archers looking to improve their skills',
                'price' => 900000,
                'duration_days' => 60,
                'session_count' => 16,
            ],
            [
                'name' => 'Premium Package',
                'description' => 'Comprehensive training for serious archers with competition goals',
                'price' => 1500000,
                'duration_days' => 90,
                'session_count' => 24,
            ],
            [
                'name' => 'Professional Package',
                'description' => 'Advanced training program for competitive archers',
                'price' => 2500000,
                'duration_days' => 180,
                'session_count' => 48,
            ],
        ];

        foreach ($packages as $packageData) {
            Package::firstOrCreate(
                ['name' => $packageData['name']],
                $packageData
            );
        }

        // Create Member Users and Members
        $memberUsers = [
            [
                'name' => 'Rudi Hartono',
                'email' => 'rudi@example.com',
                'phone' => '081234567894',
                'role' => UserRoles::MEMBER->value,
                'password' => Hash::make('member123'),
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@example.com',
                'phone' => '081234567895',
                'role' => UserRoles::MEMBER->value,
                'password' => Hash::make('member123'),
            ],
            [
                'name' => 'Agus Setiawan',
                'email' => 'agus@example.com',
                'phone' => '081234567896',
                'role' => UserRoles::MEMBER->value,
                'password' => Hash::make('member123'),
            ],
            [
                'name' => 'Linda Wijaya',
                'email' => 'linda@example.com',
                'phone' => '081234567897',
                'role' => UserRoles::MEMBER->value,
                'password' => Hash::make('member123'),
            ],
            [
                'name' => 'Bambang Sutrisno',
                'email' => 'bambang@example.com',
                'phone' => '081234567898',
                'role' => UserRoles::MEMBER->value,
                'password' => Hash::make('member123'),
            ],
            [
                'name' => 'Sari Indah',
                'email' => 'sari@example.com',
                'phone' => '081234567899',
                'role' => UserRoles::MEMBER->value,
                'password' => Hash::make('member123'),
            ],
        ];

        foreach ($memberUsers as $index => $memberUserData) {
            $user = User::firstOrCreate(
                ['email' => $memberUserData['email']],
                $memberUserData
            );

            // Create corresponding Member record
            // First 3 members will get packages (active), rest are pending
            Member::firstOrCreate(
                ['user_id' => $user->id, 'is_self' => true],
                [
                    'user_id' => $user->id,
                    'registered_by' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'is_self' => true,
                    'is_active' => true,
                    'status' => $index < 3 ? StatusMember::STATUS_ACTIVE->value : StatusMember::STATUS_PENDING->value,
                ]
            );
        }

        // Create News Articles
        $newsArticles = [
            [
                'title' => 'Opening of New Archery Training Facility',
                'content' => 'We are excited to announce the opening of our brand new state-of-the-art archery training facility. The facility features 20 indoor lanes, modern equipment, and professional coaching staff. Join us for the grand opening ceremony on December 15th!',
                'publish_date' => now()->subDays(10)->format('Y-m-d'),
                'photo_path' => 'https://images.unsplash.com/photo-1589487391730-58f20eb2c308?w=800',
            ],
            [
                'title' => 'Regional Archery Championship 2024',
                'content' => 'Our club will be hosting the Regional Archery Championship next month. Athletes from across the region will compete in various categories. Registration is now open for all interested participants. Don\'t miss this great opportunity!',
                'publish_date' => now()->subDays(5)->format('Y-m-d'),
                'photo_path' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
            ],
            [
                'title' => 'New Training Schedule for 2024',
                'content' => 'Starting next month, we will be implementing a new training schedule to better accommodate our members. Morning sessions will run from 6-9 AM, afternoon sessions from 2-5 PM, and evening sessions from 6-9 PM. Please check with your coach for specific timings.',
                'publish_date' => now()->subDays(3)->format('Y-m-d'),
                'photo_path' => null,
            ],
            [
                'title' => 'Congratulations to Our National Team Members',
                'content' => 'We are proud to announce that three of our members have been selected for the national archery team. They will represent our country in the upcoming Asian Games. Let\'s show them our support!',
                'publish_date' => now()->subDays(1)->format('Y-m-d'),
                'photo_path' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800',
            ],
            [
                'title' => 'Special Workshop: Olympic Archery Techniques',
                'content' => 'Join us for an exclusive workshop on Olympic archery techniques led by international coach Michael Chen. Limited seats available. Register before December 20th to secure your spot. Workshop fee: Rp 750,000 for members, Rp 1,000,000 for non-members.',
                'publish_date' => now()->format('Y-m-d'),
                'photo_path' => null,
            ],
        ];

        foreach ($newsArticles as $newsData) {
            News::firstOrCreate(
                ['title' => $newsData['title']],
                $newsData
            );
        }

        // Create Achievements
        $members = Member::where('status', StatusMember::STATUS_ACTIVE->value)->get();
        
        $memberAchievements = [
            [
                'type' => 'member',
                'member_id' => $members->first()?->id,
                'title' => '1st Place - National Archery Championship 2024',
                'description' => 'Won gold medal in the recurve bow category at the National Archery Championship with a score of 686/720 points.',
                'date' => now()->subMonths(2)->format('Y-m-d'),
                'photo_path' => 'https://images.unsplash.com/photo-1567694621732-fcf6b4b8b059?w=800',
            ],
            [
                'type' => 'member',
                'member_id' => $members->skip(1)->first()?->id,
                'title' => '2nd Place - Regional Championship',
                'description' => 'Secured silver medal in the compound bow category with outstanding performance and consistent accuracy throughout the competition.',
                'date' => now()->subMonths(1)->format('Y-m-d'),
                'photo_path' => 'https://images.unsplash.com/photo-1565105485295-39ea0f6f3e39?w=800',
            ],
            [
                'type' => 'member',
                'member_id' => $members->skip(2)->first()?->id,
                'title' => 'Best Newcomer Award 2024',
                'description' => 'Recognized as the most promising newcomer in the club with rapid skill development and dedication to training.',
                'date' => now()->subWeeks(2)->format('Y-m-d'),
                'photo_path' => null,
            ],
        ];

        foreach ($memberAchievements as $achievementData) {
            if ($achievementData['member_id']) {
                Achievement::firstOrCreate(
                    [
                        'type' => $achievementData['type'],
                        'member_id' => $achievementData['member_id'],
                        'title' => $achievementData['title'],
                    ],
                    $achievementData
                );
            }
        }

        $clubAchievements = [
            [
                'type' => 'club',
                'member_id' => null,
                'title' => 'Best Archery Club in Region - 2024',
                'description' => 'Awarded the prestigious title of Best Archery Club in the region for outstanding training programs, facilities, and athlete achievements.',
                'date' => now()->subMonths(3)->format('Y-m-d'),
                'photo_path' => 'https://images.unsplash.com/photo-1513885535751-8b9238bd345a?w=800',
            ],
            [
                'type' => 'club',
                'member_id' => null,
                'title' => 'Excellence in Youth Development Program',
                'description' => 'Recognized by the National Archery Federation for our exceptional youth development program that has produced numerous talented young archers.',
                'date' => now()->subMonths(4)->format('Y-m-d'),
                'photo_path' => null,
            ],
            [
                'type' => 'club',
                'member_id' => null,
                'title' => '25 Years of Excellence',
                'description' => 'Celebrating 25 years of promoting archery excellence and producing world-class athletes who represent our nation with pride.',
                'date' => now()->subMonths(6)->format('Y-m-d'),
                'photo_path' => 'https://images.unsplash.com/photo-1569443693539-175ea9f007e8?w=800',
            ],
        ];

        foreach ($clubAchievements as $achievementData) {
            Achievement::firstOrCreate(
                [
                    'type' => $achievementData['type'],
                    'title' => $achievementData['title'],
                ],
                $achievementData
            );
        }

        // Create Coaches
        $coachUsers = User::where('role', UserRoles::COACH->value)->get();
        foreach ($coachUsers as $coachUser) {
            Coach::firstOrCreate(
                ['user_id' => $coachUser->id],
                [
                    'user_id' => $coachUser->id,
                    'name' => $coachUser->name,
                    'phone' => $coachUser->phone,
                ]
            );
        }

        // Create Member Packages
        $adminUser = User::where('role', UserRoles::ADMIN->value)->first();
        $activeMembers = Member::where('status', StatusMember::STATUS_ACTIVE->value)->get();
        $allPackages = Package::all();

        if ($adminUser && $activeMembers->isNotEmpty() && $allPackages->isNotEmpty()) {
            // Assign packages to first 3 active members
            foreach ($activeMembers->take(3) as $index => $member) {
                $package = $allPackages->skip($index % $allPackages->count())->first();
                
                // Create different scenarios
                if ($index === 0) {
                    // Active package with some sessions used
                    MemberPackage::firstOrCreate(
                        [
                            'member_id' => $member->id,
                            'package_id' => $package->id,
                        ],
                        [
                            'member_id' => $member->id,
                            'package_id' => $package->id,
                            'total_sessions' => $package->session_count,
                            'used_sessions' => (int)($package->session_count * 0.4), // 40% used
                            'start_date' => Carbon::now()->subDays(15),
                            'end_date' => Carbon::now()->addDays($package->duration_days - 15),
                            'is_active' => true,
                            'validated_by' => $adminUser->id,
                            'validated_at' => Carbon::now()->subDays(15),
                        ]
                    );
                } elseif ($index === 1) {
                    // Recently started package
                    MemberPackage::firstOrCreate(
                        [
                            'member_id' => $member->id,
                            'package_id' => $package->id,
                        ],
                        [
                            'member_id' => $member->id,
                            'package_id' => $package->id,
                            'total_sessions' => $package->session_count,
                            'used_sessions' => 2,
                            'start_date' => Carbon::now()->subDays(5),
                            'end_date' => Carbon::now()->addDays($package->duration_days - 5),
                            'is_active' => true,
                            'validated_by' => $adminUser->id,
                            'validated_at' => Carbon::now()->subDays(5),
                        ]
                    );
                } elseif ($index === 2) {
                    // Expired package
                    MemberPackage::firstOrCreate(
                        [
                            'member_id' => $member->id,
                            'package_id' => $package->id,
                        ],
                        [
                            'member_id' => $member->id,
                            'package_id' => $package->id,
                            'total_sessions' => $package->session_count,
                            'used_sessions' => $package->session_count, // All sessions used
                            'start_date' => Carbon::now()->subDays($package->duration_days + 10),
                            'end_date' => Carbon::now()->subDays(10),
                            'is_active' => false,
                            'validated_by' => $adminUser->id,
                            'validated_at' => Carbon::now()->subDays($package->duration_days + 10),
                        ]
                    );
                }
            }
        }

        $this->command->info('✅ Admin test data seeded successfully!');
        $this->command->info('');
        $this->command->info('📊 Test Accounts:');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('👤 Admin: admin@clubpanahan.com / admin123');
        $this->command->info('👥 Coach: budi.coach@clubpanahan.com / coach123');
        $this->command->info('👥 Coach: siti.coach@clubpanahan.com / coach123');
        $this->command->info('👥 Coach: andi.coach@clubpanahan.com / coach123');
        $this->command->info('🎯 Member: rudi@example.com / member123 (Active with package)');
        $this->command->info('🎯 Member: dewi@example.com / member123 (Active with package)');
        $this->command->info('🎯 Member: agus@example.com / member123 (Active with expired package)');
        $this->command->info('⏳ Member: linda@example.com / member123 (Pending - no package)');
        $this->command->info('⏳ Member: bambang@example.com / member123 (Pending - no package)');
        $this->command->info('⏳ Member: sari@example.com / member123 (Pending - no package)');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('');
        $this->command->info('📦 Packages created: ' . Package::count());
        $this->command->info('👥 Total Members: ' . Member::count());
        $this->command->info('   ✅ Active: ' . Member::where('status', StatusMember::STATUS_ACTIVE->value)->count());
        $this->command->info('   ⏳ Pending: ' . Member::where('status', StatusMember::STATUS_PENDING->value)->count());
        $this->command->info('🎓 Coaches created: ' . Coach::count());
        $this->command->info('📰 News articles: ' . News::count());
        $this->command->info('🏆 Achievements: ' . Achievement::count());
        $this->command->info('📋 Member Packages: ' . MemberPackage::count());
        $this->command->info('');
        $this->command->info('💡 Test assigning packages to pending members to see status change to active!');
    }
}
