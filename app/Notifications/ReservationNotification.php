<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationNotification extends Notification
{
    use Queueable;

    private $reservation;
    private $userId;

    public function __construct($reservation, $userId)
    {
        $this->reservation = $reservation;
        $this->userId = $userId;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'package_id' => $this->reservation->package_id ?? null,
            'message' => "تم إنشاء حجز جديد #{$this->reservation->id}",
            'type' => 'reservation_created',
            'alert_level' => 'info',
            'user_id' => $this->userId,
            //تحولها ل string  
            'reservation_date' => $this->reservation->reservation_date instanceof \Carbon\Carbon
                ? $this->reservation->reservation_date->toDateString()
                : $this->reservation->reservation_date,
            ];
    }
}
