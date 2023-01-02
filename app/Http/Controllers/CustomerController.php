<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Models\Customer;
use App\Models\Country;
use App\Models\Sale;

class CustomerController extends Controller
{
    public function allCustomer()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $customers = Customer::all();
        return view('pages.customers.allCustomer', compact('authUser', 'user_role', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addCustomer()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $countries = Country::all();
        return view('pages.customers.addCustomer', compact('authUser', 'user_role', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addCustomerPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required',
            'whatsapp_phone_number' => 'required',
            'country' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
        ]);
        $authUser = auth()->user();

        $data = $request->all();
        $customer = new Customer();
        // $customer->order_id = $order->id;
        // $customer->form_holder_id = $formHolder->id;
        $customer->firstname = $data['firstname'];
        $customer->lastname = $data['lastname'];
        $customer->phone_number = $data['phone_number'];
        $customer->whatsapp_phone_number = $data['whatsapp_phone_number'];
        $customer->email = $data['email'];
        $customer->password = Hash::make('password');
        $customer->city = $data['city'];
        $customer->state = $data['state'];
        $customer->country_id = $data['country'];
        $customer->delivery_address = $data['delivery_address'];
        $customer->created_by = $authUser->id;
        $customer->status = 'true';

        if ($request->profile_picture) {
            //image
            $imageName = time().'.'.$request->profile_picture->extension();
            //store products in folder
            $request->profile_picture->storeAs('customer', $imageName, 'public');
            $customer->profile_picture = $imageName;
        }
    
        $customer->save();

        return back()->with('success', 'Customer Added Successfully');
    }

    public function singleCustomer($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $customer = Customer::where('unique_key', $unique_key)->first();
        if(!isset($customer)){
            abort(404);
        }
        return view('pages.customers.singleCustomer', compact('authUser', 'user_role', 'customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editCustomer($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $customer = Customer::where('unique_key', $unique_key)->first();
        if(!isset($customer)){
            abort(404);
        }

        $countries = Country::all();
        return view('pages.customers.editCustomer', compact('authUser', 'user_role', 'customer', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editCustomerPost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $customer = Customer::where('unique_key', $unique_key)->first();
        if(!isset($customer)){
            abort(404);
        }
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required',
            'whatsapp_phone_number' => 'required',
            'country' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
        ]);
        $authUser = auth()->user();

        $data = $request->all();
    
        $customer->firstname = $data['firstname'];
        $customer->lastname = $data['lastname'];
        $customer->phone_number = $data['phone_number'];
        $customer->whatsapp_phone_number = $data['whatsapp_phone_number'];
        $customer->email = $data['email'];
        $customer->password = Hash::make('password');
        $customer->city = $data['city'];
        $customer->state = $data['state'];
        $customer->country_id = $data['country'];
        $customer->delivery_address = $data['delivery_address'];
        $customer->status = 'true';

        //profile_picture
        if ($request->profile_picture) {
            $oldImage = $customer->profile_picture; //1.jpg
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
            $customer->profile_picture = $imageName;
        }

        $customer->save();

        return back()->with('success', 'Customer Updated Successfully');
    }

    public function singleCustomerSales($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $customer = Customer::where('unique_key', $unique_key)->first();
        $sales = Sale::where('customer_id', $customer->id)->get();
        return view('pages.customers.singleCustomerSales', compact('authUser', 'user_role', 'customer', 'sales'));
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
