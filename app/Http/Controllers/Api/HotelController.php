<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Http\Resources\HotelResource;
use App\Http\Resources\HotelDetailsResource;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::with(['location', 'photos', 'ratings'])->withMin('rooms', 'price')->get();

        return HotelResource::collection($hotels);
    }

    public function show(Hotel $hotel)
    {
        $hotel->load([
            'location',
            'photos',
            'rooms.photos',
            'ratings'
        ]);

        return new HotelDetailsResource($hotel);
    }
}
