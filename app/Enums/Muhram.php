<?php

namespace App\Enums;

enum Muhram: string
{
    case Father = 'father';
    case Husband = 'husband';
    case Brother = 'brother';
    case Son = 'son';
    case Other = 'other';
}
