<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'flight_seat_id',
        'ticket_number',
        'price',
        'status',
    ];
    protected $casts = [
        'status' => TicketStatus::class,
    ];
    // العلاقة بالمستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة بالكرسي
    public function flightSeat()
    {
        return $this->belongsTo(FlightSeat::class, 'flight_seat_id');
    }
}
