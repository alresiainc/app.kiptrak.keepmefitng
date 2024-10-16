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
            $table->json('staff_assigned_ids')->nullable()->after('staff_assigned_id'); // Store the staff IDs as a JSON array
            $table->json('staff_attendance')->nullable()->after('staff_assigned_ids'); // Store attendance information for each staff member
            $table->boolean('auto_orders_distribution')->default(false)->after('staff_attendance'); // Enable or disable automatic order distributionStore attendance information for each staff member
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
            $table->dropColumn(['staff_assigned_ids', 'staff_attendance', 'auto_orders_distribution']);
        });
    }
};
