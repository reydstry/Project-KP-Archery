<?php

use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MemberPackageController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Member\RegistrationController;
use Illuminate\Support\Facades\Route;

// Public routes - Authentication
Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

// Protected routes (perlu login)
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', LogoutController::class);
    Route::get('/me', ProfileController::class);
    Route::post('/change-password', ChangePasswordController::class);

    // Routes untuk MEMBER (orang tua / member dewasa)
    Route::middleware('role:member')->prefix('member')->group(function () {
        Route::get('/dashboard', function () {
            return response()->json(['message' => 'Member dashboard']);
        });
        
        // Member Registration
        Route::post('/register-self', [RegistrationController::class, 'registerSelf']);
        Route::post('/register-child', [RegistrationController::class, 'registerChild']);
        Route::get('/my-members', [RegistrationController::class, 'myMembers']);
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