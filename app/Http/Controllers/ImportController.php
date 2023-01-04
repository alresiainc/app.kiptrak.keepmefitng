<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PersonsImport;
use App\Imports\UsersImport;
use App\Imports\EmployeesImport;
use App\Imports\SuppliersImport;
use App\Imports\ProductsImport;
use App\Imports\WarehousesImport;
use App\Imports\AgentsImport;
use App\Imports\CustomersImport;
use App\Models\User;


class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function personsImport(Request $request)
    {
        Excel::import(new PersonsImport, $request->file);
        return 'imported';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function usersImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx|max:2048',
        ]);
        Excel::import(new UsersImport, $request->file);
        return 'imported';
    }

    public function employeesImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx|max:2048',
        ]);
        Excel::import(new EmployeesImport, $request->file);
        return back()->with('success', 'Employees Imported Successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function suppliersImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx|max:2048',
        ]);
        Excel::import(new SuppliersImport, $request->file);
        return back()->with('success', 'Suppliers Imported Successfully');
    }

    public function productsImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx|max:2048',
        ]);
        Excel::import(new ProductsImport, $request->file);
        return back()->with('success', 'Products Imported Successfully');
    }

    public function warehousesImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx|max:2048',
        ]);
        Excel::import(new WarehousesImport, $request->file);
        return back()->with('success', 'Warehouses Imported Successfully');
    }

    public function agentsImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx|max:2048',
        ]);
        Excel::import(new AgentsImport, $request->file);
        return back()->with('success', 'Agents Imported Successfully');
    }

    public function customersImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx|max:2048',
        ]);
        Excel::import(new CustomersImport, $request->file);
        return back()->with('success', 'Customers Imported Successfully');
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
