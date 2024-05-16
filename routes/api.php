<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

if(setting('app.enable_api')){

	Route::post('register', 'API\AuthController@register');
	Route::post('login', 'API\AuthController@login');

	Route::middleware('auth:api')->group(function(){
		Route::post('logout', 'API\AuthController@logout');

		Route::group(['middleware' => ['permission:users_access']], function () {
		    Route::apiResource('users', 'API\UserController');
	    	Route::post('users/{id}/resend', 'API\UserController@resendVerificationLink');
		    Route::post('users/{id}/ban', 'API\UserController@banUser');
		    Route::post('users/{id}/activate', 'API\UserController@activateUser');
		    Route::get('users/{id}/activity', 'API\UserController@activityLog');
		});

		Route::group(['middleware' => ['permission:roles_access']], function () {
		    Route::apiResource('roles', 'API\RoleController');
		});

		Route::group(['middleware' => ['permission:permissions_access']], function () {
		    Route::apiResource('permissions', 'API\PermissionController');
		});

		Route::group(['middleware' => ['role:admin']], function () {
			Route::get('settings', 'API\SettingsController@index');
		});
	});
}
