<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Order;
use App\Models\OrderLabel;
use App\Models\OrderProduct;
use App\Models\OrderBump;
use App\Models\UpSell;
use App\Models\Product;
use App\Models\OutgoingStock;
use App\Models\User;
use App\Models\CartAbandon;
use App\Models\FormHolder;
use App\Models\SoundNotification;
use App\Models\Customer;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allOrders($status="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        $agents = User::where('type','agent')->orderBy('id', 'DESC')->get();
        $staffs = User::where('type','staff')->orderBy('id', 'DESC')->get();

        if ($authUser->isSuperAdmin) {
            
            $orders = Order::all();
            if ($status=="") {
                $orders = Order::all();
            }
            if ($status=="new") {
                $orders = Order::where('status', 'new')->orderBy('id', 'DESC')->get();
            }
            if ($status=="new_from_alarm") {
                DB::table('sound_notifications')->update(['status'=>'seen']);
                $orders = Order::where('status', 'new')->orderBy('id', 'DESC')->get();
            }
            if ($status=="pending") {
                $orders = Order::where('status', 'pending')->orderBy('id', 'DESC')->get();
            }
            if ($status=="cancelled") {
                $orders = Order::where('status', 'cancelled')->orderBy('id', 'DESC')->get();
            }
            if ($status=="delivered_not_remitted") {
                $orders = Order::where('status', 'delivered_not_remitted')->orderBy('id', 'DESC')->get();
            }
            if ($status=="delivered_and_remitted") {
                $orders = Order::where('status', 'delivered_and_remitted')->orderBy('id', 'DESC')->get();
            }
    
            $entries = false; $formHolder = '';
            if ($status !== "") {
                $formHolder = FormHolder::where('unique_key', $status);
                if($formHolder->exists()) {
                    $formHolder = $formHolder->first();
                    // $formHolders = $formHolder->formHolders;
                    // $orders = Order::whereIn('orders.id', $formHolders->pluck('order_id'))->where('customer_id', '!=', null)->orWhere('id', $formHolder->order_id)->orderBy('id', 'DESC')->get();
                    $orders = Order::where('form_holder_id', $formHolder->id)->orderBy('id', 'DESC')->get();
                    $entries = true;
                }
            }
        } else {
            
            $orders = Order::where('agent_assigned_id', $authUser->id)->orWhere('staff_assigned_id', $authUser->id)->orWhere('created_by', $authUser->id)->orderBy('id', 'DESC')->get();
            if ($status=="") {
                $orders = Order::where('agent_assigned_id', $authUser->id)->orWhere('staff_assigned_id', $authUser->id)->orWhere('created_by', $authUser->id)->orderBy('id', 'DESC')->get();
            }
            if ($status=="new") {
                $orders = Order::where('status', 'new')->where('agent_assigned_id', $authUser->id)->orWhere('staff_assigned_id', $authUser->id)->orWhere('created_by', $authUser->id)->orderBy('id', 'DESC')->get();
            }
            if ($status=="new_from_alarm") {
                DB::table('sound_notifications')->update(['status'=>'seen']);
                $orders = Order::where('status', 'new')->where('agent_assigned_id', $authUser->id)->orWhere('staff_assigned_id', $authUser->id)->orWhere('created_by', $authUser->id)->orderBy('id', 'DESC')->get();
            }
            if ($status=="pending") {
                $orders = Order::where('status', 'pending')->where('agent_assigned_id', $authUser->id)->orWhere('staff_assigned_id', $authUser->id)->orWhere('created_by', $authUser->id)->orderBy('id', 'DESC')->get();
            }
            if ($status=="cancelled") {
                $orders = Order::where('status', 'cancelled')->where('agent_assigned_id', $authUser->id)->orWhere('staff_assigned_id', $authUser->id)->orWhere('created_by', $authUser->id)->orderBy('id', 'DESC')->get();
            }
            if ($status=="delivered_not_remitted") {
                $orders = Order::where('status', 'delivered_not_remitted')->where('agent_assigned_id', $authUser->id)->orWhere('staff_assigned_id', $authUser->id)->orWhere('created_by', $authUser->id)->orderBy('id', 'DESC')->get();
            }
            if ($status=="delivered_and_remitted") {
                $orders = Order::where('status', 'delivered_and_remitted')->where('agent_assigned_id', $authUser->id)->orWhere('staff_assigned_id', $authUser->id)->orWhere('created_by', $authUser->id)->orderBy('id', 'DESC')->get();
            }

            //orders whose dates are greater-than today
            if ($status=="total_follow_ups") {
                $totalFollowUpOrders1 = []; $today = Carbon::now(); $expected_date;
                foreach ($authUser->assignedOrders as $order) {
                    $expected_date = Carbon::parse($order->expected_delivery_date);
                    $result = $expected_date->gt($today);
                    if ($result) {
                        $totalFollowUpOrders1[] = $order;
                    }
                } 
                $orders = collect($totalFollowUpOrders1);
            }

            //today only
            if ($status=="today_follow_ups") {
                $todayFollowUpOrders1 = [];
                foreach ($authUser->assignedOrders as $order) {
                    $expected_date = Carbon::parse($order->expected_delivery_date);
                    $result = $expected_date->isToday();
                    if ($result) {
                        $todayFollowUpOrders1[] = $order;
                    }
                }
                $orders = collect($todayFollowUpOrders1);
            }
            
            //tomorrow only
            if ($status=="tomorrow_follow_ups") {
                $tomorrowFollowUpOrders1 = []; $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');
                foreach ($authUser->assignedOrders as $order) {
                    $expected_date = Carbon::parse($order->expected_delivery_date)->format('Y-m-d');
                    if ($expected_date == $tomorrow) {
                        $tomorrowFollowUpOrders1[] = $order;
                    }
                }
                $orders = collect($tomorrowFollowUpOrders1);
            }

            if ($status=="other_orders") {
                $orders = $authUser->assignedOrders()->where(['customer_id'=>null])->orderBy('id', 'DESC')->get();
            }
            
            $entries = false; $formHolder = '';
            if ($status !== "") {
                $formHolder = FormHolder::where('unique_key', $status);
                if($formHolder->exists()) {
                    $formHolder = $formHolder->first();
                    $formHolders = $formHolder->formHolders;
                    $orders = Order::whereIn('orders.id', $formHolders->pluck('order_id'))->where('customer_id', '!=', null)->orWhere('id', $formHolder->order_id)->orderBy('id', 'DESC')->get();
                    $entries = true;
                }
            }
        }
        
        return view('pages.orders.allOrders', compact('authUser', 'user_role', 'orders', 'agents', 'staffs', 'status', 'entries', 'formHolder'));
    }

    public function updateOrderStatus($unique_key, $status)
    {
        $order = Order::where('unique_key', $unique_key);
        if(!$order->exists()) {
            abort(404);
        }
        $order->update(['status'=>$status]);
        return back()->with('success', 'Order Updated Successfully!');
    }

    public function updateOrderDateStatus(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();
        $order_id = $data['order_id'];
        $order_delivery_date = $data['order_delivery_date'];
        $order_status = $data['order_status'];
        $order_note = $data['order_note'];

        $order = Order::where('id',$order_id)->first();
        
        $order->status = !empty($order_status) ? $order_status : $order->status;
        $order->order_note = !empty($order_note) ? $order_note : null;
        $order->expected_delivery_date = !empty($order_delivery_date) ? $order_delivery_date : $order->expected_delivery_date;
        $order->save();

        //upd order
        //DB::table('orders')->where('id',$order_id)->update(['status'=>$status, 'order_note'=>$order_note, 'expected_delivery_date'=>$order_delivery_date]);
        return back()->with('success', 'Order Date Status Updated Successfully!');
    }

    //orderForm
    public function singleOrder($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        
        $order = Order::where('unique_key', $unique_key);
        if(!$order->exists()) {
            abort(404);
        }
        $order = $order->first();
        $status = $order->status;

        $url = env('APP_URL').'/'.$order->url;
        $orderedProducts = unserialize($order->products);
        $products = [];
        $gross_revenue = 0;
        $currency = '';
        
        //return $packages;
        
        $outgoingStocks = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted'])->orderBy('id', 'DESC')->get();
        $packages = [];
        foreach ($outgoingStocks as $key => $product) {
            $products['product'] = $this->productById($product->product_id);
            $products['quantity_removed'] = $product->quantity_removed;
            $products['revenue'] =  $product->amount_accrued;
            $gross_revenue += $product->amount_accrued;
            $currency = $this->productById($product->product_id)->country->symbol;

            $packages[] = $products;
        }

        return view('pages.orders.singleOrder', compact('authUser', 'user_role', 'url', 'order', 'packages', 'gross_revenue', 'currency', 'status'));
    }

    //edit order, help customer to edit existing orders
    public function editOrder($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $order = Order::where('unique_key', $unique_key);
        if(!$order->exists()) {
            abort(404);
        }
        $order = $order->first();
        $status = $order->status;

        $products = Product::all();
        $customers = Customer::all();

        $orderbump_outgoingStock = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_orderbump'])->first();
        $upsell_outgoingStock = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_upsell'])->first();

        //order-products
        $mainProducts_outgoingStocks = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_order_firstphase'])->get();
        $packages = []; $gross_revenue = 0; $productArr = [];
        foreach ($mainProducts_outgoingStocks as $key => $outgone) {
            $productArr['product'] = $this->productById($outgone->product_id);
            $productArr['quantity_removed'] = $outgone->quantity_removed;
            $productArr['amount_accrued'] =  $outgone->amount_accrued;
            $productArr['customer_acceptance_status'] =  $outgone->customer_acceptance_status;
            $gross_revenue += $outgone->amount_accrued;
            $currency = $this->productById($outgone->product_id)->country->symbol;

            $packages[] = $productArr;
        }
        

        return view('pages.orders.editOrder', compact('authUser', 'user_role', 'order', 'status', 'products', 'customers', 'gross_revenue', 'currency', 'packages',
        'orderbump_outgoingStock', 'upsell_outgoingStock'));
    }

    //
    public function editOrderPost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $order = Order::where('unique_key', $unique_key);
        if(!$order->exists()) {
            abort(404);
        }
        $order = $order->first();

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
        $data['product_id'];
        $order->products = serialize($data['product_id']);
        $order->customer_id = $data['customer'];
        $order->status = $data['order_status'];
        $order->order_note = !empty($data['note']) ? $data['note'] : null;
        $order->save();
        
        $grand_total = 0;
        foreach ($data['product_id'] as $key => $id) {
            if(!empty($id)) {

               $outgone_existing = OutgoingStock::where('order_id', $order->id)->where('product_id', $id)->where('reason_removed', 'as_order_firstphase');
                
                if ($outgone_existing->exists()) {
                    $outgone = $outgone_existing->first();
                    $outgone->update([
                        'product_id' => $id,
                        'quantity_removed' => $data['product_qty'][$key],
                        'customer_acceptance_status' => $data['customer_acceptance_status'][$key],
                        'amount_accrued' => $data['product_qty'][$key] * $data['unit_price'][$key],
                        'reason_removed' => 'as_order_firstphase',
                        'quantity_returned' => $data['customer_acceptance_status'][$key] == 'rejected' ? $data['product_qty'][$key] : 0,
                        'reason_returned' => $data['customer_acceptance_status'][$key] == 'rejected' ? 'declined' : null,
                        'created_by' => $authUser->id,
                        'status' => 'true'
                    ]);
                } else {
                    //update product stock
                    $outgoingStock = new OutgoingStock();
                    $outgoingStock->product_id = $id;
                    $outgoingStock->order_id = $order->id;
                    $outgoingStock->quantity_removed = $data['product_qty'][$key];
                    $outgoingStock->amount_accrued = $data['product_qty'][$key] * $data['unit_price'][$key];
                    $outgoingStock->reason_removed = 'as_order_firstphase'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
                    $outgoingStock->customer_acceptance_status = $data['customer_acceptance_status'][$key];
                    $outgoingStock->quantity_returned = 0; //by default
                    $outgoingStock->created_by = $authUser->id;
                    $outgoingStock->status = 'true';
                    $outgoingStock->save();
                }
                
            }
        }

        $orderbump_outgoingStock = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_orderbump'])->first();
        $upsell_outgoingStock = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_upsell'])->first();

        //accepted orderbump
        if (!empty($data['orderbump_product']) && isset($orderbump_outgoingStock)) {
            OutgoingStock::where('order_id', $order->id)->where('reason_removed', 'as_orderbump')->update(['product_id'=>$data['orderbump_product'], 'customer_acceptance_status'=>'accepted']);
        }
        //rejected orderbump
        if (empty($data['orderbump_product']) && isset($orderbump_outgoingStock)) {
            OutgoingStock::where('order_id', $order->id)->where('reason_removed', 'as_orderbump')->update(['customer_acceptance_status'=>'rejected']);
        }
        //accepted upsell
        if (!empty($data['upsell_product'])) {
            OutgoingStock::where('order_id', $order->id)->where('reason_removed', 'as_upsell')->update(['product_id'=>$data['upsell_product']]);
        }
        //rejected upsell
        if (empty($data['upsell_product']) && isset($upsell_outgoingStock)) {
            OutgoingStock::where('order_id', $order->id)->where('reason_removed', 'as_upsell')->update(['customer_acceptance_status'=>'rejected']);
        }

        return back()->with('success', 'Order Updated Successfully');

    }

    public function assignAgentToOrder(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();
        $order_id = $data['order_id'];
        $agent_id = $data['agent_id'];

        //upd order
        Order::where('id',$order_id)->update(['agent_assigned_id'=>$agent_id]);

        return back()->with('success', 'Agent Assigned Successfully');
    }

    public function assignStaffToOrder(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();
        $order_id = $data['order_id'];
        $staff_id = $data['staff_id'];

        //upd order
        Order::where('id',$order_id)->update(['staff_assigned_id'=>$staff_id]);

        return back()->with('success', 'Staff Assigned Successfully');
    }

    public function cartAbandon()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        //remove any reminant
        CartAbandon::where([ 'customer_firstname'=>null, 'customer_lastname'=>null, 'customer_phone_number'=>null, 'customer_whatsapp_phone_number'=>null,
        'customer_email'=>null ])->delete();

        $carts = CartAbandon::all();
        
        $agents = User::where('type','agent')->orderBy('id', 'DESC')->get();
        return view('pages.orders.cartAbandon', compact('authUser', 'user_role', 'carts', 'agents'));
    }

    public function singleCartAbandon($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $cart = CartAbandon::where('unique_key', $unique_key)->first();
        $package_info = $cart->package_info; //wat customer clicked

        $order = $cart->FormHolder->order;

        $orderedProducts = unserialize($order->products);

        $products = [];
        $gross_revenue = 0;
        $currency = '';
        
        $outgoingStocks = OutgoingStock::where(['order_id'=>$order->id])->orderBy('id', 'DESC')->get();
        foreach ($outgoingStocks as $key => $product) {
            $products['product'] = $this->productById($product->product_id);
            $products['quantity_removed'] = $product->quantity_removed;
            $products['revenue'] =  $product->amount_accrued;
            $gross_revenue += $product->amount_accrued;
            $currency = $this->productById($product->product_id)->country->symbol;

            $packages[] = $products;
        }
        
        return view('pages.orders.singleCartAbandon', compact('authUser', 'user_role', 'cart', 'package_info', 'order', 'packages', 'gross_revenue', 'currency'));
    }

    public function deleteCartAbandon($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $cart = CartAbandon::where('unique_key', $unique_key)->first();
        $cart->delete();
        return back()->with('success', 'Cart Deleted Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productById($id){
        return $product = Product::where('id',$id)->first();
    }
}
