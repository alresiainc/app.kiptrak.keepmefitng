<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplier = new Supplier();
        $supplier->company_name = 'Cadbury Nigeria Ltd';
        $supplier->supplier_name = 'Abdul Abdul';
        $supplier->email = 'cadbury@email.com';
        $supplier->phone_number = '08011223311';
        $supplier->created_by = 1;
        $supplier->status = 'true';
        $supplier->save();

        $supplier = new Supplier();
        $supplier->company_name = 'Friesland Group of Companies';
        $supplier->supplier_name = 'Bruno Mars';
        $supplier->email = 'friesland@email.com';
        $supplier->phone_number = '08012223311';
        $supplier->created_by = 1;
        $supplier->status = 'true';
        $supplier->save();

        $supplier = new Supplier();
        $supplier->company_name = 'Quick Food Companies';
        $supplier->supplier_name = 'Quick Food';
        $supplier->email = 'quickfood@email.com';
        $supplier->phone_number = '08013223311';
        $supplier->created_by = 1;
        $supplier->status = 'true';
        $supplier->save();
    }
    
}
