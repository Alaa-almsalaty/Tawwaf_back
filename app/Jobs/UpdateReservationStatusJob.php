<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;
use App\Enums\ReservationState;

class UpdateReservationStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

        public  $reservationIds;
        public  $status;

        /**
         * Create a new job instance.
         */
        public function __construct($reservationIds, $status)
        {
            $this->reservationIds = $reservationIds;
            $this->status = $status;
        }

        /**
         * Execute the job.
         */
        public function handle(): void
        {
            $reservations = Reservation::whereIn('id', $this->reservationIds)->get();

            $reservations->each(function ($reservation) {
                $reservation->reservation_state = $this->status;
                $reservation->save();
            });
        }
}
