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
        Schema::create('up_sells', function (Blueprint $table) {
            $table->id();

            $table->longText('unique_key')->nullable();

            $table->string('upsell_heading')->nullable(); //
            $table->string('upsell_subheading')->nullable(); //
            $table->string('upsell_description')->nullable();

            $table->string('upsell_setting_id')->nullable();
            
            $table->string('order_id')->nullable();
            $table->string('product_id')->nullable();
            
            $table->string('upsell_discount_type')->nullable();
            $table->string('upsell_discount')->nullable();

            $table->string('product_expected_quantity_to_be_sold')->nullable(); //qty set by admin
            $table->string('product_actual_quantity_sold')->nullable(); //qty bought by user
            $table->string('product_expected_amount')->nullable(); //money. filled after admin raises order
            $table->string('product_realised_amount')->nullable(); //filled after customer places order
            //cals frm here will update the orders tbl, for final amt
            
            $table->string('created_by')->nullable();
            $table->string('status')->nullable(); //is true when customer pays
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
        Schema::dropIfExists('up_sells');
    }
};
