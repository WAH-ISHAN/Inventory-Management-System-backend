<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CupboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PlaceController;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('cupboards', CupboardController::class)->only(['index', 'show']);
    Route::apiResource('places', PlaceController::class)->only(['index', 'show']);
    Route::apiResource('items', ItemController::class)->only(['index', 'show']);
    Route::post('borrow', [BorrowingController::class, 'borrowItem']);
    Route::post('return/{id}', [BorrowingController::class, 'returnItem']);
    Route::get('audit-logs', [AuditLogController::class, 'index']);

    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        // Admin-only routes
        Route::post('/users', [AuthController::class, 'createUser']);
        
        Route::apiResource('cupboards', CupboardController::class)->except(['index', 'show']);
        Route::apiResource('places', PlaceController::class)->except(['index', 'show']);
        Route::apiResource('items', ItemController::class)->except(['index', 'show']);
        
        Route::post('/items/{item}/update-quantity', [ItemController::class, 'updateQuantity']);
        Route::post('items/{id}/quantity', [ItemController::class, 'updateQuantity']);
        Route::post('items/{id}/status', [ItemController::class, 'updateStatus']);
    });
});



Route::get('/setup', function () {
    try {

        Artisan::call('migrate', ['--force' => true]);


      if (!User::where('email', 'admin@inventory.com')->exists()) {
            User::create([
                'name' => 'System Admin',
                'email' => 'admin@inventory.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Database Migrated and Admin User Created Successfully!',
            'admin_email' => 'admin@inventory.com',
            'admin_password' => 'admin123'
        ]);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

