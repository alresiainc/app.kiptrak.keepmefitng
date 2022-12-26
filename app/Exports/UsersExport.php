<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Country;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //return User::all();
        $usersData = User::where('type', 'staff')->select('name', 'email', 'phone_1', 'phone_2', 'city', 'state', 'country_id')->get();
        foreach ($usersData as $key => $user) {
            $countryName = Country::select('name')->where('id', $user->country_id)->first();
            $userId = User::select('id')->where('email', $user->email)->first();
            
            $usersData[$key]->country_id = $user->country->name;
            $usersData[$key]->role = $user->hasAnyRole($userId->id) ? $user->role($userId->id)->role->name : 'No role';
        }

        return $usersData;
    }

    public function headings(): array{
        return['Staff Name', 'Email', 'Phone 1', 'Phone 2', 'City', 'State', 'Country', 'Role'];
    }
}
