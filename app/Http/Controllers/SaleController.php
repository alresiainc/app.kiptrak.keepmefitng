<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Illuminate\Support\Facades\Session;

use App\Models\Country;
use App\Models\Supplier;
use App\Models\Sale;
use App\Models\Product;
use App\Models\IncomingStock;
use App\Models\OutgoingStock;
use App\Models\Purchase;
use App\Models\WareHouse;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Account;
use App\Models\Payment;

class SaleController extends Controller
{
    
    public function allSale()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $sales = Sale::where('parent_id', null)->get();
        return view('pages.sales.allSale', compact('authUser', 'user_role', 'sales'));
    }

    public function addSale()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $products = Product::all();
        $customers = Customer::all();
        $warehouses = WareHouse::all();
        $countries = Country::all();
        $sale_code = 'kps-' . date("Ymd") . '-'. date("his");
        
        return view('pages.sales.addSale', compact('authUser', 'user_role', 'products', 'customers', 'sale_code', 'warehouses', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function addSalePost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'customer' => 'required|string',
            'warehouse' => 'required|string',
            // 'sale_date' => 'required|string',
            'product' => 'required|string',
            'sale_status' => 'required|string',
            'payment_status' => 'required|string',
            'note' => 'nullable|string',
            'attached_document' => 'nullable|mimes:jpg, jpeg, png, pdf, csv, docx, xlsx, txt, gif, svg, webp|max:2048',
        ]);

        $data = $request->all();
        //duplicate. break fxn here
        $dup = [];
        foreach($data['product_id'] as $key => $id){
            if(!empty($id)){
                
                if(!in_array($id, $dup)){
                    $dup[] = $id;
                
                } else {
                    return back()->with('duplicate_error', 'Duplicate Product Detected. You can increase quantity accordingly');
                }
            }
  
        }

        $sale_code = 'kps-' . date("Ymd") . '-'. date("his");

        $imageName = '';
        if ($request->attached_document) {
            //image
            $imageName = time().'.'.$request->attached_document->extension();
            //store products in folder
            $request->attached_document->storeAs('sale', $imageName, 'public');
        }

        //save Order, for order stage
        $order = new Order();
        $order->source_type = 'sale_module';
        $order->customer_id = $data['customer'];
        $order->products = serialize($data['product_id']);
        $order->status = $data['sale_status'];
        $order->save();

        //upd customer
        Customer::where('id',$data['customer'])->update(['order_id'=>$order->id]);

        $grand_total = 0;

        //for stocking
        foreach ($data['product_id'] as $key => $id) {
            if(!empty($id)){
                if($key==0){
                    //$parent_sale = Sale::where('sale_code', $data['sale_code']);

                    $grand_total += $data['product_qty'][$key] * $data['unit_price'][$key];
    
                    //update product stock
                    $outgoingStock = new OutgoingStock();
                    $outgoingStock->product_id = $id;
                    $outgoingStock->order_id = $order->id;
                    $outgoingStock->quantity_removed = $data['product_qty'][$key];
                    $outgoingStock->customer_acceptance_status = $data['sale_status'] == 'delivered_and_remitted' ? 'accepted' : null;
                    $outgoingStock->amount_accrued = $data['product_qty'][$key] * $data['unit_price'][$key];
                    $outgoingStock->reason_removed = 'as_order_firstphase'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
                    $outgoingStock->quantity_returned = 0; //by default
                    $outgoingStock->created_by = $authUser->id;
                    $outgoingStock->status = 'true';
                    $outgoingStock->save();
                    
                    $sale = new Sale();
                    $sale->sale_code = $data['sale_code'];
                    // $sale->parent_id = $parent_sale->exists() ? $parent_sale->first()->id : null;
                    $sale->customer_id = $data['customer'];
                    $sale->warehouse_id = $data['warehouse'];
                    // $sale->sale_date = $data['sale_date'];
    
                    $sale->product_id = $id;
    
                    $sale->product_qty_sold = $data['product_qty'][$key];
                    $sale->product_selling_price = $data['unit_price'][$key];
                    $sale->outgoing_stock_id = $outgoingStock->id;
                    $sale->amount_due = $data['payment_status'] == 'paid' ? 0 : $data['product_qty'][$key] * $data['unit_price'][$key];
                    $sale->amount_paid = $data['product_qty'][$key] * $data['unit_price'][$key];
    
                    $sale->payment_status = $data['payment_status'];
                    $sale->note = !empty($data['note']) ? $data['note'] : null;
    
                    $sale->attached_document = $imageName == '' ? null : $imageName;
    
                    $sale->created_by = $authUser->id;
                    $sale->status = $data['sale_status'];
    
                    $sale->save();

                    $parent_sale_id = Session::put('parent_sale_id', $sale->id); //for grouping sales
                    
                    //update product <price></price>
                    Product::where(['id'=>$id])->update(['sale_id'=>$sale->id,'sale_price'=>$data['unit_price'][$key]]);
                }else{
                    //$parent_sale = Sale::where('sale_code', $data['sale_code']);

                    $grand_total += $data['product_qty'][$key] * $data['unit_price'][$key];
    
                    //update product stock
                    $outgoingStock = new OutgoingStock();
                    $outgoingStock->product_id = $id;
                    $outgoingStock->order_id = $order->id;
                    $outgoingStock->quantity_removed = $data['product_qty'][$key];
                    $outgoingStock->customer_acceptance_status = $data['sale_status'] == 'delivered_and_remitted' ? 'accepted' : null;
                    $outgoingStock->amount_accrued = $data['product_qty'][$key] * $data['unit_price'][$key];
                    $outgoingStock->reason_removed = 'as_order_firstphase'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
                    $outgoingStock->quantity_returned = 0; //by default
                    $outgoingStock->created_by = $authUser->id;
                    $outgoingStock->status = 'true';
                    $outgoingStock->save();
                    
                    $sale = new Sale();
                    $sale->sale_code = $data['sale_code'];
                    $sale->parent_id = Session::get('parent_sale_id');
                    $sale->customer_id = $data['customer'];
                    $sale->warehouse_id = $data['warehouse'];
                    // $sale->sale_date = $data['sale_date'];
    
                    $sale->product_id = $id;
    
                    $sale->product_qty_sold = $data['product_qty'][$key];
                    $sale->product_selling_price = $data['unit_price'][$key];
                    $sale->outgoing_stock_id = $outgoingStock->id;
                    $sale->amount_due = $data['payment_status'] == 'paid' ? 0 : $data['product_qty'][$key] * $data['unit_price'][$key];
                    $sale->amount_paid = $data['product_qty'][$key] * $data['unit_price'][$key];
    
                    $sale->payment_status = $data['payment_status'];
                    $sale->note = !empty($data['note']) ? $data['note'] : null;
    
                    $sale->attached_document = $imageName == '' ? null : $imageName;
    
                    $sale->created_by = $authUser->id;
                    $sale->status = $data['sale_status'];
    
                    $sale->save();

                    //update product <price></price>
                    Product::where(['id'=>$id])->update(['sale_id'=>$sale->id,'sale_price'=>$data['unit_price'][$key]]);
                }
            }
        }

        //for balanceSheet accounting
        $account = Account::where('name','Sales Account')->first();

        $payment = new Payment;
        $payment->sale_id = $data['sale_code']; 
        $payment->account_id = $account->id;
        $payment->amount = $grand_total;
        $payment->paying_method = 'cash';
        $payment->created_by = $authUser->id;
        $payment->status = 'true';
        $payment->save();

        
        return back()->with('success', 'Sale Order Added Successfully');

        
    }

    public function singleSale($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $sale = Sale::where('unique_key', $unique_key);
        if(!$sale->exists()){
            abort(404);
        }
        $sale = $sale->first();

        return view('pages.sales.singleSale', compact('authUser', 'user_role', 'sale'));
    }

    public function editSale($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $sale = Sale::where('unique_key', $unique_key);
        // $sale_code = $sale->first()->sale_code;
        if(!$sale->exists()){
            abort(404);
        }
        $sale_code = $sale->first()->sale_code;
        $products = Product::all();
        $customers = Customer::all();
        $warehouses = WareHouse::all();
        $sales = Sale::where('sale_code', $sale_code)->get();
        $sale = $sale->first();
        
        return view('pages.sales.editSale', compact('authUser', 'user_role', 'products', 'customers', 'warehouses', 'sale', 'sales'));
    }

    public function editSalePost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $sale = Sale::where('unique_key', $unique_key);
        if(!$sale->exists()){
            abort(404);
        }
        $sale = $sale->first();
        $request->validate([
            'customer' => 'required|string',
            'warehouse' => 'required|string',
            // 'sale_date' => 'required|string',
            'product' => 'nullable|string',
            'sale_status' => 'required|string',
            'payment_status' => 'required|string',
            'note' => 'nullable|string',
            'attached_document' => 'nullable|mimes:jpg, jpeg, png, pdf, csv, docx, xlsx, txt, gif, svg, webp|max:2048',
        ]);

        $data = $request->all();

        $dup = [];
        foreach($data['product_id'] as $key => $id){
            if(!empty($id)){
                
                if(!in_array($id, $dup)){
                    $dup[] = $id;
                
                } else {
                    return back()->with('duplicate_error', 'Duplicate Product Detected. You can increase quantity accordingly');
                }
            }
        }

        $sale_code = $sale->sale_code;

        //file
        $imageName = '';
        if ($request->attached_document) {
            $oldImage = $sale->attached_document; //1.jpg
            if(Storage::disk('public')->exists('sale/'.$oldImage)){
                Storage::disk('public')->delete('sale/'.$oldImage);
                /*
                    Delete Multiple files this way
                    Storage::delete(['upload/test.png', 'upload/test2.png']);
                */
            }
            $imageName = time().'.'.$request->attached_document->extension();
            //store file in folder
            $request->attached_document->storeAs('sale', $imageName, 'public');
            //$sale->attached_document = $imageName;
        }
        //

        //update order
        $order_id = $sale->outgoingStock->order_id;
        $order = Order::find($order_id);
        $order->source_type = 'sale_module';
        $order->products = serialize($data['product_id']);
        $order->save();

        $grand_total = 0;
        foreach ($data['product_id'] as $key => $id) {
            if(!empty($id)){
                
                $parent_sale = Sale::where('sale_code', $sale->sale_code);

                $grand_total += $data['product_qty'][$key] * $data['unit_price'][$key];

                $existing_sale = $parent_sale->where('product_id', $id);

                //update product <price></price>
                Product::where(['id'=>$id])->update(['sale_id'=>$existing_sale->first()->id,'sale_price'=>$data['unit_price'][$key]]);

                if ($existing_sale->exists()) {
                    
                    $existing_sale->update([
                        'customer_id' => $data['customer'],
                        'warehouse_id' => $data['warehouse'],
                        // 'sale_date' => $data['sale_date'],
                        'product_id' => $id,
                        'product_qty_sold' => $data['product_qty'][$key],
                        'amount_due' => $data['payment_status'] == 'paid' ? 0 : $data['product_qty'][$key] * $data['unit_price'][$key],
                        'amount_paid' => $data['product_qty'][$key] * $data['unit_price'][$key],

                        'payment_status' => $data['payment_status'],
                        'note' => !empty($data['note']) ? $data['note'] : null,

                        'attached_document' => $imageName == '' ? null : $imageName,
                        'created_by' => 1,
                        'status' => $data['sale_status'],
                    ]);
                    OutgoingStock::where(['id'=>$data['outgoing_stock_id'][$key]])->update([
                     'product_id' => $id,
                     'quantity_removed' => $data['product_qty'][$key],
                     'customer_acceptance_status' => $data['sale_status'] == 'delivered_and_remitted' ? 'accepted' : null,
                     'amount_accrued' => $data['product_qty'][$key] * $data['unit_price'][$key],
                     'reason_removed' => 'as_order_firstphase',
                     'created_by' => 1,
                     'status' => 'true'
                    ]);
                    
                } else {
                    
                    $imageName = '';
                    if ($request->attached_document) {
                        //image
                        $imageName = time().'.'.$request->attached_document->extension();
                        //store products in folder
                        $request->attached_document->storeAs('sale', $imageName, 'public');
                    }

                    //update product stock
                    $outgoingStock = new OutgoingStock();
                    $outgoingStock->product_id = $id;
                    $outgoingStock->order_id = $order->id;
                    $outgoingStock->quantity_removed = $data['product_qty'][$key];
                    $outgoingStock->amount_accrued = $data['product_qty'][$key] * $data['unit_price'][$key];
                    $outgoingStock->reason_removed = 'as_order_firstphase'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
                    $outgoingStock->customer_acceptance_status = $data['sale_status'] == 'delivered_and_remitted' ? 'accepted' : null;
                    $outgoingStock->quantity_returned = 0; //by default
                    $outgoingStock->created_by = $authUser->id;
                    $outgoingStock->status = 'true';
                    $outgoingStock->save();
                    
                    //incase of new added
                    $sale = new Sale();
                    $sale->sale_code = $sale_code;
                    $sale->parent_id = $parent_sale->exists() ? $parent_sale->first()->id : null;
                    $sale->customer_id = $data['customer'];
                    $sale->warehouse_id = $data['warehouse'];
                    // $sale->sale_date = $data['sale_date'];

                    $sale->product_id = $id;

                    $sale->product_qty_sold = $data['product_qty'][$key];
                    $sale->outgoing_stock_id = $outgoingStock->id;
                    $sale->amount_due = $data['payment_status'] == 'paid' ? 0 : $data['product_qty'][$key] * $data['unit_price'][$key];
                    $sale->amount_paid = $data['product_qty'][$key] * $data['unit_price'][$key];

                    $sale->payment_status = $data['payment_status'];
                    $sale->note = !empty($data['note']) ? $data['note'] : null;

                    $sale->attached_document = $imageName == '' ? null : $imageName;

                    $sale->created_by = $authUser->id;
                    $sale->status = $data['sale_status'];

                    $sale->save();

                    //update product <price></price>
                    Product::where(['id'=>$id])->update(['sale_id'=>$sale->id,'sale_price'=>$data['unit_price'][$key]]);

                    //for balanceSheet accounting
                    $payment = new Payment;
                    $payment->sale_id = $sale->id;
                    $payment->account_id = $account->id;
                    $payment->amount = $data['product_qty'][$key] * $data['unit_price'][$key];
                    $payment->paying_method = 'cash';
                    $payment->created_by = $authUser->id;
                    $payment->status = 'true';
                    $payment->save();

                }
                  
            }
        }

        //for balanceSheet accounting
        //since sale code is still same
        $payment = Payment::where('sale_id', $sale_code)->first();
        $payment->amount = $grand_total;
        $payment->paying_method = 'cash';
        $payment->created_by = $authUser->id;
        $payment->status = 'true';
        $payment->save();

        return back()->with('success', 'Sales Updated Successfully');
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
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
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
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        //
    }
}
