<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

use App\Models\Order;
use App\Models\OrderLabel;
use App\Models\OrderProduct;
use App\Models\OrderBump;
use App\Models\UpSell;
use App\Models\User;
use App\Models\Product;
use App\Models\OutgoingStock;
use App\Models\IncomingStock;
use App\Models\Country;

class FormController extends Controller
{
    public function allForms()
    {
        $formLabels = OrderLabel::all();
        return view('pages.forms.allForms', compact('formLabels'));
    }

    public function addForm()
    {
        $agents = User::where('type', 'agent')->where('status', 'true')->get();
        $products = Product::where('status', 'true')->get();
        
        return view('pages.forms.addForm', compact('agents', 'products'));
    }

    //unused, addOrder POST, considering orderbump, upsell, and orderproducts tbl
    public function addFormPost2(Request $request)
    {
        $request->validate([
            'order_heading' => 'required|string',
            'order_subheading' => 'required|string',
            // 'product_id' => 'required|string',
            'orderbump_heading' => 'nullable|string',
            'orderbump_subheading' => 'nullable|string',
            'upsell_heading' => 'nullable|string',
            'upsell_subheading' => 'nullable|string',
            'agent_assigned' => 'required|string',
            'thankyou_heading' => 'required|string',
            'thankyou_subheading' => 'required|string',
        ]);
        
        $data = $request->all();
        
        //save OrderLabel
        $orderLabel = new OrderLabel();
        $orderLabel->order_heading = $data['order_heading'];
        $orderLabel->order_subheading = $data['order_subheading'];
        
        //if orderbump is added
        if( ($data['orderbump_status']=='true') && (!empty($data['orderbump_product_id'])) ){
            $orderLabel->orderbump_heading = $data['orderbump_heading'];
            $orderLabel->orderbump_subheading = $data['orderbump_subheading'];
        }
        //if upsell is added
        if( ($data['upsell_status']=='true') && (!empty($data['upsell_product_id'])) ){
            $orderLabel->upsell_heading = $data['upsell_heading'];
            $orderLabel->upsell_subheading = $data['upsell_subheading'];
        }
        
        //customer label
        $orderLabel->customer_firstname_label = !empty($data['firstname']) ? $data['firstname'] : null;
        $orderLabel->customer_lastname_label = !empty($data['lastname']) ? $data['lastname'] : null;
        $orderLabel->customer_phone_label = !empty($data['phone']) ? $data['phone'] : null;
        $orderLabel->customer_email_label = !empty($data['email']) ? $data['email'] : null;
        $orderLabel->customer_city_label = $data['city'];
        $orderLabel->customer_state_label = !empty($data['state']) ? $data['state'] : null;
        $orderLabel->customer_address_label = !empty($data['address']) ? $data['address'] : null;
        $orderLabel->customer_country_label = !empty($data['country']) ? $data['country'] : null;
        

        //thank you
        $orderLabel->thankyou_heading = $data['thankyou_heading'];
        $orderLabel->thankyou_subheading = $data['thankyou_subheading'];

        $orderLabel->save();

        //--------------------------------------
        //save products - OrderBumps & UpSell
        $orderbump_expected_sum = '';
        if( ($data['orderbump_status']=='true') && (!empty($data['orderbump_product_id'])) ){
            $orderBump = new OrderBump();

            //explode orderbump product
            $orderbump_product_id_array = explode('-', $data['orderbump_product_id']); //1-10-1000
            $orderbump_product_id = $orderbump_product_id_array[0];
            // $orderbump_product_quantity = $orderbump_product_id[1];
            $orderbump_product_unitprice = $orderbump_product_id_array[2]; //array to string, then int

            $orderbump_expected_sum = $data['orderbump_product_quantity'] * $orderbump_product_unitprice;

            $orderBump->product_id = $orderbump_product_id;
            //$orderBump->orderbump_discount = !empty($data['orderbump_discount']) ? $data['orderbump_discount'] : null;
            $orderBump->product_expected_quantity_to_be_sold = $data['orderbump_product_quantity'];
            $orderBump->product_expected_amount = $orderbump_expected_sum;
            $orderBump->save();
            
        }

        $upsell_expected_sum = '';
        if( ($data['upsell_status']=='true') && (!empty($data['upsell_product_id'])) ){
            $upSell = new UpSell();

            //explode upsell product
            $upsell_product_id_array = explode('-', $data['upsell_product_id']);
            $upsell_product_id = $upsell_product_id_array[0];
            // $upsell_product_quantity = $upsell_product_id[1];
            //$upsell_product_unitprice = (int) json_decode(json_encode($upsell_product_id_array[2])); //array to string, then int
            $upsell_product_unitprice = $upsell_product_id_array[2];

            $upsell_expected_sum = $data['upsell_product_quantity'] * $upsell_product_unitprice;

            $upSell->product_id = $upsell_product_id;
            //$upSell->upsell_discount = !empty($data['upsell_discount']) ? $data['upsell_discount'] : null;
            $upSell->product_expected_quantity_to_be_sold = $data['upsell_product_quantity'];
            $upSell->product_expected_amount = $upsell_expected_sum;
            $upSell->save();
        }
        //-------------------------------------------------------------------------

        //-----------------------------------
        //save Order
        $order = new Order();
        //if orderbump is added
        if( ($data['orderbump_status']=='true') && (!empty($data['orderbump_product_id'])) ){
            $order->orderbump_id = $orderBump->id;
        }
        //if upsell is added
        if( ($data['upsell_status']=='true') && (!empty($data['upsell_product_id'])) ){
            $order->upsell_id = $upSell->id;
        }
        
        $order->agent_assigned_id = $data['agent_assigned'];
        $order->order_label_id = $orderLabel->id;
        $order->discount = !empty($data['discount']) ? $data['discount'] : null;
        // $order->amount_expected = '35050'; //updated after OrderProduct
        $order->save();

        //update orderLabel
        $orderLabel->update(['order_id'=>$order->id]);

        //update orderBump & upSell
        if( ($data['orderbump_status']=='true') && (!empty($data['orderbump_product_id'])) ){
            $orderBump->update(['order_id'=>$order->id]);
        }
        if( ($data['upsell_status']=='true') && (!empty($data['upsell_product_id'])) ){
            $upSell->update(['order_id'=>$order->id]);
        }
        
        //save OrderProduct, first-phase products only
        
        // foreach ($data['product_quantity'] as $key => $qty) {
        //     if (!empty($qty)) {
        //         $string = $data['product_id'][$key]; //'1-10-1000 //id,stock,unitprice
        //         $product_id = substr($string, 0, strpos($string, '-')); //before first '-'
        //         $product_unitprice = substr($string, strrpos($string, '-' )+1); //before last '-'

        //         $orderProduct = new OrderProduct();
        //         $orderProduct->product_id = $product_id; //product_id

        //         ///////$orderProduct->discount = !empty($data['product_discount'][$key]) ? $data['product_discount'][$key] : null;
        //         $orderProduct->product_expected_quantity_to_be_sold = $qty;
        //         $orderProduct->product_expected_amount = (int) $qty * $product_unitprice; //discount will be added later
                
        //         $orderProduct->order_id = $order->id;
        //         $orderProduct->save();
        //     }
            
        //     // return '123x';
        // }
        
        //save OrderProduct. relative to 'order products without clone' in 'addForm.blade'
        $product_id_array = explode('-', $data['product_id']); //1-10-1000, id,stock,unitprice

        $product_id = $product_id_array[0];
        $product_unitprice = $product_id_array[2];

        $orderProduct = new OrderProduct();
        $orderProduct->product_id = $product_id; //product_id

        ///////$orderProduct->discount = !empty($data['product_discount'][$key]) ? $data['product_discount'][$key] : null;
        $orderProduct->product_expected_quantity_to_be_sold = $data['product_quantity'];
        $orderProduct->product_expected_amount = (int) $data['product_quantity'] * $product_unitprice; //discount will be added later
        
        $orderProduct->order_id = $order->id;
        $orderProduct->save();
        
            
        // return '123x';
        
        //update Order
        $expected_sum = OrderProduct::where('order_id', $order->id)->sum('product_expected_amount');
        if($orderbump_expected_sum !== ''){
            $expected_sum = $expected_sum + $orderbump_expected_sum;
        }
        if($upsell_expected_sum !== ''){
            $expected_sum = $expected_sum + $upsell_expected_sum;
        }
        $order->update(['amount_expected'=>$expected_sum]);

        return back()->with('success', 'Order Form Created Successfully');

    }

