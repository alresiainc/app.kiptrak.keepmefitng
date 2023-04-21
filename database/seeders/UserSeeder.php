<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Test;
use Illuminate\Support\Facades\Hash;
use Faker\Factory;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $test = new Test();
        $test->name = 'test name';
        $test->description = 'test description';
        $test->save();

        //superadmin
        $user = new User();
        $user->name = 'Super John Doe';
        $user->firstname = 'Super John';
        $user->lastname = 'Doe';
        $user->email = 'super@email.com';
        $user->password = Hash::make('password');
        $user->type = 'superadmin';
        $user->isSuperAdmin = true;

        $user->phone_1 = '01011223344';
        $user->phone_2 = '03011423644';
        $user->city = 'Ikeja';
        $user->state = 'Lagos';
        $user->country_id = 1;
        $user->address = '101 Magodo Street, Ikeja Lagos';

        $user->status = 'true';
        $user->save();

        for($a = 0; $a < 20; $a++){
            $faker = Factory::create();
            $user = new \App\Models\User();
            $user->name = $faker->name();
            $user->email = $faker->email;
            $user->password = Hash::make('password');
            $user->type = 'staff';
            $user->phone_1 = $faker->phoneNumber();
            $user->phone_2 = $faker->phoneNumber();
            $user->country_id = 1;
            $user->save();
        }

    }
}
