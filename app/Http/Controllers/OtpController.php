<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Otp\OtpService;
use Illuminate\Http\Request;
use App\Models\User;

class OtpController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    public function requestOtp(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required','string'], // send E.164 (+218...); validate properly in your project
            'email' => ['nullable','email'],
            'reason'=> ['nullable','string'],
        ]);

        $user = $request->user(); // or locate a user by phone/email for public flows
        $result = $this->otpService->send(
            user: $user,
            phoneE164: $data['phone'],
            email: $data['email'] ?? null,
            reason: $data['reason'] ?? 'login',
            tenantId: tenant('id') ?? null, // if using Stancl Tenancy
        );

        return response()->json([
            'status' => 'ok',
            'sent_via' => $result['via'],
            'expires_at' => $result['expires_at'],
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'exists:users,id'],
            'code' => ['required','string'],
            'reason' => ['nullable','string'],
        ]);
        $user = User::find($request->id);
        //dd($user);
        $ok = $this->otpService->verify($user, $data['code'], $data['reason'] ?? 'login');

        if ($ok){
            $user->is_Active = true ;
            $user->save();
        }

        return $ok
            ? response()->json(['status' => 'verified'])
            : response()->json(['status' => 'invalid_or_expired'], 422);
    }
}
