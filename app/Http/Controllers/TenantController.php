<?php

namespace App\Http\Controllers;

use App\Http\Resources\TenantResource;
use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TenantController extends Controller
{

    public function index(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = Tenant::query();

        if ($request->filled('q')) {
            $search = $request->input('q');

            $query->where(function ($q) use ($search) {
                $q->where('data->company_name', 'like', "%$search%")
                    ->orWhere('data->manager_name', 'like', "%$search%")
                    ->orWhere('data->email', 'like', "%$search%")
                    ->orWhere('data->phone', 'like', "%$search%");
            });
        }

        if ($request->filled('season')) {
            $query->where('data->season', $request->input('season'));
        }

        $tenants = $query->paginate(6);
        return TenantResource::collection($tenants);
    }


    public function store(CreateCompanyRequest $request)
    {
        $tenant = Tenant::create([
            'id' => (string) \Str::uuid(),
            'company_name' => $request->validated('company_name'),
            'address' => $request->validated('address'),
            'city' => $request->validated('city'),
            'email' => $request->validated('email'),
            'status' => $request->validated('status'),
            'balance' => $request->validated('balance'),
            'manager_name' => $request->validated('manager_name'),
            'phone' => $request->validated('phone'),
            'note' => $request->validated('note'),
            'active' => $request->validated('active'),
            'logo' => $request->validated('logo'),
            'created_by' => $request->validated('created_by')
        ]);

        // Generate domain using company name stored in data column
        $companyName = $tenant->company_name ?? 'default_company';

        $domain = strtolower(preg_replace('/\s+/', '', $companyName)) . '.localhost';

        // Attach domain
        $tenant->domains()->create([
            'domain' => $domain,
        ]);

        return response()->json([
            'message' => 'Tenant created successfully',
            'tenant' => $tenant,
            'domain' => $domain,
        ], 201);
    }


    public function show(Tenant $tenant)
    {
        // The tenant is automatically injected by Laravel's route model binding
        return new TenantResource($tenant);
    }


    public function update(UpdateCompanyRequest $request, Tenant $tenant)
    {
        $tenantData = $request->UpdateCompany();
        $tenant->update($tenantData);

        return response()->json(['message' => 'Tenant updated successfully', 'tenant' => $tenant]);
    }


    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return response()->json(['message' => 'Tenant deleted successfully']);
    }
}
