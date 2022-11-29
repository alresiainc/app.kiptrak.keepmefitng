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
        Schema::create('return_purchases', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->nullable();

            $table->string('code')->nullable();
            $table->string('supplier_id')->nullable();
            $table->string('warehouse_id')->nullable();
            $table->string('account_id')->nullable();
            $table->string('product_id')->nullable(); //product returned
            $table->string('total_qty')->nullable(); //qty returned

            $table->string('total_discount')->nullable();
            $table->string('total_tax')->nullable();
            $table->string('order_tax_rate')->nullable();
            $table->string('order_tax')->nullable();
            $table->string('grand_total')->nullable(); //total returned

            $table->string('document')->nullable(); //file
            $table->string('return_note')->nullable();
            $table->string('staff_note')->nullable();
            
            $table->string('created_by')->nullable();
            $table->string('status')->nullable(); //true, false
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
        Schema::dropIfExists('return_purchases');
    }
};
