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
        Schema::create('cart_abandons', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->nullable();

            $table->string('order_id')->nullable();
            $table->string('form_holder_id')->nullable();

            $table->longText('customer_firstname')->nullable();
            $table->longText('customer_lastname')->nullable();
            $table->longText('customer_phone_number')->nullable();
            $table->longText('customer_whatsapp_phone_number')->nullable();
            $table->longText('customer_email')->nullable();
            $table->longText('customer_password')->nullable();
            $table->longText('customer_city')->nullable();
            $table->longText('customer_state')->nullable();
            $table->longText('customer_delivery_address')->nullable();
            $table->longText('customer_delivery_duration')->nullable();
            $table->longText('customer_ip_address')->nullable();

            $table->longText('package_info')->nullable(); //serialised

            $table->string('orderbump_status')->nullable(); //true or false
            $table->longText('orderbump_info')->nullable(); //serialised

            $table->string('upsell_status')->nullable(); //true or false
            $table->longText('upsell_info')->nullable(); //serialised

            $table->string('status')->nullable(); //true/false
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
        Schema::dropIfExists('cart_abandons');
    }
};
