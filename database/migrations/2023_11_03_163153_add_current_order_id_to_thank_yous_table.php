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
        Schema::table('thank_yous', function (Blueprint $table) {
            $table->unsignedBigInteger('current_order_id')->nullable()->after('template_external_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('thank_yous', function (Blueprint $table) {
            $table->dropColumn('current_order_id');
        });
    }
};
