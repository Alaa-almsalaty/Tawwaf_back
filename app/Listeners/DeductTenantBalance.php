<?php

namespace App\Listeners;

use App\Events\ClientCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Client;
use App\Models\User;
use App\Notifications\PassportBalanceNotification;

class DeductTenantBalance
{

    public function __construct()
    {

    }


    public function handle(ClientCreated $event): void
    {
        $client = $event->client;
        $tenant = $client->tenant;
        if (!$tenant) {
            Log::warning('Client created without a valid tenant');
            return;
        }

        $tenant->balance -= 1.0;
        $tenant->save();

        $manager = User::where('tenant_id', $tenant->id)
            ->where('role', 'manager')
            ->first();

        if ($manager) {
            $manager->notify(new PassportBalanceNotification($tenant->balance, $manager->id));
        }
    }

}
