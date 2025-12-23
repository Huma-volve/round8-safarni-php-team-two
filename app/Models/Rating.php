<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'user_id',
        'rating',
        'comment',
    ];

    /**
     * The user who gave the rating
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the owning rateable model
     */
    public function rateable()
    {
        return $this->morphTo();
    }
}
