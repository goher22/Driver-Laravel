<?php

namespace App\Http\Controllers\Auth;

use App\Role;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Notifications\NewUserRegistered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validate = [
            'name' => ['required', 'string', 'max:255', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if(setting('auth.recaptcha') && setting('auth.recaptcha_key')) {
            $validate['g-recaptcha-response'] = 'required|recaptcha';
        }

        return Validator::make($data, $validate);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $default_role_setting =  setting('app.default_role');

        $role = Role::find($default_role_setting);
        if($role){
            $user->assignRole($role);
        }

        if(setting('app.notify_admin_for_new_users')){
            if($superadmin = User::where('is_superadmin', 1)->first()){
                $superadmin->notify(new NewUserRegistered($superadmin));
            }
        }

        return $user;
    }
}
