<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Permission;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //----------Dashboard---Id-1--------------------
        $permission = new Permission();
        $permission->name = 'Dashboard';
        $permission->slug = Str::slug('Dashboard');
        $permission->created_by = 1;
        $permission->save();
        
        //----------Product---Id-2--------------------
        $permission = new Permission();
        $permission->name = 'Product';
        $permission->slug = Str::slug('Product');
        $permission->created_by = 1;
        $permission->save();

        //----------Form Builder---Id-3--------------------
        $permission = new Permission();
        $permission->name = 'Form Builder';
        $permission->slug = Str::slug('Form Builder');
        $permission->created_by = 1;
        $permission->save();

        //----------Order---Id-4--------------------
        $permission = new Permission();
        $permission->name = 'Order';
        $permission->slug = Str::slug('Order');
        $permission->created_by = 1;
        $permission->save();

        //----------Warehouse---Id-5--------------------
        $permission = new Permission();
        $permission->name = 'Warehouse';
        $permission->slug = Str::slug('Warehouse');
        $permission->created_by = 1;
        $permission->save();

        //----------Purchase---Id-6--------------------
        $permission = new Permission();
        $permission->name = 'Purchase';
        $permission->slug = Str::slug('Purchase');
        $permission->created_by = 1;
        $permission->save();

        //----------Purchase---Id-7--------------------
        $permission = new Permission();
        $permission->name = 'Sale';
        $permission->slug = Str::slug('Sale');
        $permission->created_by = 1;
        $permission->save();
        //----------Sale---Id-7--------------------

        $permission = new Permission();
        $permission->name = 'Expense';
        $permission->slug = Str::slug('Expense');
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'HRM';
        $permission->slug = Str::slug('HRM');
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Supplier';
        $permission->slug = Str::slug('Supplier');
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Agent';
        $permission->slug = Str::slug('Agent');
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Customer';
        $permission->slug = Str::slug('Customer');
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Finance';
        $permission->slug = Str::slug('Finance');
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Message';
        $permission->slug = Str::slug('Message');
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Setting';
        $permission->slug = Str::slug('Setting');
        $permission->created_by = 1;
        $permission->save();

        //////////////////////////////////////////
        $permission = new Permission();
        $permission->name = 'View Dashboard';
        $permission->slug = Str::slug('View Dashboard');
        $permission->parent_id = 1;
        $permission->created_by = 1;
        $permission->save();
        //----------Dashboard---Id-1--------------------

        $permission = new Permission();
        $permission->name = 'View Product List';
        $permission->slug = Str::slug('View Product List');
        $permission->parent_id = 2;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Product';
        $permission->slug = Str::slug('Create Product');
        $permission->parent_id = 2;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Product';
        $permission->slug = Str::slug('View Product');
        $permission->parent_id = 2;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Product';
        $permission->slug = Str::slug('Edit Product');
        $permission->parent_id = 2;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Disable Product';
        $permission->slug = Str::slug('Disable Product');
        $permission->parent_id = 2;
        $permission->created_by = 1;
        $permission->save();
        //----------Product---Id-2--------------------

        $permission = new Permission();
        $permission->name = 'View Order List';
        $permission->slug = Str::slug('View Order List');
        $permission->parent_id = 4;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Order';
        $permission->slug = Str::slug('View Order');
        $permission->parent_id = 4;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Update Order Status';
        $permission->slug = Str::slug('Update Order Status');
        $permission->parent_id = 4;
        $permission->created_by = 1;
        $permission->save();
        //----------Order---Id-4--------------------

        $permission = new Permission();
        $permission->name = 'View Form List';
        $permission->slug = Str::slug('View Form List');
        $permission->parent_id = 3;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Form';
        $permission->slug = Str::slug('Create Form');
        $permission->parent_id = 3;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Form';
        $permission->slug = Str::slug('View Form');
        $permission->parent_id = 3;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Form';
        $permission->slug = Str::slug('Edit Form');
        $permission->parent_id = 3;
        $permission->created_by = 1;
        $permission->save();
        //----------Form Builder---Id-3--------------------

        $permission = new Permission();
        $permission->name = 'View Warehouse List';
        $permission->slug = Str::slug('View Warehouse List');
        $permission->parent_id = 5;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Warehouse';
        $permission->slug = Str::slug('View Warehouse');
        $permission->parent_id = 5;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Warehouse';
        $permission->slug = Str::slug('Create Warehouse');
        $permission->parent_id = 5;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Warehouse';
        $permission->slug = Str::slug('Edit Warehouse');
        $permission->parent_id = 5;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Disable Warehouse';
        $permission->slug = Str::slug('Disable Warehouse');
        $permission->parent_id = 5;
        $permission->created_by = 1;
        $permission->save();
        //----------Warehouse---Id-5--------------------

        
        $permission = new Permission();
        $permission->name = 'View Purchase List';
        $permission->slug = Str::slug('View Purchase List');
        $permission->parent_id = 6;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Purchase';
        $permission->slug = Str::slug('View Purchase');
        $permission->parent_id = 6;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Purchase';
        $permission->slug = Str::slug('Create Purchase');
        $permission->parent_id = 6;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Purchase';
        $permission->slug = Str::slug('Edit Purchase');
        $permission->parent_id = 6;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Disable Purchase';
        $permission->slug = Str::slug('Disable Purchase');
        $permission->parent_id = 6;
        $permission->created_by = 1;
        $permission->save();
        //----------Purchase---Id-6--------------------

        $permission = new Permission();
        $permission->name = 'View Sale List';
        $permission->slug = Str::slug('View Sale List');
        $permission->parent_id = 7;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Sale';
        $permission->slug = Str::slug('View Sale');
        $permission->parent_id = 7;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Sale';
        $permission->slug = Str::slug('Create Sale');
        $permission->parent_id = 7;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Sale';
        $permission->slug = Str::slug('Edit Sale');
        $permission->parent_id = 7;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Disable Sale';
        $permission->slug = Str::slug('Edit Sale');
        $permission->parent_id = 7;
        $permission->created_by = 1;
        $permission->save();


    }
}
