<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\SeetsClass;

class FlightSeat extends Model
{

    protected $table = 'flight_seats';
    protected $fillable = [
        'flight_id',
        'seat_number',
        'class',
        'price',
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'flight_seat_id');
    }


}
