<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderNotificationJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $user;

    protected $order;

    public function __construct($user, $order)
    {
        $this->user = $user;

        $this->order = $order;
    }

    public function handle(): void
    {
        $this->user->notify(

            new OrderPlacedNotification(
                $this->order
            )
        );
    }
}