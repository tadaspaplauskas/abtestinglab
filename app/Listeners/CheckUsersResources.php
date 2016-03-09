<?php

namespace App\Listeners;

use App\Events\LogNewVisit;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\TestController;
use Mail;

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

        $avail = $user->getAvailableResources();
        if (/*$user->active && */$avail <= 0)
        {
            $tests = new TestController;

            foreach($user->websites as $website)
            {
                $user->active = false;
                $website->disableTests();
                $tests->refreshTestsJS($website);
            }
            //notify user that he ran out of resources
            Mail::queue('emails.out_of_resources', compact('user'),
                function ($m) use ($user) {
                    $m->to($user->email, $user->name)
                        ->subject('You ran out of resources');
                }
            );
        }
    }
}
