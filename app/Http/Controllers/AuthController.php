<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{

    use AuthorizesRequests;

    public function register(RegisterRequest $request)
    {
        $this->authorize('create', User::class);
        $user = User::create($request->createUser());
        $user->assignRole($user->role);

        return UserResource::make($user);

    }
    public function login(LoginRequest $request)
    {
        $user = $request->authenticate();

        $token = $user->createToken('RehlatyApp')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }


}
