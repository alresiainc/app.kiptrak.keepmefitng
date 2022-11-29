<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Akaunting\Apexcharts\Chart;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Customer;
use App\Models\Supplier;



class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $chart = (new Chart)->setType('donut')
            ->setWidth('100%')
            ->setHeight(300)
            ->setLabels(['Sales', 'Deposit'])
            ->setDataset('Income by Category', 'donut', [1907, 1923]); //not used

        $purchases_due = Purchase::sum('amount_due');
        $sales_due = Sale::sum('amount_due');
        $sales_paid = Sale::sum('amount_paid');
        $expenses = Expense::sum('amount');

        $customers_count = Customer::count();
        $suppliers_count = Supplier::count();
        $purchases_count = Purchase::count();
        $salesInvoice = Sale::where('parent_id', null)->count();
        $purchasesInvoice = Purchase::where('parent_id', null)->count();

        $invoices_count = $salesInvoice + $purchasesInvoice;

        return view('pages.dashboard', compact('chart', 'purchases_due', 'sales_due', 'sales_paid', 'expenses',
        'customers_count', 'suppliers_count', 'purchases_count', 'invoices_count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
