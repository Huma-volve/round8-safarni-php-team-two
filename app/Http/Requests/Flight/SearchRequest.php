<?php

namespace App\Http\Requests\Flight;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
             'departure_airport' => 'required|string',
            'arrival_airport'   => 'required|string|different:departure_airport',
            'departure_date'    => 'required|date',
        ];
    }
}
