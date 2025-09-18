<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\User;
use App\Models\Tenant;
use App\Http\Resources\EmployeeClientsCountResource;



class DashboardController extends Controller
{

    // Manager dashboard
    public function getDashboardData()
    {
        if (!auth()->user()->hasRole('manager')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $tenantId = auth()->user()?->tenant_id;

        $clientsCount = Client::where('tenant_id', $tenantId)->count();
        $branchesCount = Client::where('tenant_id', $tenantId)->distinct('branch_id')->count('branch_id');
        $clientsCountperBranch = Client::with('branch')->groupBy('branch_id')
            ->selectRaw('branch_id, COUNT(*) as count')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->branch_id => $item->count];
            });

        $balance = auth()->user()?->tenant?->balance ?? 0;

        // $employees = User::where('tenant_id', $tenantId)
        //     ->where('role', 'employee')
        //     ->get();

        return response()->json([
            'clients_count' => $clientsCount,
            'branches_count' => $branchesCount,
            'clients_count_per_branch' => $clientsCountperBranch,
            'balance' => $balance,
            // 'employees_clients' => $employees->map(function ($employee) {
            //     return [
            //         'id' => $employee->id,
            //         'full_name' => $employee->full_name,
            //         'clients_count' => $employee->clients()->count(),
            //     ];
            // }),

        ]);
    }

    // // Employee dashboard
    // public function getClientsperEmployee()
    // {
    //     if (!auth()->user()->hasRole('employee')) {
    //         return response()->json(['message' => 'Unauthorized'], 403);
    //     }
    //     $userId = auth()->user()->id;
    //     $clientsCount = Client::where('created_by', $userId)->count();
    //     return response()->json([
    //         'clients_count' => $clientsCount
    //     ]);
    // }

    // Super admin dashboard
    public function getSuperAdminDashboardData(){
        if (!auth()->user()->IsSuperAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
       $companiesCount = Tenant::count();

       $companiesBalance = Tenant::where('data->balance','<=',5 )
            ->get()
            ->map(function ($tenant) {
                return [
                    'id' => $tenant->id,
                    'name' => $tenant->company_name,
                    'balance' => $tenant->balance,
                ];
            });


        $Balance = Tenant::sum('data->balance');

        return response()->json([
            'companies_count' => $companiesCount,
            'total_balance' => $Balance,
            'companies_balance' => $companiesBalance,

        ]);

    }

    public function getClientsCountPerEmployee()
    {
        if (!auth()->user()->hasRole('manager') && !auth()->user()->IsSuperAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $tenantId = auth()->user()?->tenant_id;

        $employees = User::where('tenant_id', $tenantId)
            ->where('role', 'employee')
            ->withCount('clients')
            ->get();

        return EmployeeClientsCountResource::collection($employees);
    }

}
