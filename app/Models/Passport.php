<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Passport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'passport_number',
        'passport_type',
        'issue_date',
        'expiry_date',
        'issue_place',
        'birth_place',
        'issue_authority',
        'passport_img',
        'nationality'
    ];


}
