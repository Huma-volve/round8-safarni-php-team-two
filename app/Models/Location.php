<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'country',
        'city',
        'address',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];
    public function tours()
    {
        return $this->hasMany(Tour::class);
    }


    /**
     * Optional: full readable address
     */
    public function getFullAddressAttribute(): string
    {
        return collect([$this->address, $this->city, $this->country])
            ->filter()
            ->implode(', ');
    }
}
