<?php

namespace App\Http\Controllers\API;

use App\Role;
use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:roles_create'])->only('store');
        $this->middleware(['permission:roles_show'])->only('show');
        $this->middleware(['permission:roles_edit'])->only('update');
        $this->middleware(['permission:roles_delete'])->only('destroy');
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
            $roles = Role::where('name','LIKE',"%$term%")->orderBy('id','ASC')->paginate(20);
        } else {
            $term = "";
            $roles = Role::orderBy('id','ASC')->paginate(20);
        }

        return response()->json($roles);
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
            'guard_name' => "web"
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

            return response()->json(['message' => __("Role created!")], 200);
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
        $role = Role::find($id);

        if($role){
            $permissions = $this->getPermissionsByGroup();

            return response()->json(['role' => $role, 'groups' => $permissions], 200);
        } else {
            return response()->json(['error' => __("Role not found!")],404);
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
        $role = Role::find($id);

        if($role){
            $data = $request->all();

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
                
                return response()->json(['message' => __("Role updated!")], 200);
            }
        } else {
            return response()->json(['error' => __("Role not found!")], 404);
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
        $role = Role::find($id);

        if($role){
            //Prevent admin role from deletion
            if($role->id === 1){
                return response()->json(['error' => __("Admin role cannot be deleted!")], 403);
            }

            $role->delete();
            return response()->json(['message' => __("Role deleted!")], 200);
        } else {
            return response()->json(['error' => __("Role not found!")], 404);
        }
    }

    /**
     * @return collection
     */
    private function getPermissionsByGroup(){
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
