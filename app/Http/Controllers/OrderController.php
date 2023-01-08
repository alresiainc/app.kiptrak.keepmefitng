<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Models\Order;
use App\Models\OrderLabel;
use App\Models\OrderProduct;
use App\Models\OrderBump;
use App\Models\UpSell;
use App\Models\Product;
use App\Models\OutgoingStock;
use App\Models\User;
use App\Models\CartAbandon;


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
        $agents = User::where('type','agent')->get();

        $orders = Order::all();
        if ($status=="") {
            $orders = Order::all();
        }
        if ($status=="new") {
            $orders = Order::where('status', 'new')->get();
        }
        if ($status=="pending") {
            $orders = Order::where('status', 'pending')->get();
        }
        if ($status=="cancelled") {
            $orders = Order::where('status', 'cancelled')->get();
        }
        if ($status=="delivered_not_remitted") {
            $orders = Order::where('status', 'delivered_not_remitted')->get();
        }
        if ($status=="delivered_and_remitted") {
            $orders = Order::where('status', 'delivered_and_remitted')->get();
        }

        return view('pages.orders.allOrders', compact('authUser', 'user_role', 'orders', 'agents', 'status'));
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
        
        $outgoingStocks = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted'])->get();
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
        
        $carts = CartAbandon::all();
        $contacts = [];
        $packages = [];
        foreach ($carts as $key => $cart) {
            $cart_ids['cart_id'] = $cart->id;
            $contacts[] = \unserialize($cart->customer_info);
            $packages [] = \unserialize($cart->package_info);
        }

        $contact_info = $contacts[0]['inputValueName'];
        $product_info = $packages[0]['product_package']; ['1'];

        //["Jerry|first-name","James|last-name","09876234567|phone-number","09876234567|whatsapp-phone-number","jerrry@email.com|active-email","Lagos|state","Ikeja|city","1"]

        $customers = []; $customer_holder=[];
        foreach ($contact_info as $key => $contact) {
            $customers['firstname'] = (explode("|", $contact)[1] == 'first-name') ? explode("|", $contact)[0] : 'none';
            $customers['lastname'] = (explode("|", $contact)[1] == 'last-name') ? explode("|", $contact)[0] : 'none';
            $customers['phone_number'] = (explode("|", $contact)[1] == 'phone-number') ? explode("|", $contact)[0] : 'none';
            $customers['whatsapp_phone_number'] = (explode("|", $contact)[1] == 'whatsapp-phone-number') ? explode("|", $contact)[0] : 'none';
            $customers['active_email'] = (explode("|", $contact)[1] == 'active-email') ? explode("|", $contact)[0] : 'none';
            $customers['state'] = (explode("|", $contact)[1] == 'state') ? explode("|", $contact)[0] : 'none';
            $customers['city'] = (explode("|", $contact)[1] == 'city') ? explode("|", $contact)[0] : 'none'; 
        }
        //return $customers;

        $customer_holder['customer'] = $customers;

        $products = [];
        foreach ($product_info as $key => $id) {
            $products['products'] = $this->productById($id)->first();
        }

        $final_cart = array_merge($customer_holder, $products, $cart_ids);

        $agents = User::where('type','agent')->get();
        return view('pages.orders.cartAbandon', compact('authUser', 'user_role', 'carts', 'agents', 'final_cart'));
    }

    public function singleCartAbandon($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $cart = CartAbandon::where('unique_key', $unique_key)->first();
        $customer_info = \unserialize($cart->customer_info)['inputValueName'];
        $package_info = \unserialize($cart->package_info)['product_package']; //wat customer clicked

        $order = $cart->FormHolder->order;

        $orderedProducts = unserialize($order->products);

        $products = [];
        $gross_revenue = 0;
        $currency = '';
        
        $outgoingStocks = OutgoingStock::where(['order_id'=>$order->id])->get();
        foreach ($outgoingStocks as $key => $product) {
            $products['product'] = $this->productById($product->product_id);
            $products['quantity_removed'] = $product->quantity_removed;
            $products['revenue'] =  $product->amount_accrued;
            $gross_revenue += $product->amount_accrued;
            $currency = $this->productById($product->product_id)->country->symbol;

            $packages[] = $products;
        }
        
        return view('pages.orders.singleCartAbandon', compact('authUser', 'user_role', 'cart', 'customer_info', 'package_info', 'order', 'packages', 'gross_revenue', 'currency'));
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
