<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelDetailsResource;
use App\Http\Resources\Hotel\HotelResource;
use App\Models\Hotel;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::with(['location', 'photos', 'ratings'])
            ->withMin('rooms', 'price')
            ->get();

        return apiResponse(true, 'Hotels retrieved successfully', HotelResource::collection($hotels), 200);
    }

    public function show($id)
    {
        $hotel = Hotel::with([
            'rooms.photos',
            'rooms.bookings',
            'location',
            'photos',
            'ratings'
        ])->find($id);

        if (!$hotel) {
            return apiResponse(false, 'Hotel not found', null, 404);
        }

        return apiResponse(true, 'Hotel details retrieved successfully', new HotelDetailsResource($hotel), 200);
    }

    public function nearbyHotels($lat, $lng, $radius = 10)
    {
        $hotels = Hotel::with(['location', 'photos', 'ratings'])
            ->whereHas('location', function ($q) use ($lat, $lng, $radius) {
                $q->select('*')->whereNotNull('latitude')->whereNotNull('longitude')->whereRaw(
                        "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?",
                        [$lat, $lng, $lat, $radius]
                    );
            })
            ->get();

        return apiResponse(true, 'Nearby hotels retrieved successfully', HotelResource::collection($hotels), 200);
    }

}