    //addOrder POST, using outgoingStock
    public function addFormPost(Request $request)
    {
        $request->validate([
            'order_heading' => 'required|string',
            'order_subheading' => 'required|string',
            // 'product_id' => 'required|string',
            'orderbump_heading' => 'nullable|string',
            'orderbump_subheading' => 'nullable|string',
            'upsell_heading' => 'nullable|string',
            'upsell_subheading' => 'nullable|string',
            'agent_assigned' => 'required|string',
            'thankyou_heading' => 'required|string',
            'thankyou_subheading' => 'required|string',
        ]);
        
        $data = $request->all();
        
        //save OrderLabel
        $orderLabel = new OrderLabel();
        $orderLabel->order_heading = $data['order_heading'];
        $orderLabel->order_subheading = $data['order_subheading'];
        
        //if orderbump is added
        if( ($data['orderbump_status']=='true') && (!empty($data['orderbump_product_id'])) ){
            $orderLabel->orderbump_heading = $data['orderbump_heading'];
            $orderLabel->orderbump_subheading = $data['orderbump_subheading'];
        }
        //if upsell is added
        if( ($data['upsell_status']=='true') && (!empty($data['upsell_product_id'])) ){
            $orderLabel->upsell_heading = $data['upsell_heading'];
            $orderLabel->upsell_subheading = $data['upsell_subheading'];
        }
        
        //customer label
        $orderLabel->customer_firstname_label = !empty($data['firstname']) ? $data['firstname'] : null;
        $orderLabel->customer_lastname_label = !empty($data['lastname']) ? $data['lastname'] : null;
        $orderLabel->customer_phone_label = !empty($data['phone']) ? $data['phone'] : null;
        $orderLabel->customer_email_label = !empty($data['email']) ? $data['email'] : null;
        $orderLabel->customer_city_label = $data['city'];
        $orderLabel->customer_state_label = !empty($data['state']) ? $data['state'] : null;
        $orderLabel->customer_address_label = !empty($data['address']) ? $data['address'] : null;
        $orderLabel->customer_country_label = !empty($data['country']) ? $data['country'] : null;
        

        //thank you
        $orderLabel->thankyou_heading = $data['thankyou_heading'];
        $orderLabel->thankyou_subheading = $data['thankyou_subheading'];

        $orderLabel->save();

        //--------------------------------------
        //save Order
        $order = new Order();
        $order->agent_assigned_id = $data['agent_assigned'];
        $order->order_label_id = $orderLabel->id;
        $order->discount = !empty($data['discount']) ? $data['discount'] : null;
        $order->save();

        //update orderLabel
        $orderLabel->update(['order_id'=>$order->id]);

        //--------------------------------------
        //save products - OrderBumps & UpSell
        $orderbump_expected_sum = '';
        if ( ($data['orderbump_status']=='true') && (!empty($data['orderbump_product_id'])) ) {
            // $orderBump = new OrderBump();

            //explode orderbump product
            $orderbump_product_id_array = explode('-', $data['orderbump_product_id']); //1-10-1000
            $orderbump_product_id = $orderbump_product_id_array[0];
            // $orderbump_product_quantity = $orderbump_product_id[1];
            $orderbump_product_unitprice = $orderbump_product_id_array[2]; //array to string, then int

            $orderbump_expected_sum = $data['orderbump_product_quantity'] * $orderbump_product_unitprice;

            // $orderBump->product_id = $orderbump_product_id;
            // $orderBump->product_expected_quantity_to_be_sold = $data['orderbump_product_quantity'];
            // $orderBump->product_expected_amount = $orderbump_expected_sum;
            // $orderBump->save();

            //outgoingStock
            $outgoingStock = new OutgoingStock();
            $outgoingStock->product_id = $orderbump_product_id;
            $outgoingStock->order_id = $order->id;
            $outgoingStock->quantity_removed = $data['orderbump_product_quantity'];
            $outgoingStock->amount_realised = 0;
            $outgoingStock->reason_removed = 'as_orderbump'; //as_order_firstphase, as_orderbump, as_upsell, as_expired, as_damaged,
            $outgoingStock->quantity_returned = 0; //by default
            $outgoingStock->created_by = 1;
            $outgoingStock->status = 'true';
            $outgoingStock->save();
            $orderbump = $outgoingStock;
            
        }

        $upsell_expected_sum = '';
        if( ($data['upsell_status']=='true') && (!empty($data['upsell_product_id'])) ){
            // $upSell = new UpSell();

            //explode upsell product
            $upsell_product_id_array = explode('-', $data['upsell_product_id']);
            $upsell_product_id = $upsell_product_id_array[0];
            // $upsell_product_quantity = $upsell_product_id[1];
            //$upsell_product_unitprice = (int) json_decode(json_encode($upsell_product_id_array[2])); //array to string, then int
            $upsell_product_unitprice = $upsell_product_id_array[2];

            $upsell_expected_sum = $data['upsell_product_quantity'] * $upsell_product_unitprice;

            // $upSell->product_id = $upsell_product_id;
            // $upSell->product_expected_quantity_to_be_sold = $data['upsell_product_quantity'];
            // $upSell->product_expected_amount = $upsell_expected_sum;
            // $upSell->save();

            //outgoingStock
            $outgoingStock = new OutgoingStock();
            $outgoingStock->product_id = $upsell_product_id;
            $outgoingStock->order_id = $order->id;
            $outgoingStock->quantity_removed = $data['upsell_product_quantity'];
            $outgoingStock->amount_realised = 0;
            $outgoingStock->reason_removed = 'as_upsell'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
            $outgoingStock->quantity_returned = 0; //by default
            $outgoingStock->created_by = 1;
            $outgoingStock->status = 'true';
            $outgoingStock->save();
            $upsell = $outgoingStock;
        }
        //-------------------------------------------------------------------------

        //-----------------------------------
        //save OrderProduct, first-phase products only
        
        // foreach ($data['product_quantity'] as $key => $qty) {
        //     if (!empty($qty)) {
        //         $string = $data['product_id'][$key]; //'1-10-1000 //id,stock,unitprice
        //         $product_id = substr($string, 0, strpos($string, '-')); //before first '-'
        //         $product_unitprice = substr($string, strrpos($string, '-' )+1); //before last '-'

        //         $orderProduct = new OrderProduct();
        //         $orderProduct->product_id = $product_id; //product_id

        //         ///////$orderProduct->discount = !empty($data['product_discount'][$key]) ? $data['product_discount'][$key] : null;
        //         $orderProduct->product_expected_quantity_to_be_sold = $qty;
        //         $orderProduct->product_expected_amount = (int) $qty * $product_unitprice; //discount will be added later
                
        //         $orderProduct->order_id = $order->id;
        //         $orderProduct->save();
        //     }
            
        //     // return '123x';
        // }
        
        //save OrderProduct. relative to 'order products without clone' in 'addForm.blade'
        $product_id_array = explode('-', $data['product_id']); //1-10-1000, id,stock,unitprice
        $product_id = $product_id_array[0];
        $product_unitprice = $product_id_array[2];

        // $orderProduct = new OrderProduct();
        // $orderProduct->product_id = $product_id; //product_id
        // $orderProduct->product_expected_quantity_to_be_sold = $data['product_quantity'];
        // $orderProduct->product_expected_amount = (int) $data['product_quantity'] * $product_unitprice; //discount will be added later
        // $orderProduct->order_id = $order->id;
        // $orderProduct->save();

        //outgoingStock, in place of orderProduct
        $outgoingStock = new OutgoingStock();
        $outgoingStock->product_id = $product_id;
        $outgoingStock->order_id = $order->id;
        $outgoingStock->quantity_removed = $data['product_quantity'];
        $outgoingStock->amount_realised = 0;
        $outgoingStock->reason_removed = 'as_order_firstphase'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
        $outgoingStock->quantity_returned = 0; //by default
        $outgoingStock->created_by = 1;
        $outgoingStock->status = 'true';
        $outgoingStock->save();
        $upsell = $outgoingStock;
        
        // return '123x';
        
        //update Order, for expected amt, can be handled in frontent later
        // $expected_sum = OrderProduct::where('order_id', $order->id)->sum('product_expected_amount');
        // if($orderbump_expected_sum !== ''){
        //     $expected_sum = $expected_sum + $orderbump_expected_sum;
        // }
        // if($upsell_expected_sum !== ''){
        //     $expected_sum = $expected_sum + $upsell_expected_sum;
        // }
        // $order->update(['amount_expected'=>$expected_sum]);

        return back()->with('success', 'Order Form Created Successfully');

    }

