<?php

namespace App\Listeners;

use App\Events\DeveloperInstructionsSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EmailInstructionsToDeveloper
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
     * @param  DeveloperInstructionsSent  $event
     * @return void
     */
    public function handle(DeveloperInstructionsSent $event)
    {
        $developer = $event->developer;
        $website = $event->website;
        $user = $event->user;

        Mail::queue('emails.developer_instructions', compact('developer', 'website', 'user'),
            function ($m) use ($developer, $user) {
                $m->to($developer->email, $developer->name)
                ->subject($user->name . ' would like you to set up AB Testing Lab script on the website');
            });
    }
}
