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
        Schema::create('order_labels', function (Blueprint $table) {
            $table->id();

            $table->longText('unique_key')->nullable();
            $table->string('order_id')->nullable();

            $table->string('order_heading');
            $table->string('order_subheading');

            $table->string('orderbump_heading')->nullable();
            $table->string('orderbump_subheading')->nullable();

            $table->string('upsell_heading')->nullable();
            $table->string('upsell_subheading')->nullable();
            
            $table->string('customer_firstname_label')->nullable();
            $table->string('customer_lastname_label')->nullable();
            $table->string('customer_phone_label')->nullable();
            $table->string('customer_email_label')->nullable();
            $table->string('customer_city_label')->nullable();
            $table->string('customer_state_label')->nullable();
            $table->string('customer_country_label')->nullable();
            $table->string('customer_address_label')->nullable();

            $table->string('thankyou_heading')->nullable();
            $table->string('thankyou_subheading')->nullable();

            $table->string('status')->nullable(); //'true'/'false'
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
        Schema::dropIfExists('order_labels');
    }
};
