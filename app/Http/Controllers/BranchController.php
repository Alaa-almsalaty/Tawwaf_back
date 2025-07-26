<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Resources\BranchResource;

class BranchController extends Controller
{

    public function index(Request $request)
    {
        $query = Branch::query();

        if ($request->filled('q')) {
            $search = $request->query('q');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('address', 'LIKE', '%' . $search . '%')
                    ->orWhere('id', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('city', 'LIKE', '%' . $search . '%');
            });
        }

        return BranchResource::collection($query->paginate(6));

    }

    public function store(CreateBranchRequest $request)
    {
        $branchData = $request->CreateBranchRequest();
        $branch = Branch::create($branchData);
        return BranchResource::make($branch);
    }


    public function show(Branch $branch)
    {
        // The branch is automatically injected by Laravel's route model binding
        return BranchResource::make($branch);
    }


    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $branchData = $request->UpdateBranch();
        $branch->update($branchData);
        return BranchResource::make($branch);
    }


    public function destroy(Branch $branch)
    {
        $branch->delete();
        return response()->json(['message' => 'Branch deleted successfully']);
    }
}
