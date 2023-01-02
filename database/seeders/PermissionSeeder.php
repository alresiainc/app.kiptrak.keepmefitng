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
        
        //----------Expense---Id-8------------------------
        $permission = new Permission();
        $permission->name = 'Expense';
        $permission->slug = Str::slug('Expense');
        $permission->created_by = 1;
        $permission->save();

        //----------HRM---Id-9------------------------
        $permission = new Permission();
        $permission->name = 'HRM';
        $permission->slug = Str::slug('HRM');
        $permission->created_by = 1;
        $permission->save();

        //----------Employee---Id-10------------------------
        $permission = new Permission();
        $permission->name = 'Employee';
        $permission->slug = Str::slug('Employee');
        $permission->created_by = 1;
        $permission->save();

        //----------Attendance---Id-11------------------------
        $permission = new Permission();
        $permission->name = 'Attendance';
        $permission->slug = Str::slug('Attendance');
        $permission->created_by = 1;
        $permission->save();

        //----------Payroll---Id-12--------------------------
        $permission = new Permission();
        $permission->name = 'Payroll';
        $permission->slug = Str::slug('Payroll');
        $permission->created_by = 1;
        $permission->save();

        //----------Supplier---Id-13--------------------------
        $permission = new Permission();
        $permission->name = 'Supplier';
        $permission->slug = Str::slug('Supplier');
        $permission->created_by = 1;
        $permission->save();

        //----------Agent---Id-14--------------------------
        $permission = new Permission();
        $permission->name = 'Agent';
        $permission->slug = Str::slug('Agent');
        $permission->created_by = 1;
        $permission->save();

        //----------Agent---Id-15--------------------------
        $permission = new Permission();
        $permission->name = 'Customer';
        $permission->slug = Str::slug('Customer');
        $permission->created_by = 1;
        $permission->save();

        //----------Agent---Id-16--------------------------
        $permission = new Permission();
        $permission->name = 'Finance';
        $permission->slug = Str::slug('Finance');
        $permission->created_by = 1;
        $permission->save();

        //----------Agent---Id-17--------------------------
        $permission = new Permission();
        $permission->name = 'Message';
        $permission->slug = Str::slug('Message');
        $permission->created_by = 1;
        $permission->save();

        //----------Agent---Id-18--------------------------
        $permission = new Permission();
        $permission->name = 'Setting';
        $permission->slug = Str::slug('Setting');
        $permission->created_by = 1;
        $permission->save();

        //----------Inventory---Id-19--------------------
        $permission = new Permission();
        $permission->name = 'Inventory';
        $permission->slug = Str::slug('Inventory');
        $permission->created_by = 1;
        $permission->save();

        //----------Accounting---Id-20--------------------
        $permission = new Permission();
        $permission->name = 'Accounting';
        $permission->slug = Str::slug('Accounting');
        $permission->created_by = 1;
        $permission->save();

        //----------Accounting---Id-21--------------------
        $permission = new Permission();
        $permission->name = 'Report';
        $permission->slug = Str::slug('Report');
        $permission->created_by = 1;
        $permission->save();

        //////////////////////////////////////////////////////////////////////////////////////////////////
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

        //----------Expense---Id-8------------------------
        $permission = new Permission();
        $permission->name = 'View Expense List';
        $permission->slug = Str::slug('View Expense List');
        $permission->parent_id = 8;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Expense';
        $permission->slug = Str::slug('View Expense');
        $permission->parent_id = 8;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Expense';
        $permission->slug = Str::slug('Create Expense');
        $permission->parent_id = 8;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Expense';
        $permission->slug = Str::slug('Create Expense');
        $permission->parent_id = 8;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Disable Expense';
        $permission->slug = Str::slug('Disable Expense');
        $permission->parent_id = 8;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Expense Category List';
        $permission->slug = Str::slug('View Expense Category List');
        $permission->parent_id = 8;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Expense Category';
        $permission->slug = Str::slug('View Expense Category');
        $permission->parent_id = 8;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Expense Category';
        $permission->slug = Str::slug('Create Expense Category');
        $permission->parent_id = 8;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Expense Category';
        $permission->slug = Str::slug('Edit Expense Category');
        $permission->parent_id = 8;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Disable Expense Category';
        $permission->slug = Str::slug('Disable Expense Category');
        $permission->parent_id = 8;
        $permission->created_by = 1;
        $permission->save();

        //----------HRM---Id-9-----------------------------
        $permission = new Permission();
        $permission->name = 'View HRM Menu';
        $permission->slug = Str::slug('View HRM Menu');
        $permission->parent_id = 9;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Role List';
        $permission->slug = Str::slug('View Role List');
        $permission->parent_id = 9;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Role';
        $permission->slug = Str::slug('Create Role');
        $permission->parent_id = 9;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Role';
        $permission->slug = Str::slug('Edit Role');
        $permission->parent_id = 9;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Disable Role';
        $permission->slug = Str::slug('Disable Role');
        $permission->parent_id = 9;
        $permission->created_by = 1;
        $permission->save();

        //----------Employee---Id-10-----------------------------
        $permission = new Permission();
        $permission->name = 'View Employee List';
        $permission->slug = Str::slug('View Employee List');
        $permission->parent_id = 10;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Employee';
        $permission->slug = Str::slug('View Employee');
        $permission->parent_id = 10;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Employee';
        $permission->slug = Str::slug('Create Employee');
        $permission->parent_id = 10;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Employee';
        $permission->slug = Str::slug('Edit Employee');
        $permission->parent_id = 10;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Disable Employee';
        $permission->slug = Str::slug('Disable Employee');
        $permission->parent_id = 10;
        $permission->created_by = 1;
        $permission->save();

        //----------Attendance---Id-11----------------------------
        $permission = new Permission();
        $permission->name = 'View Attendance List';
        $permission->slug = Str::slug('View Attendance List');
        $permission->parent_id = 11;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Attendance';
        $permission->slug = Str::slug('View Attendance');
        $permission->parent_id = 11;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Attendance';
        $permission->slug = Str::slug('Edit Attendance');
        $permission->parent_id = 11;
        $permission->created_by = 1;
        $permission->save();

        //----------Payroll---Id-12----------------------------
        $permission = new Permission();
        $permission->name = 'View Payroll List';
        $permission->slug = Str::slug('View Payroll List');
        $permission->parent_id = 12;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Payroll';
        $permission->slug = Str::slug('View Payroll');
        $permission->parent_id = 12;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Payroll';
        $permission->slug = Str::slug('Create Payroll');
        $permission->parent_id = 12;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Payroll';
        $permission->slug = Str::slug('Edit Payroll');
        $permission->parent_id = 12;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Disable Payroll';
        $permission->slug = Str::slug('Disable Payroll');
        $permission->parent_id = 12;
        $permission->created_by = 1;
        $permission->save();

        //----------Supplier---Id-13--------------------------
        $permission = new Permission();
        $permission->name = 'View Supplier List';
        $permission->slug = Str::slug('View Supplier List');
        $permission->parent_id = 13;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Supplier';
        $permission->slug = Str::slug('View Supplier');
        $permission->parent_id = 13;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Supplier';
        $permission->slug = Str::slug('Create Supplier');
        $permission->parent_id = 13;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Supplier';
        $permission->slug = Str::slug('Edit Supplier');
        $permission->parent_id = 13;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Disable Supplier';
        $permission->slug = Str::slug('Disable Supplier');
        $permission->parent_id = 13;
        $permission->created_by = 1;
        $permission->save();

        //----------Agent---Id-14--------------------------
        $permission = new Permission();
        $permission->name = 'View Agent List';
        $permission->slug = Str::slug('View Agent List');
        $permission->parent_id = 14;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Agent';
        $permission->slug = Str::slug('View Agent');
        $permission->parent_id = 14;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Agent';
        $permission->slug = Str::slug('Create Agent');
        $permission->parent_id = 14;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Agent';
        $permission->slug = Str::slug('Edit Agent');
        $permission->parent_id = 14;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Disable Agent';
        $permission->slug = Str::slug('Disable Agent');
        $permission->parent_id = 14;
        $permission->created_by = 1;
        $permission->save();

        //----------Customer---Id-15--------------------------
        $permission = new Permission();
        $permission->name = 'View Customer List';
        $permission->slug = Str::slug('View Customer List');
        $permission->parent_id = 15;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Customer';
        $permission->slug = Str::slug('View Customer');
        $permission->parent_id = 15;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Customer';
        $permission->slug = Str::slug('Create Customer');
        $permission->parent_id = 15;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Customer';
        $permission->slug = Str::slug('Edit Customer');
        $permission->parent_id = 15;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Disable Customer';
        $permission->slug = Str::slug('Disable Customer');
        $permission->parent_id = 15;
        $permission->created_by = 1;
        $permission->save();

        //----------Finance---Id-16--------------------------
        $permission = new Permission();
        $permission->name = 'View Finance Module';
        $permission->slug = Str::slug('View Finance Module');
        $permission->parent_id = 16;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Purchase Revenue';
        $permission->slug = Str::slug('View Purchase Revenue');
        $permission->parent_id = 16;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Sale Revenue';
        $permission->slug = Str::slug('View Sale Revenue');
        $permission->parent_id = 16;
        $permission->created_by = 1;
        $permission->save();

        //----------Finance---Id-17--------------------------
        $permission = new Permission();
        $permission->name = 'View Message Menu';
        $permission->slug = Str::slug('View Message Menu');
        $permission->parent_id = 17;
        $permission->created_by = 1;
        $permission->save();

        //----------Finance---Id-18--------------------------
        $permission = new Permission();
        $permission->name = 'View Setting Menu';
        $permission->slug = Str::slug('View Setting Menu');
        $permission->parent_id = 18;
        $permission->created_by = 1;
        $permission->save();

        //----------Inventory---Id-19--------------------------
        $permission = new Permission();
        $permission->name = 'View Inventory Dashboard';
        $permission->slug = Str::slug('View Inventory Dashboard');
        $permission->parent_id = 19;
        $permission->created_by = 1;
        $permission->save();

        //----------Accounting---Id-20--------------------
        $permission = new Permission();
        $permission->name = 'View Accounting Menu';
        $permission->slug = Str::slug('View Accounting Menu');
        $permission->parent_id = 20;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Income Statement';
        $permission->slug = Str::slug('View Income Statement');
        $permission->parent_id = 20;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Purchase Revenue';
        $permission->slug = Str::slug('View Purchase Revenue');
        $permission->parent_id = 20;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Sale Revenue';
        $permission->slug = Str::slug('View Sale Revenue');
        $permission->parent_id = 20;
        $permission->created_by = 1;
        $permission->save();

        //----------Report---Id-21--------------------
        $permission = new Permission();
        $permission->name = 'View Report Menu';
        $permission->slug = Str::slug('View Report Menu');
        $permission->parent_id = 21;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Product Report';
        $permission->slug = Str::slug('View Product Report');
        $permission->parent_id = 21;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Sale Report';
        $permission->slug = Str::slug('View Sale Report');
        $permission->parent_id = 21;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Purchase Report';
        $permission->slug = Str::slug('View Purchase Report');
        $permission->parent_id = 21;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Customer Report';
        $permission->slug = Str::slug('View Customer Report');
        $permission->parent_id = 21;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Supplier Report';
        $permission->slug = Str::slug('View Supplier Report');
        $permission->parent_id = 21;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Staff Report';
        $permission->slug = Str::slug('View Staff Report');
        $permission->parent_id = 21;
        $permission->created_by = 1;
        $permission->save();

        //----------productcategory---Id-2--------------------
        $permission = new Permission();
        $permission->name = 'View Product Category List';
        $permission->slug = Str::slug('View Product Category List');
        $permission->parent_id = 2;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Product Category';
        $permission->slug = Str::slug('View Product Category');
        $permission->parent_id = 2;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Product Category';
        $permission->slug = Str::slug('View Product Category');
        $permission->parent_id = 2;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Product Category';
        $permission->slug = Str::slug('Edit Product Category');
        $permission->parent_id = 2;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Disable Product Category';
        $permission->slug = Str::slug('Disable Product Category');
        $permission->parent_id = 2;
        $permission->created_by = 1;
        $permission->save();

    }
}
