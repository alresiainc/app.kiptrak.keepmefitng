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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->nullable();

            $table->string('code')->nullable(); //reference code
            $table->string('purchase_id')->nullable();
            $table->string('sale_id')->nullable();
            $table->string('cash_register_id')->nullable();
            $table->string('account_id')->nullable(); //
            $table->string('amount')->nullable();
            $table->string('used_points')->nullable(); //
            $table->string('change')->nullable(); //
            $table->string('paying_method')->nullable();
            $table->string('note')->nullable(); //payment_note
            
            $table->string('created_by')->nullable();
            $table->string('status')->nullable(); //paid, pending
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
        Schema::dropIfExists('payments');
    }
};
