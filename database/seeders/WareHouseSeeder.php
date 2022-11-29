<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WareHouse;

class WareHouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //warehouse 1
        $warehouse = new WareHouse();
        $warehouse->agent_id = 6;
        $warehouse->name = 'Warehouse 1';
        $warehouse->city = 'Ikeja';
        $warehouse->state = 'Lagos';
        $warehouse->country_id = 1;
        $warehouse->address = '22 Ikeja Lagos Street, Nigeria';
        $warehouse->created_by = 1;
        $warehouse->status = 'true';
        $warehouse->save();

        $warehouse = new WareHouse();
        $warehouse->agent_id = 7;
        $warehouse->name = 'Warehouse 2';
        $warehouse->city = 'Agip';
        $warehouse->state = 'Lagos';
        $warehouse->country_id = 1;
        $warehouse->address = '101 Magodo Street, Agip Lagos';
        $warehouse->created_by = 1;
        $warehouse->status = 'true';
        $warehouse->save();

        $warehouse = new WareHouse();
        $warehouse->agent_id = 7;
        $warehouse->name = 'Warehouse 3';
        $warehouse->city = 'Garki';
        $warehouse->state = 'Abuja';
        $warehouse->country_id = 1;
        $warehouse->address = '100 Army Street, Garki Abuja';
        $warehouse->created_by = 1;
        $warehouse->status = 'true';
        $warehouse->save();
        
        
    }
}
