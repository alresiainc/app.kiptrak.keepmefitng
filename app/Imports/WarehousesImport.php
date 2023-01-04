<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use App\Models\WareHouse;
use App\Models\User;
use App\Models\Country;


class WarehousesImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        Validator::make($collection->toArray(), [
            '*.name' => 'required',
            '*.type' => 'required',
            '*.city' => 'required',
            '*.state' => 'required',
            '*.country' => 'required|exists:countries,name',
            '*.address' => 'required',
            '*.agent' => 'nullable|exists:agents,name',
            
        ])->validate();

        foreach ($collection as $row) {
            $agent = !empty($row['agent']) ? User::where('name', $row['agent'])->first()->id : null;
            $country = !empty($row['country']) ? Country::where('name', $row['country'])->first()->id : null;
            WareHouse::create([
                'agent_id' => $agent,
                'name' => $row['name'],
                'type' => $row['type'],
                'city' => $row['city'],
                'state' => $row['state'],
                'country_id' => $country,
                'address' => $row['address'],
                'created_by' => auth()->user()->id,
                'status' => 'true',
            ]);
        }
    }
}
