<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PropertiesController;
use App\Http\Controllers\Api\TenantProfileController;
use Illuminate\Support\Facades\Route;

// Public authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Landlord-only routes
    Route::middleware('landlord')->group(function () {

        Route::get('/properties', [PropertiesController::class, 'index']);
        Route::post('/properties', [PropertiesController::class, 'store']);
        Route::get('/properties/{id}', [PropertiesController::class, 'show']);
        Route::put('/properties/{id}', [PropertiesController::class, 'update']);
        Route::patch('/properties/{id}', [PropertiesController::class, 'update']);
        Route::delete('/properties/{id}', [PropertiesController::class, 'destroy']);

        // Upload multiple property images
        Route::post('/properties/{id}/images', [PropertiesController::class, 'uploadImages']);

    });

    // Tenant-only routes
    Route::middleware('tenant')->group(function () {

        Route::get('/tenant-profile', [TenantProfileController::class, 'show']);
        Route::post('/tenant-profile', [TenantProfileController::class, 'store']);
        Route::put('/tenant-profile', [TenantProfileController::class, 'update']);
        Route::patch('/tenant-profile', [TenantProfileController::class, 'update']);
        Route::delete('/tenant-profile', [TenantProfileController::class, 'destroy']);

    });

});
