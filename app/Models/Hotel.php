<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'name',
        'description',
        'location_id',
    ];

    /**
     * Hotel belongs to a Location
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favouritable');
    }
    public function photos()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }

    public function rooms()
    {
        return $this->hasMany(HotelRoom::class);
    }
    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

}
