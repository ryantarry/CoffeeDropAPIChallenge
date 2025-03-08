<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CashbackController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('api')->group(function () {
    Route::get('/nearest-location', [LocationController::class, 'getNearestLocation']);
    Route::post('/new-location', [LocationController::class, 'createNewLocation']);
    Route::post('/calculate-cashback', [CashbackController::class, 'calculateCashback']);
    Route::get('/calculation-history', [CashbackController::class, 'calculationHistory']);
});



Route::get('/test', function (Request $request) {
    return response()->json([
        'message' => 'This is a test route!',
        'status' => 'success',
        'data' => [
            'example' => 'This is an example response.',
        ],
    ]);
});
