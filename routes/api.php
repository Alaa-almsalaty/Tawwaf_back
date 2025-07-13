<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ClientController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Central (super admin) routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tenants', TenantController::class);
});

// Tenant-scoped routes
Route::middleware([
    InitializeTenancyByDomainOrSubdomain::class,
    PreventAccessFromCentralDomains::class,
    'auth:sanctum'
])->group(function () {
    Route::apiResource('clients', ClientController::class);
    // ... other tenant routes
});

