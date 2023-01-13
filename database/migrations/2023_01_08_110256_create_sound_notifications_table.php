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
        Schema::create('sound_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('unique_key')->nullable();

            $table->string('type')->nullable(); //order
            $table->string('topic')->nullable(); //topic
            $table->string('content')->nullable(); //content
            $table->string('link')->nullable(); //link

            $table->string('order_id')->nullable();

            $table->string('updated_by')->nullable(); //staff that attended to the notification
            $table->string('status')->nullable(); //new, pending, confirmed
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
        Schema::dropIfExists('sound_notifications');
    }
};
