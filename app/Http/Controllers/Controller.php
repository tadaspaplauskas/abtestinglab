<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Response;

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

    public function respondError($msg = 'error')
    {
        return Response::json(['message' => $msg], 500);
    }

    public function respondSuccess($data = [])
    {
        return Response::json($data, 200);
    }

}