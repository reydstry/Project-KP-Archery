<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Users\MemberController;
use App\Http\Controllers\Users\AdminController;
use App\Http\Controllers\Users\CoachController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (perlu login)
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Routes untuk MEMBER (orang tua / member dewasa)
    Route::middleware('role:member')->prefix('member')->group(function () {
        Route::get('/dashboard', [MemberController::class, 'dashboard']);
        Route::post('/register-child', [MemberController::class, 'registerChild']);
        Route::post('/register-self', [MemberController::class, 'registerSelf']);
        Route::get('/my-members', [MemberController::class, 'myMembers']);
        Route::post('/book-session', [MemberController::class, 'bookSession']);
    });

    // Routes untuk COACH (pelatih)
    Route::middleware('role:coach')->prefix('coach')->group(function () {
        Route::get('/sessions', [CoachController::class, 'sessions']);
        Route::post('/validate-attendance', [CoachController::class, 'validateAttendance']);
        Route::post('/book-member', [CoachController::class, 'bookMember']);
    });

    // Routes untuk ADMIN
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/member-dashboard/{member}', [AdminController::class, 'memberDashboard']);
        Route::post('/validate-package/{memberPackage}', [AdminController::class, 'validatePackage']);
    });
});