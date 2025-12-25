<?php

use App\Http\Controllers\Api\Flight\BookFlightController;
use App\Http\Controllers\Api\Flight\FlightController;
use App\Http\Controllers\Api\Hotel\HotelBookingController;
use App\Http\Controllers\Api\Hotel\HotelController;
use App\Http\Controllers\Api\Hotel\HotelRatingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    // Hotels
    Route::get('hotels', [HotelController::class, 'index']);
    Route::get('hotels/{hotel}', [HotelController::class, 'show']);
    //hotel near you
    Route::get('nearby/{lat}/{lng}', [HotelController::class, 'nearbyHotels']);


    // Hotel Bookings
    Route::get('bookings', [HotelBookingController::class, 'index']);
    Route::post('rooms/{room}/check-availability', [HotelBookingController::class, 'checkAvailability']);
    Route::post('bookings-add', [HotelBookingController::class, 'store']);
    Route::get('bookings/{booking}', [HotelBookingController::class, 'show']);
    Route::post('bookings/{booking}/cancel', [HotelBookingController::class, 'cancel']);

    // Hotel Ratings
    Route::get('hotels/{hotel}/ratings', [HotelRatingController::class, 'index']);
    Route::post('hotels/{hotel}/add-ratings', [HotelRatingController::class, 'store']);
    Route::post('hotels/{hotel}/update-ratings/{rating}', [HotelRatingController::class, 'update']);
    Route::post('hotels/{hotel}/delete-ratings/{rating}', [HotelRatingController::class, 'destroy']);

});

// Flights
Route::middleware('auth:sanctum')->prefix('flights/')->group(function () {
    Route::get('search', [FlightController::class, 'search']);
    Route::get('{id}', [FlightController::class, 'show']);
    Route::get('{flightId}/seats', [FlightController::class, 'seats']);

    Route::post('{flightId}/book-seat', [BookFlightController::class, 'bookSeat']);
    Route::post('{ticketId}/cancel-seat', [BookFlightController::class, 'cancelTicket']);
    



});

