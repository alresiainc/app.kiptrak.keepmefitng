<?php

namespace App\Notifications;

use App\Models\Message;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderWhatsappNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @param $order
     * @param string $message
     */
    public function __construct($order, $message = '')
    {
        $this->order = $order;
        $this->message = $message;
    }

    /**
     * Determine which channels the notification should be sent on.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [\App\Channels\WhatsAppChannel::class];
    }

    /**
     * Send a WhatsApp message.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toWhatsapp($notifiable)
    {

        $customer = $this->order->customer;
        $staff = $this->order->staff;

        $mainProducts = $this->getMainProducts();
        $orderbump = $this->getOrderbump();
        $upsell = $this->getUpsell();
        $productDetails = $this->generateProductDetails($mainProducts, $orderbump, $upsell);

        $data = [
            'customer_first_name' => $customer->firstname ?? '',
            'customer_last_name' => $customer->lastname ?? '',
            'customer_phone_number' => $customer->phone_number ?? '',
            'customer_whatsapp_phone_number' => $customer->whatsapp_phone_number ?? '',
            'customer_delivery_address' => $customer->delivery_address ?? '',
            'customer_city' => $customer->city ?? '',
            'customer_state' => $customer->state ?? '',
            'customer_delivery_duration' => $customer->delivery_duration ?? '',
            'customer_email' => $customer->email ?? '',
            'staff_name' => $staff->name ?? '',
            'staff_first_name' => $staff->firstname ?? '',
            'staff_last_name' => $staff->lastname ?? '',
            'staff_phone_number' => $staff->phone_1 ?? $staff->phone_2 ?? '',
            'staff_address' => $staff->address ?? '',
            'staff_city' => $staff->city ?? '',
            'staff_state' => $staff->state ?? '',
            'product_list' => $productDetails,
            'order_status' => $this->order->status,
            'order_id' => $this->order->id,
            "order_delivery_address" => $this->order->delivery_address,
            "order_extra_cost_amount" => $this->order->extra_cost_amount,
            "order_extra_cost_reason" => $this->order->extra_cost_reason,
            "order_order_note" => $this->order->order_note,
            "order_expected_delivery_date" => $this->order->expected_delivery_date,
            "order_actual_delivery_date" => $this->order->actual_delivery_date,
            "order_url" => $this->order->url,
            "order_discount" => $this->order->discount,
            "order_amount_expected" => $this->order->amount_expected,
            "order_amount_realised" => $this->order->amount_realised,
            "order_delivery_duration" => $this->order->delivery_duration,
            "order_delivery_going_time" => $this->order->delivery_going_time,
            "order_delivery_meet_time" => $this->order->delivery_meet_time,
            "order_delivery_returning_time" => $this->order->delivery_returning_time,
            "order_delivery_going_distance" => $this->order->delivery_going_distance,
            "order_delivery_returning_distance" => $this->order->delivery_returning_distance,
            "order_delivery_going_cost" => $this->order->delivery_going_cost,
            "order_delivery_returning_cost" => $this->order->delivery_returning_cost
        ];

        // $message = new Message();
        // $message->type = 'whatsapp';
        // $message->topic = "Order Message";
        // $message->recipients = serialize([]);
        // $message->message = $data['message'];
        // $message->message_status = 'sent';
        // $message->to = 'customers';
        // $message->created_by = $authUser->id;
        // $message->status = 'true';
        // $message->save();

        $resolvedMessage = $this->resolveTemplate($this->message, $data);

        return [
            'to' => $notifiable->routeNotificationForWhatsapp(),
            'message' => $resolvedMessage,
            'session_name' => $staff?->adkombo_whatsapp_session_name ?? '3560919_Test WhatsApp Device',
        ];
    }

    /**
     * Generate product details string.
     */
    protected function generateProductDetails($mainProducts, $orderbump, $upsell)
    {
        $details = "";

        foreach ($mainProducts as $mainProduct) {
            $details .= "[Product: {$mainProduct->product->name}. Price: " . ($mainProduct->product->sale_price * $mainProduct->quantity_removed) .
                ". Qty: {$mainProduct->quantity_removed}], ";
        }

        if ($orderbump) {
            $details .= "[Product: {$orderbump->product->name}. Price: " . ($orderbump->product->sale_price * $orderbump->quantity_removed) .
                ". Qty: {$orderbump->quantity_removed}], ";
        }

        if ($upsell) {
            $details .= "[Product: {$upsell->product->name}. Price: " . ($upsell->product->sale_price * $upsell->quantity_removed) .
                ". Qty: {$upsell->quantity_removed}].";
        }

        return trim($details);
    }

    /**
     * Retrieve and process main products for the order.
     *
     * @return array
     */
    protected function getMainProducts()
    {
        $mainProductRevenue = 0;
        $mainProducts = [];

        foreach ($this->order->outgoingStock->package_bundle as $mainStock) {
            if ($mainStock['reason_removed'] === 'as_order_firstphase' && $mainStock['customer_acceptance_status'] === 'accepted') {
                $product = Product::find($mainStock['product_id']);
                if ($product) {
                    $mainStock['product'] = $product;
                    $mainProductRevenue += $product->sale_price * $mainStock['quantity_removed'];
                    $mainProducts[] = (object) $mainStock;
                }
            }
        }

        return $mainProducts;
    }

    /**
     * Retrieve and process order bump details for the order.
     *
     * @return object|null
     */
    protected function getOrderbump()
    {
        $orderbumpRevenue = 0;
        $orderbumpStock = null;

        if (isset($this->order->orderbump_id)) {
            foreach ($this->order->outgoingStock->package_bundle as $bumpStock) {
                if ($bumpStock['reason_removed'] === 'as_orderbump' && $bumpStock['customer_acceptance_status'] === 'accepted') {
                    $product = Product::find($bumpStock['product_id']);
                    if ($product) {
                        $bumpStock['product'] = $product;
                        $orderbumpRevenue += $product->sale_price * $bumpStock['quantity_removed'];
                        $orderbumpStock = (object) $bumpStock;
                    }
                }
            }
        }

        return $orderbumpStock;
    }

    /**
     * Retrieve and process upsell details for the order.
     *
     * @return object|null
     */
    protected function getUpsell()
    {
        $upsellRevenue = 0;
        $upsellStock = null;

        if (isset($this->order->upsell_id)) {
            foreach ($this->order->outgoingStock->package_bundle as $upsellStock) {
                if ($upsellStock['reason_removed'] === 'as_upsell' && $upsellStock['customer_acceptance_status'] === 'accepted') {
                    $product = Product::find($upsellStock['product_id']);
                    if ($product) {
                        $upsellStock['product'] = $product;
                        $upsellRevenue += $product->sale_price * $upsellStock['quantity_removed'];
                        $upsellStock = (object) $upsellStock;
                    }
                }
            }
        }

        return $upsellStock;
    }

    protected function resolveTemplate($template, $data)
    {
        return preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($data) {
            $placeholder = $matches[1];
            return $data[$placeholder] ?? $matches[0];
        }, $template);
    }
}
