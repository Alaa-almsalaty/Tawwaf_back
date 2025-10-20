<?php

namespace App\Enums;

enum ReservationState: string
{
    case Sent = 'sent';
    case Delivered = 'delivered';
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
    case Completed = 'completed';


}
