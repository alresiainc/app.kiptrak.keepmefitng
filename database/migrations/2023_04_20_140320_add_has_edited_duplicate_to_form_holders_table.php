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
            $table->boolean('has_edited_duplicate')->default(false)->after('staff_assigned_id');
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
            $table->dropColumn('has_edited_duplicate');
        });
    }
};
