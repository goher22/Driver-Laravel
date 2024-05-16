<?php

namespace App\Http\Controllers;

use App\Role;
use App\Mail\TestEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Elmas\Tools\PassportInstaller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Barryvdh\TranslationManager\Models\Translation;

class SettingsController extends Controller
{
    
    /**
     * Show application settings page
     * 
     */
    public function app(){
    	return view('app.settings.app', ['roles' => Role::get()]);
    }

    /**
     * Update application settings
     */
    public function updateAppSettings(Request $request){

    	$data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
        ]);

        $validator->validate();

        setting(['app.name' => $data['name']])->save();
        setting(['app.locale' => $data['locale']])->save();
        setting(['app.default_role' => $data['default_role']])->save();

        //Enable/disable API
        if(isset($data['enable_api']) && $data['enable_api'] == "on"){
            setting(['app.enable_api' => true])->save();

            $passport = new PassportInstaller;
            $passport->installClients();
            
        } elseif(!isset($data['enable_api'])){
            setting(['app.enable_api' => false])->save();
        }

        //Enable/disable notify admins when new user registered
        if(isset($data['notify_admin_for_new_users']) && $data['notify_admin_for_new_users'] == "on"){
            setting(['app.notify_admin_for_new_users' => true])->save();
        } elseif(!isset($data['notify_admin_for_new_users'])){
            setting(['app.notify_admin_for_new_users' => false])->save();
        }

        Artisan::call('route:clear');

        return redirect('settings/app')->with('success',__('Settings updated!'));
    }

    /**
     * Show authentication settings page
     */
    public function auth(){
    	return view('app.settings.auth');
    }

    /**
     * Update authentication settings
     */
    public function updateAuthSettings(Request $request){

    	$data = $request->all();

    	//Enable/disable registration
    	if(isset($data['allow_registration']) && $data['allow_registration'] == "on"){
    		setting(['auth.allow_registration' => true])->save();
    	} elseif(!isset($data['allow_registration'])){
    		setting(['auth.allow_registration' => false])->save();
    	} 

    	//Enable/disable e-mail verification
    	if(isset($data['email_verification']) && $data['email_verification'] == "on"){
    		setting(['auth.email_verification' => true])->save();
    	} elseif(!isset($data['email_verification'])){
    		setting(['auth.email_verification' => false])->save();
    	} 

    	//Show/hide remember me
    	if(isset($data['remember_me']) && $data['remember_me'] == "on"){
    		setting(['auth.remember_me' => true])->save();
    	} elseif(!isset($data['remember_me'])){
    		setting(['auth.remember_me' => false])->save();
    	}

    	//Show/hide forgot password
    	if(isset($data['forgot_password']) && $data['forgot_password'] == "on"){
    		setting(['auth.forgot_password' => true])->save();
    	} elseif(!isset($data['forgot_password'])){
    		setting(['auth.forgot_password' => false])->save();
    	} 

        return redirect('settings/auth')->with('success',__('Settings updated!'));
    }

    /**
     * Show email settings page
     */
    public function email(){
        return view('app.settings.email');
    }

    /**
     * Update email settings
     */
    public function updateEmailSettings(Request $request){

        $data = $request->all();

        $validator = Validator::make($data, [
            'MAIL_FROM_NAME' => ['required', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
            'MAIL_FROM_ADDRESS' => ['required', 'email'],
        ]);

        $validator->validate();

        if($data['MAIL_DRIVER'] == "smtp"){
            setting(['email.MAIL_DRIVER' => "smtp"])->save();

            $validator = Validator::make($data, [
                'MAIL_HOST' => ['required', 'string'],
                'MAIL_PORT' => ['required', 'integer'],
                'MAIL_USERNAME' => ['required', 'string'],
                'MAIL_PASSWORD' => ['required', 'string'],
                'MAIL_ENCRYPTION' => ['required', 'string'],
            ]);

            $validator->validate();

            setting(['email.MAIL_HOST' => $data['MAIL_HOST']])->save();
            setting(['email.MAIL_PORT' => $data['MAIL_PORT']])->save();
            setting(['email.MAIL_USERNAME' => $data['MAIL_USERNAME']])->save();
            setting(['email.MAIL_PASSWORD' => $data['MAIL_PASSWORD']])->save();
            setting(['email.MAIL_ENCRYPTION' => $data['MAIL_ENCRYPTION']])->save();
        }

        setting(['email.MAIL_DRIVER' => $data['MAIL_DRIVER']])->save();
        setting(['email.MAIL_FROM_NAME' => $data['MAIL_FROM_NAME']])->save();
        setting(['email.MAIL_FROM_ADDRESS' => $data['MAIL_FROM_ADDRESS']])->save();


        return redirect('settings/email')->with('success',__('Settings updated!'));
    }

    /**
     * Show social authentication settings page
     */
    public function social(){
        return view('app.settings.social');
    }

    /**
     * Update social authentication settings
     */
    public function updateSocialAuthSettings(Request $request){
        $data = $request->all();

        //Enable/disable facebook auth
        if(isset($data['facebook']) && $data['facebook'] == "on"){
            setting(['social.facebook' => true])->save();
            setting(['social.facebook_client_id' => $data['facebook_client_id']])->save();
            setting(['social.facebook_client_secret' => $data['facebook_client_secret']])->save();
            
            if(isset($data['facebook_register']) && $data['facebook_register'] == "on"){
                setting(['social.facebook_register' => $data['facebook_register']])->save();
            } else {
                setting(['social.facebook_register' => false])->save();
            }
        } elseif(!isset($data['facebook'])){
            setting(['social.facebook' => false])->save();
        }

        //Enable/disable google auth
        if(isset($data['google']) && $data['google'] == "on"){
            setting(['social.google' => true])->save();
            setting(['social.google_client_id' => $data['google_client_id']])->save();
            setting(['social.google_client_secret' => $data['google_client_secret']])->save();

            if(isset($data['google_register']) && $data['google_register'] == "on"){
                setting(['social.google_register' => $data['google_register']])->save();
            } else {
                setting(['social.google_register' => false])->save();
            }
        } elseif(!isset($data['google'])){
            setting(['social.google' => false])->save();
        }

        //Enable/disable twitter auth
        if(isset($data['twitter']) && $data['twitter'] == "on"){
            setting(['social.twitter' => true])->save();
            setting(['social.twitter_client_id' => $data['twitter_client_id']])->save();
            setting(['social.twitter_client_secret' => $data['twitter_client_secret']])->save();

            if(isset($data['twitter_register']) && $data['twitter_register'] == "on"){
                setting(['social.twitter_register' => $data['twitter_register']])->save();
            } else {
                setting(['social.twitter_register' => false])->save();
            }
        } elseif(!isset($data['twitter'])){
            setting(['social.twitter' => false])->save();
        }

        //Enable/disable linkedin auth
        if(isset($data['linkedin']) && $data['linkedin'] == "on"){
            setting(['social.linkedin' => true])->save();
            setting(['social.linkedin_client_id' => $data['linkedin_client_id']])->save();
            setting(['social.linkedin_client_secret' => $data['linkedin_client_secret']])->save();

            if(isset($data['linkedin_register']) && $data['linkedin_register'] == "on"){
                setting(['social.linkedin_register' => $data['linkedin_register']])->save();
            } else {
                setting(['social.linkedin_register' => false])->save();
            }
        } elseif(!isset($data['linkedin'])){
            setting(['social.linkedin' => false])->save();
        }

        //Enable/disable github auth
        if(isset($data['github']) && $data['github'] == "on"){
            setting(['social.github' => true])->save();
            setting(['social.github_client_id' => $data['github_client_id']])->save();
            setting(['social.github_client_secret' => $data['github_client_secret']])->save();

            if(isset($data['github_register']) && $data['github_register'] == "on"){
                setting(['social.github_register' => $data['github_register']])->save();
            } else {
                setting(['social.github_register' => false])->save();
            }
        } elseif(!isset($data['github'])){
            setting(['social.github' => false])->save();
        }

        //Enable/disable bitbucket auth
        if(isset($data['bitbucket']) && $data['bitbucket'] == "on"){
            setting(['social.bitbucket' => true])->save();
            setting(['social.bitbucket_client_id' => $data['bitbucket_client_id']])->save();
            setting(['social.bitbucket_client_secret' => $data['bitbucket_client_secret']])->save();

            if(isset($data['bitbucket_register']) && $data['bitbucket_register'] == "on"){
                setting(['social.bitbucket_register' => $data['bitbucket_register']])->save();
            } else {
                setting(['social.bitbucket_register' => false])->save();
            }
        } elseif(!isset($data['bitbucket'])){
            setting(['social.bitbucket' => false])->save();
        }

        return redirect('settings/social')->with('success',__('Settings updated!'));
    }

    /**
     * Show two factor authentication settings page
     */
    public function twoFActor(){
        return view('app.settings.two-factor');
    }

    /**
     * Update two factor authentication settings
     */
    public function updateTwoFactorSettings(Request $request){
        $data = $request->all();

        //Enable/Disable 2FA
        if(isset($data['two_factor']) && $data['two_factor'] == "on"){
            setting(['auth.two_factor' => true])->save();
            setting(['auth.two_factor_api_key' => $data['authy_api_key']])->save();
        } elseif(!isset($data['two_factor'])){
            setting(['auth.two_factor' => false])->save();
        }

        return redirect('settings/two-factor')->with('success',__('Settings updated!'));
    }

    /**
     * Show reCaptcha  settings page
     */
    public function reCaptcha(){
        return view('app.settings.recaptcha');
    }

    /**
     * Update reCaptcha settings
     */
    public function updateReCaptchaSettings(Request $request){
        $data = $request->all();

        //Enable/Disable reCaptcha
        if(isset($data['recaptcha']) && $data['recaptcha'] == "on"){
            setting(['auth.recaptcha' => true])->save();
            setting(['auth.recaptcha_key' => $data['recaptcha_key']])->save();
            setting(['auth.recaptcha_secret' => $data['recaptcha_secret']])->save();
        } elseif(!isset($data['recaptcha'])){
            setting(['auth.recaptcha' => false])->save();
        }

        return redirect('settings/recaptcha')->with('success',__('Settings updated!'));
    }

    /**
     * Sends test email
     */
    public function sendTestEmail(Request $request){
        $data = $request->all();

        $validator = Validator::make($data, [
            'send_to_email' => ['required', 'email', 'max:255'],
        ]);

        $validator->validate();

        try {
            Mail::to($data['send_to_email'])->send(new TestEmail());

            return redirect('settings/email')->with('test_email_success',__('Test email was sent successfully! Please check your inbox!'));
        } catch (\Exception $e) {
            Session::flash('test_email_error_message', $e->getMessage());
            
            return redirect('settings/email')->with('test_email_error',__('An error occurred while sending test email!'));
        }
        
    }
}
