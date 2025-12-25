<?php

namespace App\Http\Resources\Flight;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'ticket_number'   => $this->ticket_number,
            'user_id'         => $this->user_id,
            
            'flight_seat'     => [
                'id'            => $this->flightSeat?->id,
                'flight_id'     => $this->flightSeat?->flight_id,
                'seat_number'   => $this->flightSeat?->seat_number,
                'seat_class'    => $this->flightSeat?->class,          
                
            ], 
            'flight' => [
                'id'            => $this->flightSeat?->flight_id,
                'flight_number' => $this->flightSeat?->flight?->flight_number,
                'departure_time'  => $this->flightSeat?->flight?->departure_time,
                'arrival_time'    => $this->flightSeat?->flight?->arrival_time,
                'departure_airport' => $this->flightSeat?->flight?->departure_airport,
                'arrival_airport'   => $this->flightSeat?->flight?->arrival_airport,
            ],
            
            'price'           => (float) $this->price,
            'status'          => $this->status->value,         // because it's enum
        ];
    }
}