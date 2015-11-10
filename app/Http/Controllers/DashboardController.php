<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\Test;

class DashboardController extends Controller
{
    function __construct()
    {
        $this->user = Auth::user();
    }

    public function index()
    {

        //stopped since last activity
        $stopped = Test::disabled()
                ->my()
                ->where('updated_at', '<=', $this->user->last_activity)
                ->get();
        
        $lastUpdated = Test::enabled()
                ->my()
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
        
        return view('dashboard/index', compact('stopped', 'lastUpdated', 'idea'));
    }

}
