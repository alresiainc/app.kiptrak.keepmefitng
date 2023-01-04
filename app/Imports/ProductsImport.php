<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Product;
use App\Models\Category;
use App\Models\WareHouse;
use App\Models\ProductWarehouse;
use App\Models\IncomingStock;
use App\Models\Purchase;
use Illuminate\Support\Facades\Validator;

class ProductsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        Validator::make($collection->toArray(), [
            '*.name' => 'required',
            '*.category' => 'required|exists:categories,name', //if catname doesnot exist, it will not be allowed
            '*.purchase_price' => 'required',
            '*.sale_price' => 'required',
            '*.color' => 'nullable',
            '*.size' => 'nullable',
            '*.quantity' => 'required',
            '*.warehouse' => 'required|exists:ware_houses,name',
        ])->validate();

        foreach ($collection as $row) {
            $category = Category::where('name', $row['category'])->first();

            $product = Product::create([
                'name' => $row['name'],
                'category_id' => $category->id,
                'purchase_price' => $row['purchase_price'],
                'sale_price' => $row['sale_price'],
                'country_id' => 1,
                'color' => !empty($row['color']) ? $row['color'] : null,
                'size' => !empty($row['size']) ? $row['size'] : null,
                'created_by' => auth()->user()->id,
                'status' => 'true',
            ]);

            $warehouse = WareHouse::where('name', $row['warehouse'])->first();

            //incomingstocks
            $incomingStock = ProductWarehouse::create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'warehouse_type' => $warehouse->type,
            ]);

            //incomingstocks
            $incomingStock = IncomingStock::create([
                'product_id' => $product->id,
                'quantity_added' => $row['quantity'],
                'reason_added' => 'as_new_product',
                'created_by' => auth()->user()->id,
                'status' => 'true',
            ]);

            //Purchase
            $purchase = Purchase::create([
                'product_id' => $product->id,
                'product_qty_purchased' => $row['quantity'],
                'incoming_stock_id' => $incomingStock->id,
                'product_purchase_price' => $row['purchase_price'],
                'amount_due' => $row['quantity'] * $row['purchase_price'],
                'amount_paid' => $row['quantity'] * $row['purchase_price'],
                'payment_type' => 'cash',
                'note' => 'Product added from system',
                'created_by' => auth()->user()->id,
                'status' => 'received',
            ]);
        
            $product->update(['purchase_id'=>$purchase->id]);
            
        }
    }
}
