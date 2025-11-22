<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Stancl\Tenancy\Database\Models\Domain;

class RefreshTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $refreshToken = $request->bearerToken();

        if (!$refreshToken) {
            return response()->json(['message' => 'Refresh token missing'], 401);
        }

        $tokenModel = PersonalAccessToken::findToken($refreshToken);

        if (!$tokenModel || !$tokenModel->can('refresh')) {
            return response()->json(['message' => 'Invalid or expired refresh token'], 401);
        }

        $user = $tokenModel->tokenable;

        if (!$user) {
            return response()->json(['message' => 'Invalid or expired refresh token'], 401);
        }

        // Delete only access tokens (keep refresh-token)
        $user->tokens()
            ->where('name', 'access-token')
            ->delete();

        $newAccessToken = $user->createToken('access-token')->plainTextToken;

        $request->merge([
            'auth_user'        => $user,
            'new_access_token' => $newAccessToken,
            'tenant_domain'    => $this->resolveDomain($user),
        ]);

        return $next($request);
    }

    private function resolveDomain($user)
    {
        if (!$user->tenant_id) {
            return null;
        }

        return Domain::where('tenant_id', $user->tenant_id)->value('domain');
    }
}
