<?php

namespace App\Http\Controllers\API;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\Website;
use Response;

abstract class ApiController extends Controller
{
    
    public function checkWebsiteOwner($websiteID)
    {
        $website = Website::find($websiteID) or $this->respondError('Website not found');

        if ($website->user_id !== Auth::user()->id)
        {
            $this->respondError('not your website');
        }
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