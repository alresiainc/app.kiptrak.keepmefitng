<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserRole;

class RoleController extends Controller
{
    public function allRole()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        $roles = Role::all();
        return view('pages.hrm.role.allRole', compact('authUser', 'user_role', 'roles'));
    }

    public function addRole()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        $permissions = Permission::where('parent_id',null)->get();
        return view('pages.hrm.role.addRole', compact('authUser', 'user_role', 'permissions'));
    }

    public function addRolePost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
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
        $role->created_by = $authUser->id;
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

    public function singleRole($unique_key)
    {
        return '123';
    }

    public function editRole($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $role = Role::where(['unique_key'=>$unique_key]);
        if(!$role->exists()){
            return back()->with('role_error', 'Bad request');
        }

        $role = $role->first();
        
        $permissions = Permission::where('parent_id', null)->get();

        return view('pages.hrm.role.editRole', compact('authUser', 'user_role', 'role', 'permissions'));
    }

    public function editRolePost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $role = Role::where(['unique_key'=>$unique_key]);
        if(!$role->exists()){
            return back()->with('role_error', 'Bad request');
        }

        $role = $role->first();
        // $users = $role->users;
        $data = $request->all();
        
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

    
    public function assignRoleToUserPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        $data = $request->all();
        $user = User::find($data['user_id']);
        
        if(!empty($data['role_id'])){

            $role = Role::find($data['role_id']);
            $permissions = $role->permissions;

            if (!$user->hasAnyRole($user->id)) {
                $userRole = new UserRole();
                $userRole->user_id = $user->id;
                $userRole->role_id = $data['role_id'];
                $userRole->save();
                return back()->with('success', 'Staff Updated and Assigned Successfully');
            } 

            $former_role_id = $user->role($user->id)->role->id;
            $former_role_obj = Role::find($former_role_id);
            $former_permissions = $former_role_obj->permissions;

            //update user new role
            DB::table('user_roles')->where(['user_id'=>$user->id, 'role_id'=>$former_role_id])->update(['role_id'=>$role->id]);
            
            //update user new permissions
            // if(count($permissions) > 0){
            //     $user->permissions()->attach($permissions);
            // }
            
            return back()->with('success', 'Staff Updated and Assigned Successfully');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteRole($unique_key)
    {
        $role = Role::where(['unique_key'=>$unique_key]);
        if(!$role->exists()){
            return back()->with('role_error', 'Bad request');
        }

        $role = $role->first();
        DB::table('user_roles')->where('role_id', $role->id)->delete();
        DB::table('role_permissions')->where('role_id', $role->id)->delete();
        $role->delete();

        return back()->with('success', 'Role Removed Successfully');
    }

    //addPermission
    public function addPermission()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $mainPerms = Permission::where('parent_id',null)->get();

        return view('pages.hrm.role.addPermission', compact('authUser', 'user_role', 'mainPerms'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addPermissionPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $request->validate([
            'main_menu' => 'required',
            'permission_names' => 'required',
        ]);

        $data = $request->all();

        $permission_names = $data['permission_names'];
        $main_menu = $data['main_menu']; //id

        $slugs = array_map('Str::slug', $permission_names); //["create-task","edit-lorem","delete-menu"]

        $check = Permission::whereIn('slug', $slugs);
        if ($check->count() > 0) {
            return back()->with('error', 'Permission Name(s) Already Exist. Try New Ones');
        }

        foreach ($permission_names as $key => $perm_name) {
            if (!empty($perm_name)) {
                $permission = new Permission();
                $permission->name = $perm_name;
                $permission->slug = Str::slug($perm_name);
                $permission->parent_id = $main_menu;
                $permission->created_by = $authUser->id;
                $permission->save();
            }
            
        }
        return back()->with('success', 'Permission(s) Created Successfully');
    }

    public function ajaxCreatePermissionMainMenu(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $authUser = auth()->user();
        $data = $request->all();

        $exist = Permission::where('parent_id', null)->where('slug', Str::slug($data['menu_name']))->first();

        if (isset($exist)) {
            $data['data_error'] = 'Error: Main Menu Already Exists, Try Again';
            return response()->json([
                'status'=>true,
                'data'=>$data
            ]);
        }
        
        $permission = new Permission();
        $permission->name = $data['menu_name'];
        $permission->slug = Str::slug($data['menu_name']);
        $permission->created_by = $authUser->id;
        $permission->save();
       
        
        //store in array
        $data['permission'] = $permission;

        return response()->json([
            'status'=>true,
            'data'=>$data
        ]);
    }
}
