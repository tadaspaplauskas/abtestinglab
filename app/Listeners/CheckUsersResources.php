<?php

namespace App\Listeners;

use App\Events\LogNewVisit;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\TestController;

class CheckUsersResources
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
     * @param  LogNewVisit  $event
     * @return void
     */
    public function handle(LogNewVisit $event)
    {
        $user = $event->user;

        $user->increment('used_reach');

        if ($user->getAvailableResources() === 0)
        {
            $tests = new TestController;

            foreach($user->websites as $website)
            {
                $website->disableTests();
                $tests->refreshTestsJS($website);//implement checks on manage/enable/add website
            }

        }
    }
}
