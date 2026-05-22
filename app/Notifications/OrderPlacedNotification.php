<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderPlacedNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    // DATABASE + MAIL
    public function via(object $notifiable): array
    {
        return [

            'database', //mail etc we used if we want to send email or sms etc
        ];
    }

    // DATABASE DATA
    public function toArray(object $notifiable): array
    {
        return [

            'order_id' => $this->order->id,

            'message' => 'Your order placed successfully',

            'total' => $this->order->total,
        ];
    }
}