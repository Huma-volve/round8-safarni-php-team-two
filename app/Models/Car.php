<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'title',
        'model',
        'brand_name',
        'seat_num',
        'price_per_hour',
        'location',
        'is_available',
    ];

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favouritable');
    }
    public function photo()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }
    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }
    public function pricingTiers()
    {
        return $this->hasMany(CarPricingTier::class);
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

}
