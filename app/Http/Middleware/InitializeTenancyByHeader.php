<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Stancl\Tenancy\Database\Models\Domain;

class InitializeTenancyByHeader
{
public function handle(Request $request, Closure $next): Response
{
    if (!$request->hasHeader('X-Tenant-Domain')) {
        return response()->json([
            'message' => 'header is required.'
        ], 400);
    }

    $domain = Domain::where('domain', $request->header('X-Tenant-Domain'))->first();
    if (!$domain) {
        return response()->json([
            'message' => 'Invalid tenant domain.'
        ], 404);
    }

    tenancy()->initialize($domain->tenant);

    return $next($request);
}

}
