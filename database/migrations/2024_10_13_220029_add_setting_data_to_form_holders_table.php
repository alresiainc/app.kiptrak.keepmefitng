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
        Schema::table('form_holders', function (Blueprint $table) {
            $table->text('setting_data')->nullable()->after('form_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_holders', function (Blueprint $table) {
            $table->dropColumn('setting_data');
        });
    }
};