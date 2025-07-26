<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{

public function index(Request $request)
{
    $query = User::with('tenant');

    if ($request->filled('q')) {
        $search = $request->query('q');

        $query->where(function ($q) use ($search) {
            $q->where('full_name', 'LIKE', '%' . $search . '%')
              ->orWhere('username', 'LIKE', '%' . $search . '%')
              ->orWhere('id', 'LIKE', '%' . $search . '%')
              ->orWhere('email', 'LIKE', '%' . $search . '%');
        });

    }

    $users = $query->paginate(6);
    return UserResource::collection($users);
}



    public function show(User $user)
    {
        return new UserResource($user);
    }


public function update(UpdateUserRequest $request, User $user)
{
    $userData = $request->updateUser();
    $user->update($userData);

    return response()->json(['message' => 'User updated successfully', 'user' => $user]);
}



    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
