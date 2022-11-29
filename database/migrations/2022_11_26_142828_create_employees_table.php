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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->unique()->nullable();
            $table->string('code')->nullable();
            $table->string('user_id')->nullable(); //user with same info
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone_number')->unique()->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();

            $table->string('image')->nullable();
            $table->string('department_id')->nullable();
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
        Schema::dropIfExists('employees');
    }
};
