<?php

namespace App\Enums;

enum Season: string
{
    case Umrah = 'Umrah';
    case Hajj = 'Hajj';
    case Ramadan = 'Ramadan';
    case Eid = 'Eid';
    case Normal = 'Normal';

}
