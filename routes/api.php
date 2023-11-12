<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ParkingSpaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/parking-spaces/availability', [ParkingSpaceController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/users/{userId}/bookings', [BookingController::class, 'store']);
    Route::delete('/users/{userId}/bookings/{bookingId}', [BookingController::class, 'destroy']);
    Route::put('/users/{userId}/bookings/{bookingId}', [BookingController::class, 'update']);
});
