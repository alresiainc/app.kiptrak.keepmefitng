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
        Schema::create('form_builders', function (Blueprint $table) {
            $table->id();
            $table->string('unique_key')->nullable();

            $table->string('name')->nullable();
            $table->string('label')->nullable();
            $table->string('type')->nullable();
            $table->string('options')->nullable();
            $table->string('value')->nullable();
            $table->string('placeholder')->nullable();
            $table->string('required')->nullable();
            $table->string('size')->nullable();

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
        Schema::dropIfExists('form_builders');
    }
};
