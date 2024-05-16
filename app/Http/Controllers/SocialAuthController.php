<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use App\Notifications\NewUserRegistered;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{

	/**
	 * Redirect user to given social service
	 *
	 * @param string $provider
	 * @return \Illuminate\Http\Response
	 */
    public function redirect($provider){
    	if(setting('social.'.$provider)){
    		return Socialite::driver($provider)->redirect();
    	} else {
    		return redirect('login');
    	}
    }

    /**
     * Callback function for social authentication
     */
    public function callback($provider, Request $request){

        if($request->has('error') || $request->has('denied')){
            return redirect('login#')->with('message', __('Authentication process has been canceled.'));
        }

        $userSocial =   Socialite::driver($provider)->user();

        if($userSocial->email === null){
            return redirect('login#')->with('message', __('Please provide your e-mail address!'));
        }

        $user = User::where('email', $userSocial->email)->first();

        if($user){
            Auth::login($user);
        } else {
            if(setting("social.".$provider."_register")){

                if(!setting('auth.allow_registration')){
                    return redirect('login#')->with('message', __('Registration is disabled!'));
                }
                
                //Create a new user
                $user = User::create([
                    'name' => $userSocial->name != "" ? $userSocial->name : $userSocial->nickname,
                    'email' => $userSocial->email,
                    'provider' => $provider,
                    'provider_user_id' => $userSocial->id,
                    'social_avatar' => $userSocial->avatar,
                    'email_verified_at' => Date::now(),
                ]);

                //Assign default role to newly created user
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

                Auth::login($user);
            } else {
                return redirect('login#')->with('message', __('Registration is disabled!'));
            }
        }

        return redirect('/#');
    }
}
