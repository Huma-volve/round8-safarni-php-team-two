<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $fillable = [
        'user_id',
    ];

    /**
     * The user who added the favourite
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the owning favouritable model.
     */
    public function favouritable()
    {
        return $this->morphTo();
    }
}
