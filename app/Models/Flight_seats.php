<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\SeetsClass;

class Flight_seats extends Model
{
    protected $fillable = [
        'flight_id',
        'seat_number',
        'class',
        'is_available',
    ];

    protected $casts = [
        'class' => SeetsClass::class,
        'is_available' => 'boolean',
    ];

    /**
     * Relation to Flight
     */
    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }
    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

}
