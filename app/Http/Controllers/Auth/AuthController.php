<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Socialite;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectPath = '/dashboard';
    protected $loginPath = '/login';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }


    public function getLogout()
    {
        //clean tokens and shit
        if (Auth::check())
        {
            $user = Auth::user();

            foreach($user->websites as $website)
            {
                $website->token = '';
                $website->save();
            }
        }

        Auth::logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        event(new \App\Events\UserSignedUp($user));

        return $user;
    }

    public function login()
    {
        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function registerBuy()
    {
        return view('auth/register', ['buy' => 1]);
    }

    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        Auth::login($this->create($request->all()));

        session()->flash('success', 'Hi there and welcome abroad! Your first 3,000 visitors are on us.
            You may add a website and start testing right away.');

        if ($request->has('buy'))
            return redirect(route('pricing'));
        else
            return redirect(route('websites.create'));
    }

    public function redirectToProviderFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleProviderCallbackFacebook()
    {
        $user = Socialite::driver('facebook')->user();

        $token = $user->token;

        $facebook_id = $user->getId();
        $name = $user->getName();
        $email = $user->getEmail();

        if (empty($email))
        {
            return redirect($this->loginPath)->withMessage('error', 'Something went wrong, please try again');
        }

        $localUser = User::where("email", $email)->first();

        if (isset($localUser->id))
        {
            Auth::login($localUser);
        }
        else
        {
            $newUser = User::create(array(
               'name' => $name,
               'email' => $email,
               'password' => '',
            ));
            Auth::login($newUser);
        }
        return redirect($this->redirectPath);
    }

    public function redirectToProviderGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallbackGoogle()
    {
        $user = Socialite::driver('google')->user();
    }


}
