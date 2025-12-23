<?php

namespace App\Enums;

enum FlightStatus :string
{
    case SCHEDULED = 'scheduled';
    case DELAYED   = 'delayed';
    case CANCELLED = 'cancelled';
    case DEPARTED  = 'departed';
    case ARRIVED   = 'arrived';
}
