<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarPricingTier extends Model
{
    protected $fillable = [
        'car_id',
        'from_hours',
        'to_hours',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Tier belongs to a car
     */
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
