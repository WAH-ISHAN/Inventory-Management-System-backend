<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CupboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PlaceController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('cupboards', CupboardController::class);
    Route::apiResource('places', PlaceController::class);
    Route::apiResource('items', ItemController::class);
    Route::post('/items/{item}/update-quantity', [ItemController::class, 'updateQuantity']);
    Route::post('borrow', [BorrowingController::class, 'borrowItem']);
    Route::post('return/{id}', [BorrowingController::class, 'returnItem']);
    Route::get('audit-logs', [AuditLogController::class, 'index']);
    Route::post('items/{id}/quantity', [ItemController::class, 'updateQuantity']);
    Route::post('items/{id}/status', [ItemController::class, 'updateStatus']);

    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        // Admin-only routes
        Route::post('/users', [AuthController::class, 'createUser']);

    });
});



