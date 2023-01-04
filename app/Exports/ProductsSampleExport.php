<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Product;

class ProductsSampleExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $productsData = Product::select('name', 'category_id', 'purchase_price', 'sale_price', 'color', 'size')->take(1)->get();
        foreach ($productsData as $key => $product) {
            $productsData[$key]->name = 'lorem product';
            $productsData[$key]->category_id = 'category 1';
            $productsData[$key]->purchase_price = 2000;
            $productsData[$key]->sale_price = 2500;
            $productsData[$key]->color = '';
            $productsData[$key]->size = '';
            $productsData[$key]->quantity = 5;
            $productsData[$key]->warehouse_id = 'Warehouse 1';
        }
        
        return $productsData;
    }

    public function headings(): array{
        return['name', 'category', 'purchase_price', 'sale_price', 'color', 'size', 'quantity', 'warehouse'];
    }
}
