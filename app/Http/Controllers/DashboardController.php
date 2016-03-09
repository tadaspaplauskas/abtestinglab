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
        $stopped = $this->user->tests()->disabled()
                ->take(3)
                ->get();

        $lastUpdated = $this->user->tests()->enabled()
                ->orderBy('tests.updated_at', 'desc')
                ->take(5)
                ->get();

        return view('dashboard/index', compact('stopped', 'lastUpdated', 'idea'));
    }

}
