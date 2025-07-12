<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tenants';
    protected $guarded = ['id'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'tenant_id');
    }

    public function providers()
    {
        return $this->hasMany(Provider::class, 'tenant_id');
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'tenant_id');
    }


}
