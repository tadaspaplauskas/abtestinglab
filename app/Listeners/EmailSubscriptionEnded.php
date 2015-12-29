<?php

namespace App\Listeners;

use App\Events\SubscriptionEnded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailSubscriptionEnded
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SubscriptionEnded  $event
     * @return void
     */
    public function handle(SubscriptionEnded $event)
    {
        //
    }
}
