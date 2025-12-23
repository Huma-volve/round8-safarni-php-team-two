<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel_Room extends Model
{
    protected $fillable = [
        'hotel_id',
        'description',
        'price',
        'bathroom_number',
        'bedroom_number',
        'area',
        'is_available',
    ];


     public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
     public function photo()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }
    public function bookings()
{
    return $this->morphMany(Booking::class, 'bookable');
}

}
