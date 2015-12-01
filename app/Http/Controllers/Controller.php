<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

/*abstract*/ class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;
    
    private $user;
    
    //kick in after n views. Arbitrary number, FIXME
    const ADAPTIVE_CONVERSIONS_BOUNDARY = 20;
    
    public static function token()
    {
        return str_random(40);
    }
    
    public static function filePut($path, $content, $lock = LOCK_EX)
    {
        $dir = dirname($path);
        if (!is_dir($dir))
        {
            mkdir($dir);
        }
        return file_put_contents($path, $content, $lock);
    }
}
