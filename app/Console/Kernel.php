<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use App\Models\Website;
use App\Http\Controllers\TestController;

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
        
        //Cronjobs
        //clean old website tokens to logout inactive users from webpage editor.
        //regenerate js file to public one
        $schedule->call(function () {
            $dateLimit = Carbon::now()->subMinutes(60)->toDateTimeString();
            
            $websites = Website::where('updated_at', '<', $dateLimit)->get();
            
            $websites->update(['token' => '']);
            
            $testController = new TestController();
                        
            foreach ($websites as $website)
            {
                $testController->refreshTestsJS($website);
            }
            
            echo "Success\n";
        })
        ->everyMinute();
    }
}
