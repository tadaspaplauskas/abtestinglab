<?php

namespace App\Listeners;

use App\Events\UserPaymentReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Payment;

class RefreshUserResources
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
        $user = $event->user;

        $user->total_available_reach = $user->payments()->sum('visitors');

        if ($user->getAvailableResources() > 0)
            $user->active = true;

        $user->save();
    }
}