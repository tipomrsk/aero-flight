<?php

use App\Http\Controllers\{AuthController, OrderTravelController};
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::controller(OrderTravelController::class)->prefix('order-travel')->group(function (): void {
        Route::get('/', 'getAll');
        Route::post('/', 'store');
        Route::get('/{orderTravel}', 'show');
        Route::put('/{orderTravel}', 'update');
        Route::put('/update-status/{orderTravel}', 'updateStatus');
        Route::delete('/{orderTravel}', 'destroy');
    });
});
