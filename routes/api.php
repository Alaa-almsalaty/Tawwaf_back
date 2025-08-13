<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use App\Http\Middleware\TenantPermissionMiddleware;
use Stancl\Tenancy\Facades\Tenancy;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;


Route::post('/login', [AuthController::class, 'login']);
Route::apiResource('clients', ClientController::class);
Route::apiResource('branches', BranchController::class);


//Central (super admin) routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('tenants', TenantController::class);
    Route::get('/superdashboard', [DashboardController::class, 'getDashboardData']);
});


// Tenant-scoped routes
Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth:sanctum',
])->group(function () {
    Route::post('/adduser', [AuthController::class, 'register']);
    //Route::apiResource('passengers', ClientController::class);
    Route::apiResource('susers', UserController::class);
    Route::get('dashboard', [DashboardController::class, 'getDashboardData']);
    Route::get('empdashboard', [DashboardController::class, 'getClientsperEmployee']);
    Route::get('/test', function () {
        return response()->json(['message' => 'Tenant API is working and secured!']);
    });
});