    public function editForm($unique_key)
    {
        $formLabel = OrderLabel::where('unique_key', $unique_key)->first();
        $order_id = $formLabel->order_id;

        $orderProducts = OrderProduct::where('order_id', $order_id)->get(['product_id']); //by column
        $orderBump_product = OrderBump::where('order_id', $order_id)->first();
        $upSell_product = OrderBump::where('order_id', $order_id)->first();

        return view('pages.forms.editForm', compact('formLabel', 'orderProducts', 'orderBump_product', 'upSell_product'));
    }

    public function editFormPost(Request $request, $unique_key)
    {
        $orderLabel = OrderLabel::where('unique_key', $unique_key)->first();
        $order_id = $orderLabel->order_id;

        $data = $request->all();
        
        //save OrderLabel
        $orderLabel->order_heading = $data['order_heading'];
        $orderLabel->order_subheading = $data['order_subheading'];
        $orderLabel->orderbump_heading = !empty($data['orderbump_heading']) ? $data['orderbump_heading'] : null;
        $orderLabel->orderbump_subheading = !empty($data['orderbump_subheading']) ?  $data['orderbump_subheading'] : null;
        $orderLabel->upsell_heading = !empty($data['upsell_heading']) ? $data['upsell_heading'] : null;
        $orderLabel->upsell_subheading = !empty($data['upsell_subheading']) ? $data['upsell_subheading'] : null;

        //customer label
        $orderLabel->customer_firstname_label = !empty($data['firstname']) ? $data['firstname'] : null;
        $orderLabel->customer_lastname_label = !empty($data['lastname']) ? $data['lastname'] : null;
        $orderLabel->customer_phone_label = !empty($data['phone']) ? $data['phone'] : null;
        $orderLabel->customer_email_label = !empty($data['email']) ? $data['email'] : null;
        $orderLabel->customer_state_label = !empty($data['state']) ? $data['state'] : null;
        $orderLabel->customer_address_label = !empty($data['address']) ? $data['address'] : null;
        $orderLabel->customer_country_label = !empty($data['country']) ? $data['country'] : null;

        //thank you
        $orderLabel->thankyou_heading = $data['thankyou_heading'];
        $orderLabel->thankyou_subheading = $data['thankyou_subheading'];

        $orderProducts = OrderProduct::where('order_id', $order_id)->get(['product_id']); //by column
        $orderBump_product = OrderBump::where('order_id', $order_id)->first();
        $upSell_product = OrderBump::where('order_id', $order_id)->first();

        return view('pages.forms.editForm', compact('formLabel', 'orderProducts', 'orderBump_product', 'upSell_product'));
    }

