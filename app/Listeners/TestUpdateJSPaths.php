<?php

namespace App\Listeners;

use App\Events\TestUserCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestUpdateJSPaths
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
     * @param  TestUserCreated  $event
     * @return void
     */
    public function handle(TestUserCreated $event)
    {
        $user = $event->user;
        $files = $event->files;

        foreach ($user->websites as $website)
        {
            foreach ($files as $file)
            {
                $content = file_get_contents($file);

                //remove old scripts if any
                $content = preg_replace("/<!--TEST SCRIPT BEGIN-->(.*?)<!--TEST SCRIPT END-->/",
                             '<!--TEST SCRIPT-->',
                             $content);

                //push new code
                $content = str_replace('<!--TEST SCRIPT-->',
                               '<!--TEST SCRIPT BEGIN-->'
                               . $website->jsCode()
                               . '<!--TEST SCRIPT END-->', $content);

                file_put_contents($file, $content);
            }
        }
    }
}
