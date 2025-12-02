<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Tenant extends BaseTenant implements HasMedia
{
    use HasFactory, SoftDeletes, HasDomains, InteractsWithMedia;
    public $incrementing = false;   // المفتاح الأساسي ليس رقمًا متزايدًا تلقائيًا
    protected $keyType = 'string';  // المفتاح الأساسي هو نص (UUID)

    protected $table = 'tenants';
    protected $casts = [
        'data' => 'array',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logos')
            ->useDisk('public_html')
            ->singleFile() // always keep only one (auto-remove previous)
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->scale(width: 200, height: 200);
    }
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
