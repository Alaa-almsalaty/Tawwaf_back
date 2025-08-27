<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Package extends Model
{
    use HasFactory, SoftDeletes , BelongsToTenant;

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    // public function hotel()
    // {
    //     return $this->belongsTo(Hotel::class, 'hotel_id');
    // }

    public function MK_Hotel()
    {
        return $this->belongsTo(Hotel::class, 'MKHotel');
    }

    public function MD_Hotel()
    {
        return $this->belongsTo(Hotel::class, 'MDHotel');
    }

    public function isActive()
    {
        return $this->status === true;
    }
}
