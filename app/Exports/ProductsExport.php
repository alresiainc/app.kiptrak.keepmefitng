<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\IncomingStock;
use App\Models\OutgoingStock;

class ProductsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $productsData = Product::select('code', 'name', 'category_id', 'purchase_price', 'sale_price', 'color', 'size')->get();

        foreach ($productsData as $key => $product) {
            $pro = Product::where('code', $product->code)->first();
            $categoryName = Category::select('name')->where('id', $pro->category_id)->first();
            $sum_incomingStocks = IncomingStock::where('product_id', $pro->id)->sum('quantity_added');
            $sum_outgoingStocks = OutgoingStock::where('product_id', $pro->id)->sum(DB::raw('quantity_removed - quantity_returned'));

            $stock_available = $sum_incomingStocks - $sum_outgoingStocks;
            
            $productsData[$key]->category_id = $product->category->name;
            $productsData[$key]->quantity = $stock_available;
        }
        
        return $productsData;
    }

    public function headings(): array{
        return['code', 'name', 'category', 'purchase_price', 'sale_price', 'color', 'size', 'quantity'];
    }
}
