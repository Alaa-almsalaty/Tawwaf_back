<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Flight extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'departure_time',
        'arrival_time',
        'departure_airport',
        'arrival_airport',
        'Ycapacity',
        'Ccapacity',
        'price_dinar',
        'price_usd',
        'airline_id',
        'trip_id',
        'note',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'price_dinar' => 'decimal:2',
        'price_usd' => 'decimal:2',
    ];

    public function airline()
    {
        return $this->belongsTo(Airline::class, 'airline_id');
    }
    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

}
