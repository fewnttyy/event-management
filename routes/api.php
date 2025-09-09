<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\BookingController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'userInfo']);

    Route::get('events', [EventController::class, 'listEvents']);
    Route::post('events', [EventController::class, 'addEvents']);
    Route::get('events/{id}', [EventController::class, 'showEvent']);
    Route::put('events/{id}', [EventController::class, 'updateEvent']);
    Route::delete('events/{id}', [EventController::class, 'deleteEvent']);

    Route::post('events/{event_id}/book', [BookingController::class, 'bookTicket']);

    Route::get('bookings', [BookingController::class, 'listBookings']);
});
