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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->nullable();
            $table->string('purchase_code')->nullable(); //random number
            $table->string('parent_id')->nullable(); //for similar purchase_codes
            $table->string('supplier_id')->nullable();
            $table->string('warehouse_id')->nullable();

            $table->string('purchase_date')->nullable();

            $table->string('product_id')->nullable(); //product table will be updated here, due to change in cost price & qty
            $table->string('product_qty_purchased')->nullable(); //new qty added
            $table->string('product_purchase_price')->nullable(); //product unit price
            $table->string('incoming_stock_id')->nullable(); //used in editing to check which stock to update

            $table->string('amount_due')->nullable(); //amt remaining if amt not paid
            $table->string('amount_paid')->nullable(); //

            $table->string('order_tax')->nullable(); //will affect the product price
            $table->string('discount_type')->nullable(); //fixed or percentage
            $table->string('total_discount')->nullable(); //will affect the product price
            $table->string('shipping_cost')->nullable(); //will affect the product price

            $table->string('payment_type')->nullable(); //cash, card, cheque, bank_transfer
            $table->string('attached_document')->nullable(); //file of proove
            $table->string('batch_number')->nullable(); //product batch number
            $table->string('manufactured_date')->nullable();
            $table->string('expiry_date')->nullable();
            $table->longText('note')->nullable(); //anything
            
            $table->string('created_by')->nullable();
            $table->string('status')->nullable(); //purchase_status //received, partial, pending, ordered
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
        Schema::dropIfExists('purchases');
    }
};
