<?php

namespace App\Http\Resources\Flight;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeatsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
            'id'          => $this->id,
            'flight_id'   => $this->flight_id,
            'seat_number' => $this->seat_number,
            'price'       => number_format((float)$this->price, 2), // formatted price
            'user_id'     => $this->user_id,
            'class'       => $this->class,
            'is_available'=> (bool) $this->is_available,
            
        ];
    }
}
