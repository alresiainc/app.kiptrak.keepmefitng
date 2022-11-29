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
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->nullable();

            $table->string('cash_in_hand')->nullable();
            $table->string('user_id')->nullable();
            $table->string('warehouse_id')->nullable();
            
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
        Schema::dropIfExists('cash_registers');
    }
};
