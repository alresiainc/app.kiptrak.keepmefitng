<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\Helper;

use App\Models\Product;
use App\Models\WareHouse;
use App\Models\Sale;
use App\Models\OutgoingStock;
use App\Models\IncomingStock;
use App\Models\Purchase;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Expense;
use App\Models\ProductWarehouse;
use App\Models\GeneralSetting;
use App\Models\Payroll;
use App\Models\Category;
use App\Models\ActivityLog;
use App\Models\Order;

class ReportController extends Controller
{
    public function __construct() {
        $this->helper = new Helper(); 
    }

    public function profitLossReport()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        /////////////////////////////////////////////////////////////////
        
        $warehouses = WareHouse::all();
        $start_date = '';
        $end_date = '';
        $warehouse_selected = '';

        $today = Carbon::now();
        
        //join 'products tbl' to 'incoming_stocks tbl', whr product_id is foreignKey, then SUM the multiples of two columns, from the resulting array
        $openningStock_by_purchasePrice = DB::table('products')->whereDate('products.created_at', '<', $today)
                ->join('incoming_stocks', 'products.id', '=', 'incoming_stocks.product_id')
                //->select('table1.column1', 'table1.column2', 'table2.column3')
                ->select(DB::raw('SUM(purchase_price * quantity_added) as total'))
                ->get()[0]->total;

        // $total = DB::table('products')
        //         ->select(DB::raw('SUM(column1 * column2) as total'))
        //         ->get()[0]->total;

        $openningStock_by_salePrice = DB::table('products')->whereDate('products.created_at', '<', $today)
                ->join('incoming_stocks', 'products.id', '=', 'incoming_stocks.product_id')
                ->select(DB::raw('SUM(sale_price * quantity_added) as total'))
                ->get()[0]->total;
            
        //closingStock
        //productsAllQty minus productsSoldQty = closingStockQty
        // $openningStock_by_salePrice = DB::table('products')
        //         ->join('outgoing_stocks', 'products.id', '=', 'outgoing_stocks.product_id')
        //         ->select(DB::raw('SUM(sale_price * quantity_added) as total'))
        //         ->get()[0]->total;

        $productsLessToday = Product::whereDate('created_at', '<', $today)->get();
        $productsLessToday_SoldByPurchasePrice = 0; $productsLessToday_SoldBySalePrice = 0;
        foreach ($productsLessToday as $key=>$product) {
            if ($product->revenue() > 0) {
                $productsLessToday_SoldByPurchasePrice += $product->stock_available() * $product->purchase_price;
                $productsLessToday_SoldBySalePrice += $product->stock_available() * $product->sale_price;
            }
        }

        //actual opening stock values
        $openningStock_by_purchasePrice = $openningStock_by_purchasePrice - $productsLessToday_SoldByPurchasePrice;
        $openningStock_by_salePrice = $openningStock_by_salePrice - $productsLessToday_SoldBySalePrice;

        $purchases_amount_paid = Purchase::sum('amount_paid');
        $other_espenses = Expense::sum('amount');
        $payroll = Payroll::sum('amount');
        $total_expenses = $purchases_amount_paid + $other_espenses + $payroll;

        //closing stocks, products not sold
        $products = Product::all();
        $closingStock_by_purchasePrice = 0; $closingStock_by_salePrice = 0;
        foreach ($products as $key=>$product) {
            if ($product->revenue() == 0) {
                $closingStock_by_purchasePrice += $product->stock_available() * $product->purchase_price;
                $closingStock_by_salePrice += $product->stock_available() * $product->sale_price;
            }
        }
        //total-sales remitted
        // $sales_paid = 0;
        // $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->pluck('id');
        // $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');
        // $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        $sales_paid = $this->helper->totalSalesRevenue();

        $profit_val = $sales_paid - $total_expenses;
        
        $categories = Category::all();
        $activityLogs = ActivityLog::all();

