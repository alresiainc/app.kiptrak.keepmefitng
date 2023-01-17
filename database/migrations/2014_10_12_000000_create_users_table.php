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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->longText('unique_key')->nullable();
            $table->string('name');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->unique();
            $table->string('type')->nullable(); //customer, staff, agent, superadmin
            $table->string('profile_picture')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // user(those with initial incomplete-order), customer, agent, staff(staff are not agents), superadmin.

            $table->boolean('isSuperAdmin')->default(false);
            // $table->boolean('isStaff')->default(false);
            // $table->boolean('isAgent')->default(false);

            $table->string('phone_1')->unique()->nullable();
            $table->string('phone_2')->unique()->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country_id')->nullable();
            $table->string('address')->nullable();

            $table->string('current_salary')->nullable();

            $table->string('warehouse_id')->nullable(); //where staff or agent belongs

            $table->string('created_by')->nullable();
            $table->string('status')->nullable(); //'true'/'false'
            $table->softDeletes();
            
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
