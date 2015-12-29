<?php

namespace App\Listeners;

use App\Events\TestCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EmailTestCompleted implements ShouldQueue
{    

    public function __construct()
    {
    }

    public function handle(TestCompleted $event)
    {
        $test = $event->test;
        
        if ($test->website->user->test_notifications)
        {
            Mail::queue('emails.test_completed', compact('test'),
            function ($m) use ($test) {
                $m->to($test->website->user->email, $test->website->user->name)
                ->subject('Test "' . $test->title . '" is completed');
            });
        }
    }
}
