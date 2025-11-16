<?php

namespace App\Listeners;

use App\Events\ReservationCreated;
use App\Models\User;
use App\Notifications\ReservationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendReservationCreatedNotifications
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReservationCreated $event): void
    {

        $reservation = $event->reservation;

        $package = $reservation->package;
        if (!$package) {
            Log::warning("reservation has no package associated");
            return;
        }

        $tenantId = $package->tenant_id;

        $users = User::where('tenant_id', $tenantId)
                     ->whereIn('role', ['employee', 'manager'])
                     ->get();

        if ($users->isEmpty()) {
            Log::info("No users to notify for tenant_id");
            return;
        }

        foreach ($users as $user) {
            $user->notify(new ReservationNotification($reservation, $user->id));
        }
    }
}
