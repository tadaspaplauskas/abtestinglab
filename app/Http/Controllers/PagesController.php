<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Event;
use App\Events\TestCompleted;
use App\Models\Test;

class PagesController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }
    
    public function index()
    {
        return view('pages.index');
        
    }
}
