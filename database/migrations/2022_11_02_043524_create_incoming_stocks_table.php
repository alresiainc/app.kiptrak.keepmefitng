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
        Schema::create('incoming_stocks', function (Blueprint $table) {
            $table->id();

            $table->string('product_id')->nullable();
            $table->longText('unique_key')->nullable();
            $table->string('quantity_added')->nullable();
            $table->string('reason_added')->nullable(); //as_new_product, as_returned_product, as_administrative(purchased)

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
        Schema::dropIfExists('incoming_stocks');
    }
};
