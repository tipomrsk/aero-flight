<?php

use App\Http\Controllers\{AuthController, OrderTravelController};
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('order-travel')->group(function (): void {
        Route::get('/', [OrderTravelController::class, 'getAll']);
        Route::post('/', [OrderTravelController::class, 'store']);
        Route::get('/{orderTravel}', [OrderTravelController::class, 'show']);
        Route::put('/{orderTravel}', [OrderTravelController::class, 'update']);
        Route::put('/update-status/{orderTravel}', [OrderTravelController::class, 'updateStatus']);
        Route::delete('/{orderTravel}', [OrderTravelController::class, 'destroy']);
    });
});
