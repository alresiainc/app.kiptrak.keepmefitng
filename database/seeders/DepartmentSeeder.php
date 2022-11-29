<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //department 1
        $department = new Department();
        $department->code = 'kpd-' . date("Ymd") . '-'. date("his");;
        $department->name = 'Sales';
        $department->created_by = 1;
        $department->status = 'true';
        $department->save();

        $department = new Department();
        $department->code = 'kpd-' . date("Ymd") . '-'. date("his");;
        $department->name = 'Accounting';
        $department->created_by = 1;
        $department->status = 'true';
        $department->save();
    }
}
