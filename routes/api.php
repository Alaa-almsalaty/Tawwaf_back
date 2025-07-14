<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use App\Http\Middleware\TenantPermissionMiddleware;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ClientController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::apiResource('tenants', TenantController::class);

// Central (super admin) routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tenants', TenantController::class);
    Route::apiResource('clients', ClientController::class);
});

/*
Route::middleware([
    InitializeTenancyBySubdomain::class,
])
    ->prefix('v1')
    ->group(function () {


        Route::middleware([
            'auth:sanctum',
        ])->group(function () {
            //Route::apiResource('clients' , ClientController::class);
        });
    });

// Tenant-scoped routes
Route::middleware([
    InitializeTenancyByDomainOrSubdomain::class,
    PreventAccessFromCentralDomains::class,
    'auth:sanctum'
])->group(function () {
    //Route::apiResource('clients', ClientController::class);
    // ... other tenant routes
});*/

