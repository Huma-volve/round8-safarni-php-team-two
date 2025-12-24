<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'location'    => $this->location->address ?? '',
            'rating'      => round($this->ratings->avg('rating'), 1),
            'reviews'     => $this->ratings->count(),
            'gallery' => $this->photos->map(fn ($photo) => url($photo->url)),
            'rooms' => $this->rooms->map(fn ($room) => [
                'id'        => $room->id,
                'price'     => $room->price,
                'bedrooms'  => $room->bedroom_number,
                'bathrooms' => $room->bathroom_number,
                'area'      => $room->area,
                'available' => $room->is_available,
                'photos' => $room->photos->map(fn ($p) => url($p->url)),
            ]),
        ];
    }
}
