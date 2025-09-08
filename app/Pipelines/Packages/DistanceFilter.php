<?php

namespace App\Pipelines\Packages;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DistanceFilter
{
    public function __construct(private Request $request)
    {
    }

    public function handle(Builder $query, Closure $next)
    {
        $distance = $this->request->input('distance'); // e.g., "0", "200", "500"
        if (!filled($distance)) {
            return $next($query);
        }

        $meters = (int) $distance;
        $query->where(function (Builder $q) use ($meters) {
            $q->whereHas('MD_Hotel', fn($h) => $h->where('distance_from_center', '<=', $meters))
            ->orWhereHas('MK_Hotel', fn($h) => $h->where('distance_from_center', '<=', $meters));

        });

        return $next($query);
    }
}
