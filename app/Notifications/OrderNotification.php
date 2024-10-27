<?php

namespace App\Notifications;

use App\Models\Message;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class OrderNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $messages;

    /**
     * Create a new notification instance.
     *
     * @param $order
     * @param string|array $message
     */
    public function __construct($order, $message = '')
    {

        $updatedOrder = Order::where('id', $order->id)->first();
        $this->order = $updatedOrder;
        $this->messages = $this->processMessages($message);
    }

    /**
     * Process the messages to standardize their format.
     *
     * @param string|array $messages
     * @return array
     */
    protected function processMessages($messages)
    {
        // If the message is a string, use it for all channels
        if (is_string($messages)) {
            return [
                'database' => ['message' => $messages, 'title' => ''],
                'sms' => ['message' => $messages, 'title' => ''],
                'whatsapp' => ['message' => $messages, 'title' => ''],
                'email' => ['message' => $messages, 'title' => ''],
            ];
        }

        // If the message is an array, format accordingly
        if (is_array($messages)) {
            $formattedMessages = [];
            foreach ($messages as $channel => $message) {
                if (is_array($message)) {
                    // If it's a sub-array, extract title and message
                    $formattedMessages[$channel] = [
                        'title' => $message['title'] ?? '',
                        'message' => $message['message'] ?? '',
                    ];
                } else {
                    // Assign the message directly
                    $formattedMessages[$channel] = [
                        'title' => '',
                        'message' => $message,
                    ];
                }
            }
            return $formattedMessages;
        }

        return []; // Fallback if messages format is invalid
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            \App\Channels\EmailChannel::class,
            \App\Channels\WhatsAppChannel::class,
            \App\Channels\SmsChannel::class,
            // \App\Channels\DatabaseChannel::class,
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toEmail($notifiable)
    {

        // Only send if an email message is set
        if (!isset($this->messages['email'])) {
            return []; // Return an empty array
        }
        $title = $this->messages['email']['title'] ?? 'Order Notification';
        $message = $this->messages['email']['message'] ?? '';
        $resolvedMessage = $this->resolveMessageTemplate($message);



        return Message::create([
            'topic' => $title,
            'message' => $resolvedMessage,
            'type' => 'email',
            'recipients' => \serialize([$notifiable->routeNotificationForEmail()]),
            'message_status' => 'pending',
            'created_by' => $this?->order->staff_assigned_id,
        ]);
    }

    /**
     * Send a WhatsApp message.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toWhatsApp($notifiable)
    {
        if (!isset($this->messages['whatsapp'])) {
            return []; // Return an empty array
        }

        $message = $this->messages['whatsapp']['message'] ?? '';
        $title = $this->messages['whatsapp']['title'] ?? 'Order Notification';
        $resolvedMessage = $this->resolveMessageTemplate($message);

        return Message::create([
            'topic' => $title,
            'message' => $resolvedMessage,
            'type' => 'whatsapp',
            'recipients' => \serialize([$notifiable->routeNotificationForWhatsapp()]),
            'message_status' => 'pending',
            'created_by' => $this?->order->staff_assigned_id,
        ]);
    }

    /**
     * Send an SMS message.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toSms($notifiable)
    {

        if (!isset($this->messages['sms'])) {
            return []; // Return an empty array
        }
        $title = $this->messages['sms']['title'] ?? '';
        $message = $this->messages['sms']['message'] ?? 'Order Notification';
        $resolvedMessage = $this->resolveMessageTemplate($message);

        return Message::create([
            'topic' => $title,
            'message' => $resolvedMessage,
            'type' => 'sms',
            'recipients' => \serialize([$notifiable->routeNotificationForSMS()]),
            'message_status' => 'pending',
            'created_by' => $this?->order->staff_assigned_id,
        ]);
    }

    /**
     * Send a Database message.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        if (!isset($this->messages['database'])) {
            return []; // Return an empty array
        }

        $message = $this->messages['database']['message'] ?? '';
        return [
            'message' => $message,
        ];
    }
    /**
     * Generate product details string.
     */
    protected function generateProductDetails($products)
    {


        $mainProducts = $products['mainProducts'] ?? [];
        $orderbump = $products['orderbump'] ?? [];
        $upsell = $products['upsell'] ?? [];
        $downsell = $products['downsell'] ?? [];

        $details = "";

        foreach ($mainProducts as $mainProduct) {
            $details .= "\n\n{$mainProduct->product->name}";
            $details .= "\nPrice: " . ($mainProduct->product->sale_price * $mainProduct->quantity_removed);
            $details .= "\nQty: {$mainProduct->quantity_removed}";
        }

        if ($orderbump) {
            $details .= "\n\n{$orderbump->product->name}";
            $details .= "\nPrice: " . ($orderbump->product->sale_price * $orderbump->quantity_removed);
            $details .= "\nQty: {$orderbump->quantity_removed}";
        }

        if ($upsell) {
            $details .= "\n\n{$upsell->product->name}";
            $details .= "\nPrice: " . ($upsell->product->sale_price * $upsell->quantity_removed);
            $details .= "\nQty: {$upsell->quantity_removed}";
        }

        if ($downsell) {
            $details .= "\n\n{$downsell->product->name}";
            $details .= "\nPrice: " . ($downsell->product->sale_price * $downsell->quantity_removed);
            $details .= "\nQty: {$downsell->quantity_removed}";
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

        // dd($this->order->outgoingStock->package_bundle);
        if (isset($this->order->upsell_id)) {
            foreach ($this->order->outgoingStock->package_bundle as $item) {
                if ($item['reason_removed'] === 'as_upsell' && $item['customer_acceptance_status'] === 'accepted') {


                    $product = Product::find($item['product_id']);

                    if ($product) {
                        $item['product'] = $product;
                        $upsellRevenue += $product->sale_price * $item['quantity_removed'];
                        $upsellStock = (object) $item;
                    }
                }
            }
        }


        return $upsellStock;
    }
    /**
     * Retrieve and process upsell details for the order.
     *
     * @return object|null
     */
    protected function getDownsell()
    {
        $downsellRevenue = 0;
        $downsellStock = null;

        if (isset($this->order->downsell_id)) {
            foreach ($this->order->outgoingStock->package_bundle as $item) {
                if ($item['reason_removed'] === 'as_downsell' && $item['customer_acceptance_status'] === 'accepted') {
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $item['product'] = $product;
                        $downsellRevenue += $product->sale_price * $item['quantity_removed'];
                        $downsellStock = (object) $item;
                    }
                }
            }
        }

        return $downsellStock;
    }

    protected function resolveMessageTemplate($template)
    {

        $customer = $this->order->customer;
        $staff = $this->order->staff;

        $mainProducts = $this->getMainProducts();
        $orderbump = $this->getOrderbump();
        $upsell = $this->getUpsell();
        $downsell = $this->getDownsell();
        $productDetails = $this->generateProductDetails([
            "mainProducts" => $mainProducts,
            "orderbump" => $orderbump,
            "upsell" => $upsell,
            "downsell" => $downsell,
        ]);

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

        return preg_replace_callback('/\[(.*?)\]/', function ($matches) use ($data) {
            $placeholder = $matches[1];
            return $data[$placeholder] ?? $matches[0];
        }, $template);
    }
}
