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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('unique_key')->nullable();
            $table->string('name')->nullable();
            $table->string('logo')->nullable();
            $table->longText('description')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();

            $table->string('created_by')->nullable();
            $table->string('assigned_to')->nullable();
            $table->string('status')->nullable(); //pending, in_progress, ready, done, backlog 
            $table->string('priority')->nullable(); //low(green), medium(yellow), high(red)

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
        Schema::dropIfExists('projects');
    }
};
