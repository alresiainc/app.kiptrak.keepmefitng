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
        Schema::create('ware_houses', function (Blueprint $table) {
            $table->id();

            $table->longText('unique_key')->nullable();
            $table->string('type')->nullable(); //major, minor
            $table->string('agent_id')->nullable(); //user incharge
            
            $table->string('name')->nullable();
            $table->string('city')->nullable(); //or town
            $table->string('state')->nullable();
            $table->string('country_id')->nullable();
            $table->string('address')->nullable();

            $table->string('created_by');
            $table->string('status'); //'true','false'
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
        Schema::dropIfExists('ware_houses');
    }
};
