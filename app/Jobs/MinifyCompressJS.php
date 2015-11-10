<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use MatthiasMullie\Minify;

class MinifyCompressJS extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $path;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $minifier = new Minify\JS($this->path);
        $return = $minifier->minify($this->path);
        
        //gzip if success
        if ($return)
        {
            $return = $minifier->gzip($this->path . '.gz', 9);
            //return true;
        }
    }
}
