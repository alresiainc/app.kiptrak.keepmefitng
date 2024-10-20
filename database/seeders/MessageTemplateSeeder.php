<?php

namespace Database\Seeders;

use App\Models\MessageTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $message = new MessageTemplate();
        $message->type = 'whatsapp_new_order_message';
        $message->subject = 'New Order has been placed';
        $message->message = "
            Hello {{customer_first_name}} {{customer_last_name}}. My name is {{staff_name}}, I am contacting you from KeepMeFit, and I am the Customer Service Representative in charge of the order you placed for {{product_list}}.
            I am reaching out to confirm your order and let you know the delivery person will call you. Kindly confirm if the details you sent are correct: [Phone Number: {{customer_phone_number}}, WhatsApp Phone Number: {{customer_whatsapp_phone_number}}, Delivery Address: {{customer_delivery_address}}].
            Please let me know when we can deliver your order. Thank you!
        ";
        $message->save();

        $message = new MessageTemplate();
        $message->type = 'whatsapp_new_order_assigned';
        $message->subject = 'An Order was assigned';
        $message->message = "Hello  {{staff_name}}, you have been assigned a new order with ID {{order_id}}. Please review and take action.";
        $message->save();

        $message = new MessageTemplate();
        $message->type = 'whatsapp_order_status_changed';
        $message->subject = 'Order Status Changed';
        $message->message = "Hello {{customer_name}} your order status has been updated Status: {{order_status}}";
        $message->save();
    }
}
