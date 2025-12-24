<?php

namespace App\Http\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHotelRatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only allow the rating owner to update
        $rating = $this->route('rating');
        return $rating && $this->user()->id === $rating->user_id;
    }

    public function rules(): array
    {
        return [
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'delete_photos' => 'nullable|array',
            'delete_photos.*' => 'string',
        ];
    }
}
