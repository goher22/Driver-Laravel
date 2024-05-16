<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
	
    /**
     * Register api
	 *
     * @return \Illuminate\Http\Response 
     */
    public function register(Request $request){
    	$validator = Validator::make($request->all(), [
	        'name' => ['required', 'string', 'max:255', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
	    ]);

	    if ($validator->fails())
	    {
	        return response(['errors'=>$validator->errors()->all()], 422);
	    }

	    $data = $request->all();

	    $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if($user){
        	//Send verification email
            if(setting('auth.email_verification')){
                $user->sendEmailVerificationNotification();
            }
        }

        return response()->json(['message' => __("User created!")], 200);
    }

    /**
     * User login api
     * 
     * @return \Illuminate\Http\Response 
     */
    public function login(Request $request){
    	$request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials)) {
        	return response()->json(['message' => trans('auth.failed')], 401);
        }

        $token = auth()->user()->createToken(setting('app.name').' Password Grant Client')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * User logout api
     * 
     * @return \Illuminate\Http\Response 
     */
    public function logout(Request $request){
    	$user = auth()->user()->token()->revoke();

    	return response()->json(['message' => __('Successfully logged out.')], 200);
    }
}
