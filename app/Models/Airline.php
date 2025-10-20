<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Airline extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'airline_name',
        'airline_type',
        'capacity',
        'price_dinar',
        'provider_id',
        'note',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];


    public function flights()
    {
        return $this->hasMany(Flight::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }


}
