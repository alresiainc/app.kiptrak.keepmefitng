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
            $table->integer('staff_workload_threshold')
                ->default(0)
                ->after('auto_orders_distribution')
                ->nullable()
                ->comment('The maximum number of pending orders a staff can have before being skipped for new orders.');
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
            $table->dropColumn('staff_workload_threshold');
        });
    }
};
