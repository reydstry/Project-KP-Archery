<?php

use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// Public routes - Authentication
Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

// Protected routes (perlu login)
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', LogoutController::class);
    Route::get('/me', ProfileController::class);

    // Routes untuk MEMBER (orang tua / member dewasa)
    Route::middleware('role:member')->prefix('member')->group(function () {
        Route::get('/dashboard', function () {
            return response()->json(['message' => 'Member dashboard']);
        });
    });

    // Routes untuk COACH (pelatih)
    Route::middleware('role:coach')->prefix('coach')->group(function () {
        Route::get('/sessions', function () {
            return response()->json(['message' => 'Coach sessions']);
        });
    });

    // Routes untuk ADMIN
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return response()->json(['message' => 'Admin dashboard']);
        });
        
        // Master Packages
        Route::apiResource('packages', PackageController::class);
        
        // Master Coaches
        Route::apiResource('coaches', CoachController::class);
    });
});