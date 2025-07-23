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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
<<<<<<< HEAD
//Route::apiResource('passengers', ClientController::class);
//Route::apiResource('tenants', TenantController::class);
=======
Route::apiResource('tenants', TenantController::class);
>>>>>>> 65133738eb729ce22df01c68d3d8af37c0d7780f
// Route::apiResource('users', UserController::class);
    Route::apiResource('passengers', ClientController::class);

//Central (super admin) routes
Route::middleware('auth:sanctum')->group(function () {
<<<<<<< HEAD
    //Route::apiResource('tenants', TenantController::class);
    //Route::apiResource('clients', ClientController::class);
    Route::apiResource('tenants', TenantController::class);
Route::apiResource('users', UserController::class);
=======
    Route::apiResource('tenants', TenantController::class);
   Route::apiResource('users', UserController::class);
   Route::apiResource('companies', BranchController::class);

>>>>>>> 65133738eb729ce22df01c68d3d8af37c0d7780f
});


// Tenant-scoped routes
Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth:sanctum',  // هنا تأكد المستخدم مسجل دخول
])->group(function () {
    Route::apiResource('passengers', ClientController::class);
   // Route::apiResource('users', UserController::class);

    Route::get('/test', function () {
        return response()->json(['message' => 'Tenant API is working and secured!']);
    });
});





