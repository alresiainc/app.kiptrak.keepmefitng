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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->nullable();
            $table->string('subject_type')->nullable(); //eg all, sell, purchase, expense, User(for login), 
            $table->string('action')->nullable(); //eg login
            $table->string('user_id')->nullable();
            $table->longText('note')->nullable(); //

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
        Schema::dropIfExists('activity_logs');
    }
};
