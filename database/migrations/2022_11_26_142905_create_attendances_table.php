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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->nullable();

            $table->string('date')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('check_in')->nullable();
            $table->string('check_out')->nullable();
            $table->string('daily_status')->nullable(); //late, present, absent
            $table->string('check_in_note')->nullable();
            $table->string('check_out_note')->nullable();
            
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
        Schema::dropIfExists('attendances');
    }
};
