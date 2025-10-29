<?php

namespace App\Http\Controllers;

use App\Http\Resources\TenantResource;
use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{

    public function index(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = Tenant::query();
        // سيرش عادي
        if ($request->filled('q')) {
            $search = $request->input('q');

            $query->where(function ($q) use ($search) {
                $q->where('data->company_name', 'like', "%$search%")
                    ->orWhere('data->manager_name', 'like', "%$search%")
                    ->orWhere('data->email', 'like', "%$search%")
                    ->orWhere('data->phone', 'like', "%$search%");
            });
        }
        //تصفية حسب الموسم
        if ($request->filled('season')) {
            $query->where('data->season', $request->input('season'));
        }
        // تصفية حسب الحالة نشط/غير نشط
        if ($request->has('active')) {
            $query->where('data->active', $request->boolean('active'));
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
            'season' => $request->validated('season'),
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

     public function decreaseBalance(Request $request, Tenant $tenant)
    {
        // Optional: Check role, ensure only admin can do this
        if (!$request->user()->hasRole('super')) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 403);
        }


        return DB::transaction(function () use ($tenant) {
            // Lock the tenant row for safe balance update
            $t = Tenant::where('id', $tenant->id)->lockForUpdate()->first();

            if ($t->balance <= 0) {
                return response()->json([
                    'can_decrease' => false,
                    'message' => 'Insufficient balance',
                    'balance' => $t->balance
                ], 400);
            }

            $t->balance -= 1;
            $t->save();

            return response()->json([
                'can_decrease' => true,
                'message' => 'Balance decreased successfully',
                'balance' => $t->balance
            ]);
        });
    }



    public function uploadLogo(Request $request)
    {
        if (!$request->hasFile('logo')) {
            return response()->json(['error' => 'No file uploaded'], 400);
            // throw ValidationException::withMessages(['file' => 'No file uploaded']);
        }

        $file = $request->file('logo');
        $imageName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

        $destination = public_path("Logos");
        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        $file->move($destination, $imageName);

        // يعيد المسار للفرونت
        return response()->json([
            'path' => "/Logos/$imageName"
        ]);
    }

    public function landingTenants()
    {
        $tenants = Tenant::select('data->company_name as company_name', 'data->logo as logo')->get();
        return TenantResource::collection($tenants);
    }



public function uploadLogo(Request $request)
{
    if (!$request->hasFile('logo')) {
        return response()->json(['error' => 'No file uploaded'], 400);
        // throw ValidationException::withMessages(['file' => 'No file uploaded']);
    }

    $file = $request->file('logo');
    $imageName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

    $destination = public_path("Logos");
    if (!file_exists($destination)) {
        mkdir($destination, 0777, true);
    }

    $file->move($destination, $imageName);

    // يعيد المسار للفرونت
    return response()->json([
        'path' => "/Logos/$imageName"
    ]);
}

public function landingTenants()
{
    $tenants = Tenant::select('data->company_name as company_name', 'data->logo as logo')->get();
    return TenantResource::collection($tenants);
}
}
