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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->nullable();

            $table->string('type')->nullable(); //sms, email
            $table->string('topic')->nullable();
            $table->string('recipients')->nullable(); 
            $table->string('message')->nullable(); 
            $table->string('to')->nullable(); //users, customers 
            $table->string('message_status')->nullable(); //sent, draft

            $table->string('created_by')->nullable();
            $table->string('status')->nullable(); 
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
        Schema::dropIfExists('messages');
    }
};
