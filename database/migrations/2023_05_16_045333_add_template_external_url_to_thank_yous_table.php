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
            $table->string('template_external_url')->nullable()->after('iframe_tag');
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
            $table->string('template_external_url')->nullable()->after('iframe_tag');
        });
    }
};
