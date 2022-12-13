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
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('unique_key')->nullable();

            $table->string('site_title')->nullable();
            $table->string('site_description')->nullable();
            $table->string('site_logo')->nullable();

            $table->string('currency')->nullable();
            $table->string('currency_position')->nullable();
            $table->string('developed_by')->nullable(); //Ugo Sunday Raphael
            $table->string('official_notification_email')->nullable();
            
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
        Schema::dropIfExists('general_settings');
    }
};
