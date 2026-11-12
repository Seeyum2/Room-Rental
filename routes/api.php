
<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PropertiesController;
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

    });
});
