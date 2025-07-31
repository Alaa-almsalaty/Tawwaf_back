<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PassportBalanceNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $balance;
    private $managerId;

    public function __construct($balance, $managerId)
    {
        $this->balance = $balance;
        $this->managerId = $managerId;

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



    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
    return [
        'balance' => $this->balance,
        'manager_id' => $this->managerId,  // هنا الرقم المضاف
        'message' => $this->balance == 0
            ? 'انتهى رصيد الجوازات بالكامل.'
            : "تنبيه: تبقّى {$this->balance} جواز من الرصيد.",
        'type' => 'passport_balance',
    ];
    }
}
