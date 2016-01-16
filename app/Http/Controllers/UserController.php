<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Hash;

class UserController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return redirect('websites.index');
        return view('user/dashboard');
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit()
    {
        if (Session::has('user'))
        {
            $user = (object) Session::get('user');
        }
        else
        {
            $user = $this->user;
        }
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|max:50',
            'new_password' => 'min:6|same:new_password_verification',
        ]);

        $data = $request->all();
        $data['test_notifications'] = isset($data['test_notifications']);
        $data['weekly_reports'] = isset($data['weekly_reports']);
        $data['newsletter'] = isset($data['newsletter']);

        if (($data['email'] !== $this->user->email || !empty($data['new_password']))
            && (empty($data['old_password']) || !Hash::check($data['old_password'], $this->user->password)))
        {
            Session::flash('fail', 'Enter correct current password.');
        }
        else
        {
            if (!empty($data['new_password']) && $data['new_password'] === $data['new_password_verification'])
            {
                $data['password'] = Hash::make($data['new_password']);
            }

            $this->user->fill($data);

            if ($this->user->save())
            {
                Session::flash('success', 'Changes saved.');
            }
            else
            {
                Session::flash('warning', 'Something went wrong, please try again in a minute.');
            }
        }
        return redirect()->back()->with(['user' => $data]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
