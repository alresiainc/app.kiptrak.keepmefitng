<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Purchase;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $features1 = ['Best Men Booster', 'Health Assurance',  'Quickly Adapt to new Changes',];
        $product = new Product();
        $product->name = '1 Bottle of Instant Flusher Pill + 1 Pack of Instant Flusher Tea';
        //$product->quantity = 10;
        $product->color = 'Light Yellow';
        $product->size = '30kg';
        $product->country_id = 1;
        $product->purchase_price = 1000;
        $product->sale_price = 1500;
        $product->purchase_id = 1;
        $product->code = 'CF001';
        $product->features = !empty($features1) ? serialize($features1) : null;
        $product->warehouse_id = '1';
        $product->created_by = '1';
        $product->status = 'true';
        $product->image = '1.jpg';
        $product->save();

        
        //product2
        $features2 = ['Result Oriented Mix', 'Daily Impact',  'Quickly Adapt to new Changes',];
        $product = new Product();
        $product->name = '2 Bottles of Instant Flusher Pill + 2 Packs of Instant Flusher Tea';
        //$product->quantity = 20;
        $product->color = 'Black';
        $product->size = '30kg';
        $product->country_id = 1;
        $product->purchase_price = 1500;
        $product->sale_price = 1800;
        $product->purchase_id = 2;
        $product->code = 'CF002';
        $product->features = !empty($features2) ? serialize($features2) : null;
        $product->warehouse_id = '1';
        $product->created_by = '1';
        $product->status = 'true';
        $product->image = '2.jpg';
        $product->save();

        //product 3
        $features3 = [''];
        $product = new Product();
        $product->name = '3 Bottles of Instant Flusher Pill + 3 Packs of Instant Flusher Tea';
        //$product->quantity = 30;
        $product->color = 'White';
        $product->size = '50kg';
        $product->country_id = 1;
        $product->purchase_price = 2000;
        $product->sale_price = 2500;
        $product->purchase_id = 3;
        $product->code = 'CF003';
        $product->features = !empty($features3) ? serialize($features3) : null;
        $product->warehouse_id = '1';
        $product->created_by = '1';
        $product->status = 'true';
        $product->image = '3.jpg';
        $product->save();

        //product 4
        $features4 = [''];
        $product = new Product();
        $product->name = 'Product 4';
        //$product->quantity = 40;
        $product->color = 'White';
        $product->size = '50kg';
        $product->country_id = 1;
        $product->purchase_price = 5000;
        $product->sale_price = 5500;
        $product->purchase_id = 4;
        $product->code = 'CF004';
        $product->features = !empty($features4) ? serialize($features4) : null;
        $product->warehouse_id = '1';
        $product->created_by = '1';
        $product->status = 'true';
        $product->image = '4.jpg';
        $product->save();

        //product 5
        $features5 = ['Long Lasting Effect', 'Perfect Fit',  'Quickly Adapt to new Body Changes',];
        $product = new Product();
        $product->name = 'Product 5';
        //$product->quantity = 50;
        // $product->color = 'White';
        $product->size = '50kg';
        $product->country_id = 1;
        $product->purchase_price = 1000;
        $product->sale_price = 1200;
        $product->purchase_id = 5;
        $product->code = 'CF005';
        $product->features = !empty($features4) ? serialize($features5) : null;
        $product->warehouse_id = '1';
        $product->created_by = '1';
        $product->status = 'true';
        $product->image = '5.jpg';
        $product->save();
    }
}
