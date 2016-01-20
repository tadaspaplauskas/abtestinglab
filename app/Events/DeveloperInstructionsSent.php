<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User;

class DeveloperInstructionsSent extends Event
{
    use SerializesModels;

    public $developer;
    public $website;
    public $user;

    public function __construct(\App\Models\Developer $developer)
    {
        $this->developer = $developer;
        $this->website = $developer->website;
        $this->user = $developer->user;
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
