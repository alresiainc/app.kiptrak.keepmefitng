<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\WareHouse;
use App\Models\Expense;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Customer;


class InventoryController extends Controller
{
    
    public function inventoryDashboard()
    {
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;

        $total_products = Product::all();

        $out_of_stock_products = [];
        foreach ($total_products as $key => $product) {
            if ($product->stock_available() < 10) {
                $out_of_stock_products[] = $product;
            } 
        }

        $warehouses = WareHouse::all();

        $sale_revenue = $this->shorten(Sale::sum('amount_paid'));

        $sales_sum = Sale::sum('amount_paid');
        $purchase_sum = Purchase::sum('amount_paid');
        $expense_sum = Expense::sum('amount');

        $total_expenses = $this->shorten($purchase_sum + $expense_sum);

        $profit_val = $sales_sum - ($purchase_sum + $expense_sum);

        if ($profit_val > 0) {
            $profit = $this->shorten($profit_val);
        } else {
            $profit = $this->shorten($profit_val);
        }

        $orders = Order::all();

        $suppliers = Supplier::all();

        $purchase_sum = $this->shorten($purchase_sum);
        $sales_sum = $this->shorten($sales_sum);

        $customers = Customer::all();

        $recently_products = Product::take(5)->get();
        
        return view('pages.inventory.inventory', \compact('currency', 'total_products', 'out_of_stock_products', 'warehouses', 'sale_revenue', 'total_expenses',
        'profit', 'profit_val', 'orders', 'suppliers', 'purchase_sum', 'customers', 'sales_sum', 'recently_products'));
    }


    public function shorten($num, $digits = 1) {
        $num = preg_replace('/[^0-9]/','',$num);
        if ($num >= 1000000000) {
            $num = number_format(abs($num / 1000000000), $digits, '.', '') + 0;
            $num = $num . "b";
        }
        if ($num >= 1000000) {
            $num = number_format(abs($num / 1000000), $digits, '.', '') + 0;
            $num = $num . 'm';
        }
        if ($num >= 1000) {
            $num = number_format(abs($num / 1000), $digits, '.', '') + 0;
            $num = $num . 'k';
        }
        return $num;
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
