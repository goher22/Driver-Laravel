<?php

namespace App\Http\Controllers\Auth;

use App\Elmas\Services\Authy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $validate = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        if(setting('auth.recaptcha') && setting('auth.recaptcha_key')) {
            $validate['g-recaptcha-response'] = 'required|recaptcha';
        }

        $request->validate($validate);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if(!setting('auth.two_factor') || !$user->isTwoFactorEnabled()){
            return redirect()->intended($this->redirectTo);
        }

        $authy = new Authy;
        $status = $authy->verifyUserStatus($user->authy_id);

        if($status->ok() && $status->bodyvar('status')->registered) {
            Auth::logout();

            session()->put('password_validated', true);
            session()->put('id', $user->id);

            $sms = $authy->sendToken($user->authy_id);

            if($sms->ok()){
                return redirect('/token');
            }
        } else {
             Auth::logout();
            return redirect('login')->with('message', __('Could not confirm Authy status!'));
        }
    }
}
