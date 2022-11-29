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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->nullable();
            $table->string('company_name')->nullable();
            $table->string('supplier_name')->nullable(); //like sender name

            $table->string('email')->nullable();
            $table->longText('phone_number')->nullable(); //active phone_number
            $table->longText('company_logo')->nullable();
            
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
        Schema::dropIfExists('suppliers');
    }
};
