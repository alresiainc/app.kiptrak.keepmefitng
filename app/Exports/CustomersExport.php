<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Country;
use App\Models\Customer;

class CustomersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //return User::all();
        $usersData = Customer::select('firstname', 'lastname', 'phone_number', 'whatsapp_phone_number', 'email', 'city', 'state', 'country_id', 'delivery_address')->get();
        foreach ($usersData as $key => $user) {
            $countryName = Country::select('name')->where('id', $user->country_id)->first();
            $userId = Customer::select('id')->where('email', $user->email)->first();
            
            $usersData[$key]->country_id = $user->country->name;
        }

        return $usersData;
    }

    public function headings(): array{
        return['Firstname', 'Lastname', 'Phone Number', 'Whatsapp Phone Number', 'Email', 'City', 'State', 'Country', 'Delivery Address'];
    }
}
