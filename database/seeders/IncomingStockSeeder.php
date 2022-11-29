<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IncomingStock;

class IncomingStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //product 1
        $incomingStock = new IncomingStock();
        $incomingStock->product_id = '1';
        $incomingStock->quantity_added = 10;
        $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
        $incomingStock->created_by = 1;
        $incomingStock->status = 'true';
        $incomingStock->save();

        //product 2
        $incomingStock = new IncomingStock();
        $incomingStock->product_id = '2';
        $incomingStock->quantity_added = 20;
        $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
        $incomingStock->created_by = 1;
        $incomingStock->status = 'true';
        $incomingStock->save();

        //product 3
        $incomingStock = new IncomingStock();
        $incomingStock->product_id = '3';
        $incomingStock->quantity_added = 30;
        $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
        $incomingStock->created_by = 1;
        $incomingStock->status = 'true';
        $incomingStock->save();

        //product 4
        $incomingStock = new IncomingStock();
        $incomingStock->product_id = '4';
        $incomingStock->quantity_added = 40;
        $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
        $incomingStock->created_by = 1;
        $incomingStock->status = 'true';
        $incomingStock->save();

        //product 5
        $incomingStock = new IncomingStock();
        $incomingStock->product_id = '5';
        $incomingStock->quantity_added = 50;
        $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
        $incomingStock->created_by = 1;
        $incomingStock->status = 'true';
        $incomingStock->save();
    }
}
