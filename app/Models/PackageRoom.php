<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PackageRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'package_rooms';

    protected $fillable = [
        'package_id',
        'room_type',
        'total_price_dinar',
        'total_price_usd',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

}


