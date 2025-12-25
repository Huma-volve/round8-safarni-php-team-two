<?php

namespace App\Enums;

enum TicketStatus: string
{
    case BOOKED = 'booked';
    case CANCELLED = 'cancelled';
    case USED = 'used';
}
