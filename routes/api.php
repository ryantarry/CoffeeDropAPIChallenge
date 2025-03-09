<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CashbackController;
use App\Http\Controllers\AuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/test', function (Request $request) {
    return response()->json([
        'message' => 'This is a test route!',
        'status' => 'success',
        'data' => [
            'example' => 'This is an example response.',
        ],
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    
    //Location routes
    Route::get('/nearest-location', [LocationController::class, 'getNearestLocation']);
    Route::post('/new-location', [LocationController::class, 'createNewLocation']);
    
    //Cashback routes
    Route::post('/calculate-cashback', [CashbackController::class, 'calculateCashback']);
    Route::get('/calculation-history', [CashbackController::class, 'calculationHistory']);
});
