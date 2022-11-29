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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->nullable();

            $table->string('code')->nullable(); //reference code
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('phone_1')->nullable(); //phone_1
            $table->string('phone_2')->nullable(); //phone_2
            $table->string('user_id')->nullable(); //for auth
            $table->string('address')->nullable();
            $table->string('warehouse_id')->nullable();
            
            $table->string('created_by')->nullable();
            $table->string('status')->nullable(); 

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
        Schema::dropIfExists('agents');
    }
};
