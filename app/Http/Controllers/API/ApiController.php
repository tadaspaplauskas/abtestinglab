<?php

namespace App\Http\Controllers\API;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\Website;

abstract class ApiController extends Controller
{
    
    public function checkWebsiteOwner($websiteID)
    {
        $website = Website::find($websiteID) or self::respondError('Website not found');

        if ($website->user_id !== Auth::user()->id)
        {
            self::respondError('not your website');
        }
    }
    
    public static function respondError($msg = 'error')
    {
        die(json_encode(['message' => $msg]));
    }
    
    public static function respondSuccess($data = [])
    {
        return json_encode($data);
    }
}