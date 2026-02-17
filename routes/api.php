<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Coach\DashboardController as CoachDashboardController;
use App\Http\Controllers\Coach\MemberController as CoachMemberController;
use App\Http\Controllers\Coach\TrainingSessionController;
use App\Http\Controllers\Member\DashboardController;
use App\Http\Controllers\Member\RegistrationController;
use App\Http\Controllers\PublicSite\NewsController as PublicNewsController;
use App\Http\Controllers\PublicSite\AchievementController as PublicAchievementController;
use App\Modules\Admin\Attendance\Controllers\AttendanceController as AdminAttendanceController;
use App\Modules\Admin\Coach\Controllers\CoachController;
use App\Modules\Admin\Dashboard\Controllers\AchievementController as AdminAchievementController;
use App\Modules\Admin\Dashboard\Controllers\DashboardController as AdminDashboardController;
use App\Modules\Admin\Dashboard\Controllers\NewsController as AdminNewsController;
use App\Modules\Admin\Member\Controllers\MemberController as AdminMemberController;
use App\Modules\Admin\Member\Controllers\MemberPackageController as AdminMemberPackageController;
use App\Modules\Admin\Package\Controllers\PackageController;
use App\Modules\Admin\Report\Controllers\ReportController;
use App\Modules\Admin\Training\Controllers\TrainingSessionController as AdminTrainingSessionController;
use App\Modules\Admin\WhatsApp\Controllers\ReminderSettingsController;
use App\Modules\Admin\WhatsApp\Controllers\WhatsAppController as AdminWhatsAppController;
use App\Modules\Admin\WhatsApp\Controllers\WhatsAppSettingsController;
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

    });

    // Routes untuk COACH (pelatih)
    Route::middleware('role:coach')->prefix('coach')->group(function () {
        Route::get('/dashboard', [CoachDashboardController::class, 'index']);

        // Members list
        Route::get('members', [CoachMemberController::class, 'index']);

        // Training Sessions
        Route::get('training-sessions', [TrainingSessionController::class, 'index']);
        Route::get('training-sessions/{trainingSession}', [TrainingSessionController::class, 'show']);
        Route::delete('training-sessions/{trainingSession}', [TrainingSessionController::class, 'destroy']);
        Route::patch('training-sessions/{trainingSession}/quota', [TrainingSessionController::class, 'updateQuota']);
        Route::patch('training-sessions/{trainingSession}/coaches', [TrainingSessionController::class, 'updateCoaches']);
        Route::post('training-sessions/{trainingSession}/open', [TrainingSessionController::class, 'open']);
        Route::post('training-sessions/{trainingSession}/close', [TrainingSessionController::class, 'close']);
        Route::post('training-sessions/{trainingSession}/cancel', [TrainingSessionController::class, 'cancel']);

    });

    // Routes untuk ADMIN
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index']);

        // Master Packages
        Route::apiResource('packages', PackageController::class);
        Route::post('packages/{package}/restore', [PackageController::class, 'restore']);

        // News
        Route::apiResource('news', AdminNewsController::class);

        // Achievements
        Route::apiResource('achievements', AdminAchievementController::class);

        // Master Coaches
        Route::apiResource('coaches', CoachController::class);

        // Member domain
        Route::prefix('members')->group(function () {
            Route::get('/', [AdminMemberController::class, 'index']);
            Route::post('/', [AdminMemberController::class, 'store']);
            Route::get('/{member}', [AdminMemberController::class, 'show']);
            Route::match(['put', 'patch'], '/{member}', [AdminMemberController::class, 'update']);
            Route::delete('/{member}', [AdminMemberController::class, 'destroy']);
            Route::post('/{id}/restore', [AdminMemberController::class, 'restore']);

            Route::post('/{member}/assign-package', [AdminMemberPackageController::class, 'assignPackage']);
            Route::get('/{member}/packages', [AdminMemberPackageController::class, 'getMemberPackages']);
        });

        Route::get('member-packages', [AdminMemberPackageController::class, 'index']);
        Route::get('member-packages/{memberPackage}', [AdminMemberPackageController::class, 'show']);

        // Pending Members
        Route::get('pending-members', [RegistrationController::class, 'pendingMembers']);

        // Training & Attendance (admin)
        Route::get('training-sessions', [AdminTrainingSessionController::class, 'index']);
        Route::post('training-sessions', [AdminTrainingSessionController::class, 'store']);
        Route::get('training-sessions/{trainingSession}', [AdminTrainingSessionController::class, 'show']);
        Route::delete('training-sessions/{trainingSession}', [AdminTrainingSessionController::class, 'destroy']);
        Route::patch('training-session-slots/{trainingSessionSlot}/coaches', [AdminTrainingSessionController::class, 'updateSlotCoaches']);

        Route::get('attendance/active-members', [AdminAttendanceController::class, 'activeMembers']);
        Route::get('training-sessions/{trainingSession}/attendances', [AdminAttendanceController::class, 'index']);
        Route::post('training-sessions/{trainingSession}/attendances', [AdminAttendanceController::class, 'store']);

        // WhatsApp Blast & Logs
        Route::get('whatsapp/recipients-count', [AdminWhatsAppController::class, 'recipientsCount']);
        Route::post('whatsapp/blast', [AdminWhatsAppController::class, 'blast']);
        Route::get('whatsapp/logs', [AdminWhatsAppController::class, 'logs']);
        Route::get('whatsapp/logs/export', [AdminWhatsAppController::class, 'export']);
        Route::get('whatsapp/settings', [WhatsAppSettingsController::class, 'show']);
        Route::put('whatsapp/settings', [WhatsAppSettingsController::class, 'update']);
        Route::post('whatsapp/settings/test-connection', [WhatsAppSettingsController::class, 'testConnection']);
        Route::get('whatsapp/reminder-settings', [ReminderSettingsController::class, 'show']);
        Route::put('whatsapp/reminder-settings', [ReminderSettingsController::class, 'update']);

        // Report Export
        Route::get('reports/export', [ReportController::class, 'export']);
    });
});
