<?php

namespace App\Listeners;

use App\Events\TestCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Test;

use Mail;

class EmailCompletedTestNotification implements ShouldQueue
{    

    public function __construct()
    {
    }

    public function handle(TestCompleted $event)
    {
        $test = $event->test;
        
        Mail::queue('emails.test_completed', compact('test'),
        function ($m) use ($test) {
            $m->to($test->website->user->email, $test->website->user->name)
            ->subject('Test "' . $test->title . '" is completed');
        });
    }
}
