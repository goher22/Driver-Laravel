<?php

namespace App\Http\Controllers;

use App\User;
use App\Elmas\Services\Authy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TwoFactorController extends Controller
{

    /**
     * Display two factor token page
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if(!session('password_validated') || !session('id')) {
            return redirect('login');
        }

        return view('auth.two_factor');
    }

    public function verifyToken(Request $request, Authy $authy){
        $user = User::find(session('id'));

        if(!$user){
            return redirect('login');
        }

        $data = $request->all();

        $validator = Validator::make($data, [
            'authy_token' => ['required', 'digits_between:6,10'],
        ]);

        $validator->validate();

        $verfiy = $authy->verifyToken($user->authy_id, $request->get('authy_token'));
        if($verfiy->ok()){
            Auth::login($user);
            return redirect('/dashboard');
        } else {
            return redirect('token')->with('message', __('The token you entered is incorrect'));
        }
    }

}
