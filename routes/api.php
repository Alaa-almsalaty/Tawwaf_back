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
use App\Models\User;
use Illuminate\Http\Request;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


//Central (super admin) routes
Route::middleware('auth:sanctum')->group(function () {
Route::apiResource('tenants', TenantController::class);
//Route::apiResource('users', UserController::class);

});


// Tenant-scoped routes
Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth:sanctum',
])->group(function () {
    Route::apiResource('clients', ClientController::class);

    Route::apiResource('users', UserController::class);
    Route::apiResource('branches', BranchController::class);
    Route::get('/notifications', function (Request $request) {
        // جلب إشعارات المستخدم المسجل
        return $request->user()->notifications()->latest()->get();
    });
     Route::get('/notifications/unread-count', function (Request $request) {
        return ['count' => $request->user()->unreadNotifications()->count()];
    });
Route::post('/notifications/mark-as-read', function (Request $request) {
    $user = $request->user();
    $user->unreadNotifications()->update(['read_at' => now()]);
    return response()->noContent();
});
Route::post('/notifications/{id}/mark-as-read', function ($id, Request $request) {
    $notification = $request->user()->notifications()->findOrFail($id);
    $notification->markAsRead();
    return response()->noContent();
});
Route::delete('/notifications/{id}', function ($id, Request $request) {
    $notification = $request->user()->notifications()->find($id);
    if (!$notification) {
        return response()->json(['message' => 'Notification not found'], 404);
    }
    $notification->delete();
    return response()->noContent();
});

    Route::post('/upload-passport-image', [ClientController::class, 'uploadPassportImage']);
    Route::post('/upload-personal-image', [ClientController::class, 'uploadPersonalImage']);

    Route::get('/test', function () {
        return response()->json(['message' => 'Tenant API is working and secured!']);
    });
});





