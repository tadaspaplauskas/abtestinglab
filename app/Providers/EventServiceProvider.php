<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\TestCompleted' => [
            'App\Listeners\EmailTestCompleted',
        ],
        'App\Events\UserSignedUp' => [
            'App\Listeners\EmailWelcome',
        ],
        'App\Events\SubscriptionEnded' => [
            'App\Listeners\EmailSubscriptionEnded',
        ],
        'App\Events\UserPaymentReceived' => [
            'App\Listeners\RefreshUserResources',
            'App\Listeners\EmailPaymentReceived',
            'App\Listeners\CheckAvailableResourcesNotice',
        ],
        'App\Events\LogNewVisit' => [
            'App\Listeners\CheckUsersResources',
        ],
        'App\Events\TestsModified' => [
            'App\Listeners\CheckAvailableResourcesNotice',
        ],
        'App\Events\DeveloperInstructionsSent' => [
            'App\Listeners\EmailInstructionsToDeveloper',
        ],

        /* testing stuff */
        'App\Events\TestUserCreated' => [
            'App\Listeners\TestUpdateJSPaths'
        ],
        'App\Events\TestsEnded' => [
            'App\Listeners\TestCleanup'
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
