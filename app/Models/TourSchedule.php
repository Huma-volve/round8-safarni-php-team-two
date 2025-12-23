<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourSchedule extends Model
{

    protected $fillable = [
        'tour_id',
        'activity_id',
        'date',
        'time',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
    ];

    /**
     * Schedule belongs to a Tour
     */
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Schedule belongs to an Activity
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
    
}
