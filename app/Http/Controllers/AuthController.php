<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stancl\Tenancy\Database\Models\Domain;

class AuthController extends Controller
{

    use AuthorizesRequests;

    public function register(RegisterRequest $request)
    {
        //  $this->authorize('create', User::class);
        $user = User::create($request->createUser());
        $user->assignRole($user->role);

        return UserResource::make($user);

    }

    public function login(LoginRequest $request)
    {
        $user = $request->authenticate();

        $token = $user->createToken('RehlatyApp')->plainTextToken;

        $mainDomain = parse_url(config('app.url'), PHP_URL_HOST);

        // إذا لم يكن لدى المستخدم tenant_id، استخدم الدومين الافتراضي
        if (!$user->tenant_id) {
            return response()->json([
                'user' => $user,
                'token' => $token,
                'tenant' => [
                    'domain' => parse_url(config('app.url'), PHP_URL_HOST),
                ],
            ]);
        }

        // الحصول على الدومين الحالي من الهيدر أو من الرابط الحالي
        $currentHost = $request->header('X-Tenant-Domain');

    // لو دخل من الدومين الرئيسي، يرجع أول دومين متاح له
        if ($currentHost === $mainDomain) {
            $domain = Domain::where('tenant_id', $user->tenant_id)->first();

            return response()->json([
                'user' => $user,
                'token' => $token,
                'tenant' => [
                    'domain' => $domain?->domain ?? parse_url(config('app.url'), PHP_URL_HOST),
                ],
            ]);
        }

        // البحث عن الدومين ضمن الدومينات الخاصة بالمستخدم
        $domain = Domain::where('domain', $currentHost)
            ->where('tenant_id', $user->tenant_id)
            ->first();

        // رفض الوصول إذا الدومين غير موجود
        if (!$domain) {
            return response()->json([
                'message' => 'User does not belong to this tenant domain'
            ], 403);
        }

        return response()->json([
            'user' => $user,
            'token' => $token,
            'tenant' => [
                'domain' => $domain->domain,
            ],
        ]);
    }


    public function checkUser(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::where('username', $username)->first();

        if (!$user) {
            return 'false';
        }

        if (Hash::check($password, $user->password)) {
            return 'true';
        }

        return 'false';
    }

}
