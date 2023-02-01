<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;

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

    
        if ($authUser->isSuperAdmin) {
            
            $orders = Order::all();
            if ($status=="") {
                $orders = Order::all();
            }
            if ($status=="new") {
                $orders = Order::where('status', 'new')->get();
            }
            if ($status=="new_from_alarm") {
                DB::table('sound_notifications')->update(['status'=>'seen']);
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
    
            $entries = false; $formHolder = '';
            if ($status !== "") {
                $formHolder = FormHolder::where('unique_key', $status);
                if($formHolder->exists()) {
                    $formHolder = $formHolder->first();
                    // $formHolders = $formHolder->formHolders;
                    // $orders = Order::whereIn('orders.id', $formHolders->pluck('order_id'))->where('customer_id', '!=', null)->orWhere('id', $formHolder->order_id)->get();
                    $orders = Order::where('form_holder_id', $formHolder->id)->get();
                    $entries = true;
                }
            }
        } else {
            
            $orders = Order::where('agent_assigned_id', $authUser->id)->orWhere('staff_assigned_id', $authUser->id)->orWhere('created_by', $authUser->id)->get();
            if ($status=="") {
                $orders = Order::where('agent_assigned_id', $authUser->id)->orWhere('staff_assigned_id', $authUser->id)->orWhere('created_by', $authUser->id)->get();
            }
            if ($status=="new") {
                $orders = Order::where('status', 'new')->where('agent_assigned_id', $authUser->id)->orWhere('staff_assigned_id', $authUser->id)->orWhere('created_by', $authUser->id)->get();
            }
            if ($status=="new_from_alarm") {
                DB::table('sound_notifications')->update(['status'=>'seen']);
                $orders = Order::where('status', 'new')->where('agent_assigned_id', $authUser->id)->orWhere('staff_assigned_id', $authUser->id)->orWhere('created_by', $authUser->id)->get();
            }
            if ($status=="pending") {
                $orders = Order::where('status', 'pending')->where('agent_assigned_id', $authUser->id)->orWhere('staff_assigned_id', $authUser->id)->orWhere('created_by', $authUser->id)->get();
            }
            if ($status=="cancelled") {
                $orders = Order::where('status', 'cancelled')->where('agent_assigned_id', $authUser->id)->orWhere('staff_assigned_id', $authUser->id)->orWhere('created_by', $authUser->id)->get();
            }
            if ($status=="delivered_not_remitted") {
                $orders = Order::where('status', 'delivered_not_remitted')->where('agent_assigned_id', $authUser->id)->orWhere('staff_assigned_id', $authUser->id)->orWhere('created_by', $authUser->id)->get();
            }
            if ($status=="delivered_and_remitted") {
                $orders = Order::where('status', 'delivered_and_remitted')->where('agent_assigned_id', $authUser->id)->orWhere('staff_assigned_id', $authUser->id)->orWhere('created_by', $authUser->id)->get();
            }

            $entries = false; $formHolder = '';
            if ($status !== "") {
                $formHolder = FormHolder::where('unique_key', $status);
                if($formHolder->exists()) {
                    $formHolder = $formHolder->first();
                    $formHolders = $formHolder->formHolders;
                    $orders = Order::whereIn('orders.id', $formHolders->pluck('order_id'))->where('customer_id', '!=', null)->orWhere('id', $formHolder->order_id)->get();
                    $entries = true;
                }
            }
        }
        

        return view('pages.orders.allOrders', compact('authUser', 'user_role', 'orders', 'agents', 'status', 'entries', 'formHolder'));
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

        //remove any reminant
        CartAbandon::where([ 'customer_firstname'=>null, 'customer_lastname'=>null, 'customer_phone_number'=>null, 'customer_whatsapp_phone_number'=>null,
        'customer_email'=>null ])->delete();

        $carts = CartAbandon::all();
        
        $agents = User::where('type','agent')->get();
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
        
        $outgoingStocks = OutgoingStock::where(['order_id'=>$order->id])->get();
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
