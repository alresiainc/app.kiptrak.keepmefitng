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
        Schema::create('company_structures', function (Blueprint $table) {
            $table->id();
            $table->string('unique_key')->nullable();

            $table->string('position')->nullable(); //eg CEO
            $table->string('user_id')->nullable();
            $table->string('parent_id')->nullable();

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
        Schema::dropIfExists('company_structures');
    }
};
