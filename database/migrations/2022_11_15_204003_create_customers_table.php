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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->nullable();
            $table->string('order_id')->nullable();
            $table->string('form_holder_id')->nullable();

            $table->string('firstname');
            $table->string('lastname');
            $table->longText('phone_number')->nullable(); //active phone_number
            $table->longText('whatsapp_phone_number')->nullable();

            $table->string('email');
            $table->string('password')->nullable();

            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->longText('delivery_address')->nullable();
            $table->longText('delivery_duration')->nullable();

            $table->longText('profile_picture')->nullable();
            
            $table->string('created_by');
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
        Schema::dropIfExists('customers');
    }
};
