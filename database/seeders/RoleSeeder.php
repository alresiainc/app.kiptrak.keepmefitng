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
        $role->name = 'Manager';
        $role->slug = Str::slug('Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Human Resource';
        $role->slug = Str::slug('Human Resource');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Sales Manager';
        $role->slug = Str::slug('Sales Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Purchase Manager';
        $role->slug = Str::slug('Purchase Manager');
        $role->created_by = 1;
        $role->save();

        $role = new Role();
        $role->name = 'Orders Manager';
        $role->slug = Str::slug('Orders Manager');
        $role->created_by = 1;
        $role->save();

        
    }
}
