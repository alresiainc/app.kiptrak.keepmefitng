<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Supplier;
use Illuminate\Support\Facades\Validator;

class SuppliersImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        Validator::make($collection->toArray(), [
            '*.company_name' => 'required',
            '*.supplier_name' => 'required',
            '*.email' => 'required|email',
            '*.phone_number' => 'required',
        ])->validate();

        foreach ($collection as $row) {
            Supplier::create([
                'company_name' => $row['company_name'],
                'supplier_name' => $row['supplier_name'],
                'email' => $row['email'],
                'phone_number' => $row['phone_number'],
                'created_by' => auth()->user()->id,
                'status' => 'true',
            ]);
        }
    }
}
