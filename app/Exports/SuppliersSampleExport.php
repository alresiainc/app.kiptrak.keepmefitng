<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SuppliersSampleExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $suppliersData = Supplier::select('company_name', 'supplier_name', 'email', 'phone_number')->take(1)->get();
        foreach ($suppliersData as $key => $user) {
            $suppliersData[$key]->company_name = 'ABC Ventures';
            $suppliersData[$key]->supplier_name = 'John Doe';
            $suppliersData[$key]->email = 'john@email.com';
            $suppliersData[$key]->phone_number = '08011223344'; //logic, since password is hidden by default
        }
        
        return $suppliersData;
    }

    public function headings(): array{
        return['company_name', 'supplier_name', 'email', 'phone_number'];
    }
}
