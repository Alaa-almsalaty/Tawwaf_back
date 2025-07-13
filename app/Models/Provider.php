<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Provider extends Model
{
    use HasFactory , SoftDeletes , BelongsToTenant;

    protected $guarded = ['id'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function hotels()
    {
        return $this->hasMany(Hotel::class, 'provider_id');
    }

    public function airlines()
    {
        return $this->hasMany(Airline::class, 'provider_id');
    }

    public function visas(){
        return $this->hasMany(Visa::class, 'provider_id');
    }



}
