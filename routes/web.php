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
use App\Models\SessionTime;
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
        Route::get('/members', fn() => view('dashboards.admin.members'))->name('members');
        Route::get('/coaches', fn() => view('dashboards.admin.coaches'))->name('coaches');
        Route::get('/packages', fn() => view('dashboards.admin.packages'))->name('packages');
        Route::get('/member-packages', fn() => view('dashboards.admin.member-packages'))->name('member-packages');
        Route::get('/sessions/create', fn() => view('dashboards.admin.sessions-create', [
            'sessionTimes' => SessionTime::query()->active()->orderBy('start_time')->get(['id', 'name', 'start_time', 'end_time']),
            'coaches' => Coach::query()->orderBy('name')->get(['id', 'name']),
        ]))->name('sessions.create');
        Route::get('/sessions/{id}/edit', fn($id) => view('dashboards.admin.sessions-edit', [
            'id' => $id,
            'coaches' => Coach::query()->orderBy('name')->get(['id', 'name']),
        ]))->name('sessions.edit');
        Route::get('/bookings/create', fn() => view('dashboards.admin.bookings-create'))->name('bookings.create');
        Route::get('/news', fn() => view('dashboards.admin.news'))->name('news');
        Route::get('/achievements', fn() => view('dashboards.admin.achievements'))->name('achievements');
    });
    // Coach routes
    Route::prefix('coach')->name('coach.')->middleware('role:coach')->group(function () {
        Route::get('/sessions', fn() => view('dashboards.coach.sessions'))->name('sessions.index');
        Route::get('/bookings/create', fn() => view('dashboards.coach.bookings-create'))->name('bookings.create');
        Route::get('/sessions/create', fn() => view('dashboards.coach.sessions-create', [
            'sessionTimes' => SessionTime::query()->active()->orderBy('start_time')->get(['id', 'name', 'start_time', 'end_time']),
            'coaches' => Coach::query()->orderBy('name')->get(['id', 'name']),
            'myCoachId' => auth()->user()?->coach?->id,
        ]))->name('sessions.create');
        Route::get('/sessions/{id}/edit', fn($id) => view('dashboards.coach.sessions-edit', [
            'id' => $id,
            'coaches' => Coach::query()->orderBy('name')->get(['id', 'name']),
            'myCoachId' => auth()->user()?->coach?->id,
        ]))->name('sessions.edit');
        Route::get('/attendance', fn() => view('dashboards.coach.attendance'))->name('attendance.index');
        Route::post('/attendance', fn() => redirect()->route('coach.attendance.index'))->name('attendance.store');
        Route::get('/change-password', function() {
            $user = auth()->user();
            $coach = $user->coach;
            return view('dashboards.coach.change-password', compact('user', 'coach'));
        })->name('change-password');
        Route::post('/change-password', [\App\Http\Controllers\Coach\ProfileController::class, 'updatePassword'])->name('change-password.password');
    });
    // Member routes
     Route::prefix('member')->name('member.')->middleware('role:member')->group(function () {
        Route::get('/dashboard', fn() => view('dashboards.member.dashboard'))->name('dashboard');
        Route::get('/profile', fn() => view('dashboards.member.profile'))->name('profile');
        Route::get('/bookings', fn() => view('dashboards.member.bookings'))->name('bookings');
        Route::get('/bookings/create', fn() => view('dashboards.member.bookings-create'))->name('bookings.create');
        Route::get('/membership', fn() => view('dashboards.member.membership'))->name('membership');
        Route::get('/achievements', fn() => view('dashboards.member.achievements'))->name('achievements');
    });
});

