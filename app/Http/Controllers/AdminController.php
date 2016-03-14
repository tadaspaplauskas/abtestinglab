<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Controllers\Controller;
use App\User;

class AdminController extends Controller
{
    function __construct()
    {
        $this->user = Auth::user();
    }

    public function login_as(User $user)
    {
        if (!is_null($user) && Auth::check() && Auth::user()->email === env('ADMIN_EMAIL'))
        {
            Auth::login($user);
            return redirect(route('dashboard'))->with('success', 'Now you\'re logged in as ' . $user->name);
        }
        else
        {
            return 'User not found';
        }
    }

}
