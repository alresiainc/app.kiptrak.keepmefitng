<?php

namespace App\Traits;

use App\Models\Product;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

trait NotifiesOrderViaWhatsapp
{
    /**
     * Send a WhatsApp message to the assigned staff and the customer.
     */
    public function sendWhatsappMessage($phones, $message = '')
    {
        $phones = is_array($phones) ? $phones : [$phones];
        $notificationService = new NotificationService();
        $messages = []; // Initialize an array to hold all messages
        $customer = $this->customer;
        $staff = $this->staff;
        Log::alert("staff_assigned_id:" . $this->staff_assigned_id);
        Log::alert("staff:" . $this->staff);

        $mainProducts = $this->getMainProducts();
        $orderbump = $this->getOrderbump();
        $upsell = $this->getUpsell();

        $productDetails = $this->generateProductDetails($mainProducts, $orderbump, $upsell);


        $data = [
            'customer_first_name' => isset($customer) ? $customer->firstname : '',
            'customer_last_name' => isset($customer) ? $customer->lastname : '',
            'customer_phone_number' => isset($customer) ? $customer->phone_number : '',
            'customer_whatsapp_phone_number' => isset($customer) ? $customer->whatsapp_phone_number : '',
            'customer_delivery_address' => isset($customer) ? $customer->delivery_address : '',
            'customer_city' => isset($customer) ? $customer->city : '',
            'customer_state' => isset($customer) ? $customer->state : '',
            'customer_delivery_duration' => isset($customer) ? $customer->delivery_duration : '',
            'customer_email' => isset($customer) ? $customer->email : '',

            'staff_name' => isset($staff) ? $staff->name : '',
            'staff_first_name' => isset($staff) ? $staff->firstname : '',
            'staff_last_name' => isset($staff) ? $staff->lastname : '',
            'staff_phone_number' => isset($staff) ? $staff->phone_1 ?? $staff->phone_2 : '',
            'staff_address' => isset($staff) ? $staff->address : '',
            'staff_city' => isset($staff) ? $staff->city : '',
            'staff_state' => isset($staff) ? $staff->state : '',

            'product_list' => $productDetails,

            'order_status' => $this->status,
            'order_id' => $this->id,
            "order_delivery_address" => $this->delivery_address,
            "order_extra_cost_amount" => $this->extra_cost_amount,
            "order_extra_cost_reason" => $this->extra_cost_reason,
            "order_order_note" => $this->order_note,
            "order_expected_delivery_date" => $this->expected_delivery_date,
            "order_actual_delivery_date" => $this->actual_delivery_date,
            "order_url" => $this->url,
            "order_discount" => $this->discount,
            "order_amount_expected" => $this->amount_expected,
            "order_amount_realised" => $this->amount_realised,
            "order_delivery_duration" => $this->delivery_duration,
            "order_delivery_going_time" => $this->delivery_going_time,
            "order_delivery_meet_time" => $this->delivery_meet_time,
            "order_delivery_returning_time" => $this->delivery_returning_time,
            "order_delivery_going_distance" => $this->delivery_going_distance,
            "order_delivery_returning_distance" => $this->delivery_returning_distance,
            "order_delivery_going_cost" => $this->delivery_going_cost,
            "order_delivery_returning_cost" => $this->delivery_returning_cost
        ];

        $resolvedMessage = $this->resolveTemplate($message, $data);

        foreach ($phones as $key => $phone) {
            $messageData = [
                'number' => $phone,
                'message' => $resolvedMessage,
                'session_name' => '3560919_Test WhatsApp Device', // Optional: use a session name if needed
                // Optional: 'schedule_at' => '2024-07-10 15:30:00',
                // Optional: 'media' => 'image',
                // Optional: 'url' => 'https://some-site-example.jpg'
            ];
            if ($staff?->adkombo_whatsapp_session_name) {
                $messageData['session_name'] = $staff?->adkombo_whatsapp_session_name;
            }

            $messages[] = $messageData;
        }

        Log::alert($messages);



        // Send the combined messages if there are any
        if (!empty($messages)) {
            $response = $notificationService->sendWhatsAppMessage($messages);

            // Log or handle the response if necessary
            // if (!$response['success']) {
            //     Log::error("Error sending WhatsApp messages: " . $response['message']);
            // }
        }
    }

    /**
     * Generate the WhatsApp message content for the staff.
     */
    protected function generateStaffWhatsappMessage()
    {
        return "Hello {$this->staffAssigned->name}, you have been assigned a new order with ID {$this->id}. Please review and take action.";
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

        foreach ($this->outgoingStock->package_bundle as $mainStock) {
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

        if (isset($this->orderbump_id)) {
            foreach ($this->outgoingStock->package_bundle as $bumpStock) {
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

        if (isset($this->upsell_id)) {
            foreach ($this->outgoingStock->package_bundle as $upsellStock) {
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

    /**
     * Generate product details string from products and other order details.
     *
     * @param array $mainProducts
     * @param object|null $orderbump
     * @param object|null $upsell
     * @return string
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
    // Resolve placeholders in a template with the given data
    public function resolveTemplate($template, $data)
    {
        return preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($data) {
            $placeholder = $matches[1];
            return $data[$placeholder] ?? $matches[0]; // Replace if key exists, otherwise keep placeholder
        }, $template);
    }
}
