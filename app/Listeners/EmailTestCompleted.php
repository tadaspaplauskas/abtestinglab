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
        $user = $test->website->user;

        if ($user->test_notifications)
        {
            Mail::queue('emails.test_completed', compact('test', 'user'),
            function ($m) use ($test, $user) {
                $m->to($user->email, $user->name)
                ->subject('Test "' . $test->title . '" is completed');
            });
        }
    }
}
