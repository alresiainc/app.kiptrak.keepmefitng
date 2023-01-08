<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IncomingStock;
use App\Models\Purchase;

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
        $incomingStock->product_id = 1;
        $incomingStock->quantity_added = 10;
        $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
        $incomingStock->created_by = 1;
        $incomingStock->status = 'true';
        $incomingStock->save();

        //Purchase
        $purchase = new Purchase();
        $purchase_code = 'kppur-' . date("Ymd") . '-'. date("his");
        $purchase->purchase_code = $purchase_code;
        $purchase->product_id = 1;
        $purchase->product_qty_purchased = 10;
        $purchase->incoming_stock_id = $incomingStock->id;
        $purchase->product_purchase_price = 1000; //per unit
        $purchase->amount_due = 10 * 1000;
        $purchase->amount_paid = 10 * 1000; //u cant owe as d admin
        $purchase->payment_type = 'cash';
        $purchase->note = 'Product added from system';
        $purchase->created_by = 1;
        $purchase->status = 'received';
        $purchase->save();

        //product 2
        $incomingStock = new IncomingStock();
        $incomingStock->product_id = 2;
        $incomingStock->quantity_added = 20;
        $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
        $incomingStock->created_by = 1;
        $incomingStock->status = 'true';
        $incomingStock->save();

        $purchase = new Purchase();
        $purchase_code = 'kppur-' . date("Ymd") . '-'. date("his");
        $purchase->purchase_code = $purchase_code;
        $purchase->product_id = 2;
        $purchase->product_qty_purchased = 20;
        $purchase->incoming_stock_id = $incomingStock->id;
        $purchase->product_purchase_price = 1500; //per unit
        $purchase->amount_due = 20 * 1500;
        $purchase->amount_paid = 20 * 1500; //u cant owe as d admin
        $purchase->payment_type = 'cash';
        $purchase->note = 'Product added from system';
        $purchase->created_by = 1;
        $purchase->status = 'received';
        $purchase->save();

        //product 3
        $incomingStock = new IncomingStock();
        $incomingStock->product_id = 3;
        $incomingStock->quantity_added = 30;
        $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
        $incomingStock->created_by = 1;
        $incomingStock->status = 'true';
        $incomingStock->save();

        $purchase = new Purchase();
        $purchase_code = 'kppur-' . date("Ymd") . '-'. date("his");
        $purchase->purchase_code = $purchase_code;
        $purchase->product_id = 3;
        $purchase->product_qty_purchased = 30;
        $purchase->incoming_stock_id = $incomingStock->id;
        $purchase->product_purchase_price = 2000; //per unit
        $purchase->amount_due = 30 * 2000;
        $purchase->amount_paid = 30 * 2000; //u cant owe as d admin
        $purchase->payment_type = 'cash';
        $purchase->note = 'Product added from system';
        $purchase->created_by = 1;
        $purchase->status = 'received';
        $purchase->save();

        //product 4
        $incomingStock = new IncomingStock();
        $incomingStock->product_id = 4;
        $incomingStock->quantity_added = 40;
        $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
        $incomingStock->created_by = 1;
        $incomingStock->status = 'true';
        $incomingStock->save();

        $purchase = new Purchase();
        $purchase_code = 'kppur-' . date("Ymd") . '-'. date("his");
        $purchase->purchase_code = $purchase_code;
        $purchase->product_id = 4;
        $purchase->product_qty_purchased = 40;
        $purchase->incoming_stock_id = $incomingStock->id;
        $purchase->product_purchase_price = 5000; //per unit
        $purchase->amount_due = 40 * 5000;
        $purchase->amount_paid = 40 * 5000; //u cant owe as d admin
        $purchase->payment_type = 'cash';
        $purchase->note = 'Product added from system';
        $purchase->created_by = 1;
        $purchase->status = 'received';
        $purchase->save();

        //product 5
        $incomingStock = new IncomingStock();
        $incomingStock->product_id = 5;
        $incomingStock->quantity_added = 50;
        $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
        $incomingStock->created_by = 1;
        $incomingStock->status = 'true';
        $incomingStock->save();

        $purchase = new Purchase();
        $purchase_code = 'kppur-' . date("Ymd") . '-'. date("his");
        $purchase->purchase_code = $purchase_code;
        $purchase->product_id = 5;
        $purchase->product_qty_purchased = 50;
        $purchase->incoming_stock_id = $incomingStock->id;
        $purchase->product_purchase_price = 1000; //per unit
        $purchase->amount_due = 50 * 1000;
        $purchase->amount_paid = 50 * 1000; //u cant owe as d admin
        $purchase->payment_type = 'cash';
        $purchase->note = 'Product added from system';
        $purchase->created_by = 1;
        $purchase->status = 'received';
        $purchase->save();
    }
}
