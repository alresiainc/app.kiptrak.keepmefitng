<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\OutgoingStock;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = []; 
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // $model->unique_key = $model->createUniqueKey(Str::random(30));
            // $model->url = 'order-form/'.$model->unique_key;
            // $model->save();

            $string = Str::random(30);
            $randomStrings = static::where('unique_key', 'like', $string.'%')->pluck('unique_key');

            do {
                $randomString = $string.rand(100000, 999999);
            } while ($randomStrings->contains($randomString));
    
            $model->unique_key = $randomString;
            $model->url = 'order-form/'.$model->unique_key;

        });
    }


    //check if unique_key exists
    // private function createUniqueKey($string){
    //     if (static::whereUniqueKey($unique_key = $string)->exists()) {
    //         $random = rand(1000, 9000);
    //         $unique_key = $string.''.$random;
    //         return $unique_key;
    //     }

    //     return $string;
    // }

    //not used, but alternative to creating unique codes
    public function createOrderCode(Order $order)
    {
        $today = date('Ymd');
        $orderNumbers = Order::where('order_number', 'like', $today.'%')->pluck('order_number');
        do {
            $orderNumber = $today.rand(100000, 999999);
        } while ($orderNumbers->contains($orderNumber));

        $order->order_number = $orderNumber;
    }

    public function orderCode($orderId){
        if ($orderId < 10){
            return $orderCode = 'kp-0000'.$orderId;
        }
        // <!-- > 10 < 100 -->
        if (($orderId > 10) && ($orderId < 100)) {
            return $orderCode = 'kp-000'.$orderId;
        }
        // <!-- > 100 < 1000 -->
        if (($orderId) > 100 && ($orderId < 1000)) {
            return $orderCode = 'kp-00'.$order->id;
        }
        // <!-- > 1000 < 10000++ -->
        if (($orderId) > 1000 && ($orderId < 10000)) {
            return $orderCode = 'kp-0'.$orderId;
        }

    }

    // public function orderDate(){
    //     $time = strtotime($this->created_at);
    //     $newformat = date('D, jS M Y',$time);
    //     return $newformat;
    // }

    public function hasOrderbump() {
        return (bool) OutgoingStock::where(['order_id'=>$this->id, 'reason_removed'=>'as_orderbump'])->count();
    }

    public function hasUpsell() {
        return (bool) OutgoingStock::where(['order_id'=>$this->id, 'reason_removed'=>'as_upsell'])->count();
    }

    public function outgoingStocks()
    {
        return $this->hasMany(OutgoingStock::class, 'order_id');  
    }

    public function orderLabel() {
        return $this->belongsTo(OrderLabel::class, 'order_label_id');  
    }

    public function formHolder() {
        return $this->belongsTo(FormHolder::class, 'form_holder_id');  
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');  
    }

    public function agent() {
        return $this->belongsTo(User::class, 'agent_assigned_id');  
    }

    public function staff() {
        return $this->belongsTo(User::class, 'staff_assigned_id');  
    }

    //used in allOrders
    public function whatsappMessages() {
        $messages = '';
        if (isset($this->whatsapp_message_ids)) {
            $message_ids = unserialize($this->whatsapp_message_ids);
            $messages = Message::whereIn('id', $message_ids)->get();
        }
        
        return $messages;
    }

    public function emailMessages() {
        $messages = '';
        if (isset($this->email_message_ids)) {
            $message_ids = unserialize($this->email_message_ids);
            $messages = Message::whereIn('id', $message_ids)->get();
        }
        
        return $messages;
    }

    public function orderId($order) {
        $orderId = ''; //used in thankYou section
        if ($order->id < 10){
            $orderId = '0000'.$order->id;
        }
        // <!-- > 10 < 100 -->
        if (($order->id > 10) && ($order->id < 100)) {
            $orderId = '000'.$order->id;
        }
        // <!-- > 100 < 1000 -->
        if (($order->id) > 100 && ($order->id < 1000)) {
            $orderId = '00'.$order->id;
        }
        // <!-- > 1000 < 10000++ -->
        if (($order->id) > 1000 && ($order->id < 1000)) {
            $orderId = '0'.$order->id;
        }

        return $orderId;
    }

    public function whatsappNewOrderMessage($order) {

        $authUser = auth()->user();
        $customer = $order->customer;

        //mainProduct_revenue
        $mainProduct_revenue = 0;  //price * qty
        $mainProducts_outgoingStocks = $order->outgoingStocks()->where(['reason_removed'=>'as_order_firstphase', 'customer_acceptance_status'=>'accepted'])->get();

        if ( count($mainProducts_outgoingStocks) > 0 ) {
            foreach ($mainProducts_outgoingStocks as $key => $main_outgoingStock) {
                $mainProduct_revenue = $mainProduct_revenue + ($main_outgoingStock->product->sale_price * $main_outgoingStock->quantity_removed);
            }
        }

        //orderbump
        $orderbumpProduct_revenue = 0; //price * qty
        $orderbump_outgoingStock = '';
        if (isset($formHolder->orderbump_id)) {
            $orderbump_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_orderbump')->first();
            if ($orderbump_outgoingStock->customer_acceptance_status == 'accepted') {
                $orderbumpProduct_revenue = $orderbumpProduct_revenue + ($orderbump_outgoingStock->product->sale_price * $orderbump_outgoingStock->quantity_removed);
            }
        }
        
        //upsell
        $upsellProduct_revenue = 0; //price * qty
        $upsell_outgoingStock = '';
        if (isset($formHolder->upsell_id)) {
            $upsell_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_upsell')->first();
            if ($upsell_outgoingStock->customer_acceptance_status == 'accepted') {
                $upsellProduct_revenue += $upsellProduct_revenue + ($upsell_outgoingStock->product->sale_price * $upsell_outgoingStock->quantity_removed);
            }
        }

        if (isset($order->customer_id)) {

            $whatsapp_msg = "Hello ".$customer->firstname." ".$customer->lastname.". My name is ".$authUser->name.", I am contacting you from KeepMeFit and I am the Customer Service Representative incharge of the order you placed for ";
            $whatsapp_msg .= "";
            foreach($mainProducts_outgoingStocks as $main_outgoingStock):
                $whatsapp_msg .= " [Product: ".$main_outgoingStock->product->name.". Price: ".$mainProduct_revenue.". Qty: ".$main_outgoingStock->quantity_removed."], ";
            endforeach;
    
            if($orderbump_outgoingStock != ''):
                $whatsapp_msg .= "[Product: ".$orderbump_outgoingStock->product->name.". Price: ".$orderbump_outgoingStock->product->sale_price * $orderbump_outgoingStock->quantity_removed.". Qty: ".$orderbump_outgoingStock->quantity_removed."], ";
            endif;
    
            if($upsell_outgoingStock != ''):
                $whatsapp_msg .= "[Product: ".$upsell_outgoingStock->product->name.". Price: ".$upsell_outgoingStock->product->sale_price * $upsell_outgoingStock->quantity_removed.". Qty: ".$upsell_outgoingStock->quantity_removed."]. ";
            endif;
    
            $whatsapp_msg .= "I am reaching out to you to confirm your order and to let you know the delivery person will call you to deliver your order. Kindly confirm if the details you sent are correct ";
    
            $whatsapp_msg .= "[Phone Number: ".$customer->phone_number.". Whatsapp Phone Number: ".$customer->whatsapp_phone_number.". Delivery Address: ".$customer->delivery_address."]. ";
    
            $whatsapp_msg .= "Please kindly let me know when we can deliver your order. Thank you!";
    
            return $whatsapp_msg;
        } else {
            return $whatsapp_msg = "";
        }
        
    }
    

}
