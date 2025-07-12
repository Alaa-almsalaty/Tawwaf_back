<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'total_dinar',
        'total_usd',
        'discount_dinar',
        'discount_usd',
        'status',
        'paid_amount',
        'note',
        'reservation_id', // Foreign key to the reservation
        'created_by' // Foreign key to the user who created the bill
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
