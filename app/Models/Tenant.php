<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant
{
    use HasFactory, SoftDeletes, HasDomains;
    public $incrementing = false;   // المفتاح الأساسي ليس رقمًا متزايدًا تلقائيًا
    protected $keyType = 'string';  // المفتاح الأساسي هو نص (UUID)

    protected $table = 'tenants';
    protected $casts = [
        'data' => 'array',
    ];

    public function getInfoAttribute()
    {
        return $this->data;
    }

    public function setInfoAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }


    protected $fillable = [
        'id',
        'data',
        'company_name',
        'address',
        'city',
        'email',
        'status',
        'balance',
        'manager_name',
        'phone',
        'note',
        'created_by',
        'season',
        'logo',
        'active'
    ];

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

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }
}
