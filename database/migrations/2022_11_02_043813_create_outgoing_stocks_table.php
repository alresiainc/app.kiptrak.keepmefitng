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
        Schema::create('outgoing_stocks', function (Blueprint $table) {
            $table->id();

            $table->longText('unique_key')->nullable();
            $table->string('product_id')->nullable();
            $table->string('order_id')->nullable(); //if reason is by 'order'
            $table->string('quantity_removed')->nullable();
            $table->string('amount_accrued')->nullable(); //accrued amt by product. can serve as expected amt
            $table->string('customer_acceptance_status')->nullable(); //accepted, rejected
            
            $table->string('reason_removed')->nullable(); //as_order_firstphase, as_orderbump, as_upsell, as_expired, as_damaged, as_administrative
            $table->string('quantity_returned')->nullable(); //0 by default //$quantity_removed - $quantity_returned = actual qty sold
            $table->string('reason_returned')->nullable(); //administrative, will be added by staff later

            $table->string('isCombo')->nullable();
            $table->json('package_bundle')->nullable(); //[{},{}]


            $table->string('created_by');
            $table->string('status'); //'true','false'
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
        Schema::dropIfExists('outgoing_stocks');
    }
};
