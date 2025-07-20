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
            'logo' => $request->validated('logo'),
            //'created_by' => $this->validated('created_by'),
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
