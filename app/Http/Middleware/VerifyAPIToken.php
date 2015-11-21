<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Website;
use App\Http\Controllers\Controller;

class VerifyAPIToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //check before
        $token = $request->header('token');
        $websiteID = $request->header('website-id');
        
        if (!is_null($token) && !is_null($websiteID))
        {
            $website = Website::findOrFail($websiteID);
            if ($website->token !== $token)
            {
                return response()->json(['error' => 'token mismatch'], 401);
            }
        } else {
            return response()->json(['error' => 'no token'], 400);
        }
        
        //main stuff
        $response = $next($request);
        
        //make new token
        $token = Controller::token();
        $website->token = $token;
        $website->save();
        
        $response->header('token', $token)->header('status', 200);
        
        return $response;
    }
}
