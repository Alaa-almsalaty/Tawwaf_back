<?php

namespace App\Services\Auth;

use Stancl\Tenancy\Database\Models\Domain;
use App\Models\User;
use Illuminate\Http\Request;

class LoginService
{
    public function handle(User $user, Request $request)
    {
        // Clear old tokens
        $user->tokens()->delete();

        // Create tokens
        $accessToken = $user->createToken('access-token')->plainTextToken;
        $refreshToken = $user->createToken('refresh-token', ['refresh'])->plainTextToken;

        // Resolve domain (returns null or domain string)
        $domain = $this->resolveDomain($user, $request);

        return [
            'user'          => $user,
            'accessToken'   => $accessToken,
            'refreshToken'  => $refreshToken,
            'domain'        => $domain,
        ];
    }


    private function resolveDomain(User $user, Request $request)
    {
        // If user does not belong to a tenant => Super Admin
        if (!$user->tenant_id) {
            return null;
        }

        $currentHost = $request->header('X-Tenant-Domain');
        $centralDomains = ['tawwaf.ly', 'www.tawwaf.ly', 'localhost'];

        // Case 1: Coming from main domain → return tenant’s first domain
        if (in_array($currentHost, $centralDomains)) {
            return Domain::where('tenant_id', $user->tenant_id)->value('domain');
        }

        // Case 2: Coming from a tenant subdomain → verify ownership
        $validDomain = Domain::where('tenant_id', $user->tenant_id)
            ->where('domain', $currentHost)
            ->value('domain');

        if (!$validDomain) {
            abort(403, 'User does not belong to this tenant domain');
        }

        return $validDomain;
    }
}
