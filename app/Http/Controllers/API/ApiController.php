<?php

namespace App\Http\Controllers\API;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\Website;

abstract class ApiController extends Controller
{

    public function checkWebsiteOwner($websiteID)
    {
        $website = Website::find($websiteID) or $this->respondError('Website not found');

        if (!Auth::check() || $website->user_id !== Auth::user()->id)
        {
            return $this->respondError('not your website');
        }
    }
}