<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'HomeController@index')->name('home');

Auth::routes(['verify' => true, 'register' => setting('auth.allow_registration'), 'reset' => setting('auth.forgot_password')]);

//Social authentication routes
Route::get('/login/{provider}','SocialAuthController@redirect')->name('social-login');
Route::get('/login/{provider}/callback','SocialAuthController@callback');

//Two factor authentication routes
Route::get('/token','TwoFactorController@index');
Route::post('/token','TwoFactorController@verifyToken')->name('verify-token');

$middlewares = ['auth'];

if(setting('auth.email_verification')){
	$middlewares[] = 'verified';
}

Route::middleware($middlewares)->group(function(){
	Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');

	Route::get('account', 'UserAccountController@index')->name('account.index');
	Route::get('account/edit', 'UserAccountController@edit')->name('account.edit');
	Route::patch('account/update', 'UserAccountController@update')->name('account.update');
	Route::get('account/password', 'UserAccountController@password')->name('account.password');
	Route::patch('account/password_update', 'UserAccountController@passwordUpdate')->name('account.password_update');
	Route::post('account/update-photo', 'UserAccountController@updatePhoto')->name('account.update_photo');
	Route::get('account/delete-photo', 'UserAccountController@deletePhoto')->name('account.delete_photo');
	Route::get('account/two-factor', 'UserAccountController@twoFactor')->name('account.two_factor');
	Route::patch('account/two-factor-update', 'UserAccountController@twoFactorUpdate')->name('account.two_factor_update');

	Route::group(['middleware' => ['permission:users_access']], function () {
	    Route::resources(['users' => 'UserController']);
	    Route::get('users/{id}/resend', 'UserController@resendVerificationLink')->name('users.resend');
	    Route::get('users/{id}/ban', 'UserController@banUser')->name('users.ban');
	    Route::get('users/{id}/activate', 'UserController@activateUser')->name('users.activate');
	    Route::get('users/{id}/activity', 'UserController@activityLog')->name('users.activity');
	    Route::post('users/{id}/update-photo', 'UserController@updatePhoto')->name('users.update_photo');
	    Route::get('users/{id}/delete-photo', 'UserController@deletePhoto')->name('users.delete_photo');
	});

	Route::group(['middleware' => ['permission:activitylog_access']], function () {
		Route::get('activitylog', 'ActivityLogController@index')->name('activitylog.index');
		Route::get('activitylog/{id}', 'ActivityLogController@show')->name('activitylog.show');
		Route::delete('activitylog/{id}', 'ActivityLogController@destroy')->name('activitylog.destroy');
	});

	Route::group(['middleware' => ['permission:roles_access']], function () {
	    Route::resources(['roles' => 'RoleController']);
	});

	Route::group(['middleware' => ['permission:permissions_access']], function () {
	    Route::resources(['permissions' => 'PermissionController']);
	});

	Route::group(['middleware' => ['role:admin']], function () {
	    Route::get('settings/app', 'SettingsController@app')->name('settings.app');
	    Route::patch('settings/app_update', 'SettingsController@updateAppSettings')->name('settings.app_update');
	    Route::get('settings/auth', 'SettingsController@auth')->name('settings.auth');
	    Route::patch('settings/auth_update', 'SettingsController@updateAuthSettings')->name('settings.auth_update');
	    Route::get('settings/email', 'SettingsController@email')->name('settings.email');
	    Route::patch('settings/email_update', 'SettingsController@updateEmailSettings')->name('settings.email_update');
	    Route::get('settings/social', 'SettingsController@social')->name('settings.social');
	    Route::patch('settings/social_update', 'SettingsController@updateSocialAuthSettings')->name('settings.social_update');
	    Route::get('settings/two-factor', 'SettingsController@twoFactor')->name('settings.two-factor');
	    Route::patch('settings/two_factor_update', 'SettingsController@updateTwoFactorSettings')->name('settings.two_factor_update');
	    Route::get('settings/recaptcha', 'SettingsController@reCaptcha')->name('settings.recaptcha');
	    Route::patch('settings/recaptcha_update', 'SettingsController@updateReCaptchaSettings')->name('settings.recaptcha_update');
	    Route::patch('settings/send_test_email', 'SettingsController@sendTestEmail')->name('settings.send_test_email');
	});

	Route::get('uploads/avatars/{file_path}', 'AvatarController@showAvatar');
});
