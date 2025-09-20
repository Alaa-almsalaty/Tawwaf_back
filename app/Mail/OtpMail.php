<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class OtpMail extends Mailable
{
    use Queueable;
    public function __construct(public string $code, public int $ttlMinutes) {}
    public function build()
    {
        return $this->subject('Your Tawwaf verification code')
            ->view('emails.otp')
            ->with(['code' => $this->code, 'ttl' => $this->ttlMinutes]);
    }
}
