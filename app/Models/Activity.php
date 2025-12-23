<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'title',
        'description',
    ];

    /**
     * Optional: Activity can have many photos (polymorphic)
     */
    public function photos()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }

    /**
     * Optional: Activity can be favourited (polymorphic)
     */
    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favouritable');
    }

    /**
     * Activity has many schedules
     */
    public function schedules()
    {
        return $this->hasMany(TourSchedule::class);
    }

    /**
     * Optional: get tours through schedules
     */
    public function tours()
    {
        return $this->belongsToMany(Tour::class, 'tour_schedules')
            ->withPivot('date', 'time')
            ->withTimestamps();
    }


}
