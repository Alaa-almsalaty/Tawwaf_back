<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use App\Http\Middleware\TenantPermissionMiddleware;
use Stancl\Tenancy\Facades\Tenancy;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::apiResource('clients', ClientController::class);
Route::apiResource('tenants', TenantController::class);
// Route::apiResource('users', UserController::class);

// Central (super admin) routes
Route::middleware('auth:sanctum')->group(function () {
    //Route::apiResource('tenants', TenantController::class);
    //Route::apiResource('clients', ClientController::class);
    Route::apiResource('users', UserController::class);
});


// Tenant-scoped routes
Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class
])->group(function () {
    Route::get(
        '/test',
        function () {
            return response()->json(['message' => 'Tenant API is working! ']);
        }
    );
    Route::apiResource('passengers', ClientController::class);

});





