<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id', // Foreign key to the client
        'room_id', // Foreign key to the room
        'check_in',
        'check_out',
        'family_id', // Foreign key to the family, if applicable
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

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function family()
    {
        return $this->belongsTo(Client::class, 'family_id');
    }


}
