<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Role;
use App\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:roles_create'])->only(['create','store']);
        $this->middleware(['permission:roles_show'])->only('show');
        $this->middleware(['permission:roles_edit'])->only(['edit','update']);
        $this->middleware(['permission:roles_delete'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->filled('s')){
            $term = $request->get('s');
            $roles = Role::where('name','LIKE',"%$term%")->orderBy('id','ASC')->paginate(20);
        } else {
            $term = "";
            $roles = Role::orderBy('id','ASC')->paginate(20);
        }

        return view('app.roles.list', ['roles' => $roles, 'term' => $term]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = $this->getPermissionsByGroup();

        return view('app.roles.create', ['groups' => $permissions]);
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
            'name' => ['required', 'string', 'max:255', 'unique:roles', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
        ]);

        $validator->validate();

        $role = Role::create([
            'name' => $data['name'],
        ]);

        if ($role){
            //Permissions
            $permissions = Permission::get();

            foreach ($permissions as $p) {
                if(isset($data[$p->name]) && $data[$p->name] == "on"){
                    $role->givePermissionTo($p);
                } else {
                    $role->revokePermissionTo($p);
                }
            }

            return redirect('roles')->with('success',__("Role created!"));
        } else {
            return redirect('roles/create')->with('error',__("There has been an error!"));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);

        if($role){
            $permissions = $this->getPermissionsByGroup();

            return view('app.roles.show', ['role' => $role, 'groups' => $permissions]);
        } else {
            return redirect('roles')->with('error',__("Role not found!"));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::with('permissions')->find($id);
        $permissions = $this->getPermissionsByGroup();

        if($role){
            return view('app.roles.edit', ['role' => $role, 'groups' => $permissions]);
        } else {
            return redirect('roles')->with('error',__("Role not found!"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if($role){
            $data = $request->all();

            if($role->id === 1 && auth()->user()->id !== 1){
                return redirect('roles/'.$id)->with('error',__("Admin role can only change by admin account!"));
            }

            if($role->id === 1){
                $data['name'] = "admin";
            }

            $validator = Validator::make($data, [
                'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role), 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
            ]);

            $validator->validate();

            $role->name = $data['name'];

            if($role->save()){
                //Permissions
                $permissions = Permission::get();

                foreach ($permissions as $p) {
                    if(isset($data[$p->name]) && $data[$p->name] == "on"){
                        $role->givePermissionTo($p);
                    } else {
                        $role->revokePermissionTo($p);
                    }
                }
                
                return redirect('roles/'.$id)->with('success',__("Role updated!"));
            } else {
                return redirect('roles/'.$id)->with('error',__("There has been an error!"));
            }
        } else {
            return redirect('roles')->with('error',__("Role not found!"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);

        if($role){
            //Prevent admin role from deletion
            if($role->id === 1){
                return redirect('roles')->with('error',__("Admin role cannot be deleted!"));
            }

            $role->delete();
            return redirect('roles')->with('success',__("Role deleted!"));
        } else {
            return redirect('roles')->with('error',__("Role not found!"));
        }
    }

    /**
     * @return collection
     */
    public function getPermissionsByGroup(){
        $permissions = Permission::get();
        $group_arr = [];

        foreach ($permissions as $permission) {
            if($permission->group_slug !== null && !in_array($permission->group_slug, $group_arr)){
                $group_arr[] = $permission->group_slug;
            }
        }
        foreach ($permissions as $permission) {
            if($permission->group_slug == null && !in_array($permission->group_slug, $group_arr)){
                $group_arr[] = $permission->group_slug;
            }
        }
        foreach ($permissions as $permission) {
            foreach ($group_arr as $group) {
                if($permission->group_slug == $group) {
                    $groups[$permission->group_slug]['name'] = $permission->group_name;
                    $groups[$permission->group_slug]['permissions'][] = $permission->toArray();
                }
            }
        }
        $collection = collect($groups);

        return $collection;
    }
}
