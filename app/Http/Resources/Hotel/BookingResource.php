<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'status'         => $this->status,
            'total_price'    => $this->total_price,
            'check_in_date'  => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
            'note_to_owner'  => $this->note_to_owner,
            'booked_date'    => $this->booked_date,

            'room' => [
                'id'    => $this->bookable->id,
                'price' => $this->bookable->price,
                'photos'=> $this->bookable->photos->map(fn ($p) => asset('storage/' . $p->url)),
                'hotel' => [
                    'id'    => $this->bookable->hotel->id,
                    'name'  => $this->bookable->hotel->name,
                    'photos'=> $this->bookable->hotel->photos->map(fn ($p) => asset('storage/' . $p->url)),
                ],
            ],
        ];
    }
}
