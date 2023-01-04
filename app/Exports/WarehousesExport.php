<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\WareHouse;

class WarehousesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $warehousesData = WareHouse::select('name', 'type', 'agent_id', 'city', 'state', 'country_id', 'address', 'created_by', 'created_at')->get();
        foreach ($warehousesData as $key => $warehouse) {
            $warehousesData[$key]->agent_id = isset($warehouse->agent_id) ? $warehouse->agent->firstname.' '.$warehouse->agent->lastname : '';
            $warehousesData[$key]->country_id = isset($warehouse->country_id) ? $warehouse->country->name : '';
            $warehousesData[$key]->created_by = isset($warehouse->created_by) ? $warehouse->createdBy->firstname.' '.$warehouse->createdBy->lastname : '';
        }

        return $warehousesData;
    }

    public function headings(): array{
        return['name', 'type', 'agent', 'city', 'state', 'country', 'address', 'Created By', 'Date Added'];
    }
}
