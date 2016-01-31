<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Event;

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

    public function pricing()
    {
    	return view('pages.pricing');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function questions()
    {
        return view('pages.questions');
    }

    public function contacts()
    {
        return view('pages.contacts');
    }
}
