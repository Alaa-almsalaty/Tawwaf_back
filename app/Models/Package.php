<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Package extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

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


    public function scopeForUser(Builder $q, User $user): Builder
    {
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
                ->orWhere('total_price_dinar', '<=', "%{$term}%")
                ->orWhere('total_price_usd', '<=', "%{$term}%")

                ->orWhereHas('tenant', function (Builder $t) use ($term) {
                    $t->where('data->company_name', 'like', "%{$term}%");
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

