<?php

namespace App\Enums;

enum UserRole: string
{
    case Super = 'super';
    case Manager = 'manager';
    case Employee = 'employee';

}
