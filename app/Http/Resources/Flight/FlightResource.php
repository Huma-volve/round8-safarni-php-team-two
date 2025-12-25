<?php

namespace App\Http\Resources\Flight;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlightResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,
            'flight_number' => $this->flight_number,
            'carrier' => $this->carrier,
            'aircraft_type' => $this->aircraft_type,
            'departure_airport' => $this->departure_airport,
            'arrival_airport' => $this->arrival_airport,
            'departure_time' => $this->departure_time,
            'departure_date' => $this->departure_date,
            'arrival_time' => $this->arrival_time,
            'arrival_date' => $this->arrival_date,
            'duration_minutes' => $this->duration_minutes,
            'status' => $this->status,
            'available_seats' => $this->available_seats,
        ];
    }
}
