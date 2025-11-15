<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stancl\Tenancy\Database\Models\Domain;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{

    use AuthorizesRequests;

    public function register(RegisterRequest $request)
    {
        //  $this->authorize('create', User::class);
        $user = User::create($request->createUser());
        $user->assignRole($user->role);

        return UserResource::make($user);

    }

    public function login(LoginRequest $request)
    {
        $user = $request->authenticate();

        // Delete old tokens to avoid unlimited device sessions
        $user->tokens()->delete();

        // Create short-lived access token
        $accessToken = $user->createToken('access-token')->plainTextToken;

        // Create long-lived refresh token
        $refreshToken = $user->createToken('refresh-token', ['refresh'])->plainTextToken;

        // If the user does not belong to a tenant (Super Admin or Visitor)
        if (!$user->tenant_id) {
            return response()->json([
                'user' => $user,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'token_type' => 'Bearer',
                'expires_in' => config('sanctum.expiration') ? config('sanctum.expiration') * 1 : null,
                'tenant' => [
                    'domain' => parse_url(config('app.url'), PHP_URL_HOST),
                ],
            ]);
        }

        // Determine current domain from frontend header
        $currentHost = $request->header('X-Tenant-Domain');

        // If accessing from central domain or localhost → return the tenant's first domain instead
        if (in_array($currentHost, ['tawwaf.ly', 'www.tawwaf.ly', 'localhost'])) {
            $domain = Domain::where('tenant_id', $user->tenant_id)->first();

            return response()->json([
                'user' => $user,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'token_type' => 'Bearer',
                'expires_in' => config('sanctum.expiration') ? config('sanctum.expiration') * 1 : null,
                'tenant' => [
                    'domain' => $domain?->domain ?? parse_url(config('app.url'), PHP_URL_HOST),
                ],
            ]);
        }

        // For login from a tenant subdomain → verify ownership
        $domain = Domain::where('domain', $currentHost)
            ->where('tenant_id', $user->tenant_id)
            ->first();

        if (!$domain) {
            return response()->json([
                'message' => 'User does not belong to this tenant domain'
            ], 403);
        }

        // Return final successful response
        return response()->json([
            'user' => $user,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration') ? config('sanctum.expiration') * 1 : null,
            'tenant' => [
                'domain' => $domain->domain,
            ],
        ]);
    }


    public function refresh(Request $request)
    {
        $refreshToken = $request->bearerToken();

        if (!$refreshToken) {
            return response()->json(['message' => 'Refresh token missing'], 401);
        }

        // Find the token model from the provided plain-text token string
        $tokenModel = PersonalAccessToken::findToken($refreshToken);

        // Token not found or doesn't have refresh ability
        if (!$tokenModel || !$tokenModel->can('refresh')) {
            return response()->json(['message' => 'Invalid or expired refresh token'], 401);
        }

        $user = $tokenModel->tokenable;

        if (!$user) {
            return response()->json(['message' => 'Invalid or expired refresh token'], 401);
        }

        // Delete old access tokens (but keep refresh token alive)
        $user->tokens()->where('name', 'access-token')->delete();

        // Issue new access token
        $newAccessToken = $user->createToken('access-token')->plainTextToken;

        return response()->json([
            'access_token' => $newAccessToken,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration') ? config('sanctum.expiration') * 1 : null,
        ]);
    }

    public function logout(Request $request)
    {
        // Deletes only the current access token
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function logoutAll(Request $request) // OPTIONAL
    {
        // Deletes ALL tokens of this user (access + refresh)
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out from all devices']);
    }


    public function checkUser(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::where('username', $username)->first();

        if (!$user) {
            return 'false';
        }

        if (Hash::check($password, $user->password)) {
            return 'true';
        }

        return 'false';
    }

}
