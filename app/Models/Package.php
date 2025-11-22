<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Package extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, BelongsToTenant, InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public_html')
            ->path('/Packages');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(200);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

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

    public function rooms()
    {
        return $this->hasMany(PackageRoom::class);
    }

    public function scopeForUser(Builder $q, User $user): Builder
    {
        if ($user->hasRole('super')) {
            return $q;
        }
        if ($user->hasAnyRole(['manager', 'employee'])) {
            return $q->where('tenant_id', $user->tenant_id);
        }
        return $q;
    }

    public function scopeSearch(Builder $q, ?string $rawTerm): Builder
    {
        $term = trim((string) $rawTerm);
        if ($term === '')
            return $q;
        return $q->where(function (Builder $w) use ($term) {
            $w->where('package_type', 'like', "%{$term}%")
                ->orWhere('package_name', 'like', "%{$term}%")
                ->orWhere('start_date', 'like', "%{$term}%")
                ->orWhere('season', 'like', "%{$term}%")
                ->orWhere('currency', 'like', "%{$term}%")

                ->orWhereHas('tenant', function (Builder $t) use ($term) {
                    $t->where('data->company_name', 'like', "%{$term}%");
                })
                ->orWhereHas('rooms', function (Builder $r) use ($term) {
                    $r->where('room_type', 'like', "%{$term}%")
                        ->orWhere('total_price_dinar', 'like', "%{$term}%")
                        ->orWhere('total_price_usd', 'like', "%{$term}%");
                })
                ->orWhereHas('MK_Hotel', function (Builder $h) use ($term) {
                    $h->where(function (Builder $g) use ($term) {
                        $g->where('hotel_name', 'like', "%{$term}%")
                            ->orWhere('distance_from_center', '<=', "%{$term}%");
                    });
                });
        });
    }
}

