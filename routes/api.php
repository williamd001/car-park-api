<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ParkingSpaceController;
use Illuminate\Http\Request;
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

Route::controller(BookingController::class)->group(function () {
    Route::post('/customers/{customerId}/bookings', 'store');
    Route::delete('/customers/{customerId}/bookings/{bookingId}', 'destroy');
    Route::put('/customers/{customerId}/bookings/{bookingId}', 'update');
});