    //orderForm, not functioning yet
    public function singleOrder($unique_key)
    {
        $order = Order::where('unique_key', $unique_key);
        if(count($order->get()) !== 1) {
            abort(404);
        }

        $order = $order->first();
        $url = env('APP_URL').'/'.$order->url;
        return view('pages.orders.singleOrder', compact('url', 'order'));
    }

    //orderForm sent to customer
    public function customerOrderForm($unique_key)
    {
        $order = Order::where('unique_key', $unique_key);
        if(count($order->get()) !== 1) {
            abort(404);
        }
        // $url = url('/').'/'.$order->url; //form-link
        $countries = Country::all();

        $order = $order->first();
        $orderLabel = $order->orderLabel;
        $mainProducts_outgoingStocks = $order->outgoingStocks()->where('reason_removed', 'as_order_firstphase')->get();

        $orderId = ''; //used in thankYou section
        if ($order->id < 10){
            $orderId = '0000'.$order->id;
        }
        // <!-- > 10 < 100 -->
        if (($order->id > 10) && ($order->id < 100)) {
            $orderId = '000'.$order->id;
        }
        // <!-- > 100 < 1000 -->
        if (($order->id) > 100 && ($order->id < 100)) {
            $orderId = '00'.$order->id;
        }
        // <!-- > 1000 < 10000++ -->
        if (($order->id) > 100 && ($order->id < 100)) {
            $orderId = '0'.$order->id;
        }

        //orderbump
        $orderbump_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_orderbump')->first();
        
        //upsell
        $upsell_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_upsell')->first();
        
        //products + orderbump + upsell
        if ( isset($orderbump_outgoingStock) && isset($upsell_outgoingStock) ) {
            //orderbump features
            $features_orderbump = unserialize($orderbump_outgoingStock->product->features) == [null] ? '' :
            unserialize($orderbump_outgoingStock->product->features);

            //upsell features
            $features_upsell = unserialize($upsell_outgoingStock->product->features) == [null] ? '' :
            unserialize($upsell_outgoingStock->product->features);

            return view('pages.forms.customerOrderForm', compact('order', 'orderLabel', 'mainProducts_outgoingStocks',
            'orderbump_outgoingStock', 'features_orderbump', 'upsell_outgoingStock', 'features_upsell', 'countries', 'unique_key', 'orderId'));
        }

        //products + orderbump
        if ( isset($orderbump_outgoingStock) && !isset($upsell_outgoingStock) ) {
            //orderbump features
            $features_orderbump = unserialize($orderbump_outgoingStock->product->features) == [null] ? '' :
            unserialize($orderbump_outgoingStock->product->features);

            return view('pages.forms.customerProductOrderbumpForm', compact('order', 'orderLabel', 'mainProducts_outgoingStocks',
            'orderbump_outgoingStock', 'features_orderbump', 'countries', 'unique_key', 'orderId'));
        }

        //products + upsell
        if ( !isset($orderbump_outgoingStock) && isset($upsell_outgoingStock) ) {
            //upsell features
            $features_upsell = unserialize($upsell_outgoingStock->product->features) == [null] ? '' :
            unserialize($upsell_outgoingStock->product->features);

            return view('pages.forms.customerProductUpsellForm', compact('order', 'orderLabel', 'mainProducts_outgoingStocks',
            'upsell_outgoingStock', 'features_upsell', 'countries', 'unique_key', 'orderId'));
        }

        //products only
        if ( !isset($orderbump_outgoingStock) && !isset($upsell_outgoingStock) ) {
            
            return view('pages.forms.customerProductOnly', compact('order', 'orderLabel', 'mainProducts_outgoingStocks',
            'countries', 'unique_key', 'orderId'));
        }

        

        
    }

