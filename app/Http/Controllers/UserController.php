<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

   // $users = $query->get();
    $users = $query->paginate(6); // يعيد 10 عناصر فقط لكل صفحة
    return response()->json($users);
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest  $request)
    {
        $validated = $request->validated();

        $validated['password'] = bcrypt($validated['password']);

        $user = User::create([
            'username'    => $validated['username'],
            'password'    => $validated['password'],
            'email'       => $validated['email'],
            'full_name'   => $validated['full_name'],
            'phone'       => $validated['phone'],
            'role'        => $validated['role'],
            'tenant_id'   => $validated['tenant_id'],
            'is_Active'   => $validated['is_Active'] ?? true,
        ]);

        return response()->json($user);
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
public function update(UpdateUserRequest $request, User $user)
{
    $userData = $request->updateUser();
    $user->update($userData);

    return response()->json(['message' => 'User updated successfully', 'user' => $user]);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
