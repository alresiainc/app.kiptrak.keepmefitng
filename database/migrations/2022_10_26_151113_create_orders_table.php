<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->longText('unique_key')->nullable();
            $table->longText('order_code')->nullable();

            $table->string('products')->nullable(); //unserialized data(product_ids)

            $table->string('source_type')->nullable(); //depends on whr order is coming frm //form_holder_module, sale_module

            $table->string('customer_id')->nullable(); //not used
            $table->longText('delivery_address')->nullable();
            $table->longText('email_message_ids')->nullable(); //serialized array
            $table->longText('whatsapp_message_ids')->nullable(); //serialized array
            $table->longText('sms_message_ids')->nullable(); //serialized array

            $table->longText('order_note')->nullable();
            $table->string('expected_delivery_date')->nullable();
            $table->string('actual_delivery_date')->nullable(); //by agent

            $table->string('form_holder_id')->nullable(); //incase coming from embedded form
            $table->string('agent_assigned_id')->nullable(); //delivery agent that delivered the order
            
            $table->string('created_by')->nullable();
            $table->string('staff_assigned_id')->nullable();

            $table->longText('url')->nullable(); //link sent to customer, not used
            $table->string('discount')->nullable(); //10% or 10
            
            // $table->string('product_ids'); //managed in OrderProduct tbl
            // $table->string('orderbump_product_id')->nullable(); //managed in OrderProduct tbl
            // $table->string('orderbump_discount')->nullable(); //managed in OrderProduct tbl
            
            // $table->string('upsell_product_id')->nullable(); //managed in OrderProduct tbl
            // $table->string('upsell_discount')->nullable(); //managed in OrderProduct tbl
            
            $table->string('orderbump_id')->nullable();
            $table->string('upsell_id')->nullable();

            $table->string('order_label_id')->nullable(); //might be in use. due to form_holder_id above, not in use
            
            $table->string('amount_expected')->nullable(); //total when admin is preparing it
            $table->string('amount_realised')->nullable(); //total after customer paid

            $table->string('delivery_duration')->nullable(); //in days //updated in //formBuilderContoller@newformlinkPost
            $table->string('delivery_going_time')->nullable(); //time agent took-off
            $table->string('delivery_meet_time')->nullable(); //time btwn take-off, and meeting customer
            $table->string('delivery_returning_time')->nullable(); //time agent got home

            $table->string('delivery_going_distance')->nullable(); //distance covered by agent
            $table->string('delivery_returning_distance')->nullable();

            $table->string('delivery_going_cost')->nullable(); //cost amt by agent
            $table->string('delivery_returning_cost')->nullable();
            
            $table->string('status')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