    //ajax call from customer, complete-customer-order
    public function completeCustomerOrder(Request $request)
    {
        $data = $request->all();
        $unique_key = $data['unique_key']; //order unique_key
        $grand_total = $data['grand_total'];
        $orderbump_revenue = $data['orderbump_revenue'];
        $upsell_revenue = $data['upsell_revenue'];

        $order = Order::where(['unique_key'=>$unique_key])->first();
        $orderbump_outgoing = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_orderbump'])->first();
        $upsell_outgoing = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_upsell'])->first();

        //orderbump not choosen
        $qty_orderbump = 0;
        if ($orderbump_revenue == "" || $orderbump_revenue == null) {
            $orderbump_outgoing->update(['customer_acceptance_status'=>'declined',
            'quantity_returned'=>$orderbump_outgoing->quantity_removed]);
        } else {
            $orderbump_outgoing->update(['customer_acceptance_status'=>'accepted']);
            $qty_orderbump = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_orderbump'])->sum('quantity_removed');
        }

        //upsell not choosen
        $qty_upsell = 0;
        if ($upsell_revenue == "" || $upsell_revenue == null) {
            $upsell_outgoing->update(['customer_acceptance_status'=>'declined',
            'quantity_returned'=>$upsell_outgoing->quantity_removed]);
        } else {
            $upsell_outgoing->update(['customer_acceptance_status'=>'accepted']);
            $qty_upsell = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_upsell'])->sum('quantity_removed');
        }

        //product_firstphase. incase its of array, use get
        $product_firstphase_outgoing = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_order_firstphase'])->get();
        foreach ($product_firstphase_outgoing as $key => $outgoing) {
            $outgoing->update(['customer_acceptance_status'=>'accepted']);
        }

        //contacts
        $firstname = $data['firstname']; 
        $lastname = $data['lastname'];
        $phone = $data['phone'];
        $email = $data['email'];
        $city = $data['city'];
        $state = $data['state'];
        $country = $data['country'];
        $address = $data['address'];

        $user = new User();
        $user->name = $firstname.' '.$lastname;
        $user->phone_1 = $phone;
        $user->phone_2 = $phone;
        $user->email = $email;
        $user->type = 'customer'; //customer, staff, agent, superadmin
        $user->password = Hash::make('password');
        $user->city = $city;
        $user->state = $state;
        $user->country_id = $country;
        $user->address = $address;
        $user->save();

        //update order status
        $order->update(['customer_id'=>$user->id, 'status'=>'pending']);

        //addtionals
        $data['selected_country'] = Country::where('id', $data['country'])->first()->name;
        $qty_main_product = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_order_firstphase'])->sum('quantity_removed');
        $data['qty_total'] = $qty_main_product + $qty_orderbump + $qty_upsell;
        $data['order_updated_date'] = $order->updated_at->format('j F Y'); //7 November 2022
        
        return response()->json([
            // 'unique_key'=>$unique_key,
            // 'grand_total'=>$grand_total,
            'data'=>$data,
        ]);
        
    }

