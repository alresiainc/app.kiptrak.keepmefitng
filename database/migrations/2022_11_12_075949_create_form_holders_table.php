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
        Schema::create('form_holders', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->nullable();
            $table->string('parent_id')->nullable();
            $table->string('order_id')->nullable();

            $table->string('name');
            $table->string('slug')->unique()->nullable(); //form code
            $table->longText('form_data')->nullable();

            $table->longText('contact')->nullable(); //replaced by form_data
            $table->longText('package')->nullable();  //replaced by form_data

            $table->longText('url')->nullable();
            $table->longText('embedded_tag')->nullable();
            $table->longText('iframe_tag')->nullable();
            $table->longText('object_tag')->nullable();
            
            $table->string('orderbump_id')->nullable();
            $table->string('upsell_id')->nullable();

            $table->string('staff_assigned_id')->nullable();

            $table->string('created_by');
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
        Schema::dropIfExists('form_holders');
    }
};
