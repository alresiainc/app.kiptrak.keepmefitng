<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allRole()
    {
        $roles = Role::orderBy('id','desc')->get();
        return view('pages.hrm.role.allRole', compact('roles'));
    }

    public function addRole()
    {
        $permissions = Permission::where('parent_id',null)->get();
        return view('pages.hrm.role.addRole', compact('permissions'));
    }

    public function addRolePost(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string',
        ]);

        //code start
        $data = $request->all();
        $role_exists = Role::where('slug', Str::slug($data['role_name']))->exists();
        if($role_exists){
            return back()->with('role_error', 'Role name already exist, Try again');
        }

        $role = new Role();
        $role->name = $data['role_name'];
        $role->slug = Str::slug($data['role_name']);
        $role->created_by = 1;
        $role->save();

        //added checked perms
        if(!empty($data['perms'])){
        
            foreach ($data['perms'] as $key => $perm_id) {
                $rolePerms = DB::table('role_permissions')->where(['role_id'=>$role->id, 'permission_id'=>$data['perms'][$key]]);
                if (!$rolePerms->exists()){
                    $role->permissions()->attach($perm_id);
                }
                 //adding to roles_permissions
            }
        }

        return back()->with('success', 'Role Created Successfully');
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function singleRole($unique_key)
    {
        return '123';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editRole($unique_key)
    {
        $role = Role::where(['unique_key'=>$unique_key]);
        if(!$role->exists()){
            return back()->with('role_error', 'Bad request');
        }

        $role = $role->first();
        
        $permissions = Permission::where('parent_id', null)->get();

        return view('pages.hrm.role.editRole', compact('role', 'permissions'));
    }

    /**
     * Update the specified role with permissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editRolePost(Request $request, $unique_key)
    {
        
        $role = Role::where(['unique_key'=>$unique_key]);
        if(!$role->exists()){
            return back()->with('role_error', 'Bad request');
        }

        $role = $role->first();
        // $users = $role->users;
        $data = $request->all();
        // $authUser = auth()->user();
        $perms_unchecked = $data['perms_unchecked'];

        //remove unchecked perms, frm role & users
        foreach ($data['perms_unchecked'] as $key => $unchecked_id) {
            if ($unchecked_id != null){
                DB::table('role_permissions')->where(['role_id'=>$role->id, 'permission_id'=>$data['perms_unchecked'][$key]])->delete();

                //not used - users_permissions tbl
                // if($users->count() > 0){
                //     foreach ($users as $user) {
                //         DB::table('users_permissions')->where(['user_id'=>$user->id, 'permission_id'=>$data['perms_unchecked'][$key]])->delete();
                //     }
                // }
            }
            
        }
        
        //added checked perms
        foreach ($data['perms'] as $key => $perm_id) {
            $rolePerms = DB::table('role_permissions')->where(['role_id'=>$role->id, 'permission_id'=>$data['perms'][$key]]);
            if (!$rolePerms->exists()){
                $role->permissions()->attach($perm_id);
            }
             //adding to roles_permissions
        }

        //update Role table
        if($role->name != $data['role_name']){
            $role->name = $data['role_name'];
            $role->slug = Str::slug($data['role_name'].'-'.$tenant_id);
            $role->created_by = 1;
            $role->save();
        }
        
        return back()->with('success', 'Role and Permissions Updated Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
