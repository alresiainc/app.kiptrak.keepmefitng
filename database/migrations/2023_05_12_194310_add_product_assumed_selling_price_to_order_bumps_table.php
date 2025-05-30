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
        Schema::table('order_bumps', function (Blueprint $table) {
            $table->string('product_assumed_selling_price')->nullable()->after('product_actual_selling_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_bumps', function (Blueprint $table) {
            $table->dropColumn('product_assumed_selling_price');
        });
    }
};
