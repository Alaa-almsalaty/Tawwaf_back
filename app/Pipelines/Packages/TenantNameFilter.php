<?php

namespace App\Pipelines\Packages;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TenantNameFilter
{

    public function __construct(private Request $request)
    {
    }

public function handle(Builder $query, Closure $next)
    {
            $tenantName = $this->request->input('tenant_name');

            $query->whereHas('tenant', function (Builder $q) use ($tenantName) {
                $q->where('data->company_name', 'like', "%{$tenantName}%");
            });


        return $next($query);
    }
}
