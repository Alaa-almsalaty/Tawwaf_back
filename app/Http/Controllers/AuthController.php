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
use App\Http\Resources\AuthResource;
use App\Services\Auth\LoginService;

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

    public function login(LoginRequest $request, LoginService $loginService)
    {
        $user = $request->authenticate()->load('tenant');

        $data = $loginService->handle($user, $request);

        return new AuthResource(
            $data['user'],
            $data['accessToken'],
            $data['refreshToken'],
            $data['domain']
        );
    }



    public function refresh(Request $request)
    {
        return new AuthResource(
            $request->auth_user,
            $request->new_access_token,
            null,
            $request->tenant_domain
        );
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


    //Tamer win app
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

    public function validateUser(Request $request)
    {
        $user = auth()->user();
        return new UserResource($user);
    }

}
