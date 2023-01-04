<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\WareHouse;

class WarehousesSampleExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $warehousesData = WareHouse::select('name', 'type', 'city', 'state', 'country_id', 'address')->take(1)->get();
        foreach ($warehousesData as $key => $warehouse) {
            $warehousesData[$key]->name = 'lorem warehouse';
            $warehousesData[$key]->type = 'major';
            $warehousesData[$key]->city = 'Ikeja';
            $warehousesData[$key]->state = 'Lagos';
            $warehousesData[$key]->country_id = 'Nigeria';
            $warehousesData[$key]->address = '101 Pizza Road, Ikeja, Lagos';
            $warehousesData[$key]->agent = '';
        }

        return $warehousesData;
    }

    public function headings(): array{
        return['name', 'type', 'city', 'state', 'country', 'address', 'agent'];
    }
}
