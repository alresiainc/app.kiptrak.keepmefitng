<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Exports\SampleUsersExport;

use App\Exports\SuppliersExport;
use App\Exports\SuppliersSampleExport;

use App\Exports\ProductsSampleExport;
use App\Exports\ProductsExport;

use App\Exports\WarehousesSampleExport;
use App\Exports\WarehousesExport;

use App\Exports\PurchasesExport;
use App\Exports\SalesExport;

use App\Exports\AgentsSampleExport;
use App\Exports\AgentsExport;

use App\Exports\customersSampleExport;
use App\Exports\customersExport;

use App\Models\User;


class ExportController extends Controller
{
    //employees
    public function sampleUsersExport()
    {
        return Excel::download(new SampleUsersExport, 'sample_staff.xlsx');
    }
    public function usersExport()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    //suppliers
    public function suppliersSampleExport()
    {
        return Excel::download(new SuppliersSampleExport, 'suppliers_sample.xlsx');
    }
    public function suppliersExport()
    {
        return Excel::download(new SuppliersExport, 'suppliers.xlsx');
    }  //
    
    //products
    public function productsSampleExport()
    {
        return Excel::download(new ProductsSampleExport, 'products_sample.xlsx');
    }
    public function productsExport()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    //warehouses
    public function warehousesSampleExport()
    {
        return Excel::download(new WarehousesSampleExport, 'warehouses_sample.xlsx');
    }
    public function warehousesExport()
    {
        return Excel::download(new WarehousesExport, 'warehouses.xlsx');
    }

    //purchases
    public function purchasesExport()
    {
        return Excel::download(new PurchasesExport, 'purchases.xlsx');
    }

    //sales
    public function salesExport()
    {
        return Excel::download(new SalesExport, 'sales.xlsx');
    }

    //agents
    public function agentsSampleExport()
    {
        return Excel::download(new AgentsSampleExport, 'agents_sample.xlsx');
    }
    public function agentsExport()
    {
        return Excel::download(new AgentsExport, 'agents.xlsx');
    }

    //customers
    public function customersSampleExport()
    {
        return Excel::download(new CustomersSampleExport, 'customers_sample.xlsx');
    }
    public function customersExport()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
