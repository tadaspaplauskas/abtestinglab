<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckAvailableResources
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
        if (Auth::check() && Auth::user()->getAvailable() > 0)
        {
            return $next($request);
        }
        else
        {
            return redirect()->back()->with('warning', 'This functionality is disabled until you have enough resources to manage tests.');
        }

    }
}
