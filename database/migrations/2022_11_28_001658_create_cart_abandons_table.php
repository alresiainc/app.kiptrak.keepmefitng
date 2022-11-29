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

            $table->string('form_holder_id')->nullable();

            $table->longText('customer_info')->nullable(); //serialised
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
