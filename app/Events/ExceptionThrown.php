<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ExceptionThrown extends Event
{
    use SerializesModels;

    public $e;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($e)
    {
        $this->e = $e;
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
