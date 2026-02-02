<?php

use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MemberPackageController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\AchievementController as AdminAchievementController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Coach\AttendanceController;
use App\Http\Controllers\Coach\TrainingSessionController;
use App\Http\Controllers\Member\DashboardController;
use App\Http\Controllers\Member\RegistrationController;
use App\Http\Controllers\Member\SessionBookingController;
use App\Http\Controllers\PublicSite\NewsController as PublicNewsController;
use App\Http\Controllers\PublicSite\AchievementController as PublicAchievementController;
use Illuminate\Support\Facades\Route;

// Public routes - Authentication
Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

// Public routes - News (for company profile)
Route::get('/news', [PublicNewsController::class, 'index']);
Route::get('/news/{news}', [PublicNewsController::class, 'show']);

// Public routes - Achievements (for company profile)
Route::get('/achievements', [PublicAchievementController::class, 'index']);
Route::get('/achievements/{achievement}', [PublicAchievementController::class, 'show']);

// Protected routes (perlu login)
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', LogoutController::class);
    Route::get('/me', ProfileController::class);
    Route::post('/change-password', ChangePasswordController::class);

    // Routes untuk MEMBER (orang tua / member dewasa)
    Route::middleware('role:member')->prefix('member')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        
        // Member Registration
        Route::post('/register-self', [RegistrationController::class, 'registerSelf']);
        Route::post('/register-child', [RegistrationController::class, 'registerChild']);
        Route::get('/my-members', [RegistrationController::class, 'myMembers']);
        
        // Session Bookings
        Route::get('bookings', [SessionBookingController::class, 'index']);
        Route::post('bookings', [SessionBookingController::class, 'store']);
        Route::get('bookings/{sessionBooking}', [SessionBookingController::class, 'show']);
        Route::post('bookings/{sessionBooking}/cancel', [SessionBookingController::class, 'cancel']);
    });

    // Routes untuk COACH (pelatih)
    Route::middleware('role:coach')->prefix('coach')->group(function () {
        Route::get('/dashboard', function () {
            return response()->json(['message' => 'Coach dashboard']);
        });
        
        // Training Sessions
        Route::get('training-sessions', [TrainingSessionController::class, 'index']);
        Route::post('training-sessions', [TrainingSessionController::class, 'store']);
        Route::get('training-sessions/{trainingSession}', [TrainingSessionController::class, 'show']);
        Route::patch('training-sessions/{trainingSession}/quota', [TrainingSessionController::class, 'updateQuota']);
        Route::post('training-sessions/{trainingSession}/open', [TrainingSessionController::class, 'open']);
        Route::post('training-sessions/{trainingSession}/close', [TrainingSessionController::class, 'close']);
        Route::post('training-sessions/{trainingSession}/cancel', [TrainingSessionController::class, 'cancel']);
        
        // Attendance
        Route::get('training-sessions/{trainingSession}/bookings', [AttendanceController::class, 'getSessionBookings']);
        Route::post('bookings/{sessionBooking}/attendance', [AttendanceController::class, 'validateAttendance']);
        Route::patch('bookings/{sessionBooking}/attendance', [AttendanceController::class, 'update']);
    });

    // Routes untuk ADMIN
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return response()->json(['message' => 'Admin dashboard']);
        });
        
        // Master Packages
        Route::apiResource('packages', PackageController::class);

        // News
        Route::apiResource('news', AdminNewsController::class);

        // Achievements
        Route::apiResource('achievements', AdminAchievementController::class);
        
        // Master Coaches
        Route::apiResource('coaches', CoachController::class);
        
        // Master Members
        Route::apiResource('members', MemberController::class);
        Route::post('members/{id}/restore', [MemberController::class, 'restore']);
        
        // Member Packages
        Route::get('member-packages', [MemberPackageController::class, 'index']);
        Route::post('members/{member}/assign-package', [MemberPackageController::class, 'assignPackage']);
        Route::get('member-packages/{memberPackage}', [MemberPackageController::class, 'show']);
        Route::get('members/{member}/packages', [MemberPackageController::class, 'getMemberPackages']);
        
        // Pending Members
        Route::get('pending-members', [RegistrationController::class, 'pendingMembers']);
    });
});