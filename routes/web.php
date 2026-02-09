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
use Illuminate\Support\Facades\Route;

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
        Route::get('/news', fn() => view('dashboards.admin.news'))->name('news');
        Route::get('/achievements', fn() => view('dashboards.admin.achievements'))->name('achievements');
    });
});