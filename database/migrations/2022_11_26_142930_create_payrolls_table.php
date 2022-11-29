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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->nullable();

            $table->string('code')->nullable(); //reference code
            $table->string('employee_id')->nullable();
            $table->string('account_id')->nullable();
            $table->string('amount')->nullable();
            $table->string('paying_method')->nullable(); //late, present, absent
            $table->string('note')->nullable();
            
            $table->string('created_by')->nullable();
            $table->string('status')->nullable(); //paid, pending
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
        Schema::dropIfExists('payrolls');
    }
};
