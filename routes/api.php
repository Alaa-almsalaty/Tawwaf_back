<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use App\Http\Middleware\TenantPermissionMiddleware;
use App\Http\Middleware\InitializeTenancyByHeader;
use Stancl\Tenancy\Facades\Tenancy;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\hotelController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\OtpController;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::get('/landing_packages', [PackageController::class, 'publicIndex']);
//Route::apiResource('tenants', TenantController::class)->only(['index']);
Route::get('/landing_tenants', [TenantController::class, 'landingTenants']);
Route::post('/visitor_register', [VisitorController::class, 'store']);
Route::post('/auth/otp/request', [OtpController::class, 'requestOtp']);
Route::post('/auth/otp/verify', [OtpController::class, 'verifyOtp']);


Route::post('/check-user', [AuthController::class, 'checkUser']);



//Central (super admin) routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::apiResource('users', UserController::class);
    Route::put('/resetPassword/{user}', [UserController::class, 'resetPassword']);
    Route::get('/allpackages', [PackageController::class, 'index']);
    Route::get('/allclients', [ClientController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);

    Route::apiResource('tenants', TenantController::class);
    Route::post('uploadLogo', [TenantController::class, 'uploadLogo']);
    Route::get('/superdashboard', [DashboardController::class, 'getDashboardData']);
    Route::get('/reservation', [ReservationController::class, 'index']);
    //  Route::apiResource('reservations', ReservationController::class);
    Route::apiResource('/visitors', VisitorController::class);
    Route::post('/visitors/{visitor}/cart', [VisitorController::class, 'addToCart']);
    Route::get('/visitors/{visitor}/cart', [VisitorController::class, 'viewCart']);
    Route::delete('/cart/{cart}', [VisitorController::class, 'removeFromCart']);
    Route::put('/profile/update-password', [UserController::class, 'updatePassword']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile/update', [UserController::class, 'update']);
    Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancelReservation']);

    Route::patch('/reservations/bulk-status', [ReservationController::class, 'bulkUpdateStatus']);
    Route::apiResource('/reservations', ReservationController::class);
    Route::patch('/reservations/{reservation}/status', [ReservationController::class, 'editStatus']);
    //User::whereName('')
    Route::get('/super-admin/dashboard', [DashboardController::class, 'getSuperAdminDashboardData']);

});


// Tenant-scoped routes
Route::middleware([
    'api',
    InitializeTenancyByHeader::class,
    PreventAccessFromCentralDomains::class,
    'auth:sanctum',
])->group(function () {
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('all-users', UserController::class);
    //Route::post('/logout', [AuthController::class, 'logout']);
    //Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    Route::put('/reset-password/{user}', [UserController::class, 'resetPassword']);
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

    Route::post('/tenants/{tenant}/balance/decrease', [TenantController::class, 'decreaseBalance']);
    Route::post('/upload-passport-image', [ClientController::class, 'uploadPassportImage']);
    Route::post('/upload-personal-image', [ClientController::class, 'uploadPersonalImage']);
    Route::get('user/profile', [UserController::class, 'profile']);
    Route::put('update/profile', [UserController::class, 'update']);
    //Route::put('/profile/update-password', [UserController::class, 'updatePassword']);
    Route::delete('/packages/rooms/{id}', [PackageController::class,'destroyRoom']);
    Route::apiResource('packages', PackageController::class);
    Route::apiResource('hotels', hotelController::class);
    // Route::apiResource('visitors', VisitorController::class);
    Route::get('/users/{userId}/clients-count', [ClientController::class, 'getClientsCountByUser']);

    Route::post('uploadImage', [PackageController::class, 'uploadImage']);


    Route::post('/adduser', [AuthController::class, 'register']);
    //Route::apiResource('passengers', ClientController::class);
    Route::apiResource('susers', UserController::class);
    Route::get('dashboard', [DashboardController::class, 'getDashboardData']);
    //Route::get('empdashboard', [DashboardController::class, 'getClientsperEmployee']);
    Route::get('/test', function () {
        return response()->json(['message' => 'Tenant API is working and secured!']);
    });
    Route::get('/employees/clients-count', [DashboardController::class, 'getClientsCountPerEmployee']);

});





