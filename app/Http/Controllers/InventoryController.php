<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\WareHouse;
use App\Models\Expense;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\ProductWarehouse;


class InventoryController extends Controller
{
    
    public function inventoryDashboard()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'all';

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
        
        return view('pages.inventory.inventory', \compact('authUser', 'user_role', 'record', 'currency', 'total_products', 'out_of_stock_products', 'warehouses', 'sale_revenue', 'total_expenses',
        'profit', 'profit_val', 'orders', 'suppliers', 'purchase_sum', 'customers', 'sales_sum', 'recently_products'));
    }

    //today
    public function inventoryDashboardToday()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'today';
        /////////////////////////////////////////////////////
        $dt = Carbon::now();

        $total_products = Product::all();

        $out_of_stock_products = [];
        foreach ($total_products as $key => $product) {
            if ($product->stock_available() < 10) {
                $out_of_stock_products[] = $product;
            } 
        }

        $warehouses = WareHouse::all();

        $sales_sum = Sale::whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->sum('amount_paid'); 
        $sale_revenue = $this->shorten($sales_sum);
        
        $purchase_sum = Purchase::whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->sum('amount_paid');
        $expense_sum = Expense::whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->sum('amount');

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
        
        return view('pages.inventory.inventory', \compact('authUser', 'user_role', 'record', 'currency', 'total_products', 'out_of_stock_products', 'warehouses', 'sale_revenue', 'total_expenses',
        'profit', 'profit_val', 'orders', 'suppliers', 'purchase_sum', 'customers', 'sales_sum', 'recently_products'));
    }

    //weekly
    public function inventoryDashboardWeekly()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'weekly';
        /////////////////////////////////////////////////////
        $dt = Carbon::now();

        $total_products = Product::all();

        $out_of_stock_products = [];
        foreach ($total_products as $key => $product) {
            if ($product->stock_available() < 10) {
                $out_of_stock_products[] = $product;
            } 
        }

        $warehouses = WareHouse::all();

        $sales_sum = Sale::whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->sum('amount_paid'); 
        $sale_revenue = $this->shorten($sales_sum);
        
        $purchase_sum = Purchase::whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->sum('amount_paid');
        $expense_sum = Expense::whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->sum('amount');

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
        
        return view('pages.inventory.inventory', \compact('authUser', 'user_role', 'record', 'currency', 'total_products', 'out_of_stock_products', 'warehouses', 'sale_revenue', 'total_expenses',
        'profit', 'profit_val', 'orders', 'suppliers', 'purchase_sum', 'customers', 'sales_sum', 'recently_products'));
    }

    //monthly
    public function inventoryDashboardMonthly()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'monthly';
        /////////////////////////////////////////////////////
        $dt = Carbon::now();

        $total_products = Product::all();

        $out_of_stock_products = [];
        foreach ($total_products as $key => $product) {
            if ($product->stock_available() < 10) {
                $out_of_stock_products[] = $product;
            } 
        }

        $warehouses = WareHouse::all();

        $sales_sum = Sale::whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->sum('amount_paid'); 
        $sale_revenue = $this->shorten($sales_sum);
        
        $purchase_sum = Purchase::whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->sum('amount_paid');
        $expense_sum = Expense::whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->sum('amount');

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
        
        return view('pages.inventory.inventory', \compact('authUser', 'user_role', 'record', 'currency', 'total_products', 'out_of_stock_products', 'warehouses', 'sale_revenue', 'total_expenses',
        'profit', 'profit_val', 'orders', 'suppliers', 'purchase_sum', 'customers', 'sales_sum', 'recently_products'));
    }

    //yearly
    public function inventoryDashboardYearly()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'yearly';
        /////////////////////////////////////////////////////
        $dt = Carbon::now();

        $total_products = Product::all();

        $out_of_stock_products = [];
        foreach ($total_products as $key => $product) {
            if ($product->stock_available() < 10) {
                $out_of_stock_products[] = $product;
            } 
        }

        $warehouses = WareHouse::all();

        $sales_sum = Sale::whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()])->sum('amount_paid'); 
        $sale_revenue = $this->shorten($sales_sum);
        
        $purchase_sum = Purchase::whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()])->sum('amount_paid');
        $expense_sum = Expense::whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()])->sum('amount');

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
        
        return view('pages.inventory.inventory', \compact('authUser', 'user_role', 'record', 'currency', 'total_products', 'out_of_stock_products', 'warehouses', 'sale_revenue', 'total_expenses',
        'profit', 'profit_val', 'orders', 'suppliers', 'purchase_sum', 'customers', 'sales_sum', 'recently_products'));
    }

    //by major warehouse
    public function inStockProductsByWarehouse()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $products = Product::all();
        $pro = Product::find(1);
        // return $pro->warehouses->where('type','minor')->count();
        // $in_stock_products = [];
        foreach ($products as $key => $product) {
            $product_warehouse = ProductWarehouse::where('product_id',$product->id);
            //if ($product_warehouse->exists()) {
            $warehouses = $product->warehouses->where('type','major');
            if ($warehouses->count() > 0) {
                if ($product->stock_available() > 10) {
                    $in_stock_products[] = $product;
                }
            }
            
            // $warehouse_id = $product_warehouse->first()->warehouse_id;
            // $warehouse = WareHouse::where('id',$warehouse_id)->first();
            // if ($warehouse->type=='major') {
            //     if ($product->stock_available() > 10) {
            //         $in_stock_products[] = $product;
            //     }
            // }
            //} 
        }

        $warehouses = WareHouse::where('type','major')->get();
        $start_date = '';
        $end_date = '';
        $warehouse_selected = '';
        
        return view('pages.inventory.inStockProductsByWarehouse', \compact('authUser', 'user_role', 'products', 'in_stock_products', 'warehouses', 'start_date', 'end_date', 'warehouse_selected'));
    }

    public function inStockProductsByWarehouseQuery(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();

        $start_date = '';
        $end_date = '';
        $warehouse_selected = '';

        //1st instance
        if (!empty($data['warehouse_id']) && empty($data['start_date']) && empty($data['end_date'])) {
            $warehouse_selected = WareHouse::find($data['warehouse_id']);
            $products = $warehouse_selected->products;
        }

        //2nd instance
        if (empty($data['warehouse_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);

            if ($start_date > $end_date) {
                return back()->with('error', 'Start Date Cannot be greater than End Date');
            }

            $start_date = date('Y-m-d',$start_date);
            $end_date = date('Y-m-d',$end_date);

            // $products = Product::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->get();
            $products1 = Product::all();

            $in_stock_products = []; $products = [];
            foreach ($products1 as $key => $product) {
                //using dates n duplicates check
               $product_warehouses = ProductWarehouse::select(DB::raw('product_id, warehouse_type'))->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])
               ->groupBy('product_id', 'warehouse_type')->get();
                if ($product_warehouses->contains('warehouse_type','major')) {
                    if ($product->stock_available() > 10) {
                        $products[] = $product;
                    }
                }
                
            }
           // return var_dump($in_stock_products);
        }

        //3rd instance
        if (!empty($data['warehouse_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);
            $warehouse_selected = WareHouse::find($data['warehouse_id']);

            if ($start_date > $end_date) {
                return back()->with('error', 'Start Date Cannot be greater than End Date');
            }

            $start_date = date('Y-m-d',$start_date);
            $end_date = date('Y-m-d',$end_date);

            $products1 = Product::all();

            $in_stock_products = []; $products = [];
            foreach ($products1 as $key => $product) {
                //using dates n duplicates check
               $product_warehouses = ProductWarehouse::where('warehouse_id', $data['warehouse_id'])->select(DB::raw('product_id, warehouse_type'))
               ->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->groupBy('product_id', 'warehouse_type')->get();
                if ($product_warehouses->contains('warehouse_type','major')) {
                    if ($product->stock_available() > 10) {
                        $products[] = $product;
                    }
                }   
            }
        }

        $in_stock_products = [];
        foreach ($products as $key => $product) {
            
            $warehouses = $product->warehouses->where('type','major');
            if ($warehouses->count() > 0) {
                if ($product->stock_available() > 10) {
                    $in_stock_products[] = $product;
                }
            }
        }

        
        $warehouses = WareHouse::where('type','major')->get();
        return view('pages.inventory.inStockProductsByWarehouse', \compact('authUser', 'user_role', 'products', 'in_stock_products','warehouses', 'start_date', 'end_date', 'warehouse_selected'));
    }

    //by minor warehouse
    public function inStockProductsByOtherAgents()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $products = Product::all();

        $in_stock_products = [];
        foreach ($products as $key => $product) {
            $product_warehouses = $product->warehouses;
            if ($product_warehouses->contains('type','minor')) {
                if ($product->stock_available() > 10) {
                    $in_stock_products[] = $product;
                }
            }
            
        }

        $warehouses = WareHouse::where('type','minor')->get();
        $start_date = '';
        $end_date = '';
        $warehouse_selected = '';
        
        return view('pages.inventory.inStockProductsByOtherAgents', \compact('authUser', 'user_role', 'products', 'in_stock_products', 'warehouses', 'start_date', 'end_date', 'warehouse_selected'));
    }

    public function inStockProductsByOtherAgentsQuery(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();

        $start_date = '';
        $end_date = '';
        $warehouse_selected = '';

        //1st instance
        if (!empty($data['warehouse_id']) && empty($data['start_date']) && empty($data['end_date'])) {
            $warehouse_selected = WareHouse::find($data['warehouse_id']);
            $products = $warehouse_selected->products;
        }

        //2nd instance
        if (empty($data['warehouse_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);

            if ($start_date > $end_date) {
                return back()->with('error', 'Start Date Cannot be greater than End Date');
            }

            $start_date = date('Y-m-d',$start_date);
            $end_date = date('Y-m-d',$end_date);

            // $products = Product::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->get();
            $products1 = Product::all();

            $in_stock_products = []; $products = [];
            foreach ($products1 as $key => $product) {
                //using dates n duplicates check
               $product_warehouses = ProductWarehouse::select(DB::raw('product_id, warehouse_type'))->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])
               ->groupBy('product_id', 'warehouse_type')->get();
                if ($product_warehouses->contains('warehouse_type','minor')) {
                    if ($product->stock_available() > 10) {
                        $products[] = $product;
                    }
                }
                
            }
           // return var_dump($in_stock_products);
        }

        //3rd instance
        if (!empty($data['warehouse_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);
            $warehouse_selected = WareHouse::find($data['warehouse_id']);

            if ($start_date > $end_date) {
                return back()->with('error', 'Start Date Cannot be greater than End Date');
            }

            $start_date = date('Y-m-d',$start_date);
            $end_date = date('Y-m-d',$end_date);

            $products1 = Product::all();

            $in_stock_products = []; $products = [];
            foreach ($products1 as $key => $product) {
                //using dates n duplicates check
               $product_warehouses = ProductWarehouse::where('warehouse_id', $data['warehouse_id'])->select(DB::raw('product_id, warehouse_type'))
               ->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->groupBy('product_id', 'warehouse_type')->get();
                if ($product_warehouses->contains('warehouse_type','minor')) {
                    if ($product->stock_available() > 10) {
                        $products[] = $product;
                    }
                }   
            }
        }

        $in_stock_products = [];
        foreach ($products as $key => $product) {
            $product_warehouse = ProductWarehouse::where('product_id',$product->id);
            $product_warehouses = $product->warehouses;
            if ($product_warehouses->contains('type','minor')) {
                if ($product->stock_available() > 10) {
                    $in_stock_products[] = $product;
                }
            }  
        }

        $warehouses = WareHouse::where('type','minor')->get();
        return view('pages.inventory.inStockProductsByOtherAgents', \compact('authUser', 'user_role', 'products', 'in_stock_products','warehouses', 'start_date', 'end_date', 'warehouse_selected'));
    }

    public function allProductInventory()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $products = Product::all();
        return view('pages.inventory.allProductInventory', compact('authUser', 'user_role', 'products'));
    }

    public function singleProductSales($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $product = Product::where('unique_key', $unique_key)->first();
        $sales = Sale::where('product_id', $product->id)->get();
        return view('pages.inventory.singleProductSales', compact('authUser', 'user_role', 'product', 'sales'));
    }

    public function singleProductPurchases($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $product = Product::where('unique_key', $unique_key)->first();
        $purchases = Purchase::where('product_id', $product->id)->orderBy('id', 'ASC')->get();
        return view('pages.inventory.singleProductPurchases', compact('authUser', 'user_role', 'product', 'purchases'));
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        //
    }
}
