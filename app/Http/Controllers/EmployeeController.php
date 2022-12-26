<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Country;
use App\Models\Role;
use App\Models\UserRole;


class EmployeeController extends Controller
{
    public function allStaff()
    {
        $staffs = User::where('type', 'staff')->orderBy('id', 'DESC')->get();
        $roles = Role::all();
        return view('pages.hrm.employee.allEmployee', compact('staffs', 'roles'));
    }
    
    //add any user, like registration
    public function addStaff()
    {
        $countries = Country::all();
        $roles = Role::all();
        return view('pages.hrm.employee.addEmployee', compact('countries', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addStaffPost(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'phone_1' => 'required|unique:users',
            'phone_2' => 'nullable|unique:users',
            'country' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
        ]);

        $data = $request->all();

        $user = new User();
        $user->name = $data['firstname'].' '.$data['lastname'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->type = 'staff';  //customer, staff, agent, superadmin
        $user->phone_1 = !empty($data['phone_1']) ? $data['phone_1'] : null;
        $user->phone_2 = !empty($data['phone_2']) ? $data['phone_2'] : null;
        $user->city = !empty($data['city']) ? $data['city'] : null;
        $user->state = $data['state'];
        $user->country_id = $data['country'];
        $user->address = !empty($data['address']) ? $data['address'] : null;

        $user->created_by = 1;
        $user->status = 'true';

        if ($request->profile_picture) {
            //image
            $imageName = time().'.'.$request->profile_picture->extension();
            //store products in folder
            $request->profile_picture->storeAs('staff', $imageName, 'public');
            $user->profile_picture = $imageName;
        }

        $user->save();

        //add role to user
        if(!empty($data['role_id'])){

            $role = Role::find($data['role_id']);
            $permissions = $role->permissions;
            //no need since its a new user
            // if ($user->hasRole($role->slug)) {
            //     return 'role already assigned to user';
            // }
            $user->roles()->attach($role);
            // if(count($permissions) > 0){
            //     $user->permissions()->attach($permissions);
            // }
            return back()->with('success', 'Staff Created and Assigned Role Successfully');
        }
        
        return back()->with('success', 'Staff Created Successfully');

        
    }

    public function singleStaff($unique_key)
    {
        $staff = User::where('unique_key', $unique_key)->first();
        if(!isset($staff)){
            abort(404);
        }
        return view('pages.hrm.employee.singleEmployee', compact('staff'));
    }

    public function editStaff($unique_key)
    {
        $staff = User::where('unique_key', $unique_key)->first();
        if(!isset($staff)){
            abort(404);
        }
        
        $countries = Country::all();
        $name = explode(' ', $staff->name);
        $firstname = $name[0];
        $lastname = $name[1];

        $roles = Role::all();

        return view('pages.hrm.employee.editEmployee', compact('staff', 'countries', 'firstname', 'lastname', 'roles'));
    }

    public function editStaffPost(Request $request, $unique_key)
    {
        $user = User::where('unique_key', $unique_key)->first();
        if(!isset($user)){
            abort(404);
        }
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
            'phone_1' => 'required',
            'phone_2' => 'nullable',
            'country' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
        ]);

        $data = $request->all();

        $user->name = $data['firstname'].' '.$data['lastname'];
        $user->email = $data['email'];
        $user->type = 'staff';  //customer, staff, agent, superadmin
        $user->phone_1 = !empty($data['phone_1']) ? $data['phone_1'] : null;
        $user->phone_2 = !empty($data['phone_2']) ? $data['phone_2'] : null;
        $user->city = !empty($data['city']) ? $data['city'] : null;
        $user->state = $data['state'];
        $user->country_id = $data['country'];
        $user->address = !empty($data['address']) ? $data['address'] : null;

        $user->created_by = 1;
        $user->status = 'true';

        //profile_picture
        if ($request->profile_picture) {
            $oldImage = $user->profile_picture; //1.jpg
            if(Storage::disk('public')->exists('staff/'.$oldImage)){
                Storage::disk('public')->delete('staff/'.$oldImage);
                /*
                    Delete Multiple files this way
                    Storage::delete(['upload/test.png', 'upload/test2.png']);
                */
            }
            $imageName = time().'.'.$request->profile_picture->extension();
            //store products in folder
            $request->profile_picture->storeAs('staff', $imageName, 'public');
            $user->profile_picture = $imageName;
        }

        $user->save();

        //update role to user
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
        
        return back()->with('success', 'Staff Updated Successfully');
    }
//-----------------AGENTS-----------------------------------------------
    
public function allAgent()
{
    $agents = User::where('type', 'agent')->get();
    return view('pages.agents.allAgent', compact('agents'));
}

//add any user, like registration
public function addAgent()
{
    $countries = Country::all();
    return view('pages.agents.addAgent', compact('countries'));
}

/**
 * Show the form for creating a new resource.
 *
 * @return \Illuminate\Http\Response
 */
public function addAgentPost(Request $request)
{
    $request->validate([
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'email' => 'required|email|unique:users',
        'password' => 'required|string',
        'phone_1' => 'required|unique:users',
        'phone_2' => 'nullable|unique:users',
        'country' => 'required|string',
        'city' => 'required|string',
        'state' => 'required|string',
        'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
    ]);

    $data = $request->all();

    $user = new User();
    $user->name = $data['firstname'].' '.$data['lastname'];
    $user->email = $data['email'];
    $user->password = Hash::make($data['password']);
    $user->type = 'agent';  //customer, staff, agent, superadmin
    $user->phone_1 = !empty($data['phone_1']) ? $data['phone_1'] : null;
    $user->phone_2 = !empty($data['phone_2']) ? $data['phone_2'] : null;
    $user->city = !empty($data['city']) ? $data['city'] : null;
    $user->state = $data['state'];
    $user->country_id = $data['country'];
    $user->address = !empty($data['address']) ? $data['address'] : null;

    $user->created_by = 1;
    $user->status = 'true';

    if ($request->profile_picture) {
        //image
        $imageName = time().'.'.$request->profile_picture->extension();
        //store products in folder
        $request->profile_picture->storeAs('agent', $imageName, 'public');
        $user->profile_picture = $imageName;
    }

    $user->save();
    
    return back()->with('success', 'Agent Created Successfully');

    
}

public function singleAgent($unique_key)
{
    $agent = User::where('unique_key', $unique_key)->first();
    if(!isset($agent)){
        abort(404);
    }
    return view('pages.agents.singleAgent', compact('agent'));
}

public function editAgent($unique_key)
{
    $agent = User::where('unique_key', $unique_key)->first();
    if(!isset($agent)){
        abort(404);
    }
    
    $countries = Country::all();
    $name = explode(' ', $agent->name);
    $firstname = $name[0];
    $lastname = $name[1];

    return view('pages.agents.editAgent', compact('agent', 'countries', 'firstname', 'lastname'));
}

public function editAgentPost(Request $request, $unique_key)
{
    $user = User::where('unique_key', $unique_key)->first();
    if(!isset($user)){
        abort(404);
    }
    $request->validate([
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'email' => 'required|email',
        'phone_1' => 'required',
        'phone_2' => 'nullable',
        'country' => 'required|string',
        'city' => 'required|string',
        'state' => 'required|string',
        'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
    ]);

    $data = $request->all();

    $user->name = $data['firstname'].' '.$data['lastname'];
    $user->email = $data['email'];
    $user->type = 'agent';  //customer, staff, agent, superadmin
    $user->phone_1 = !empty($data['phone_1']) ? $data['phone_1'] : null;
    $user->phone_2 = !empty($data['phone_2']) ? $data['phone_2'] : null;
    $user->city = !empty($data['city']) ? $data['city'] : null;
    $user->state = $data['state'];
    $user->country_id = $data['country'];
    $user->address = !empty($data['address']) ? $data['address'] : null;

    $user->created_by = 1;
    $user->status = 'true';

    //profile_picture
    if ($request->profile_picture) {
        $oldImage = $user->profile_picture; //1.jpg
        if(Storage::disk('public')->exists('agent/'.$oldImage)){
            Storage::disk('public')->delete('agent/'.$oldImage);
            /*
                Delete Multiple files this way
                Storage::delete(['upload/test.png', 'upload/test2.png']);
            */
        }
        $imageName = time().'.'.$request->profile_picture->extension();
        //store products in folder
        $request->profile_picture->storeAs('agent', $imageName, 'public');
        $user->profile_picture = $imageName;
    }

    $user->save();
    
    return back()->with('success', 'Agent Updated Successfully');
}

//-----------------CUSTOMERS-----------------------------------------------
    
public function allCustomer()
{
    $customers = User::where('type', 'customer')->get();
    return view('pages.customers.allCustomer', compact('customers'));
}

public function addCustomer()
{
    $countries = Country::all();
    return view('pages.customers.addCustomer', compact('countries'));
}

/**
 * Show the form for creating a new resource.
 *
 * @return \Illuminate\Http\Response
 */
public function addCustomerPost(Request $request)
{
    $request->validate([
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'email' => 'required|email|unique:users',
        'password' => 'required|string',
        'phone_1' => 'required|unique:users',
        'phone_2' => 'nullable|unique:users',
        'country' => 'required|string',
        'city' => 'required|string',
        'state' => 'required|string',
        'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
    ]);

    $data = $request->all();

    $user = new User();
    $user->name = $data['firstname'].' '.$data['lastname'];
    $user->email = $data['email'];
    $user->password = Hash::make($data['password']);
    $user->type = 'customer';  //customer, staff, agent, superadmin
    $user->phone_1 = !empty($data['phone_1']) ? $data['phone_1'] : null;
    $user->phone_2 = !empty($data['phone_2']) ? $data['phone_2'] : null;
    $user->city = !empty($data['city']) ? $data['city'] : null;
    $user->state = $data['state'];
    $user->country_id = $data['country'];
    $user->address = !empty($data['address']) ? $data['address'] : null;

    $user->created_by = 1;
    $user->status = 'true';

    if ($request->profile_picture) {
        //image
        $imageName = time().'.'.$request->profile_picture->extension();
        //store products in folder
        $request->profile_picture->storeAs('customer', $imageName, 'public');
        $user->profile_picture = $imageName;
    }

    $user->save();
    
    return back()->with('success', 'Customer Created Successfully');

    
}

public function singleCustomer($unique_key)
{
    $customer = User::where('unique_key', $unique_key)->first();
    if(!isset($customer)){
        abort(404);
    }
    return view('pages.customers.singleCustomer', compact('customer'));
}

public function editCustomer($unique_key)
{
    $customer = User::where('unique_key', $unique_key)->first();
    if(!isset($customer)){
        abort(404);
    }
    
    $countries = Country::all();
    $name = explode(' ', $customer->name);
    $firstname = $name[0];
    $lastname = $name[1];

    return view('pages.customers.editCustomer', compact('customer', 'countries', 'firstname', 'lastname'));
}

public function editCustomerPost(Request $request, $unique_key)
{
    $user = User::where('unique_key', $unique_key)->first();
    if(!isset($user)){
        abort(404);
    }
    $request->validate([
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'email' => 'required|email',
        'phone_1' => 'required',
        'phone_2' => 'nullable',
        'country' => 'required|string',
        'city' => 'required|string',
        'state' => 'required|string',
        'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
    ]);

    $data = $request->all();

    $user->name = $data['firstname'].' '.$data['lastname'];
    $user->email = $data['email'];
    $user->type = 'customer';  //customer, staff, agent, superadmin
    $user->phone_1 = !empty($data['phone_1']) ? $data['phone_1'] : null;
    $user->phone_2 = !empty($data['phone_2']) ? $data['phone_2'] : null;
    $user->city = !empty($data['city']) ? $data['city'] : null;
    $user->state = $data['state'];
    $user->country_id = $data['country'];
    $user->address = !empty($data['address']) ? $data['address'] : null;

    $user->created_by = 1;
    $user->status = 'true';

    //profile_picture
    if ($request->profile_picture) {
        $oldImage = $user->profile_picture; //1.jpg
        if(Storage::disk('public')->exists('customer/'.$oldImage)){
            Storage::disk('public')->delete('customer/'.$oldImage);
            /*
                Delete Multiple files this way
                Storage::delete(['upload/test.png', 'upload/test2.png']);
            */
        }
        $imageName = time().'.'.$request->profile_picture->extension();
        //store products in folder
        $request->profile_picture->storeAs('customer', $imageName, 'public');
        $user->profile_picture = $imageName;
    }

    $user->save();
    
    return back()->with('success', 'Customer Updated Successfully');
}

}
