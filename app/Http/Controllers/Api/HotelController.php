<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HotelDetailsResource;
use App\Models\Hotel;
use App\Http\Resources\HotelResource;
use Carbon\Carbon;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::with(['location', 'photos', 'ratings'])->withMin('rooms', 'price')->get();

        return apiResponse(true, 'Hotels retrieved successfully', HotelResource::collection($hotels), 200);
    }

    public function show($id)
    {
        $hotel = Hotel::with('rooms.photos', 'rooms.bookings', 'location', 'photos', 'ratings')->find($id);

        if (!$hotel) {
            return apiResponse(false, 'Hotel not found', null, 404);
        }

        return apiResponse(true, 'Hotel details retrieved successfully', new HotelDetailsResource($hotel), 200);
    }

}
