<?php

namespace App\Pipelines\Packages;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PackageTypeFilter
{
    public function __construct(private Request $request)
    {
    }

    public function handle(Builder $query, Closure $next)
    {
        $type = $this->request->input('type'); // e.g., "basic"
        if (!filled($type)) {
            return $next($query);
        }
        //$query->where('package_type', $type);
        // case-insensitive match (DB has "Basic"/"VIP", etc.)
        $query->whereRaw('LOWER(package_type) = ?', [strtolower($type)]);
        return $next($query);
    }
}
