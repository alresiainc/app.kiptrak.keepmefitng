<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addCustomer()
    {
        return 'here';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addCustomerPost(Request $request)
    {
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
        $customer->delivery_address = $data['delivery_address'];
        $customer->created_by = 1;
        $customer->status = 'true';
        $customer->save();

        return back();
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
