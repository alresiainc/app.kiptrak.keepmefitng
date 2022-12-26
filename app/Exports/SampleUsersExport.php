<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SampleUsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $usersData = User::select('firstname', 'lastname', 'email', 'type', 'phone_1', 'phone_2', 'city', 'state', 'address')->take(1)->get();
        foreach ($usersData as $key => $user) {
            $usersData[$key]->firstname = 'John';
            $usersData[$key]->lastname = 'Doe';
            $usersData[$key]->email = 'john@email.com';
            $usersData[$key]->type = 'password'; //logic, since password is hidden by default
            $usersData[$key]->phone_1 = '08011223344';
            $usersData[$key]->phone_2 = '08021223345';
            $usersData[$key]->city = 'Ikeja';
            $usersData[$key]->state = 'Lagos';
            $usersData[$key]->address = 'Sample Address';
            

        }
        

        return $usersData;
    }

    public function headings(): array{
        return['firstname', 'lastname', 'email', 'password', 'phone_1', 'phone_2', 'city', 'state', 'address'];
    }
}