    //ajax call, productOrderbumpCustomerOrder
    public function productOrderbumpCustomerOrder(Request $request)
    {
        $data = $request->all();
        $unique_key = $data['unique_key']; //order unique_key
        $grand_total = $data['grand_total'];
        $orderbump_revenue = $data['orderbump_revenue'];
        // $upsell_revenue = $data['upsell_revenue'];

        $order = Order::where(['unique_key'=>$unique_key])->first();
        $orderbump_outgoing = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_orderbump'])->first();
        // $upsell_outgoing = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_upsell'])->first();

        //orderbump not choosen
        $qty_orderbump = 0;
        if ($orderbump_revenue == "" || $orderbump_revenue == null) {
            $orderbump_outgoing->update(['customer_acceptance_status'=>'declined',
            'quantity_returned'=>$orderbump_outgoing->quantity_removed]);
        } else {
            $orderbump_outgoing->update(['customer_acceptance_status'=>'accepted']);
            $qty_orderbump = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_orderbump'])->sum('quantity_removed');
        }

        //product_firstphase. incase its of array
        $product_firstphase_outgoing = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_order_firstphase'])->get();
        foreach ($product_firstphase_outgoing as $key => $outgoing) {
            $outgoing->update(['customer_acceptance_status'=>'accepted']);
        }

        //contacts
        $firstname = $data['firstname']; 
        $lastname = $data['lastname'];
        $phone = $data['phone'];
        $email = $data['email'];
        $city = $data['city'];
        $state = $data['state'];
        $country = $data['country'];
        $address = $data['address'];

        //store new customer
        $user = new User();
        $user->name = $firstname.' '.$lastname;
        $user->phone_1 = $phone;
        $user->phone_2 = $phone;
        $user->email = $email;
        $user->type = 'customer'; //customer, staff, agent, superadmin
        $user->password = Hash::make('password');
        $user->city = $city;
        $user->state = $state;
        $user->country_id = $country;
        $user->address = $address;
        $user->save();

        //update order status
        $order->update(['customer_id'=>$user->id, 'status'=>'pending']);

        $data['selected_country'] = Country::where('id', $data['country'])->first()->name;
        $qty_main_product = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_order_firstphase'])->sum('quantity_removed');
        $data['qty_total'] = $qty_main_product + $qty_orderbump;
        $data['order_updated_date'] = $order->updated_at->format('j F Y'); //7 November 2022
        
        return response()->json([
            // 'unique_key'=>$unique_key,
            // 'grand_total'=>$grand_total,
            'data'=>$data,
        ]);
        
    }

