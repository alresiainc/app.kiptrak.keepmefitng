<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AgentsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        Validator::make($collection->toArray(), [
            '*.firstname' => 'required',
            '*.lastname' => 'required',
            '*.email' => 'required|email|unique:users',
            '*.password' => 'required',
            '*.phone_1' => 'required',
            '*.phone_2' => 'required',
            '*.city' => 'required',
            '*.state' => 'required',
            '*.address' => 'required',
        ])->validate();

        foreach ($collection as $row) {
            User::create([
                'name' => $row['firstname'].' '.$row['lastname'],
                'firstname' => $row['firstname'],
                'lastname' => $row['lastname'],
                'email' => $row['email'],
                'password' => Hash::make($row['password']),
                'phone_1' => $row['phone_1'],
                'phone_2' => $row['phone_2'],
                'city' => $row['city'],
                'state' => $row['state'],
                'address' => $row['address'],
                'type' => 'agent',
                'country_id' => 1,
            ]);
        }
    }
}
