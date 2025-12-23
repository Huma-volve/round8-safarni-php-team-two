<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $fillable = [
        'image',
        'title',
        'country',
        'capital',
        'price',
        'description',
        'start_date',
        'duration_days',
        'languages',
        'available',
        'day_num',
        'night_num',
        'location_id',
    ];


    public function location()
    {
        return $this->belongsTo(Location::class);
    }
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

    public function schedules()
    {
        return $this->hasMany(TourSchedule::class);
    }

    /**
     * Optional: get activities through schedules
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'tour_schedules')
            ->withPivot('date', 'time')
            ->withTimestamps();
    }
    public function ratings()
{
    return $this->morphMany(Rating::class, 'rateable');
}

}
