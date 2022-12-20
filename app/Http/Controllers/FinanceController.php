<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\GeneralSetting;

class FinanceController extends Controller
{
    
    public function incomeStatement()
    {
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;

        $start_date_info = '';
        $end_date_info = '';

        $start_date = '';
        $end_date = '';
        $sales_sum = Sale::sum('amount_paid');
        $purchase_sum = Purchase::sum('amount_paid');
        $expense_sum = Expense::sum('amount');

        $total_expenses = $purchase_sum + $expense_sum;
        $net_profit = $sales_sum - $total_expenses;

        $expenses = Expense::orderBy('id', 'desc')->get();
        // $expenses_by_category = Expense::select(DB::raw('product_id, sum(product_qty_sold) as sold_qty'))->whereDate('created_at', '>=' , date("Y").'-01-01')
        // ->whereDate('created_at', '<=' , date("Y").'-12-31')->groupBy('product_id')->orderBy('sold_qty', 'desc')->take(5)->get();
        $expenses_by_category = Expense::select(DB::raw('expense_category_id, sum(amount) as amount_spent'))->groupBy('expense_category_id')
        ->get();

        return view('pages.finance.incomeStatement', compact('currency', 'start_date_info', 'end_date_info', 'start_date', 'end_date', 'sales_sum', 'purchase_sum', 'expense_sum', 'expenses_by_category', 'total_expenses',
        'net_profit'));
    }

    public function incomeStatementQuery(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;

        $data = $request->all();

        $start_date = strtotime($data['start_date']);
        $end_date = strtotime($data['end_date']);

        if ($start_date > $end_date) {
            return back()->with('error', 'Start Date Cannot be greater than End Date');
        }
        $start_date_info = date('jS M Y',$start_date); //18th Dec 2022
        $end_date_info = date('jS M Y',$end_date); //18th Dec 2022

        $datediff = $end_date - $start_date;

        $daysDiff = round($datediff / (60 * 60 * 24));

        $start_date = date('Y-m-d',$start_date);
        $end_date = date('Y-m-d',$end_date);

        $sales_sum = Sale::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->sum('amount_paid');
        $purchase_sum = Purchase::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->sum('amount_paid');
        $expense_sum = Expense::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->sum('amount');

        $total_expenses = $purchase_sum + $expense_sum;
        $net_profit = $sales_sum - $total_expenses;

        $expenses = Expense::orderBy('id', 'desc')->get();
        // $expenses_by_category = Expense::select(DB::raw('product_id, sum(product_qty_sold) as sold_qty'))->whereDate('created_at', '>=' , date("Y").'-01-01')
        // ->whereDate('created_at', '<=' , date("Y").'-12-31')->groupBy('product_id')->orderBy('sold_qty', 'desc')->take(5)->get();
        $expenses_by_category = Expense::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->select(DB::raw('expense_category_id, sum(amount) as amount_spent'))->groupBy('expense_category_id')
        ->get();
        

        return view('pages.finance.incomeStatement', compact('currency', 'start_date_info', 'end_date_info', 'daysDiff', 'start_date', 'end_date', 'sales_sum', 'purchase_sum', 'expense_sum', 'expenses_by_category', 'total_expenses',
        'net_profit'));
        
    }

    public function purchaseRevenue()
    {
        $purchases = Purchase::all();
        return view('pages.finance.purchaseRevenue', compact('purchases'));
    }

    //
    public function saleRevenue()
    {
        $sales = Sale::all();
        return view('pages.finance.saleRevenue', compact('sales'));
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
