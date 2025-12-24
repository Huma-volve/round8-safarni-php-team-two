<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'location'  => $this->location->city ?? '',
            'rating'    => round($this->ratings->avg('rating'), 1),
            'price'     => $this->rooms_min_price,
            'image' => url($this->photos->first()?->url),
        ];
    }
}
