<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

/*abstract*/ class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;
    
    private $user;
    const JS_FILENAME = 'tests.js';
    const USERS_PATH = 'users/';
    
    public static function token()
    {
        return str_random(40);
    }
}
