<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserPaymentReceived extends Event
{
    use SerializesModels;

    public $payment;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($payment)
    {
        $this->payment = $payment;
        $this->user = $payment->user;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
