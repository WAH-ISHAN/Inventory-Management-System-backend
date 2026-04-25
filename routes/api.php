<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ItemController;



Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        // Admin-only routes
        Route::post('/users', [AuthController::class, 'createUser']);

        });


    });

Route::apiResource('cupboards', \App\Http\Controllers\CupboardController::class);
Route::apiResource('places', \App\Http\Controllers\PlaceController::class);
Route::apiResource('items', \App\Http\Controllers\ItemController::class);
Route::post('/items/{item}/update-quantity', [\App\Http\Controllers\ItemController::class, 'updateQuantity']);
Route::post('borrow', [BorrowingController::class, 'borrowItem']);
Route::post('return/{id}', [BorrowingController::class, 'returnItem']);
Route::get('audit-logs', [AuditLogController::class, 'index']);
Route::post('items/{id}/quantity', [ItemController::class, 'updateQuantity']);
Route::post('items/{id}/status', [ItemController::class, 'updateStatus']);

