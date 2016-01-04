<?php

namespace App\Listeners;

use App\Events\ResourcesUsed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckAvailableResourcesNotice
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
     * @param  ResourcesUsed  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;

        $needed = $user->getCurrentlyNeededResources();

        if ($needed > $user->getAvailableResources())
        {
            $user->low_resources_notice = 1;
        }
        else
        {
            $user->low_resources_notice = 0;
        }
        $user->save();
    }
}
