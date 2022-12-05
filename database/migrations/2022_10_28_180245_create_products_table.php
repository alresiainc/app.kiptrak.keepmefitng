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

            $table->string('name');
            // $table->string('quantity'); //handled in 'InomingStock' tbl
            $table->string('quantity_limit')->nullable(); //lessthan this is out-of-stock
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('country_id'); //will contain country_id
            
            $table->string('purchase_id');
            $table->string('sale_id');

            $table->string('price');
            $table->string('code')->unique()->nullable();
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
