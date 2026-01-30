<?php
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

// ===== ROUTES UNTUK LOGIN & REGISTER (FRONTEND ONLY) =====
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Route POST (untuk submit form - nanti backend yang handle)
Route::post('/login', function () {
    return back()->withErrors(['email' => 'Backend belum siap. Hubungi backend developer.']);
})->name('login.post');

Route::post('/register', function () {
    return back()->withErrors(['error' => 'Backend belum siap. Hubungi backend developer.']);
})->name('register.post');

Route::post('/forgot-password', function () {
    return back()->with('status', 'Reset link sent! Check your email (Backend belum siap).');
})->name('password.email');