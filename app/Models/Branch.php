<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;

class Branch extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $guarded = ['id'];

    public function tenants()
    {
        return $this->belongsTo(Tenant::class , 'tenant_id');
    }

public function scopeSearch(Builder $q, ?string $rawTerm): Builder
{
    $term = trim((string) $rawTerm);
    if ($term === '')
        return $q;
    return $q->where(function (Builder $w) use ($term) {
        $w->where('name', 'like', "%{$term}%")
            ->orWhere('address', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->orWhere('city', 'like', "%{$term}%");
    });
}

}
