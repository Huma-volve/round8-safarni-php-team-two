<?php

namespace App\Http\Resources\Hotel;

use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar ? asset('storage/' . $this->user->avatar) : null,
            ],
            'rating' => $this->rating,
            'comment' => $this->comment,
            'photos' => $this->photos ? array_map(fn($photo) => asset('storage/' . $photo), json_decode($this->photos, true)) : [],
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
