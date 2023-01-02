<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SuppliersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $suppliersData = Supplier::select('company_name', 'supplier_name', 'email', 'phone_number')->get();
        
        return $suppliersData;
    }

    public function headings(): array{
        return['company_name', 'supplier_name', 'email', 'phone_number'];
    }
}
