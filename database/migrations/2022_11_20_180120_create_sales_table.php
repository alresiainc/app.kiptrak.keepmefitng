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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            
            $table->string('unique_key')->nullable();

            $table->string('sale_code')->nullable();
            $table->string('parent_id')->nullable(); //for similar sale_codes
            
            $table->string('customer_id')->nullable();
            $table->string('warehouse_id')->nullable();

            $table->string('sale_date')->nullable();

            // $table->integer('biller_id'); like created_by
            $table->string('product_id')->nullable();
            $table->string('product_qty_sold')->nullable(); //product_qty sold

            $table->string('outgoing_stock_id')->nullable();

            $table->string('total_discount')->nullable();
            $table->string('total_tax')->nullable();

            $table->string('amount_due')->nullable(); ////same as amount_accrued in outgoing_stocks
            $table->string('amount_paid')->nullable(); //amt paid by customer

            $table->string('order_tax_rate')->nullable();
            $table->string('order_tax')->nullable();
            $table->string('order_discount')->nullable();
            $table->string('shipping_cost')->nullable();

            $table->string('payment_type')->nullable(); //cash, card, cheque, bank_transfer
            $table->string('payment_status'); //pending, due, partial, paid

            $table->string('attached_document')->nullable();
            $table->text('note')->nullable(); //staff note
            
            $table->string('created_by')->nullable();
            $table->string('status')->nullable(); //purchase_status //pending, completed
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
        Schema::dropIfExists('sales');
    }
};
