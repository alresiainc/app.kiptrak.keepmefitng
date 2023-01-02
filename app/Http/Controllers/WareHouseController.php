<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WareHouse;
use App\Models\User;
use App\Models\Country;

class WareHouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allWarehouse()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $warehouses = WareHouse::all();
        return view('pages.warehouses.allWarehouse', compact('authUser', 'user_role', 'warehouses'));
    }

    //add
    public function addWarehouse()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $agents = User::where('type', 'agent')->get();
        $countries = Country::all();
        return view('pages.warehouses.addWarehouse', compact('authUser', 'user_role', 'agents', 'countries'));
    }

    //addpost
    public function addWarehousePost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'name' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
        ]);

        $data = $request->all();
        $warehouse = new WareHouse();
        $warehouse->agent_id = !empty($data['agent_id']) ? $data['agent_id'] : null;
        $warehouse->name = $data['name'];
        $warehouse->city = !empty($data['city']) ? $data['city'] : null;
        $warehouse->state = !empty($data['state']) ? $data['state'] : null;
        $warehouse->country_id = !empty($data['country']) ? $data['country'] : null;
        $warehouse->address = !empty($data['address']) ? $data['address'] : null;
        $warehouse->created_by = $authUser->id;
        $warehouse->status = 'true';
        $warehouse->save();

        return back()->with('success', 'Warehouse Added Successfully');
    }

    public function singleWarehouse($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $warehouse = WareHouse::where('unique_key', $unique_key)->first();
        if(!isset($warehouse)){
            abort(404);
        }
        return view('pages.warehouses.singleWarehouse', compact('authUser', 'user_role', 'warehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editWarehouse($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $warehouse = WareHouse::where('unique_key', $unique_key)->first();
        if(!isset($warehouse)){
            abort(404);
        }
        $agents = User::where('type', 'agent')->get();
        $countries = Country::all();
        return view('pages.warehouses.editWarehouse', compact('authUser', 'user_role', 'warehouse', 'agents', 'countries'));
    }

    
    public function editWarehousePost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $warehouse = WareHouse::where('unique_key', $unique_key)->first();
        if(!isset($warehouse)){
            abort(404);
        }
        $request->validate([
            'name' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
        ]);

        $data = $request->all();
        
        $warehouse->agent_id = !empty($data['agent_id']) ? $data['agent_id'] : null;
        $warehouse->name = $data['name'];
        $warehouse->city = !empty($data['city']) ? $data['city'] : null;
        $warehouse->state = !empty($data['state']) ? $data['state'] : null;
        $warehouse->country_id = !empty($data['country']) ? $data['country'] : null;
        $warehouse->address = !empty($data['address']) ? $data['address'] : null;
        $warehouse->status = 'true';
        $warehouse->save();

        return back()->with('success', 'Warehouse Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addWarehouseAjax(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $authUser = auth()->user();
        $data = $request->all();
        $warehouse = new WareHouse();
        if ($data['agent_id'] != "" || $data['agent_id'] != null) {
            $warehouse->agent_id = $data['agent_id'];
        }
        if ($data['name'] != "" || $data['name'] != null) {
            $warehouse->name = $data['name'];
        }
        if ($data['type'] != "" || $data['type'] != null) {
            $warehouse->type = $data['type'];
        }
        if ($data['city'] != "" || $data['city'] != null) {
            $warehouse->city = $data['city'];
        }
        if ($data['state'] != "" || $data['state'] != null) {
            $warehouse->state = $data['state'];
        }
        if ($data['country'] != "" || $data['country'] != null) {
            $warehouse->country_id = $data['country'];
        }
        if ($data['address'] != "" || $data['address'] != null) {
            $warehouse->address = $data['address'];
        }
        $warehouse->created_by = $authUser->id;
        $warehouse->status = 'true';
        $warehouse->save();

        //store in array
        $data['warehouse'] = $warehouse;

        // $categories = ExpenseCategory::all();

        return response()->json([
            'status'=>true,
            'data'=>$data
        ]);
    }
}
