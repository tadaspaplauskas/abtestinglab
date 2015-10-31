<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Website;

class DashboardController extends Controller
{
    function __construct()
    {
        $this->user = Auth::user();
    }

    public function index()
    {
        return view('dashboard/index');
    }

}
