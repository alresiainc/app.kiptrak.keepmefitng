<?php

namespace Database\Seeders;

use App\Models\MessageTemplate;
use Illuminate\Database\Seeder;

class MessageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders_statuses = config('site.order_statuses');
        $channels = config('site.notification_channels');

        // Loop through each channel and status to create the templates for order status changes
        foreach ($channels as $channel) {
            foreach ($orders_statuses as $key => $status) {
                $messageTemplate = new MessageTemplate();
                $messageTemplate->name = "Order Changed to {$status}";
                $messageTemplate->channel = $channel;
                $messageTemplate->type = "{$channel}_order_status_changed_to_{$key}";
                $messageTemplate->subject = "Your Order Status Has Been Changed to {$status}";
                $messageTemplate->message = "Hello [customer_first_name], your order has been updated to status: {$status}.";
                $messageTemplate->save();
            }
        }

        // Create the "new order message" template for each channel
        foreach ($channels as $channel) {
            $messageTemplate = new MessageTemplate();
            $messageTemplate->name = 'New Order placed with staff assigned';
            $messageTemplate->channel = $channel;
            $messageTemplate->type = "{$channel}_new_order_message_with_staff";
            $messageTemplate->subject = 'New Order has been placed';
            $messageTemplate->message = "Hello [customer_first_name] [customer_last_name]. My name is [staff_name], I am contacting you from KeepMeFit, and I am the Customer Service Representative in charge of the order you placed for [product_list]. \n I am reaching out to confirm your order and let you know the delivery person will call you. Kindly confirm if the details you sent are correct: [Phone Number: [customer_phone_number], WhatsApp Phone Number: [customer_whatsapp_phone_number], Delivery Address: [customer_delivery_address]]. \n Please let me know when we can deliver your order. Thank you!";
            $messageTemplate->save();

            $messageTemplate = new MessageTemplate();
            $messageTemplate->name = 'New Order placed with no staff assigned';
            $messageTemplate->channel = $channel;
            $messageTemplate->type = "{$channel}_new_order_message_with_no_staff";
            $messageTemplate->subject = 'New Order has been placed';
            $messageTemplate->message = "Hello [customer_first_name] [customer_last_name]. I am contacting you from KeepMeFit, concerning of the order you placed for [product_list]. \n I am reaching out to confirm your order and let you know the delivery person will call you. Kindly confirm if the details you sent are correct: [Phone Number: [customer_phone_number], WhatsApp Phone Number: [customer_whatsapp_phone_number], Delivery Address: [customer_delivery_address]]. \n Please let me know when we can deliver your order. Thank you!";
            $messageTemplate->save();
        }

        // Create the "assigned order" message template for each channel
        foreach ($channels as $channel) {
            $messageTemplate = new MessageTemplate();
            $messageTemplate->name = 'An Order was assigned to Staff';
            $messageTemplate->type = "{$channel}_new_order_assigned";
            $messageTemplate->channel = $channel;
            $messageTemplate->subject = 'An Order was assigned to You!';
            $messageTemplate->message = "Hello [staff_name], you have been assigned a new order with ID [order_id]. Please review and take action.";
            $messageTemplate->save();
        }
    }
}
