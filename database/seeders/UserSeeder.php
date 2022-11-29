<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //superadmin
        $user = new User();
        $user->name = 'Super John Doe';
        $user->email = 'super@email.com';
        $user->password = Hash::make('password');
        $user->type = 'superadmin';

        $user->phone_1 = '01011223344';
        $user->phone_2 = '03011423644';
        $user->city = 'Ikeja';
        $user->state = 'Lagos';
        $user->country_id = 1;
        $user->address = '101 Magodo Street, Ikeja Lagos';

        $user->status = 'true';
        $user->save();

        //staff1
        $user = new User();
        $user->name = 'Staff Ben Jude';
        $user->email = 'staff@email.com';
        $user->password = Hash::make('password');
        $user->type = 'staff';

        $user->phone_1 = '02011223344';
        $user->phone_2 = '02011623344';
        $user->city = 'Ikeja';
        $user->state = 'Lagos';
        $user->country_id = 1;
        $user->address = '101 Magodo Street, Ikeja Lagos';

        $user->created_by = '1';
        $user->status = 'true';
        $user->save();

        //staff1
        $user = new User();
        $user->name = 'Ben Jude';
        $user->email = 'ben@email.com';
        $user->password = Hash::make('password');
        $user->type = 'staff';

        $user->phone_1 = '02011223345';
        $user->phone_2 = '02051223345';
        $user->city = 'Ikeja';
        $user->state = 'Lagos';
        $user->country_id = 1;
        $user->address = '101 Magodo Street, Ikeja Lagos';

        $user->created_by = '1';
        $user->status = 'true';
        $user->save();


        //staff2
        $user = new User();
        $user->name = 'Peter Bruce';
        $user->email = 'peter@email.com';
        $user->password = Hash::make('password');
        $user->type = 'staff';

        $user->phone_1 = '03011223344';
        $user->phone_2 = '03014223344';
        $user->city = 'Ikeja';
        $user->state = 'Lagos';
        $user->country_id = 1;
        $user->address = '101 Magodo Street, Ikeja Lagos';

        $user->created_by = 1;
        $user->status = 'true';
        $user->save();

        //staff3
        $user = new User();
        $user->name = 'Max Lucado';
        $user->email = 'max@email.com';
        $user->password = Hash::make('password');
        $user->type = 'staff';

        $user->phone_1 = '04011223344';
        $user->phone_2 = '04011523344';
        $user->city = 'Ikeja';
        $user->state = 'Lagos';
        $user->country_id = 1;
        $user->address = '101 Magodo Street, Ikeja Lagos';

        $user->created_by = 1;
        $user->status = 'true';
        $user->save();
        //---------------------------------------

        //agent1
        $user = new User();
        $user->name = 'Agent Tony Cruz';
        $user->email = 'agent@email.com';
        $user->password = Hash::make('password');
        $user->type = 'agent';

        $user->phone_1 = '05011223344';
        $user->phone_2 = '06011723344';
        $user->city = 'Ikeja';
        $user->state = 'Lagos';
        $user->country_id = 1;
        $user->address = '101 Magodo Street, Ikeja Lagos';

        $user->created_by = 1;
        $user->status = 'true';
        $user->save();

        //agent2
        $user = new User();
        $user->name = 'Tony Cruz';
        $user->email = 'tony@email.com';
        $user->password = Hash::make('password');
        $user->type = 'agent';

        $user->phone_1 = '05011223345';
        $user->phone_2 = '05021243345';
        $user->city = 'Ikeja';
        $user->state = 'Lagos';
        $user->country_id = 1;
        $user->address = '101 Magodo Street, Ikeja Lagos';

        $user->created_by = 1;
        $user->status = 'true';
        $user->save();

        //agent3
        $user = new User();
        $user->name = 'Nkem John';
        $user->email = 'nkem@email.com';
        $user->password = Hash::make('password');
        $user->type = 'agent';

        $user->phone_1 = '06011223344';
        $user->phone_2 = '06021253344';
        $user->city = 'Ikeja';
        $user->state = 'Lagos';
        $user->country_id = 1;
        $user->address = '101 Magodo Street, Ikeja Lagos';

        $user->created_by = 1;
        $user->status = 'true';
        $user->save();

        //agent4
        $user = new User();
        $user->name = 'Amara Kara';
        $user->email = 'amara@email.com';
        $user->password = Hash::make('password');
        $user->type = 'agent';

        $user->phone_1 = '07051243344';
        $user->phone_2 = '08051243344';
        $user->city = 'Ikeja';
        $user->state = 'Lagos';
        $user->country_id = 1;
        $user->address = '101 Magodo Street, Ikeja Lagos';

        $user->created_by = '1';
        $user->status = 'true';
        $user->save();

        //agent4
        $user = new User();
        $user->name = 'Kelly Rowland';
        $user->email = 'kelly@email.com';
        $user->password = Hash::make('password');
        $user->type = 'agent';

        $user->phone_1 = '08021223344';
        $user->phone_2 = '08051723344';
        $user->city = 'Ikeja';
        $user->state = 'Lagos';
        $user->country_id = 1;
        $user->address = '101 Magodo Street, Ikeja Lagos';

        $user->created_by = '1';
        $user->status = 'true';
        $user->save();

    }
}
