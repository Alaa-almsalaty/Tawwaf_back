<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = User::query();

    if ($request->filled('q')) {
        $search = $request->query('q');

        $query->where('full_name', 'LIKE', '%' . $search . '%')
        ->orWhere('username', 'LIKE', '%' . $search . '%')
        ->orWhere('id', 'LIKE', '%' . $search . '%')
        ->orWhere('email', 'LIKE', '%' . $search . '%');
    }

    $users = $query->get();
    return $users;
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
    public function store(Request $request)
    {


    $validated = $request->validate([
        'username'    => 'required|string|max:40|unique:users,username',
        'password'    => 'required|string|min:6',
        'email'       => 'required|email|unique:users,email',
        'full_name'   => 'required|string|max:255',
        'phone'       => 'required|string|max:20',
        'role'        => 'required|in:employee,manager,super',
        'is_Active'   => 'boolean',
    ]);

    $validated['password'] = bcrypt($validated['password']);

    $user = User::create([
        'username'    => $validated['username'],
        'password'    => $validated['password'],
        'email'       => $validated['email'],
        'full_name'   => $validated['full_name'],
        'phone'       => $validated['phone'],
        'role'        => $validated['role'],
        'is_Active'   => $validated['is_Active'] ?? true,
    ]);

    return response()->json($user);
}

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
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
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validated = $request->validate([
        'username'    => 'required|string|max:40|unique:users,username,' . $user->id,
        'password'    => 'nullable|string|min:6',
        'email'       => 'required|email|unique:users,email,' . $user->id,
        'full_name'   => 'required|string|max:255',
        'phone'       => 'required|string|max:20',
        'role'        => 'required|in:employee,manager,super',
        'is_Active'   => 'boolean',
    ]);

    $user->update([
        'username'   => $validated['username'],
        'full_name'  => $validated['full_name'],
        'email'      => $validated['email'],
        'phone'      => $validated['phone'],
        'role'       => $validated['role'],
        'is_Active'  => $validated['is_Active'] ?? $user->is_Active,
    ]);

    if (!empty($validated['password'])) {
        $user->password = bcrypt($validated['password']);
        $user->save();
    }

    return response()->json($user);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
