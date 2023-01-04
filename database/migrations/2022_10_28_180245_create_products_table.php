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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->longText('unique_key')->nullable();
            $table->string('code')->unique()->nullable();

            $table->string('name');
            // $table->string('quantity'); //handled in 'InomingStock' tbl
            $table->string('quantity_limit')->nullable(); //lessthan this is out-of-stock
            $table->string('category_id')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('country_id')->nullable(); //will contain country_id
            
            $table->string('purchase_price')->nullable(); //currently
            $table->string('sale_price')->nullable(); //currently

            $table->string('purchase_id')->nullable(); //in product update, to knw the exact purchase to update
            $table->string('sale_id')->nullable();

            $table->string('price')->nullable(); //might not be in use
            
            $table->longText('features')->nullable(); //serialized

            $table->string('warehouse_id')->nullable();
            $table->string('image')->nullable();
            
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
        Schema::dropIfExists('products');
    }
};
