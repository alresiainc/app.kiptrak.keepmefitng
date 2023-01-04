<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Sale;

class SalesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $salesData = Sale::select('sale_code', 'customer_id', 'product_id', 'product_qty_sold', 'product_selling_price', 'amount_paid', 'payment_type', 'note', 'created_by', 'status', 'created_at')->get();
        foreach ($salesData as $key => $sale) {
            $salesData[$key]->customer_id = isset($sale->customer_id) ? $sale->customer->firstname.' '.$sale->customer->lastname : '';
            $salesData[$key]->product_id = isset($sale->product_id) ? $sale->product->name : '';
            $salesData[$key]->created_by = isset($sale->created_by) ? $sale->createdBy->firstname.' '.$sale->createdBy->lastname : '';
            $salesData[$key]->note = isset($sale->note) ? $sale->note : '';
        }

        return $salesData;
    }

    public function headings(): array{
        return ['Sale Code', 'Customer', 'Product', 'Qty Sold', 'Sold Unit Price', 'Amount', 'Payment Type', 'Note', 'Created By', 'Status', 'Date Added'];
    }
}
