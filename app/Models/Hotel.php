<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hotel_name',
        'manager_name',
        'address',
        'city',
        'capacity',
        'phone',
        'rooms_count',
        'stars',
        'distance_from_center',
        'provider_id',
        'note'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'stars' => 'integer',
        'capacity' => 'integer',
        'rooms_count' => 'integer',
        'distance_from_center' => 'float',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'hotel_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }


}
