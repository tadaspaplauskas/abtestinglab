<?php

namespace App\Listeners;

use App\Event\ExceptionThrown;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use Auth;

class EmailAdminNotice
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
     * @param  ExceptionThrown  $event
     * @return void
     */
    public function handle(ExceptionThrown $event)
    {
        $e = $event->e;

        $user = Auth::user();
        $debug = $e->xdebug_message;

        Mail::queue('admin.email_exception', compact('debug', 'user'),
            function ($m) {
                $m->to(env('ADMIN_EMAIL'), env('ADMIN_NAME'));
                $m->subject('Problems with A/B Testing Lab');
            });
    }
}
