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
            $table->text('serlzo_account_token')->nullable()->after('setting_data');
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
            $table->dropColumn(['serlzo_account_token']);
        });
    }
};
