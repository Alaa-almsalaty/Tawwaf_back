<?php
namespace App\Services\Otp;

use App\Mail\OtpMail;
use App\Models\OtpCode;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;
use App\Models\User;

class OtpService
{
    public function __construct(private WhatsappProvider $whatsapp) {}

    public function send(
        User $user,
        string $phoneE164,
        ?string $email = null,
        string $reason = 'login',
        ?string $tenantId = null
    ): array {
        $length = (int) config('services.otp.length', 6);
        $ttl = (int) config('services.otp.ttl', 5);

        $code = str_pad((string) random_int(0, (10 ** $length) - 1), $length, '0', STR_PAD_LEFT);
        $hash = hash('sha256', $code);

        $record = null;
        $sentVia = 'whatsapp';

        DB::transaction(function () use ($user, $reason, $phoneE164, $tenantId, $hash, $ttl, &$record) {
            $record = OtpCode::create([
                'tenant_id'        => $tenantId,
                'notifiable_type'  => get_class($user),
                'notifiable_id'    => $user->getAuthIdentifier(),
                'reason'           => $reason,
                'target'           => $phoneE164,
                'primary_channel'  => 'whatsapp',
                'fallback_channel' => 'email',
                'code_hash'        => $hash,
                'expires_at'       => now()->addMinutes($ttl),
            ]);
        });

        try {
            $messageId = $this->whatsapp->sendTemplateOtp($phoneE164, $code);
            $record->update(['provider_message_id' => $messageId]);
            // Success via WhatsApp
        } catch (Throwable $e) {
            // Immediate fallback to email
            if ($email) {
                Mail::to($email)->send(new OtpMail($code, $ttl));
                $sentVia = 'email';
            } else {
                // If no email available, surface the error (or log and rethrow)
                throw $e;
            }
        }

        return [
            'via'        => $sentVia,
            'expires_at' => $record->expires_at->toIso8601String(),
        ];
    }

    public function verify(User $user, string $code, string $reason = 'login'): bool
    {
        $hash = hash('sha256', $code);

        $otp = OtpCode::where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->getAuthIdentifier())
            ->where('reason', $reason)
            ->orderByDesc('id')
            ->first();

        if (!$otp || $otp->isConsumed() || $otp->isExpired()) {
            return false;
        }

        $otp->increment('attempts');

        if (hash_equals($otp->code_hash, $hash)) {
            $otp->update(['consumed_at' => now()]);
            return true;
        }

        return false;
    }
}
