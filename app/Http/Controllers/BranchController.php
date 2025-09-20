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
        $search = trim((string) $request->query('q'));
        $query = Branch::query()
        ->with('tenant')
        ->search($search)
        ->latest('id')
        ->paginate(6);

        return BranchResource::collection($query);

    }

    public function store(CreateBranchRequest $request)
    {
        $branchData = $request->CreateBranchRequest();
        $branch = Branch::create($branchData);
        return BranchResource::make($branch);
    }


    public function show(Branch $branch)
    {
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
