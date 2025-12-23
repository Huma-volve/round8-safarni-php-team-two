<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\FlightStatus;

class Flight extends Model
{
    protected $fillable = [
        'flight_number',
        'carrier',
        'aircraft_type',
        'departure_airport',
        'arrival_airport',
        'departure_time',
        'arrival_time',
        'seat_price',
        'duration_minutes',
        'status',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'status' => FlightStatus::class,
    ];

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favouritable');
    }
    public function seats()
    {
        return $this->hasMany(Flight_seats::class);
    }

    /**
     * Get available seats
     */
    public function availableSeats()
    {
        return $this->seats()->where('is_available', true);
    }
   
    public function ratings()
{
    return $this->morphMany(Rating::class, 'rateable');
}



}
