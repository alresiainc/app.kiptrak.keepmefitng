<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employee = new Employee();
        $employee->code = 'kpemp-' . date("Ymd") . '-'. date("his");;
        $employee->name = 'Ibe Peter';
        $employee->phone_number = '080765432345';
        $employee->address = 'lorem address road';
        $employee->city = 'Ikeja';
        $employee->state = 'Lagos';
        $employee->country = 1;
        
        $employee->department_id = 1;
        $employee->warehouse_id = 1;

        $employee->created_by = 1;
        $employee->status = 'true';
        $employee->save();

        //2
        $employee = new Employee();
        $employee->code = 'kpemp-' . date("Ymd") . '-'. date("his");;
        $employee->name = 'Mark Jones';
        $employee->phone_number = '080365432345';
        $employee->address = 'lorem address road';
        $employee->city = 'Ikeja';
        $employee->state = 'Lagos';
        $employee->country = 1;
        
        $employee->department_id = 1;
        $employee->warehouse_id = 1;

        $employee->created_by = 1;
        $employee->status = 'true';
        $employee->save();

        
    }
}
