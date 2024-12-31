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
            $table->longText('header_scripts')->nullable()->after('status');
            $table->longText('footer_scripts')->nullable()->after('status');
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
            $table->dropColumn(['header_scripts', 'footer_scripts']);
        });
    }
};
