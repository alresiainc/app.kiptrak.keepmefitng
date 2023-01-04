<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersSampleExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $customersData = Customer::select('firstname', 'lastname', 'phone_number', 'whatsapp_phone_number', 'email', 'unique_key', 'city', 'state', 'delivery_address')->take(1)->get();
        foreach ($customersData as $key => $user) {
            $customersData[$key]->firstname = 'Customer';
            $customersData[$key]->lastname = 'User';
            $customersData[$key]->phone_number = '08017223344';
            $customersData[$key]->whatsapp_phone_number = '08021223345';
            $customersData[$key]->email = 'customer@email.com';
            $customersData[$key]->unique_key = 'password'; //logic, since password is hidden by default
            $customersData[$key]->city = 'Ikeja';
            $customersData[$key]->state = 'Lagos';
            $customersData[$key]->delivery_address = 'Sample Address';
        }
        

        return $customersData;
    }

    public function headings(): array{
        return['firstname', 'lastname', 'phone_number', 'whatsapp_phone_number', 'email', 'password', 'city', 'state', 'delivery_address'];
    }
}
