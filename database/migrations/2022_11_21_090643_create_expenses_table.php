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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('unique_key')->nullable();
            
            $table->string('expense_code');
            $table->string('expense_category_id')->nullable();
            $table->string('warehouse_id')->nullable();
            $table->string('product_id')->nullable(); //expense relating to purchases
            $table->string('staff_id')->nullable(); //expense relating to purchases
            $table->string('amount');
            $table->string('account_id')->nullable();
            $table->text('note')->nullable();
            $table->string('expense_date')->nullable();

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
        Schema::dropIfExists('expenses');
    }
};