        return view('pages.reports.profitLossReport', compact('authUser', 'user_role', 'currency', 'warehouses', 'start_date', 'end_date', 'warehouse_selected',
        'openningStock_by_purchasePrice', 'openningStock_by_salePrice', 'purchases_amount_paid', 'other_espenses', 'payroll', 'total_expenses', 'products', 'categories',
        'activityLogs', 'closingStock_by_purchasePrice', 'closingStock_by_salePrice', 'sales_paid', 'profit_val'));
    }

    public function profitLossReportAjax(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $authUser = auth()->user();
        $data = $request->all();

        //only date
        if (empty($data['warehouse_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
            $warehouse_id = "";
            //strtotime for checking
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);

            if ($start_date > $end_date) {
                $data['error'] = 'Start Date Cannot be greater than End Date';
                return response()->json([
                    'status'=>true,
                    'data'=>$data
                ]);
            }
            //date proper
            $start_date = date('Y-m-d',$start_date);
            $end_date = date('Y-m-d',$end_date);
            
            $openningStock_by_purchasePrice = DB::table('products')->whereBetween(DB::raw('DATE(products.created_at)'), [$start_date, $end_date])
                ->join('incoming_stocks', 'products.id', '=', 'incoming_stocks.product_id')
                ->select(DB::raw('SUM(purchase_price * quantity_added) as total'))
                ->get()[0]->total;

            $openningStock_by_salePrice = DB::table('products')->whereBetween(DB::raw('DATE(products.created_at)'), [$start_date, $end_date])
                ->join('incoming_stocks', 'products.id', '=', 'incoming_stocks.product_id')
                ->select(DB::raw('SUM(sale_price * quantity_added) as total'))
                ->get()[0]->total;
            
            $productsLessToday = Product::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->get();
            $productsLessToday_SoldByPurchasePrice = 0; $productsLessToday_SoldBySalePrice = 0;
            foreach ($productsLessToday as $key=>$product) {
                if ($product->revenue() > 0) {
                    $productsLessToday_SoldByPurchasePrice += $product->stock_available() * $product->purchase_price;
                    $productsLessToday_SoldBySalePrice += $product->stock_available() * $product->sale_price;
                }
            }
    
            //actual opening stock values
            $openningStock_by_purchasePrice = $openningStock_by_purchasePrice - $productsLessToday_SoldByPurchasePrice;
            $openningStock_by_salePrice = $openningStock_by_salePrice - $productsLessToday_SoldBySalePrice;

            $purchases_amount_paid = Purchase::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->sum('amount_paid');
            $other_espenses = Expense::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->sum('amount');
            $payroll = Payroll::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->sum('amount');
            $total_expenses = $purchases_amount_paid + $other_espenses + $payroll;

            //closing stocks, products not sold
            $products = Product::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->get();
            $closingStock_by_purchasePrice = 0; $closingStock_by_salePrice = 0;
            foreach ($products as $key=>$product) {
                if ($product->revenue() == 0) {
                    $closingStock_by_purchasePrice += $product->stock_available() * $product->purchase_price;
                    $closingStock_by_salePrice += $product->stock_available() * $product->sale_price;
                }
            }

            //total-sales remitted
            //$sales_paid = 0;
            $delivered_and_remitted_orders = Order::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->where('status', 'delivered_and_remitted')->pluck('id');
            // $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');
            // $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');

            $sales_paid = $this->helper->orderSalesRevenue($delivered_and_remitted_orders);
            
            $profit_val = $sales_paid - $total_expenses;

            //for datatables
            $categories = Category::all();
            $warehouse_id = "";
            foreach ($categories as $key=>$category) {
                $categories[$key]->revenue = number_format($category->revenue($start_date, $end_date, $warehouse_id));
            }

            $activityLogs = ActivityLog::all();

            $products = Product::all(); $staff_id="";
            //store filter in array
            foreach ($products as $key=>$product) {
                $products[$key]->revenue = number_format($product->revenue($start_date, $end_date, $warehouse_id));
            }

            // $allExpenses = Expense::where('staff_id', $staff_id)->get();
            // foreach ($allExpenses as $key=>$expense) {
            //     $allExpenses[$key]->category_name = $expense->category->name;
            //     $allExpenses[$key]->amount = $expense->amount;
            //     $allExpenses[$key]->staff_name = $expense->staff->name;
            // }
            
            ///////////////////////////////////////////

        }

        //warehouse, location
        if (!empty($data['warehouse_id']) && empty($data['start_date']) && empty($data['end_date'])) {
            $warehouse_id = $data['warehouse_id'];
            $start_date = "";
            $end_date = "";

            $warehouse = WareHouse::find($warehouse_id);
            $product_ids = $warehouse->products->pluck('id');
            
            $openningStock_by_purchasePrice = DB::table('products')->whereIn('products.id', $product_ids)
                ->join('incoming_stocks', 'products.id', '=', 'incoming_stocks.product_id')
                ->select(DB::raw('SUM(purchase_price * quantity_added) as total'))
                ->get()[0]->total;

            $openningStock_by_salePrice = DB::table('products')->whereIn('products.id', $product_ids)
                ->join('incoming_stocks', 'products.id', '=', 'incoming_stocks.product_id')
                ->select(DB::raw('SUM(sale_price * quantity_added) as total'))
                ->get()[0]->total;
            
            $productsLessToday = Product::whereIn('id', $product_ids)->get();
            $productsLessToday_SoldByPurchasePrice = 0; $productsLessToday_SoldBySalePrice = 0;
            foreach ($productsLessToday as $key=>$product) {
                if ($product->revenue() > 0) {
                    $productsLessToday_SoldByPurchasePrice += $product->stock_available() * $product->purchase_price;
                    $productsLessToday_SoldBySalePrice += $product->stock_available() * $product->sale_price;
                }
            }
    
            //actual opening stock values
            $openningStock_by_purchasePrice = $openningStock_by_purchasePrice - $productsLessToday_SoldByPurchasePrice;
            $openningStock_by_salePrice = $openningStock_by_salePrice - $productsLessToday_SoldBySalePrice;

            $purchases_amount_paid = Purchase::whereIn('product_id', $product_ids)->sum('amount_paid');
            $other_espenses = Expense::where('warehouse_id', $warehouse_id)->sum('amount');
            $payroll = Payroll::sum('amount');
            $total_expenses = $purchases_amount_paid + $other_espenses + $payroll;

            //closing stocks, products not sold
            $products = Product::whereIn('id', $product_ids)->get();
            $closingStock_by_purchasePrice = 0; $closingStock_by_salePrice = 0;
            foreach ($products as $key=>$product) {
                if ($product->revenue() == 0) {
                    $closingStock_by_purchasePrice += $product->stock_available() * $product->purchase_price;
                    $closingStock_by_salePrice += $product->stock_available() * $product->sale_price;
                }
            }

            //total-sales remitted
            //$sales_paid = 0;
            $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->pluck('id');
            // $accepted_outgoing_stock = OutgoingStock::whereIn('product_id', $product_ids)->whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');
            // $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
            $sales_paid = $this->helper->orderSalesRevenue($delivered_and_remitted_orders);
            
            $profit_val = $sales_paid - $total_expenses;

            //for datatables
            $categories = Category::all();
            $warehouse_id = "";
            foreach ($categories as $key=>$category) {
                $categories[$key]->revenue = number_format($category->revenue($start_date, $end_date, $warehouse_id));
            }

            $activityLogs = ActivityLog::all();

            $products = Product::all(); $staff_id="";
            //store filter in array
            foreach ($products as $key=>$product) {
                $products[$key]->revenue = number_format($product->revenue($start_date, $end_date, $warehouse_id));
            }

            // $allExpenses = Expense::where('staff_id', $staff_id)->get();
            // foreach ($allExpenses as $key=>$expense) {
            //     $allExpenses[$key]->category_name = $expense->category->name;
            //     $allExpenses[$key]->amount = $expense->amount;
            //     $allExpenses[$key]->staff_name = $expense->staff->name;
            // }
            
            ///////////////////////////////////////////

        }

        //all
        if (!empty($data['warehouse_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
            $warehouse_id = $data['warehouse_id'];
            $warehouse = WareHouse::find($warehouse_id);
            $product_ids = $warehouse->products->pluck('id');

            //strtotime for checking
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);

            if ($start_date > $end_date) {
                $data['error'] = 'Start Date Cannot be greater than End Date';
                return response()->json([
                    'status'=>true,
                    'data'=>$data
                ]);
            }
            //date proper
            $start_date = date('Y-m-d',$start_date);
            $end_date = date('Y-m-d',$end_date);
            
            $openningStock_by_purchasePrice = DB::table('products')->whereBetween(DB::raw('DATE(products.created_at)'), [$start_date, $end_date])
                ->whereIn('products.id', $product_ids)
                ->join('incoming_stocks', 'products.id', '=', 'incoming_stocks.product_id')
                ->select(DB::raw('SUM(purchase_price * quantity_added) as total'))
                ->get()[0]->total;

            $openningStock_by_salePrice = DB::table('products')->whereBetween(DB::raw('DATE(products.created_at)'), [$start_date, $end_date])
                ->whereIn('products.id', $product_ids)
                ->join('incoming_stocks', 'products.id', '=', 'incoming_stocks.product_id')
                ->select(DB::raw('SUM(sale_price * quantity_added) as total'))
                ->get()[0]->total;
            
            $productsLessToday = Product::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->whereIn('id', $product_ids)->get();
            $productsLessToday_SoldByPurchasePrice = 0; $productsLessToday_SoldBySalePrice = 0;
            foreach ($productsLessToday as $key=>$product) {
                if ($product->revenue() > 0) {
                    $productsLessToday_SoldByPurchasePrice += $product->stock_available() * $product->purchase_price;
                    $productsLessToday_SoldBySalePrice += $product->stock_available() * $product->sale_price;
                }
            }
    
            //actual opening stock values
            $openningStock_by_purchasePrice = $openningStock_by_purchasePrice - $productsLessToday_SoldByPurchasePrice;
            $openningStock_by_salePrice = $openningStock_by_salePrice - $productsLessToday_SoldBySalePrice;

            $purchases_amount_paid = Purchase::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->whereIn('product_id', $product_ids)->sum('amount_paid');
            $other_espenses = Expense::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->where('warehouse_id', $warehouse_id)->sum('amount');
            $payroll = Payroll::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->sum('amount');
            $total_expenses = $purchases_amount_paid + $other_espenses + $payroll;

            //closing stocks, products not sold
            $products = Product::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->whereIn('id', $product_ids)->get();
            $closingStock_by_purchasePrice = 0; $closingStock_by_salePrice = 0;
            foreach ($products as $key=>$product) {
                if ($product->revenue() == 0) {
                    $closingStock_by_purchasePrice += $product->stock_available() * $product->purchase_price;
                    $closingStock_by_salePrice += $product->stock_available() * $product->sale_price;
                }
            }

            //total-sales remitted
            //$sales_paid = 0;
            $delivered_and_remitted_orders = Order::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->where('status', 'delivered_and_remitted')->pluck('id');
            // $accepted_outgoing_stock = OutgoingStock::whereIn('product_id', $product_ids)->whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');
            // $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');

            $sales_paid = $this->helper->orderSalesRevenue($delivered_and_remitted_orders);
            
            $profit_val = $sales_paid - $total_expenses;

            //for datatables
            $categories = Category::all();
            $warehouse_id = "";
            foreach ($categories as $key=>$category) {
                $categories[$key]->revenue = number_format($category->revenue($start_date, $end_date, $warehouse_id));
            }

            $activityLogs = ActivityLog::all();

            $products = Product::all(); $staff_id="";
            //store filter in array
            foreach ($products as $key=>$product) {
                $products[$key]->revenue = number_format($product->revenue($start_date, $end_date, $warehouse_id));
            }

            // $allExpenses = Expense::where('staff_id', $staff_id)->get();
            // foreach ($allExpenses as $key=>$expense) {
            //     $allExpenses[$key]->category_name = $expense->category->name;
            //     $allExpenses[$key]->amount = $expense->amount;
            //     $allExpenses[$key]->staff_name = $expense->staff->name;
            // }
            
            ///////////////////////////////////////////

        }

        //store in array
        $data['openningStock_by_purchasePrice'] = number_format($openningStock_by_purchasePrice);
        $data['openningStock_by_salePrice'] = number_format($openningStock_by_salePrice);
        $data['purchases_amount_paid'] = number_format($purchases_amount_paid);
        $data['other_espenses'] = number_format($other_espenses);
        $data['payroll'] = number_format($payroll);
        $data['total_expenses'] = number_format($total_expenses);
        $data['other_espenses'] = number_format($other_espenses);
        $data['closingStock_by_purchasePrice'] = number_format($closingStock_by_purchasePrice);
        $data['closingStock_by_salePrice'] = number_format($closingStock_by_salePrice);
        $data['sales_paid'] = number_format($sales_paid);
        $data['profit'] = number_format($profit_val);
        $data['products'] = $products;
        $data['categories'] = $categories;


        //store in array
        return response()->json([
            'status'=>true,
            'data'=>$data
        ]);

        
    }

    public function activityLogReport()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        /////////////////////////////////////////////////////////////////
        
        $warehouses = WareHouse::all();
        $warehouse_selected = '';

        $activityLogs = ActivityLog::all();

        return view('pages.reports.activityLog', compact('authUser', 'user_role', 'currency', 'warehouses', 'warehouse_selected', 'activityLogs'));
    }

    public function salesRepReport($staff_unique_key="", $start_date="", $end_date="", $location="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        ////////////////////////////////////////////////////////////////
        $staffs = User::where('type', 'staff')->orderBy('id', 'DESC')->get();
        $warehouses = WareHouse::all();
        $start_date = '';
        $end_date = '';
        $warehouse_selected = '';

        //revenue
        //$sales_paid = 0;
        $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->pluck('id');
        // $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');

        // $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        $sales_paid = $this->helper->orderSalesRevenue($delivered_and_remitted_orders);
        $sales_paid = $this->shorten($sales_paid); //total revenue

        //expenses
        // $expenses = Expense::where('staff_id', $authUser->id)->sum('amount');
        $expenses = Expense::sum('amount');
        $expenses = $this->shorten($expenses);

        //products
        $products = Product::all();
        $allExpenses = Expense::all();

        // if ($staff_unique_key != "") {
        //     $staff = User::where('unique_key', $staff_unique_key)->first();
        //     //revenue
        //     $sales_paid = 0;
        //     $delivered_and_remitted_orders = Order::where('staff_assigned_id', $staff->id)->where('status', 'delivered_and_remitted')->pluck('id');
        //     $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');
        // }

        return view('pages.reports.salesRepReport', \compact('authUser', 'user_role', 'currency', 'staffs', 'warehouses', 'start_date', 'end_date', 'warehouse_selected',
        'sales_paid', 'expenses', 'products', 'allExpenses'));
    }

    //salesRepReportAjax
    public function salesRepReportAjax(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $authUser = auth()->user();
        $data = $request->all();
        
        //only staff
        if (!empty($data['staff_id']) && empty($data['start_date']) && empty($data['end_date'])) {
            $staff_id = $data['staff_id'];
            $start_date = "";
            $end_date = "";
            
            //revenue
            //$sales_paid = 0;
            $delivered_and_remitted_orders = Order::where('staff_assigned_id', $staff_id)->where('status', 'delivered_and_remitted')->pluck('id');
            // $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');
            // $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');

            $sales_paid = $this->helper->orderSalesRevenue($delivered_and_remitted_orders);
            $sales_paid = $this->shorten($sales_paid); //total revenue

            $expenses = Expense::where('staff_id', $staff_id)->sum('amount');
            $expenses = $this->shorten($expenses);

            //for datatables
            $products = Product::all(); $theProducts = [];
            //store filter in array
            foreach ($products as $key=>$product) {
                if ($product->revenue($staff_id, $start_date, $end_date) > 0) {
                    $theProducts[] = $product;
                }
            }

            //loop tru resulting array
            foreach ($theProducts as $key=>$product) {
                $theProducts[$key]->name = $product->name; 
                $theProducts[$key]->revenue = number_format($product->revenue($staff_id, $start_date, $end_date)); 
                $theProducts[$key]->soldQty = $product->soldQty($staff_id); 
                $theProducts[$key]->stock_available = $product->stock_available(); 
            }

            $allExpenses = Expense::where('staff_id', $staff_id)->get();
            foreach ($allExpenses as $key=>$expense) {
                $allExpenses[$key]->category_name = $expense->category->name;
                $allExpenses[$key]->amount = $expense->amount;
                $allExpenses[$key]->staff_name = $expense->staff->name;
            }
            
            $data['sales'] = $sales_paid;
            $data['expenses'] = $expenses;
            $data['products'] = $theProducts;
            $data['allExpenses'] = $allExpenses;
        }

        //only date
        if (empty($data['staff_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
            $staff_id = "";
            //strtotime for checking
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);

            if ($start_date > $end_date) {
                $data['error'] = 'Start Date Cannot be greater than End Date';
                return response()->json([
                    'status'=>true,
                    'data'=>$data
                ]);
            }
            //date proper
            $start_date = date('Y-m-d',$start_date);
            $end_date = date('Y-m-d',$end_date);
            
            //revenue
            //$sales_paid = 0;
            $delivered_and_remitted_orders = Order::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->where('status', 'delivered_and_remitted')->pluck('id');
            // $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');
            // $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
            $sales_paid = $this->helper->orderSalesRevenue($delivered_and_remitted_orders);
            $sales_paid = $this->shorten($sales_paid); //total revenue

            $expenses = Expense::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->sum('amount');
            $expenses = $this->shorten($expenses);

            //for datatables
            $products = Product::all(); $theProducts = [];
            //store filter in array
            foreach ($products as $key=>$product) {
                if ($product->revenue($staff_id, $start_date, $end_date) > 0) {
                    $theProducts[] = $product;
                }
            }

            //loop tru resulting array
            foreach ($theProducts as $key=>$product) {
                $theProducts[$key]->name = $product->name; 
                $theProducts[$key]->revenue = number_format($product->revenue($staff_id, $start_date, $end_date)); 
                $theProducts[$key]->soldQty = $product->soldQty($staff_id); 
                $theProducts[$key]->stock_available = $product->stock_available(); 
            }

            $allExpenses = Expense::where('staff_id', $staff_id)->get();
            foreach ($allExpenses as $key=>$expense) {
                $allExpenses[$key]->category_name = $expense->category->name;
                $allExpenses[$key]->amount = $expense->amount;
                $allExpenses[$key]->staff_name = $expense->staff->name;
            }
            
            $data['sales'] = $sales_paid;
            $data['expenses'] = $expenses;
            $data['products'] = $theProducts;
            $data['allExpenses'] = $allExpenses;

            ///////////////////////////////////////////
            
        }

        //all
        if (!empty($data['staff_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
            $staff_id = $data['staff_id'];

            //strtotime for checking
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);

            if ($start_date > $end_date) {
                $data['error'] = 'Start Date Cannot be greater than End Date';
                return response()->json([
                    'status'=>true,
                    'data'=>$data
                ]);
            }
            //date proper
            $start_date = date('Y-m-d',$start_date);
            $end_date = date('Y-m-d',$end_date);
            
            //revenue
            //$sales_paid = 0;
            $delivered_and_remitted_orders = Order::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->where('staff_assigned_id', $staff_id)->where('status', 'delivered_and_remitted')->pluck('id');
            // $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');
            // $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');

            $sales_paid = $this->helper->orderSalesRevenue($delivered_and_remitted_orders);
            $sales_paid = $this->shorten($sales_paid); //total revenue

            $expenses = Expense::where('staff_id', $staff_id)->sum('amount');
            $expenses = $this->shorten($expenses);

            //for datatables
            $products = Product::all(); $theProducts = [];
            //store filter in array
            foreach ($products as $key=>$product) {
                if ($product->revenue($staff_id, $start_date, $end_date) > 0) {
                    $theProducts[] = $product;
                }
            }

            //loop tru resulting array
            foreach ($theProducts as $key=>$product) {
                $theProducts[$key]->name = $product->name; 
                $theProducts[$key]->revenue = number_format($product->revenue($staff_id, $start_date, $end_date)); 
                $theProducts[$key]->soldQty = $product->soldQty($staff_id); 
                $theProducts[$key]->stock_available = $product->stock_available(); 
            }

            $allExpenses = Expense::where('staff_id', $staff_id)->get();
            foreach ($allExpenses as $key=>$expense) {
                $allExpenses[$key]->category_name = $expense->category->name;
                $allExpenses[$key]->amount = $expense->amount;
                $allExpenses[$key]->staff_name = $expense->staff->name;
            }
            
            $data['sales'] = $sales_paid;
            $data['expenses'] = $expenses;
            $data['products'] = $theProducts;
            $data['allExpenses'] = $allExpenses;
        }
        
        //store in array
        return response()->json([
            'status'=>true,
            'data'=>$data
        ]);
    }
    
    public function productReport()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $products = Product::all();
        $warehouses = WareHouse::all();
        $start_date = '';
        $end_date = '';
        $warehouse_selected = '';
        
        return view('pages.reports.productReport', \compact('authUser', 'user_role', 'products', 'warehouses', 'start_date', 'end_date', 'warehouse_selected'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function productReportQuery(Request $request)
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

        if (empty($data['warehouse_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);

            if ($start_date > $end_date) {
                return back()->with('error', 'Start Date Cannot be greater than End Date');
            }

            $start_date = date('Y-m-d',$start_date);
            $end_date = date('Y-m-d',$end_date);

            $products = Product::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->get();
        }
        
        if (!empty($data['warehouse_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);
            $warehouse_selected = WareHouse::find($data['warehouse_id']);

            if ($start_date > $end_date) {
                return back()->with('error', 'Start Date Cannot be greater than End Date');
            }

            $start_date = date('Y-m-d',$start_date);
            $end_date = date('Y-m-d',$end_date);

            $products = Product::where('warehouse_id',$data['warehouse_id'])->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->get();
        }

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
                if ($product_warehouses->contains('warehouse_type','minor') || $product_warehouses->contains('warehouse_type','major')) {
                    
                        $products[] = $product;
                    
                }   
            }
        }
        
        $warehouses = WareHouse::all();
        return view('pages.reports.productReport', \compact('authUser', 'user_role', 'products', 'warehouses', 'start_date', 'end_date', 'warehouse_selected'));
    }

    public function saleReport()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $categories = Category::all();
        $warehouses = WareHouse::all();
        $start_date = '';
        $end_date = '';
        $warehouse_selected = '';

        // $yearly_best_selling_qty = Sale::select(DB::raw('product_id, sum(product_qty_sold) as sold_qty'))->whereDate('created_at', '>=' , date("Y").'-01-01')
        // ->whereDate('created_at', '<=' , date("Y").'-12-31')->groupBy('product_id')->orderBy('sold_qty', 'desc')->take(5)->get();
        //$yearly_best_selling_qty = Sale::select(DB::raw('product_id, sum(product_qty_sold) as sold_qty'))->groupBy('product_id')->orderBy('sold_qty', 'desc')->get();
        $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->pluck('id');
        // $accepted_outgoing_stocks = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
        // ->select(DB::raw('product_id, sum(amount_accrued) as sold_amount, sum(quantity_removed) as sold_qty'))->groupBy('product_id')
        // ->orderBy('sold_amount', 'desc')->get();

        // Step 1: array containing the "package_bundle" data
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->pluck('package_bundle'); //[[{}], [{}], [{}]] multidimensional
        $packageBundleArray = $accepted_outgoing_stock;

        // Initialize an empty array to store the grouped and summarized results
        $groupedSummarizedArray = [];

        // Step 2: Group the items by "product_id"
        foreach ($packageBundleArray as $innerArray) {
            foreach ($innerArray as $item) {
                // Only consider items with 'customer_acceptance_status' being 'accepted'
                if ($item['customer_acceptance_status'] === 'accepted') {
                    $product_id = $item['product_id'];
                    if (!isset($groupedSummarizedArray[$product_id])) {
                        $groupedSummarizedArray[$product_id] = [
                            'product_id' => $product_id,
                            'sold_amount' => 0,
                            'sold_qty' => 0,
                        ];
                    }

                    // Step 3: Calculate the sum of "amount_accrued" and "quantity_removed" for each group
                    $groupedSummarizedArray[$product_id]['sold_amount'] += $item['amount_accrued'];
                    $groupedSummarizedArray[$product_id]['sold_qty'] += $item['quantity_removed'];
                }
            }
        }

        // Step 4: Sort the groups based on the sum of "amount_accrued" in descending order
        usort($groupedSummarizedArray, function ($a, $b) {
            return $b['sold_amount'] - $a['sold_amount'];
        });

        //return $groupedSummarizedArray;
        
        $sellingProductsBulk = [];
        foreach ($groupedSummarizedArray as $key => $stock) {
            $product = Product::find($stock['product_id']);
            $sellingProducts['product_name'] = $product->name;
            $sellingProducts['product_category'] = $product->category ? $product->category->name : null;
            $sellingProducts['sold_amount'] = $stock['sold_amount'];
            $sellingProducts['sold_qty'] = $stock['sold_qty'];
            $sellingProducts['stock_available'] = $product->stock_available();

            $sellingProductsBulk[] = $sellingProducts;
        }

        //return $sellingProductsBulk;

        return view('pages.reports.saleReport', \compact('authUser', 'user_role', 'warehouses', 'start_date', 'end_date', 'warehouse_selected', 'sellingProductsBulk', 'categories'));
    }

    
    public function saleReportQuery(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();

        $start_date = '';
        $end_date = '';
        $warehouse_selected = '';
        //$delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->pluck('id');
        $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted');

        //warehouse only
        if (!empty($data['warehouse_id']) && empty($data['start_date']) && empty($data['end_date'])) {

            $warehouse_selected = WareHouse::find($data['warehouse_id']);

            // $yearly_best_selling_qty = Sale::where('warehouse_id', $data['warehouse_id'])->select(DB::raw('product_id, sum(product_qty_sold) as sold_qty'))
            // ->groupBy('product_id')->orderBy('sold_qty', 'desc')->get();
            $delivered_and_remitted_orders = $delivered_and_remitted_orders->where('warehouse_id', $data['warehouse_id'])->pluck('id');
            // $accepted_outgoing_stocks = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
            // ->select(DB::raw('product_id, sum(amount_accrued) as sold_amount, sum(quantity_removed) as sold_qty'))
            // ->groupBy('product_id')->orderBy('sold_amount', 'desc')->get();

            // Step 1: array containing the "package_bundle" data
            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->pluck('package_bundle'); //[[{}], [{}], [{}]] multidimensional
            $packageBundleArray = $accepted_outgoing_stock;

            // Initialize an empty array to store the grouped and summarized results
            $groupedSummarizedArray = [];

            // Step 2: Group the items by "product_id"
            foreach ($packageBundleArray as $innerArray) {
                foreach ($innerArray as $item) {
                    // Only consider items with 'customer_acceptance_status' being 'accepted'
                    if ($item['customer_acceptance_status'] === 'accepted') {
                        $product_id = $item['product_id'];
                        if (!isset($groupedSummarizedArray[$product_id])) {
                            $groupedSummarizedArray[$product_id] = [
                                'product_id' => $product_id,
                                'sold_amount' => 0,
                                'sold_qty' => 0,
                            ];
                        }

                        // Step 3: Calculate the sum of "amount_accrued" and "quantity_removed" for each group
                        $groupedSummarizedArray[$product_id]['sold_amount'] += $item['amount_accrued'];
                        $groupedSummarizedArray[$product_id]['sold_qty'] += $item['quantity_removed'];
                    }
                }
            }

            // Step 4: Sort the groups based on the sum of "amount_accrued" in descending order
            usort($groupedSummarizedArray, function ($a, $b) {
                return $b['sold_amount'] - $a['sold_amount'];
            });
            
            $sellingProductsBulk = [];
            foreach ($groupedSummarizedArray as $key => $stock) {
                $product = Product::find($stock->product_id);
                $sellingProducts['product_name'] = $product->name;
                $sellingProducts['product_category'] = $product->category ? $product->category->name : null;
                $sellingProducts['sold_amount'] = $stock->sold_amount;
                $sellingProducts['sold_qty'] = $stock->sold_qty;
                $sellingProducts['stock_available'] = $product->stock_available();

                $sellingProductsBulk[] = $sellingProducts;
            }
        }

        //start & end-dates only
        if (empty($data['warehouse_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);

            if ($start_date > $end_date) {
                return back()->with('error', 'Start Date Cannot be greater than End Date');
            }

            $start_date = date('Y-m-d',$start_date);
            $end_date = date('Y-m-d',$end_date);

            //$yearly_best_selling_qty = Sale::select(DB::raw('product_id, sum(product_qty_sold) as sold_qty'))->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->groupBy('product_id')->orderBy('sold_qty', 'desc')->get();
            $delivered_and_remitted_orders = $delivered_and_remitted_orders->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->pluck('id');
            // $accepted_outgoing_stocks = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
            // ->select(DB::raw('product_id, sum(amount_accrued) as sold_amount, sum(quantity_removed) as sold_qty'))
            // ->groupBy('product_id')->orderBy('sold_amount', 'desc')->get();

            // Step 1: array containing the "package_bundle" data
            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->pluck('package_bundle'); //[[{}], [{}], [{}]] multidimensional
            $packageBundleArray = $accepted_outgoing_stock;

            // Initialize an empty array to store the grouped and summarized results
            $groupedSummarizedArray = [];

            // Step 2: Group the items by "product_id"
            foreach ($packageBundleArray as $innerArray) {
                foreach ($innerArray as $item) {
                    // Only consider items with 'customer_acceptance_status' being 'accepted'
                    if ($item['customer_acceptance_status'] === 'accepted') {
                        $product_id = $item['product_id'];
                        if (!isset($groupedSummarizedArray[$product_id])) {
                            $groupedSummarizedArray[$product_id] = [
                                'product_id' => $product_id,
                                'sold_amount' => 0,
                                'sold_qty' => 0,
                            ];
                        }

                        // Step 3: Calculate the sum of "amount_accrued" and "quantity_removed" for each group
                        $groupedSummarizedArray[$product_id]['sold_amount'] += $item['amount_accrued'];
                        $groupedSummarizedArray[$product_id]['sold_qty'] += $item['quantity_removed'];
                    }
                }
            }

            // Step 4: Sort the groups based on the sum of "amount_accrued" in descending order
            usort($groupedSummarizedArray, function ($a, $b) {
                return $b['sold_amount'] - $a['sold_amount'];
            });
            
            $sellingProductsBulk = [];
            foreach ($groupedSummarizedArray as $key => $stock) {
                $product = Product::find($stock->product_id);
                $sellingProducts['product_name'] = $product->name;
                $sellingProducts['product_category'] = $product->category ? $product->category->name : null;
                $sellingProducts['sold_amount'] = $stock->sold_amount;
                $sellingProducts['sold_qty'] = $stock->sold_qty;
                $sellingProducts['stock_available'] = $product->stock_available();

                $sellingProductsBulk[] = $sellingProducts;
            }
        }

        //all options
        if (!empty($data['warehouse_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);
            $warehouse_selected = WareHouse::find($data['warehouse_id']);

            if ($start_date > $end_date) {
                return back()->with('error', 'Start Date Cannot be greater than End Date');
            }

            $start_date = date('Y-m-d',$start_date);
            $end_date = date('Y-m-d',$end_date);
            
            //$yearly_best_selling_qty = Sale::where('warehouse_id',$data['warehouse_id'])->select(DB::raw('product_id, sum(product_qty_sold) as sold_qty'))->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->groupBy('product_id')->orderBy('sold_qty', 'desc')->get();
            $delivered_and_remitted_orders = $delivered_and_remitted_orders->where('warehouse_id', $data['warehouse_id'])
            ->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->pluck('id');
            // $accepted_outgoing_stocks = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
            // ->select(DB::raw('product_id, sum(amount_accrued) as sold_amount, sum(quantity_removed) as sold_qty'))
            // ->groupBy('product_id')->orderBy('sold_amount', 'desc')->get();

            // Step 1: array containing the "package_bundle" data
            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->pluck('package_bundle'); //[[{}], [{}], [{}]] multidimensional
            $packageBundleArray = $accepted_outgoing_stock;

            // Initialize an empty array to store the grouped and summarized results
            $groupedSummarizedArray = [];

            // Step 2: Group the items by "product_id"
            foreach ($packageBundleArray as $innerArray) {
                foreach ($innerArray as $item) {
                    // Only consider items with 'customer_acceptance_status' being 'accepted'
                    if ($item['customer_acceptance_status'] === 'accepted') {
                        $product_id = $item['product_id'];
                        if (!isset($groupedSummarizedArray[$product_id])) {
                            $groupedSummarizedArray[$product_id] = [
                                'product_id' => $product_id,
                                'sold_amount' => 0,
                                'sold_qty' => 0,
                            ];
                        }

                        // Step 3: Calculate the sum of "amount_accrued" and "quantity_removed" for each group
                        $groupedSummarizedArray[$product_id]['sold_amount'] += $item['amount_accrued'];
                        $groupedSummarizedArray[$product_id]['sold_qty'] += $item['quantity_removed'];
                    }
                }
            }

            // Step 4: Sort the groups based on the sum of "amount_accrued" in descending order
            usort($groupedSummarizedArray, function ($a, $b) {
                return $b['sold_amount'] - $a['sold_amount'];
            });

            $sellingProductsBulk = [];
            foreach ($groupedSummarizedArray as $key => $stock) {
                $product = Product::find($stock->product_id);
                $sellingProducts['product_name'] = $product->name;
                $sellingProducts['product_category'] = $product->category ? $product->category->name : null;
                $sellingProducts['sold_amount'] = $stock->sold_amount;
                $sellingProducts['sold_qty'] = $stock->sold_qty;
                $sellingProducts['stock_available'] = $product->stock_available();

                $sellingProductsBulk[] = $sellingProducts;
            }
        }
        
        $warehouses = WareHouse::all();
        return view('pages.reports.saleReport', \compact('authUser', 'user_role', 'warehouses', 'start_date', 'end_date', 'warehouse_selected',  'sellingProductsBulk'));
    }

    //purchaseReport
    public function purchaseReport()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $warehouses = WareHouse::all();
        $start_date = '';
        $end_date = '';
        $warehouse_selected = '';

        $yearly_best_purchasing_qty = Purchase::select(DB::raw('product_id, sum(product_qty_purchased) as purchased_qty, sum(amount_paid) as purchased_amount'))->groupBy('product_id')->orderBy('purchased_qty', 'desc')->get();
        
        $purchasingProductsBulk = [];
        foreach ($yearly_best_purchasing_qty as $key => $purchase) {
            $product = Product::find($purchase->product_id);
            $purchasingProducts['product_name'] = $product->name;
            $purchasingProducts['purchased_amount'] = $purchase->purchased_amount;
            $purchasingProducts['purchased_qty'] = $purchase->purchased_qty;
            $purchasingProducts['stock_available'] = $product->stock_available();

            $purchasingProductsBulk[] = $purchasingProducts;
        }

        return view('pages.reports.purchaseReport', \compact('authUser', 'user_role', 'warehouses', 'start_date', 'end_date', 'warehouse_selected', 'purchasingProductsBulk'));
    }

    public function purchaseReportQuery(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();

        $start_date = '';
        $end_date = '';
        $warehouse_selected = '';

        if (!empty($data['warehouse_id']) && empty($data['start_date']) && empty($data['end_date'])) {

            $warehouse_selected = WareHouse::find($data['warehouse_id']);

            $yearly_best_purchasing_qty = Purchase::where('warehouse_id', $data['warehouse_id'])->select(DB::raw('product_id, sum(product_qty_purchased) as purchased_qty, sum(amount_paid) as purchased_amount'))
            ->groupBy('product_id')->orderBy('purchased_qty', 'desc')->get();
            
            $purchasingProductsBulk = [];
            foreach ($yearly_best_purchasing_qty as $key => $purchase) {
                $product = Product::find($purchase->product_id);
                $purchasingProducts['product_name'] = $product->name;
                $purchasingProducts['purchased_amount'] = $purchase->purchased_amount;
                $purchasingProducts['purchased_qty'] = $purchase->purchased_qty;
                $purchasingProducts['stock_available'] = $product->stock_available();

                $purchasingProductsBulk[] = $purchasingProducts;
            }
        }

        if (empty($data['warehouse_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);

            if ($start_date > $end_date) {
                return back()->with('error', 'Start Date Cannot be greater than End Date');
            }

            $start_date = date('Y-m-d',$start_date);
            $end_date = date('Y-m-d',$end_date);
            
            $yearly_best_purchasing_qty = Purchase::select(DB::raw('product_id, sum(product_qty_purchased) as purchased_qty, sum(amount_paid) as purchased_amount'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->groupBy('product_id')->orderBy('purchased_qty', 'desc')->get();

            $purchasingProductsBulk = [];
            foreach ($yearly_best_purchasing_qty as $key => $purchase) {
                $product = Product::find($purchase->product_id);
                $purchasingProducts['product_name'] = $product->name;
                $purchasingProducts['purchased_amount'] = $purchase->purchased_amount;
                $purchasingProducts['purchased_qty'] = $purchase->purchased_qty;
                $purchasingProducts['stock_available'] = $product->stock_available();

                $purchasingProductsBulk[] = $purchasingProducts;
            }
        }

        if (!empty($data['warehouse_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);
            $warehouse_selected = WareHouse::find($data['warehouse_id']);

            if ($start_date > $end_date) {
                return back()->with('error', 'Start Date Cannot be greater than End Date');
            }

            $start_date = date('Y-m-d',$start_date);
            $end_date = date('Y-m-d',$end_date);
            
            $yearly_best_purchasing_qty = Purchase::where('warehouse_id',$data['warehouse_id'])->select(DB::raw('product_id, sum(product_qty_purchased) as purchased_qty, sum(amount_paid) as purchased_amount'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->groupBy('product_id')->orderBy('purchased_qty', 'desc')->get();

            $purchasingProductsBulk = [];
            foreach ($yearly_best_purchasing_qty as $key => $purchase) {
                $product = Product::find($purchase->product_id);
                $purchasingProducts['product_name'] = $product->name;
                $purchasingProducts['purchased_amount'] = $purchase->purchased_amount;
                $purchasingProducts['purchased_qty'] = $purchase->purchased_qty;
                $purchasingProducts['stock_available'] = $product->stock_available();

                $purchasingProductsBulk[] = $purchasingProducts;
            }
        }
        
        $warehouses = WareHouse::all();
        return view('pages.reports.purchaseReport', \compact('authUser', 'user_role', 'warehouses', 'start_date', 'end_date', 'warehouse_selected',  'purchasingProductsBulk'));
    }

    //customerReport
    public function customerReport($type="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $customers = Customer::all();
        $start_date = '';
        $end_date = '';
        $customer_selected = '';
        
        $customer_selected = Customer::inRandomOrder()->where('id', '>', 0)->first();
        
        $customer_sales = isset($customer_selected) ? Sale::where('parent_id', null)->where('customer_id', $customer_selected->id)->orderBy('id', 'desc')->get() : collect();
        
        return view('pages.reports.customerReport', \compact('authUser', 'user_role', 'customers', 'start_date', 'end_date', 'customer_selected', 'customer_sales'));
    }

    public function customerReportQuery(Request $request, $type="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();
        $customers = Customer::all();
        $start_date = '';
        $end_date = '';
        $customer_selected = '';

        if (!empty($data['customer_id']) && empty($data['start_date']) && empty($data['end_date'])) {
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);
            $customer_selected = Customer::find($data['customer_id']);
            $customer_sales = Sale::where('parent_id', null)->where('customer_id', $customer_selected->id)->orderBy('id', 'desc')->get();
        }

        if (!empty($data['customer_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {

            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);
            $customer_selected = Customer::find($data['customer_id']);
            $start_date = date('Y-m-d',$start_date);
            $end_date = date('Y-m-d',$end_date);
            $customer_sales = Sale::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->where('parent_id', null)
            ->where('customer_id', $customer_selected->id)->orderBy('id', 'desc')->get();
        }

        return view('pages.reports.customerReport', \compact('authUser', 'user_role', 'customers', 'start_date', 'end_date', 'customer_selected', 'customer_sales'));
    }

    //supplierReport
    public function supplierReport($type="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $suppliers = Supplier::all();
        $start_date = '';
        $end_date = '';
        $supplier_selected = '';

        $supplier_selected = Supplier::inRandomOrder()->where('id', '>', 0)->first();
        $supplier_purchases = Purchase::where('parent_id', null)->where('supplier_id', $supplier_selected->id)->orderBy('id', 'desc')->get();

        return view('pages.reports.supplierReport', \compact('authUser', 'user_role', 'suppliers', 'start_date', 'end_date', 'supplier_selected', 'supplier_purchases'));
    }

    public function supplierReportQuery(Request $request, $type="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();
        $suppliers = Supplier::all();
        $start_date = '';
        $end_date = '';
        $supplier_selected = '';

        if (!empty($data['supplier_id']) && empty($data['start_date']) && empty($data['end_date'])) {
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);
            $supplier_selected = Supplier::find($data['supplier_id']);
            $supplier_purchases = Purchase::where('parent_id', null)->where('supplier_id', $supplier_selected->id)->orderBy('id', 'desc')->get();
        }

        if (!empty($data['supplier_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
            $start_date = strtotime($data['start_date']);
            $end_date = strtotime($data['end_date']);
            $supplier_selected = Supplier::find($data['supplier_id']);
            $start_date = date('Y-m-d',$start_date);
            $end_date = date('Y-m-d',$end_date);
            $supplier_purchases = Purchase::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->where('parent_id', null)
            ->where('supplier_id', $supplier_selected->id)->orderBy('id', 'desc')->get();
        }

        return view('pages.reports.supplierReport', \compact('authUser', 'user_role', 'suppliers', 'start_date', 'end_date', 'supplier_selected', 'supplier_purchases'));
    }

    //staffReport
    public function staffReport($type="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $authUser = auth()->user();
        $aspect = 'Sales';
        $staffs = User::where('type','staff')->orWhere('isSuperAdmin', true)->get();
        $start_date = '';
        $end_date = '';
        $staff_selected = $authUser;
        $staff_sales = '';
        $staff_purchases = '';
        $staff_expenses = '';

        $staff_sales = Sale::where('parent_id', null)->where('created_by', $staff_selected->id)->orderBy('id', 'desc')->get();

        return view('pages.reports.staffReport', \compact('authUser', 'user_role', 'staffs', 'start_date', 'end_date', 'staff_selected', 'staff_sales', 'aspect', 'staff_purchases', 'staff_expenses'));
    }

    public function staffReportQuery(Request $request, $type="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();
        $authUser = auth()->user();
        $aspect = $data['aspect'];
        $staffs = User::where('type','staff')->orWhere('isSuperAdmin', true)->get();
        $start_date = '';
        $end_date = '';
        $staff_selected = $authUser;
        $staff_sales = '';
        $staff_purchases = '';
        $staff_expenses = '';

        $staff_selected = $authUser;

        if ($data['aspect']=='Sales') {
    
            if (!empty($data['staff_id']) && empty($data['start_date']) && empty($data['end_date'])) {
                $start_date = strtotime($data['start_date']);
                $end_date = strtotime($data['end_date']);
                $staff_selected = User::find($data['staff_id']);
                $staff_sales = Sale::where('parent_id', null)->where('created_by', $staff_selected->id)->orderBy('id', 'desc')->get();
            }
    
            if (!empty($data['staff_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
                $start_date = strtotime($data['start_date']);
                $end_date = strtotime($data['end_date']);
                $staff_selected = User::find($data['staff_id']);
                $start_date = date('Y-m-d',$start_date);
                $end_date = date('Y-m-d',$end_date);
                $staff_sales = Sale::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->where('parent_id', null)
                ->where('created_by', $staff_selected->id)->orderBy('id', 'desc')->get();
            }
        }
        
        if ($data['aspect']=='Purchases') {
    
            if (!empty($data['staff_id']) && empty($data['start_date']) && empty($data['end_date'])) {
                $start_date = strtotime($data['start_date']);
                $end_date = strtotime($data['end_date']);
                $staff_selected = User::find($data['staff_id']);
                $staff_purchases = Purchase::where('parent_id', null)->where('created_by', $staff_selected->id)->orderBy('id', 'desc')->get();
            }
    
            if (!empty($data['staff_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
                $start_date = strtotime($data['start_date']);
                $end_date = strtotime($data['end_date']);
                $staff_selected = User::find($data['staff_id']);
                $start_date = date('Y-m-d',$start_date);
                $end_date = date('Y-m-d',$end_date);
                $staff_purchases = Purchase::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->where('parent_id', null)
                ->where('created_by', $staff_selected->id)->orderBy('id', 'desc')->get();
            }
        }

        if ($data['aspect']=='Expenses') {
    
            if (!empty($data['staff_id']) && empty($data['start_date']) && empty($data['end_date'])) {
                $start_date = strtotime($data['start_date']);
                $end_date = strtotime($data['end_date']);
                $staff_selected = User::find($data['staff_id']);
                $staff_purchases = Expense::where('created_by', $staff_selected->id)->orderBy('id', 'desc')->get();
            }
    
            if (!empty($data['staff_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
                $start_date = strtotime($data['start_date']);
                $end_date = strtotime($data['end_date']);
                $staff_selected = User::find($data['staff_id']);
                $start_date = date('Y-m-d',$start_date);
                $end_date = date('Y-m-d',$end_date);
                $staff_purchases = Expense::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->where('created_by', $staff_selected->id)
                ->orderBy('id', 'desc')->get();
            }
        }

        return view('pages.reports.staffReport', \compact('authUser', 'user_role', 'staffs', 'start_date', 'end_date', 'staff_selected', 'staff_sales', 'aspect', 'staff_purchases', 'staff_expenses'));
    }

    public function soldAmount($product_id){
        $soldAmount = OutgoingStock::where('product_id', $product_id)->sum('amount_accrued');
        return $soldAmount;
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
            $num = number_format(abs( (int) $num / 1000), $digits, '.', '') + 0;
            $num = $num . 'k';
        }
        return $num;
    }
}
