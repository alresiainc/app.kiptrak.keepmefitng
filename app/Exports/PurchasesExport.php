<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Purchase;

class PurchasesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $purchasesData = Purchase::select('purchase_code', 'supplier_id', 'product_id', 'product_qty_purchased', 'product_purchase_price', 'amount_paid', 'payment_type', 'note', 'created_by', 'status', 'created_at')->get();
        foreach ($purchasesData as $key => $purchase) {
            $purchasesData[$key]->supplier_id = isset($purchase->supplier_id) ? $purchase->supplier->company_name : '';
            $purchasesData[$key]->product_id = isset($purchase->product_id) ? $purchase->product->name : '';
            $purchasesData[$key]->created_by = isset($purchase->created_by) ? $purchase->createdBy->firstname.' '.$purchase->createdBy->lastname : '';
            $purchasesData[$key]->note = isset($purchase->note) ? $purchase->note : '';
        }

        return $purchasesData;
    }

    public function headings(): array{
        return ['Purchase Code', 'Supplier', 'Product', 'Qty Purchased', 'Purchased Unit Price', 'Amount', 'Payment Type', 'Note', 'Created By', 'Status', 'Date Added'];
    }
}
