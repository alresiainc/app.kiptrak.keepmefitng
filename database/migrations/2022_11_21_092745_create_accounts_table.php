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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('unique_key')->nullable();

            $table->string('account_no');
            $table->string('name');
            $table->string('initial_balance')->nullable();
            $table->string('amount_added')->nullable();
            $table->string('total_balance')->nullable();
            
            $table->text('note')->nullable();

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
        Schema::dropIfExists('accounts');
    }
};
