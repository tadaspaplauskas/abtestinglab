<?php

namespace App\Listeners;

use App\Events\UserPaymentReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailPaymentReceived
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
     * @param  UserPaymentReceived  $event
     * @return void
     */
    public function handle(UserPaymentReceived $event)
    {
        $payment = $event->payment;
        $user = $event->user;

        \Mail::queue('emails.your_payment_received', compact('payment', 'user'),
            function ($m) use ($user) {
                $m->to($user->email, $user->name)
                ->subject('Your payment has been received');
            });
    }
}
