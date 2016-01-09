<?php

namespace App\Listeners;

use App\Events\TestsEnded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use File;

class TestCleanup
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
     * @param  TestsEnded  $event
     * @return void
     */
    public function handle(TestsEnded $event)
    {
        $user = $event->user;

        File::deleteDirectory($user->path());

        $user->delete();
    }
}
