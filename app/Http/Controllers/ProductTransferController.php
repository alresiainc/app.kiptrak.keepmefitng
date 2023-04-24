<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WareHouse;
use App\Models\ProductWarehouse;

class ProductTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function productTransferSetupPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $request->validate([
            'from_warehouse' => 'required',
            'to_warehouse' => 'required|different:from_warehouse',
        ]);

        return redirect()->route('productTransferSetup', ['from_warehouse_unique_key'=>$request->from_warehouse, 'to_warehouse_unique_key'=>$request->to_warehouse]);

    }

    public function productTransferSetup($from_warehouse_unique_key, $to_warehouse_unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $from_warehouse = WareHouse::where('unique_key',$from_warehouse_unique_key);
        $to_warehouse = WareHouse::where('unique_key',$to_warehouse_unique_key);
        if ((!$from_warehouse->exists()) || (!$to_warehouse->exists())) {
            abort(404);
        }
        
        $from_warehouse = $from_warehouse->first();
        $to_warehouse = $to_warehouse->first();

        $products = $from_warehouse->products;
        $warehouses = WareHouse::all();

        return view('pages.transfers.productTransfer', compact('authUser', 'user_role', 'from_warehouse', 'to_warehouse', 'products', 'warehouses'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function productTransferPost(Request $request, $from_warehouse_unique_key, $to_warehouse_unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        //check
        $from_warehouse = WareHouse::where('unique_key',$from_warehouse_unique_key);
        $to_warehouse = WareHouse::where('unique_key',$to_warehouse_unique_key);
        if ((!$from_warehouse->exists()) || (!$to_warehouse->exists())) {
            abort(404);
        }
        $from_warehouse = $from_warehouse->first();
        $to_warehouse = $to_warehouse->first();

        //grab requests
        $data = $request->all();
        if (empty($data)) {
            return back()->with('empty_error', 'Nothing Selected');
        }

        //check duplicates
        $dup = [];
        foreach($data['product_id'] as $key => $id){
            if(!empty($id)) {
                if(!in_array($id, $dup)){
                    $dup[] = $id;
                } else {
                    return back()->with('duplicate_error', 'Duplicate Product Detected. You can increase quantity accordingly');
                }
            }
        }

        foreach ($data['product_id'] as $key => $id) {
            if ( (!empty($data['product_qty'][$key])) && ($data['product_qty'][$key] != 0) ) {
                //update 'from-warehouse', cos it always exists
               $productWarehouse = ProductWarehouse::where('product_id', $id)->where('warehouse_id', $from_warehouse->id)->update(['product_qty'=>$data['qty_avail_in_from'][$key]]);
               
               $productWarehouse2 = ProductWarehouse::where('product_id', $id)->where('warehouse_id', $to_warehouse->id)->first();
               if (isset($productWarehouse2)) {
                $productWarehouse2->update(['product_qty'=>$data['qty_avail_in_to'][$key]]);
               } else {
                $product_warehouse = new ProductWarehouse();
                $product_warehouse->product_id = $id;
                $product_warehouse->product_qty = $data['qty_avail_in_to'][$key];
                $product_warehouse->warehouse_id = $to_warehouse->id;
                $product_warehouse->save();
               }
            }  
        }
        return back()->with('success', 'Products Transferred Successfully'); 
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
