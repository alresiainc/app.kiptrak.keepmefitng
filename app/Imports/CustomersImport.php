<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;

class CustomersImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        Validator::make($collection->toArray(), [
            '*.firstname' => 'required',
            '*.lastname' => 'required',
            '*.phone_number' => 'required',
            '*.whatsapp_phone_number' => 'required',
            '*.email' => 'required|email|unique:users',
            '*.password' => 'required',
            '*.city' => 'required',
            '*.state' => 'required',
            '*.delivery_address' => 'required',
        ])->validate();

        foreach ($collection as $row) {
            Customer::create([
                'firstname' => $row['firstname'],
                'lastname' => $row['lastname'],
                'phone_number' => $row['phone_number'],
                'whatsapp_phone_number' => $row['whatsapp_phone_number'],
                'email' => $row['email'],
                'password' => Hash::make($row['password']),
                'city' => $row['city'],
                'state' => $row['state'],
                'country_id' => 1,
                'delivery_address' => $row['delivery_address'],
                'created_by' => auth()->user()->id,
                'status' => 'true',
            ]);
        }
    }
}
