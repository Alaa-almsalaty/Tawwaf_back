<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Support\Facades\Log;

class TenantController extends Controller
{

    public function index()
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $tenants = Tenant::all();
        return response()->json($tenants);
    }


    public function store(CreateCompanyRequest $request)
    {
        Log::info('Inside TenantController@store');

        // Create tenant with only ID and 'data' column
        $tenant = Tenant::create([
            'id' => (string) \Str::uuid(),
            'data' => $request->toArrayForTenant(),
        ]);

        // Generate domain using company name stored in data column
        $companyName = $tenant->getAttribute('company_name');
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
        return response()->json($tenant);
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
