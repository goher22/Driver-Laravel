<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Permission;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:permissions_create'])->only(['create','store']);
        $this->middleware(['permission:permissions_show'])->only('show');
        $this->middleware(['permission:permissions_edit'])->only(['edit','update']);
        $this->middleware(['permission:permissions_delete'])->only('destroy');
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
            $permissions = Permission::where('name','LIKE',"%$term%")->orderBy('id','ASC')->paginate(20);
        } else {
            $term = "";
            $permissions = Permission::orderBy('id','ASC')->paginate(20);
        }

        return view('app.permissions.list', ['permissions' => $permissions, 'term' => $term]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('app.permissions.create');
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
            'display_name' => $data['display_name'],
            'group_name' => $data['group_name'],
            'group_slug' => str_slug($data['group_name'],""),
        ]);

        if ($permission){
            return redirect('permissions')->with('success',__("Permission created!"));
        } else {
            return redirect('permissions/create')->with('error',__("There has been an error!"));
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
            return view('app.permissions.show', ['permission' => $permission]);
        } else {
            return redirect('permissions')->with('error',__("Permission not found!"));
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
        $permission = Permission::find($id);

        if($permission){
            return view('app.permissions.edit', ['permission' => $permission]);
        } else {
            return redirect('permissions')->with('error',__("Permission not found!"));
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
                return redirect('permissions/'.$id)->with('success',__("Permission updated!"));
            } else {
                return redirect('permissions/'.$id)->with('error',__("There has been an error!"));
            }
        } else {
            return redirect('permissions')->with('error',__("Permission not found!"));
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
            return redirect('permissions')->with('success',__("Permission deleted!"));
        } else {
            return redirect('permissions')->with('error',__("Permission not found!"));
        }
    }
}