    //ajax call, productUpsellCustomerOrder
    public function productUpsellCustomerOrder(Request $request)
    {
        $data = $request->all();
        $unique_key = $data['unique_key']; //order unique_key
        $grand_total = $data['grand_total'];
        $upsell_revenue = $data['upsell_revenue'];
        // $upsell_revenue = $data['upsell_revenue'];

        $order = Order::where(['unique_key'=>$unique_key])->first();
        $upsell_outgoing = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_upsell'])->first();
        // $upsell_outgoing = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_upsell'])->first();

        //upsell not choosen
        $qty_upsell = 0;
        if ($upsell_revenue == "" || $upsell_revenue == null) {
            $upsell_outgoing->update(['customer_acceptance_status'=>'declined',
            'quantity_returned'=>$upsell_outgoing->quantity_removed]);
        } else {
            $upsell_outgoing->update(['customer_acceptance_status'=>'accepted']);
            $qty_upsell = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_upsell'])->sum('quantity_removed');
        }

        //product_firstphase. incase its of array, use get
        $product_firstphase_outgoing = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_order_firstphase'])->get();
        foreach ($product_firstphase_outgoing as $key => $outgoing) {
            $outgoing->update(['customer_acceptance_status'=>'accepted']);
        }

        //contacts
        $firstname = $data['firstname']; 
        $lastname = $data['lastname'];
        $phone = $data['phone'];
        $email = $data['email'];
        $city = $data['city'];
        $state = $data['state'];
        $country = $data['country'];
        $address = $data['address'];

        //store new customer
        $user = new User();
        $user->name = $firstname.' '.$lastname;
        $user->phone_1 = $phone;
        $user->phone_2 = $phone;
        $user->email = $email;
        $user->type = 'customer'; //customer, staff, agent, superadmin
        $user->password = Hash::make('password');
        $user->city = $city;
        $user->state = $state;
        $user->country_id = $country;
        $user->address = $address;
        $user->save();

        //update order status
        $order->update(['customer_id'=>$user->id, 'status'=>'pending']);

        //additionals
        $data['selected_country'] = Country::where('id', $data['country'])->first()->name;
        $qty_main_product = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_order_firstphase'])->sum('quantity_removed');
        $data['qty_total'] = $qty_main_product + $qty_upsell;
        $data['order_updated_date'] = $order->updated_at->format('j F Y'); //7 November 2022
        
        return response()->json([
            // 'unique_key'=>$unique_key,
            // 'grand_total'=>$grand_total,
            'data'=>$data,
        ]);
        
    }

