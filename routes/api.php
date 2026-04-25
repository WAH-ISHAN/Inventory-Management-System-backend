<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


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

