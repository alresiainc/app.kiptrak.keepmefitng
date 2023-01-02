<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'Dashboard Manager';
        $role->slug = Str::slug('Dashboard Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Product Manager';
        $role->slug = Str::slug('Product Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Form Builder Manager';
        $role->slug = Str::slug('Form Builder Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Order Manager';
        $role->slug = Str::slug('Order Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Warehouse Manager';
        $role->slug = Str::slug('Warehouse Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Purchase Manager';
        $role->slug = Str::slug('Purchase Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Inventory Manager';
        $role->slug = Str::slug('Inventory Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Sale Manager';
        $role->slug = Str::slug('Sales Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Expense Manager';
        $role->slug = Str::slug('Expense Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Human Resource Manager';
        $role->slug = Str::slug('Human Resource Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Supplier Manager';
        $role->slug = Str::slug('Supplier Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Agent Manager';
        $role->slug = Str::slug('Agent Manager');
        $role->created_by = 1;
        $role->save();
        
        $role = new Role();
        $role->name = 'Customer Manager';
        $role->slug = Str::slug('Customer Manager');
        $role->created_by = 1;
        $role->save();
        
        $role = new Role();
        $role->name = 'Accounting Manager';
        $role->slug = Str::slug('Accounting Manager');
        $role->created_by = 1;
        $role->save();
        
        $role = new Role();
        $role->name = 'Report Manager';
        $role->slug = Str::slug('Report Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Messaging Manager';
        $role->slug = Str::slug('Messaging Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Referral Manager';
        $role->slug = Str::slug('Referral Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Setting Manager';
        $role->slug = Str::slug('Setting Manager');
        $role->created_by = 1;
        $role->save();
        
    }
}