    //ajax call, productOnlyCustomerOrder
    public function productOnlyCustomerOrder(Request $request)
    {
        $data = $request->all();
        $unique_key = $data['unique_key']; //order unique_key
        $grand_total = $data['grand_total'];
        // $upsell_revenue = $data['upsell_revenue'];
        // $upsell_revenue = $data['upsell_revenue'];

        $order = Order::where(['unique_key'=>$unique_key])->first();
        
        
        //product_firstphase. incase its of array, use get
        $product_firstphase_outgoing = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_order_firstphase'])->get();
        foreach ($product_firstphase_outgoing as $key => $outgoing) {
            $outgoing->update(['customer_acceptance_status'=>'accepted']);
        }
        
        //contacts
        $firstname = $data['firstname']; 
        $lastname = $data['lastname'];
        $phone = $data['phone'];
        $email = $data['email'];
        $city = $data['city'];
        $state = $data['state'];
        $country = $data['country'];
        $address = $data['address'];

        //store new customer
        $user = new User();
        $user->name = $firstname.' '.$lastname;
        $user->phone_1 = $phone;
        $user->phone_2 = $phone;
        $user->email = $email;
        $user->type = 'customer'; //customer, staff, agent, superadmin
        $user->password = Hash::make('password');
        $user->city = $city;
        $user->state = $state;
        $user->country_id = $country;
        $user->address = $address;
        $user->save();

        //update order status
        $order->update(['customer_id'=>$user->id, 'status'=>'pending']);

        //additionals
        $data['selected_country'] = Country::where('id', $data['country'])->first()->name;
        $qty_main_product = OutgoingStock::where(['order_id'=>$order->id, 'reason_removed'=>'as_order_firstphase'])->sum('quantity_removed');
        $data['qty_total'] = $qty_main_product;
        $data['order_updated_date'] = $order->updated_at->format('j F Y'); //7 November 2022

        return response()->json([
            // 'unique_key'=>$unique_key,
            // 'grand_total'=>$grand_total,
            'data'=>$data,
        ]);
        
    }

}
