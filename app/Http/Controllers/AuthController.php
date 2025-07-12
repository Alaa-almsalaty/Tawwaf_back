<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->createUser());

        return  UserResource::make($user);

    }
    public function login(LoginRequest $request)
    {
        $user = $request->authenticate();

        $token = $user->createToken('RehlatyApp')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }


}
