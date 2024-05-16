<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use OwenIt\Auditing\Models\Audit;
use App\Elmas\Tools\AuditMessages;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:users_create'])->only(['create','store']);
        $this->middleware(['permission:users_show'])->only('show');
        $this->middleware(['permission:users_edit'])->only(['edit','update']);
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
        $users = User::orderBy('id','ASC')
                        ->when($request->filled('s'), function($query) use ($request){
                            $term = $request->get('s');
                            return $query->where('name','LIKE',"%$term%")->orWhere('email','LIKE',"%$term%");
                        })
                        ->when($request->has('new'), function($query){
                            $now = Carbon::now();
                            $monthAgo = $now->copy()->subMonth();
                            return $query->where('id', '!=', 1)->whereBetween('created_at', [$monthAgo->format('Y-m-d 00:00:00'), $now->format('Y-m-d 23:59:59')]);
                        })
                        ->when($request->has('active'), function($query){
                            return $query->where('banned', 0)->whereNotNull('email_verified_at');
                        })
                        ->when($request->has('banned'), function($query){
                            return $query->where('banned', 1);
                        })
                        ->paginate(20);

        return view('app.users.list', ['users' => $users, 'term' => $request->get('s'), 'request' => $request]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::orderBy('id', 'ASC')->get();
        $countries = \App\Country::orderBy('name','ASC')->get();

        return view('app.users.create', ['roles' => $roles, 'countries' => $countries]);
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
            'name' => ['required', 'string', 'max:255', 'not_regex:/[#$%^&*()+=\-\[\]\';,\/{}|":<>?~\\\\]/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'integer'],
            'phone' => ['max:60', 'nullable', 'not_regex:/[#$%^&*+=\\[\]\';,\/{}|":<>?~\\\\]/'],
            'address' => ['max:255', 'nullable', 'not_regex:/[#$%^&*()+=\\[\]\';\/{}|"<>?~\\\\]/'],
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

            return redirect('users')->with('success',__("User created!"));
        } else {
            return redirect('users/create')->with('error',__("There has been an error!"));
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
        $user = User::with('country')->find($id);

        if($user){
            $permissions = app('App\Http\Controllers\RoleController')->getPermissionsByGroup();

            return view('app.users.show', ['user' => $user, 'groups' => $permissions]);
        } else {
            return redirect('users')->with('error',__("User not found!"));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        if($user){
            $roles = Role::orderBy('id', 'ASC')->get();
            $countries = \App\Country::orderBy('name','ASC')->get();

            return view('app.users.edit', ['user' => $user, 'roles' => $roles, 'countries' => $countries]);
        } else {
            return redirect('users')->with('error',__("User not found!"));
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
                unset($data['password_confirmation']);
            }

            $validator = Validator::make($data, [
                'name' => ['required', 'string', 'max:255', 'not_regex:/[#$%^&*()+=\-\[\]\';,\/{}|":<>?~\\\\]/'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
                'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
                'phone' => ['max:60', 'nullable', 'not_regex:/[#$%^&*+=\\[\]\';,\/{}|":<>?~\\\\]/'],
                'address' => ['max:255', 'nullable', 'not_regex:/[#$%^&*()+=\\[\]\';\/{}|"<>?~\\\\]/'],
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

                return redirect('users/'.$id)->with('success',__("User updated!"));
            } else {
                return redirect('users/'.$id)->with('error',__("There has been an error!"));
            }
        } else {
            return redirect('users')->with('error',__("User not found!"));
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
            //Prevent super admin user from deletion
            if($user->isSuperAdmin()){
                return redirect('users')->with('error',__("Super admin user cannot be deleted!"));
            }

            $user->audits()->delete();
            $user->delete();
            return redirect('users')->with('success',__("User deleted!"));
        } else {
            return redirect('users')->with('error',__("User not found!"));
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

            return redirect('users/'.$id)->with('success',__("A fresh verification link has been sent to user email address."));
        } else {
            return redirect('users')->with('error',__("User not found!"));
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
            //Prevent super admin user from being banned
            if($user->isSuperAdmin()){
                return redirect('users/'.$id)->with('error',__("Super admin user cannot be banned!"));
            }

            $user->banned = true;
            $user->save();

            return redirect('users/'.$id)->with('success',__("User banned!"));
        } else {
            return redirect('users')->with('error',__("User not found!"));
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

            return redirect('users/'.$id)->with('success',__("User account activated!"));
        } else {
            return redirect('users')->with('error',__("User not found!"));
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
            $audits = Audit::where('new_values','NOT LIKE','%remember_token%')->where('user_id',$user->id)->orderBy('created_at','DESC')->paginate(20);

            $audits = AuditMessages::get($audits);

            return view('app.users.activity', ['user' => $user, 'audits' => $audits]);
        } else {
            return redirect('users')->with('error',__("User not found!"));
        }
    }

    /**
     * Update profile photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePhoto(Request $request, $id){
        $user = User::find($id);

        if($user){
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
        } else {
            return response()->json(['error'=>__("User not found!")]);
        }
    }

    /**
     * Delete profile photo.
     *
     * @return \Illuminate\Http\Response
     */
    public function deletePhoto($id){
        $user = User::find($id);

        if($user){
            $user->photo = null;
            $user->save();

            return redirect('users/'.$id)->with('success',__("User profile photo deleted!"));
        } else {
            return response()->json(['error'=>__("User not found!")]);
        }
    }
}
