<?php

namespace App\Http\Controllers\Api\Flight;

use App\Http\Controllers\Controller;
use App\Http\Requests\Flight\SearchRequest;
use App\Http\Resources\Flight\FlightResource;
use App\Http\Resources\Flight\SeatsResource;
use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function search(SearchRequest $request)
    {
        $flights = Flight::where('departure_airport', $request->departure_airport)
            ->where('arrival_airport', $request->arrival_airport)
            ->where('departure_date', $request->departure_date)
            ->where('status', 'scheduled')
            ->withCount([
                'seats as available_seats' => fn($q) =>
                    $q->where('is_available', true)
            ])
            ->paginate(10);



        return apiResponse(true, 'Flights retrieved successfully', FlightResource::collection($flights), 200);
    }

    public function show($id)
    {
        $flight = Flight::withCount([
            'seats as available_seats' => fn($q) => $q->where('is_available', true)
        ])->find($id);

        if (!$flight) {
            return apiResponse(false, 'Flight not found', null, 404);

        }

        return apiResponse(true, 'Flight retrieved successfully', new FlightResource($flight), 200);

    }


    public function seats($flightId)
    {
        
        $flight = Flight::with('seats')->find($flightId);
        
        if (! $flight) {
            return apiResponse(false, 'Flight not found', null, 404);
        }
        $seats = $flight->seats;
        return apiResponse(true, 'Flight retrieved successfully',  SeatsResource::collection($seats), 200);

        
    }
}
