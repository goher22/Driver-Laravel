<?php

namespace App\Http\Controllers\API;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use OwenIt\Auditing\Models\Audit;
use App\Elmas\Tools\AuditMessages;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:users_create'])->only('store');
        $this->middleware(['permission:users_show'])->only('show');
        $this->middleware(['permission:users_edit'])->only('update');
        $this->middleware(['permission:users_delete'])->only('destroy');
        $this->middleware(['permission:users_ban'])->only(['banUser','activateUser']);
        $this->middleware(['permission:users_activity'])->only('activityLog');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->filled('search')){
            $term = $request->get('search');
            $users = User::where('name','LIKE',"%$term%")->orWhere('email','LIKE',"%$term%")->orderBy('id','ASC')->paginate($request->get('each'));
        } else {
            $term = "";
            $users = User::orderBy('id','ASC')->paginate($request->get('each'));
        }

        foreach ($users as $key => $value) {
            $users[$key] = $this->getUserInfo($value);
        }

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'integer'],
            'phone' => ['max:60', 'nullable', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
            'address' => ['max:255', 'nullable', 'not_regex:/[#$%^&*()+=\-\[\]\';.\/{}|"<>?~\\\\]/'],
            'city' => ['max:60', 'nullable', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
        ]);

        $validator->validate();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city' => $data['city'],
            'country_id' => $data['country_id'],
        ]);

        if ($user){
            //Send verification email
            if(setting('auth.email_verification')){
                $user->sendEmailVerificationNotification();
            }

            //Assign role
            if(isset($data['role_id']) && $data['role_id'] != ""){
                $role = Role::find($data['role_id']);
                if($role){
                    $user->assignRole($role);
                }
            }

            return response()->json(['message' => __("User created!")], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        if($user){
            $user = $this->getUserInfo($user);
            return response()->json($user);
        } else {
            return response()->json(['error' => __("User not found!")],404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if($user){
            $data = $request->all();

            if($data['password'] === null){
                unset($data['password']);
            }

            $validator = Validator::make($data, [
                'name' => ['required', 'string', 'max:255', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
                'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
                'phone' => ['max:60', 'nullable', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
                'address' => ['max:255', 'nullable', 'not_regex:/[#$%^&*()+=\-\[\]\';.\/{}|"<>?~\\\\]/'],
                'city' => ['max:60', 'nullable', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
            ]);

            $validator->validate();

            if(isset($data['password']) && $data['password'] !== null){
               $data['password'] = Hash::make($data['password']);
            }

            if($user->update($data)){
                //Update role
                if(!$user->isSuperAdmin()){
                    if(isset($data['role_id']) && $data['role_id'] != ""){
                        $role = Role::find($data['role_id']);

                        //Check if the posted role_id same with user's current role
                        //if not revoke the old role and assign a new one
                        if($role && !$user->hasRole($role)){

                            //Check if the user has any role
                            if(!$user->hasAnyRole(Role::all())){
                                $user->assignRole($role);
                            } else {
                                $currentRole = $user->getRoleNames()[0];
                                $user->removeRole($currentRole);
                                $user->assignRole($role);
                            } 
                        }
                    }
                }

                return response()->json(['message' => __("User updated!")], 200);
            }
        } else {
            return response()->json(['error' => __("User not found!")], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if($user){
            //Prevent admin user from deletion
            if($user->isSuperAdmin()){
                return response()->json(['error' => __("Super admin user cannot be deleted!")], 403);
            }

            $user->audits()->delete();
            $user->delete();
            return response()->json(['success' => __("User deleted!")], 200);
        } else {
            return response()->json(['error' => __("User not found!")], 404);
        }
    }

    /**
     * Resend the email verification link
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resendVerificationLink($id)
    {
        $user = User::find($id);

        if($user){
            $user->sendEmailVerificationNotification();

            return response()->json(['success' => __("A fresh verification link has been sent to user email address.")], 200);
        } else {
            return response()->json(['error' => __("User not found!")], 404);
        }
    }

    /**
     * Ban user from the application
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function banUser($id)
    {
        $user = User::find($id);

        if($user){
            //Prevent admin user from being banned
            if($user->isSuperAdmin()){
                return response()->json(['error' => __("Super admin user cannot be banned!")], 403);
            }

            $user->banned = true;
            $user->save();

            return response()->json(['success' => __("User banned!")], 200);
        } else {
            return response()->json(['error' => __("User not found!")], 404);
        }
    }

    /**
     * Activate banned user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activateUser($id)
    {
        $user = User::find($id);

        if($user){
            $user->banned = false;
            $user->save();

            return response()->json(['success' => __("User account activated!")], 200);
        } else {
            return response()->json(['error' => __("User not found!")], 404);
        }
    }

    /**
     * Show user's activities
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activityLog($id)
    {
        $user = User::find($id);

        if($user){
            $audits = Audit::where('new_values','NOT LIKE','%remember_token%')->where('user_id',$user->id)->orderBy('created_at','DESC')->get();

            $audits = AuditMessages::get($audits);

            $audits = $audits->map(function ($item) {
                return $item->only(['created_at', 'ip_address', 'event_message']);
            });

            $user = $this->getUserInfo($user);

            return response()->json(['user' => $user, 'activitylog' => $audits], 200);
        } else {
            return response()->json(['error' => __("User not found!")], 404);
        }
    }

    /**
     * Get clean user info
     *
     ** @return \Illuminate\Database\Eloquent\Model
     */
    private function getUserInfo($user){

        $user->country_name = isset($user->country->name) ? $user->country->name : "";
        unset($user->country);
        unset($user->deleted_at);
        unset($user->photo);
        unset($user->banned);

        if($user->banned){
            $user->status = "Banned";
        } else{
            if(setting('auth.email_verification'))
                if($user->email_verified_at == null){
                    $user->status = "Unconfirmed";
                } else {
                    $user->status = "Active";
                }
            else {
                $user->status = "Active";
            }
        }

        return $user;
    }
}
