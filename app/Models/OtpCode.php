<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class OtpCode extends Model
{
    protected $fillable = [
        'tenant_id',
        'notifiable_type',
        'notifiable_id',
        'reason',
        'target',
        'primary_channel',
        'fallback_channel',
        'provider_message_id',
        'code_hash',
        'expires_at',
        'consumed_at',
        'attempts',
    ];
    protected $casts = ['expires_at' => 'datetime', 'consumed_at' => 'datetime'];

    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }
    public function isConsumed(): bool
    {
        return !is_null($this->consumed_at);
    }
}
