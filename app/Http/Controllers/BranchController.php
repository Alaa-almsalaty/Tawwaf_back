<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

         $query = Branch::query();

    if ($request->filled('q')) {
        $search = $request->query('q');

        $query->where('name', 'LIKE', '%' . $search . '%')
        ->orWhere('address', 'LIKE', '%' . $search . '%')
        ->orWhere('id', 'LIKE', '%' . $search . '%')
        ->orWhere('email', 'LIKE', '%' . $search . '%');
    }

    $branches = $query->get();
    return $branches;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    $validated = $request->validate([
        'name' => 'nullable|string|max:100',
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'email' => 'nullable|email|unique:branches,email',
        'subscription_status' => 'nullable|string',
        'manager_name' => 'nullable|string|max:100',
        'phone' => 'nullable|string|max:20',
        'note' => 'nullable|string',
        'active' => 'boolean',
    ]);

    $branch = Branch::create([
        'name' => $validated['name'] ?? null,
        'address' => $validated['address'],
        'city' => $validated['city'],
        'email' => $validated['email'] ?? null,
        'subscription_status' => $validated['subscription_status'] ?? null,
        'manager_name' => $validated['manager_name'] ?? null,
        'phone' => $validated['phone'] ?? null,
        'note' => $validated['note'] ?? null,
        'active' => $validated['active'] ?? true,
    ]);

    return response()->json($branch);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $branch = Branch::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:branches,email,' . $branch->id,
            'subscription_status' => 'nullable|string',
            'manager_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'Balance' => 'nullable|numeric',
            'active' => 'sometimes|required|boolean',
        ]);

        $branch->update([
            'name' => $validated['name'] ?? $branch->name,
            'city' => $validated['city'] ?? $branch->city,
            'address' => $validated['address'] ?? $branch->address,
            'email' => $validated['email'] ?? $branch->email,
            'manager_name' => $validated['manager_name'] ?? $branch->manager_name,
            'phone' => $validated['phone'] ?? $branch->phone,
            'Balance' => $validated['Balance'] ?? $branch->Balance,
            'active' => $validated['active'] ?? $branch->active,
        ]);

        return response()->json($branch);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
