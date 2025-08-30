<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;

class Reservation extends Model
{
    use HasFactory, SoftDeletes , BelongsToTenant;

    protected $guarded = ['id'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function family()
    {
        return $this->belongsTo(Family::class, 'family_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function visitor()
    {
        return $this->belongsTo(User::class, 'visitor_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeForUser(Builder $q, User $user): Builder
    {
        if ($user->hasAnyRole(['manager','employee'])) {
            return $q->whereHas('package', fn ($p) => $p->where('tenant_id', $user->tenant_id));
        }
        if ($user->hasRole('visitor')) {
            return $q->where('visitor_id', $user->id);
        }
        return $q; // super-admin, etc.
    }

public function scopeSearch(Builder $q, ?string $rawTerm): Builder
    {
        $term = trim((string) $rawTerm);
        if ($term === '') return $q;

        return $q->where(function (Builder $w) use ($term) {
            // PACKAGE FIELDS — make sure these names match your schema
            $w->whereHas('package', function (Builder $p) use ($term) {
                $p->where(function (Builder $g) use ($term) {
                    $g->where('package_type', 'like', "%{$term}%")
                      ->orWhere('package_name', 'like', "%{$term}%")
                      ->orWhere('currency', 'like', "%{$term}%")
                      ->orWhere('total_price_dinar', 'like', "%{$term}%")
                      ->orWhere('total_price_usd', 'like', "%{$term}%");
                });
            })
            // VISITOR FIELDS — adjust to your actual columns
            ->orWhereHas('visitor', function (Builder $v) use ($term) {
                $v->where(function (Builder $g) use ($term) {
                    $g->where('full_name', 'like', "%{$term}%")
                      ->orWhere('email', 'like', "%{$term}%")
                      ->orWhere('phone', 'like', "%{$term}%");
                });
            });
        });
    }

}
