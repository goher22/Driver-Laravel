<?php

namespace App\Http\Controllers;

use App\Elmas\Services\Authy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAccountController extends Controller
{
    /**
     * Show user profile page.
	 *
     * @return \Illuminate\Http\Response
     */
    public function index(){
    	return view('app.account.profile', ['user' => auth()->user()]);
    }

    /**
     * Show the form for editing account details.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = auth()->user();

        $countries = \App\Country::orderBy('name','ASC')->get();

        return view('app.account.edit', ['user' => $user, 'countries' => $countries]);
    }

    /**
     * Update account details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'not_regex:/[#$%^&*()+=\-\[\]\';,\/{}|":<>?~\\\\]/'],
            'phone' => ['max:60', 'nullable', 'not_regex:/[#$%^&*+=\\[\]\';,\/{}|":<>?~\\\\]/'],
            'address' => ['max:255', 'nullable', 'not_regex:/[#$%^&*()+=\\[\]\';\/{}|"<>?~\\\\]/'],
            'city' => ['max:60', 'nullable', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
        ]);

        $validator->validate();

        if($user->update($data)){
            return redirect('account')->with('success',__("Account details updated!"));
        } else {
            return redirect('account')->with('error',__("There has been an error!"));
        }
    }

    /**
     * Show the form for changing password.
     *
     * @return \Illuminate\Http\Response
     */
    public function password()
    {   
        $user = auth()->user();

        //Redirect to user details if it's a social account
        //Because social accounts don't need password
        if($user->provider !== null){
            return redirect('account');
        }

        return view('app.account.password', ['user' => $user]);
    }

    /**
     * Update password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function passwordUpdate(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $validator->validate();

        if(!Hash::check($data['current_password'],auth()->user()->password)){
        	return redirect('account/password')->with('error', __("Incorrect password!"));
        }

        $user = auth()->user();
        $user->password = Hash::make($data['password']);

        if($user->save()){
        	return redirect('account')->with('success',__("Your password successfully changed!"));
        } else {
            return redirect('account')->with('error',__("There has been an error!"));
        }
    }

    /**
     * Update profile photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePhoto(Request $request){
        $user = auth()->user();

        $data = $request->image;

        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);

        $data = base64_decode($data);
        $image_name = time().'.png';
        $path = storage_path() . "/app/avatars/" . $image_name;

        file_put_contents($path, $data);

        $user->photo = $image_name;
        $user->save();

        return response()->json(['success'=>'done']);
    }

    /**
     * Delete profile photo.
     *
     * @return \Illuminate\Http\Response
     */
    public function deletePhoto(){
        $user = auth()->user();

        $user->photo = null;
        $user->save();

        return redirect('account')->with('success',__("Profile photo deleted!"));
    }

    /**
     * Show two factor authentication setting page
     *
     ** @return \Illuminate\Http\Response
     */
    public function twoFactor(){

        $user = auth()->user();

        //Redirect to user details if it's a social account
        if($user->provider !== null){
            return redirect('account');
        }

        //Redirect if 2FA is disabled
        if(!setting('auth.two_factor')){
            return redirect('account');
        }

        return view('app.account.two-factor', ['user' => $user]);
    }

    /**
     * Update two factor authentication settings
     */
    public function twoFactorUpdate(Request $request, Authy $authy){
        $data = $request->all();

        $user = auth()->user();

        if($request->has('authy_enabled') && $request->get('authy_enabled') == "on"){
            $validator = Validator::make($data, [
                'authy_email' => ['required', 'email'],
                'country_code' => ['required'],
                'authy_phone' => ['required'],
            ]);

            $validator->validate();

            $register = $authy->register($data['authy_email'], $data['authy_phone'], $data['country_code']);

            if ($register->ok()) {
                $user->authy_enabled = true;
                $user->authy_id = $register->id();
                $user->authy_email = $data['authy_email'];
                $user->authy_phone = $data['authy_phone'];
                $user->country_code = $data['country_code'];
                $user->save();

                return redirect('account/two-factor')->with('success', __('Two factor authentication has been enabled.'));
            }

            return redirect('account/two-factor')->with('authy_errors', $register->errors());
        } else {
            $user->authy_enabled = false;
            $user->save();
            
            return redirect('account/two-factor')->with('success', __('Two factor authentication has been disabled.'));
        }
    }
}
