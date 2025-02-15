<?php

use App\Http\Controllers\{AuthController, OrderTravelController};
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('order-travel')->group(function () {
        Route::post('/', [OrderTravelController::class, 'store']);
        Route::get('/', [OrderTravelController::class, 'index']);
        Route::get('/{orderTravel}', [OrderTravelController::class, 'show']);
        Route::put('/{orderTravel}', [OrderTravelController::class, 'update']);
        Route::delete('/{orderTravel}', [OrderTravelController::class, 'destroy']);
    });
});
