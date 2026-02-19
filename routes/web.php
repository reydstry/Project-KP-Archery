<?php

use App\Http\Controllers\Auth\WebLoginController;
use App\Http\Controllers\Auth\WebLogoutController;
use App\Http\Controllers\Auth\WebRegisterController;
use App\Http\Controllers\Auth\WebForgotPasswordController;
use App\Http\Controllers\Auth\WebResetPasswordController;
use App\Http\Controllers\Auth\WebSetPasswordController;
use App\Http\Controllers\Auth\GoogleRedirectController;
use App\Http\Controllers\Auth\GoogleCallbackController;
use App\Http\Controllers\WebDashboardController;
use App\Http\Controllers\LanguageController;
use App\Modules\Admin\Attendance\Controllers\AttendancePageController as AdminAttendancePageController;
use App\Modules\Admin\Member\Controllers\MemberPageController as AdminMemberPageController;
use App\Modules\Admin\Report\Controllers\ReportPageController as AdminReportPageController;
use App\Modules\Admin\Training\Controllers\TrainingPageController as AdminTrainingPageController;
use App\Modules\Admin\WhatsApp\Controllers\WhatsAppPageController as AdminWhatsAppPageController;
use App\Models\SessionTime;
use App\Models\Coach;
use Illuminate\Support\Facades\Route;

// Language switching
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('/', function () {
    return view('pages.beranda');
})->name('beranda');

Route::get('/tentang-kami', function () {
    return view('pages.tentang-kami');
})->name('tentang-kami');

Route::get('/program', function () {
    return view('pages.program');
})->name('program');

Route::get('/galeri', function () {
    return view('pages.galeri');
})->name('galeri');

Route::get('/kontak', function () {
    return view('pages.kontak');
})->name('kontak');


// dummay data berita detail
use App\Http\Controllers\BeritaController;
Route::get('/berita/{id}', [BeritaController::class, 'show'])->name('berita.detail');

// Guest only
Route::middleware('guest')->group(function () {
    Route::get('/register', [WebRegisterController::class, 'create'])->name('register');
    Route::post('/register', [WebRegisterController::class, 'store'])->name('register.post');

    Route::get('/login', [WebLoginController::class, 'create'])->name('login');
    Route::post('/login', [WebLoginController::class, 'store'])->middleware('throttle:login')->name('login.post');

    Route::get('/forgot-password', [WebForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [WebForgotPasswordController::class, 'store'])->name('password.email')->middleware('throttle:forgot-password');

    Route::get('/reset-password/{token}', [WebResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [WebResetPasswordController::class, 'store'])->name('password.update');

    Route::get('/auth/google/redirect', GoogleRedirectController::class)->name('auth.google.redirect');
    Route::get('/auth/google/callback', GoogleCallbackController::class)->name('auth.google.callback');
});

// Authenticated
Route::middleware('auth')->group(function () {
    Route::post('/logout', WebLogoutController::class)->name('logout');

    Route::get('/set-password', [WebSetPasswordController::class, 'create'])->name('password.set');
    Route::post('/set-password', [WebSetPasswordController::class, 'store'])->name('password.store');

    // Main dashboard router
    Route::get('/dashboard', WebDashboardController::class)->name('dashboard');

    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        // Member domain (modular)
        Route::prefix('member')->name('member.')->group(function () {
            Route::get('/members', [AdminMemberPageController::class, 'index'])->name('index');
            Route::get('/packages', [AdminMemberPageController::class, 'packages'])->name('packages');
        });

        // Backward-compatible named routes
        Route::get('/members', [AdminMemberPageController::class, 'index'])->name('members');
        Route::get('/member-packages', [AdminMemberPageController::class, 'packages'])->name('member-packages');

        // Coach domain
        Route::get('/coaches', fn() => view('dashboards.admin.coach.coaches'))->name('coaches');

        // Package domain
        Route::get('/packages', fn() => view('dashboards.admin.package.packages'))->name('packages');

        // Training operations
        Route::get('/sessions', [AdminTrainingPageController::class, 'sessionsIndex'])->name('sessions.index');
        Route::get('/sessions/create', [AdminTrainingPageController::class, 'sessionsCreate'])->name('sessions.create');
        Route::get('/sessions/{id}/edit', [AdminTrainingPageController::class, 'sessionsEdit'])->name('sessions.edit');
        Route::get('/training/slots', [AdminTrainingPageController::class, 'slotCoachAssignment'])->name('training.slots');
        Route::get('/training/attendance', [AdminAttendancePageController::class, 'attendanceManagement'])->name('training.attendance');
        Route::get('/sessions/{id}/attendance', [AdminAttendancePageController::class, 'sessionAttendanceInput'])->name('sessions.attendance');

        // Website
        Route::get('/news', fn() => view('dashboards.admin.dashboard.news'))->name('news');
        Route::get('/achievements', fn() => view('dashboards.admin.dashboard.achievements'))->name('achievements');

        // Communication
        Route::get('/communication/wa-blast', [AdminWhatsAppPageController::class, 'waBlast'])->name('communication.wa-blast');
        Route::get('/communication/logs', [AdminWhatsAppPageController::class, 'waLogs'])->name('communication.logs');

        // Reporting
        Route::get('/reports/monthly', [AdminReportPageController::class, 'monthlyRecap'])->name('reports.monthly');
        Route::get('/reports/export', [AdminReportPageController::class, 'exportExcel'])->name('reports.export');

        // Settings
        Route::get('/settings/wa-api', [AdminWhatsAppPageController::class, 'waApiSettings'])->name('settings.wa-api');
        Route::get('/settings/reminder', [AdminWhatsAppPageController::class, 'reminderSettings'])->name('settings.reminder');
    });
    // Coach routes
    Route::prefix('coach')->name('coach.')->middleware('role:coach')->group(function () {
        Route::get('/sessions', fn() => view('dashboards.coach.sessions'))->name('sessions.index');
        Route::get('/attendance', fn() => view('dashboards.coach.attendance'))->name('attendance.index');
        Route::get('/sessions/{id}/edit', fn($id) => view('dashboards.coach.sessions-edit', [
            'id' => $id,
            'coaches' => Coach::query()->orderBy('name')->get(['id', 'name']),
            'myCoachId' => auth()->user()?->coach?->id,
        ]))->name('sessions.edit');
        Route::get('/change-password', function() {
            $user = auth()->user();
            $coach = $user->coach;
            return view('dashboards.coach.change-password', compact('user', 'coach'));
        })->name('change-password');
        Route::post('/change-password', [\App\Http\Controllers\Coach\ProfileController::class, 'updatePassword'])->name('change-password.password');
    });
    // Member routes
     Route::prefix('member')->name('member.')->middleware('role:member')->group(function () {
        Route::get('/dashboard', fn() => view('components.dashboards.member.dashboard'))->name('dashboard');
        Route::get('/profile', fn() => view('components.dashboards.member.profile'))->name('profile');
        Route::get('/membership', fn() => view('components.dashboards.member.membership'))->name('membership');
        Route::get('/achievements', fn() => view('components.dashboards.member.achievements'))->name('achievements');
    });
});

