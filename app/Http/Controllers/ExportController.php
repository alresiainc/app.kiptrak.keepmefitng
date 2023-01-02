<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Exports\SampleUsersExport;

use App\Exports\SuppliersExport;
use App\Exports\SuppliersSampleExport;
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
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
