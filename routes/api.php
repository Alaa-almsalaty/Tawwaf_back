<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Super Admin Routes
    Route::apiResource('tenants', TenantController::class);

    // Tenant Routes
    Route::middleware('tenant')->group(function () {
        //Route::apiResource('branches', BranchController::class);
        Route::apiResource('clients', ClientController::class);
});


});
