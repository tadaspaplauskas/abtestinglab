<?php

namespace App\Listeners;

use App\Events\UserSignedUp;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EmailWelcome
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
     * @param  UserSignedUp  $event
     * @return void
     */
    public function handle(UserSignedUp $event)
    {
        $user = $event->user;

        Mail::queue('emails.welcome', compact('user'),
            function ($m) use ($user) {
                $m->to($user->email, $user->name)
                ->subject('Welcome to A/B Testing Lab');
            });
    }
}
