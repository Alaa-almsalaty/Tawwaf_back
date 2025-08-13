<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginRequest;
use Stancl\Tenancy\Database\Models\Domain;

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

        $token = $user->createToken('RehlatyApp')->plainTextToken;
        $tenantId = $user->tenant_id;

        // جلب أول دومين مرتبط بالتينانت
        $tenantDomain = null;
        if ($tenantId) {
            $tenantDomain = Domain::where('tenant_id', $tenantId)->value('domain');
        }

        return response()->json([
            'user' => $user,
            'token' => $token,
            'tenant' => [
                'domain' => $tenantDomain,
            ],
        ]);
}


}
