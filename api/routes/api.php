<?php

use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication routes with rate limiting
Route::middleware('throttle.auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// API v1 routes
Route::prefix('v1')->name('api.v1.')->group(function () {
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // User profile route
        Route::get('/user', function (Request $request) {
            return $request->user();
        })->name('user.profile');

        // Resource routes
        Route::apiResource('users', UserController::class);
    });
});

// Rate limiting for API routes
Route::middleware(['throttle:api'])->group(function () {
    // Add any routes that need rate limiting here
});
