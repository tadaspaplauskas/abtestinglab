<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use App\Models\Website;
            
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
        
        //clean old website tokens to logout inactive users from webpage editor
        $schedule->call(function () {
            $dateLimit = Carbon::now()->subMinutes(60)->toDateTimeString();
            Website::where('updated_at', '<', $dateLimit)
                    ->update(['token' => 'null']);
            
            echo "Great success\n";
        })
        ->everyFiveMinutes();
    }
}
