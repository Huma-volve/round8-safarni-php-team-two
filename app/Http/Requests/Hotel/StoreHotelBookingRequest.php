<?php

namespace App\Http\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotelBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'room_id' => 'required|exists:hotel_rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'note_to_owner' => 'nullable|string|max:1000',
        ];
    }
}
