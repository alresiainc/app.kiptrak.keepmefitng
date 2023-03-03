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
        Schema::create('product_combos', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->nullable();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->longText('product_ids')->nullable(); //serialized ids of productd combined

            $table->string('expected_grand_total')->nullable(); //price of the combo before discount
            $table->string('discount_type')->nullable(); //fixed, percentage
            $table->string('discount')->nullable();
            $table->string('discount_final_amount')->nullable(); //actual amt removed from expected_grand_total
            $table->string('actual_grand_total')->nullable(); //price of the combo after discount

            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('product_combos');
    }
};
