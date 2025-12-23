<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\BookingStatus;
class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'booke_date',
    ];

    protected $casts = [
        'status' => BookingStatus::class,
        'booke_date' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    /**
     * Booking belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the owning bookable model (polymorphic)
     */
    public function bookable()
    {
        return $this->morphTo();
    }
    /**
     * Booking has one payment
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

}
