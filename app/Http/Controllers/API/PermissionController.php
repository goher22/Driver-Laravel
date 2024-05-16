<?php

namespace App\Http\Controllers\API;

use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:permissions_create'])->only('store');
        $this->middleware(['permission:permissions_show'])->only('show');
        $this->middleware(['permission:permissions_edit'])->only('update');
        $this->middleware(['permission:permissions_delete'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->filled('serach')){
            $term = $request->get('serach');
            $permissions = Permission::where('name','LIKE',"%$term%")->orderBy('id','ASC')->paginate(20);
        } else {
            $term = "";
            $permissions = Permission::orderBy('id','ASC')->paginate(20);
        }

        return response()->json($permissions);
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
            'name' => ['required', 'string', 'max:255', 'unique:permissions', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
            'display_name' => ['nullable', 'max:60', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
            'group_name' => ['nullable', 'max:60', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
        ]);

        $validator->validate();

        $permission = Permission::create([
            'name' => $data['name'],
            'guard_name' => "web",
            'display_name' => $data['display_name'],
            'group_name' => $data['group_name'],
            'group_slug' => str_slug($data['group_name'],""),
        ]);

        if ($permission){
            return response()->json(['message' => __("Permission created!")], 200);
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
        $permission = Permission::find($id);

        if($permission){
            return response()->json(['permission' => $permission], 200);
        } else {
            return response()->json(['error' => __("Permission not found!")],404);
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
        $permission = Permission::find($id);

        if($permission){
            $data = $request->all();

            $validator = Validator::make($data, [
                'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission), 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
                'display_name' => ['nullable', 'max:60', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
                'group_name' => ['nullable', 'max:60', 'not_regex:/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/'],
            ]);

            $validator->validate();

            $data['group_slug'] = str_slug($data['group_name'],"");

            if($permission->update($data)){
                return response()->json(['message' => __("Permission updated!")], 200);
            }
        } else {
            return response()->json(['error' => __("Permission not found!")],404);
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
        $permission = Permission::find($id);

        if($permission){
            $permission->delete();
            return response()->json(['message' => __("Permission deleted!")], 200);
        } else {
            return response()->json(['error' => __("Permission not found!")], 404);
        }
    }
}
