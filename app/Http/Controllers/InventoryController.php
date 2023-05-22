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
use App\Models\Category;
use App\Models\OutgoingStock;
use App\Models\ProductTransfer;


class InventoryController extends Controller
{
    
    public function inventoryDashboard($warehouse_unique_key="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'all';
        //////////////////////////////////////////////////////

        $selected_warehouse = '';
        if($warehouse_unique_key !== "") {
            $selected_warehouse = WareHouse::where('unique_key', $warehouse_unique_key)->first();
            if(!isset($selected_warehouse)){
                abort(404);
            }
            $warehouse_product_ids = $selected_warehouse->products()->pluck('purchase_id'); 
            $product_purchase_ids = Product::whereIn('id', $warehouse_product_ids)->whereNull('combo_product_ids')->pluck('purchase_id');
            $purchases_sum = Purchase::whereIn('id', $product_purchase_ids)->sum('amount_paid');
    
            $purchases_amount_paid = $this->shorten($purchases_sum);
            //$sales_paid = $this->shorten(Sale::sum('amount_paid'));

            $sales_paid = 0;
            $delivered_and_remitted_orders = $selected_warehouse->orders()->where('status', 'delivered_and_remitted')->pluck('id');
            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');
            $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        
            $total_products = Product::whereIn('id', $warehouse_product_ids)->whereNull('combo_product_ids')->get();
            
            $out_of_stock_products = [];
            if(count($total_products) > 0) {
                foreach ($total_products as $key => $product) {
                    if ($product->stock_available() < 10) {
                        $out_of_stock_products[] = $product;
                    } 
                }
            }
            
            $warehouses = WareHouse::all();
    
            //$sale_revenue = $this->shorten(Sale::sum('amount_paid'));
    
            //$sales_sum = Sale::sum('amount_paid');
            $sales_sum = $sales_paid;
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
            $sales_paid = $this->shorten($sales_paid);
    
            $customers = Customer::all();
    
            $recently_products = $selected_warehouse->products;
            
            $categories = Category::all();

            //warehouse orders
            $orders = $selected_warehouse->orders; $outgoingStocks = ''; $total_revenue = 0; $packages = [];
            if (count($orders) > 0) {
                $outgoingStocks = OutgoingStock::whereIn('order_id', $orders->pluck('id'));
        
                if (count($outgoingStocks->get()) > 0) {
                    $packages = []; $warehouseOrders = []; $total_revenue += $outgoingStocks->sum('amount_accrued');
                    foreach ($orders as $key => $order) {
                        $outgoingStock = $order->outgoingStocks()->orderBy('id', 'DESC');
                        if (count($outgoingStock->get()) > 0) {
                            $warehouseOrders['warehouseOrder'] = 
                            [
                                'order'=>$order,
                                'outgoingStock'=>$outgoingStock->get(),
                                'orderRevenue'=>$outgoingStock->sum('amount_accrued'),
                            ];
                            $packages[] = $warehouseOrders;
                        }
                    }
                }
            }
            
            //warehouse product transfers
            $transfers = ProductTransfer::where('from_warehouse_id', $selected_warehouse->id)->orWhere('to_warehouse_id', $selected_warehouse->id)->get();
            
        } else {
            $product_purchase_ids = Product::whereNull('combo_product_ids')->pluck('purchase_id');
            $purchases_sum = Purchase::whereIn('id', $product_purchase_ids)->sum('amount_paid');
    
            $purchases_amount_paid = $this->shorten($purchases_sum);
            //$sales_paid = $this->shorten(Sale::sum('amount_paid'));
            $sales_paid = 0;
            $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->pluck('id');
            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');
            $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
    
            $total_products = Product::whereNull('combo_product_ids')->get();
            
            $out_of_stock_products = [];
            foreach ($total_products as $key => $product) {
                if ($product->stock_available() < 10) {
                    $out_of_stock_products[] = $product;
                } 
            }
    
            $warehouses = WareHouse::all();
    
            //$sale_revenue = $this->shorten(Sale::sum('amount_paid'));
    
            //$sales_sum = Sale::sum('amount_paid');
            $sales_sum = $sales_paid;
            $purchase_sum = Purchase::sum('amount_paid');
            $expense_sum = Expense::sum('amount');
    
            $total_expenses = $this->shorten($purchase_sum + $expense_sum);
    
            $profit_val = $sales_sum - ($purchase_sum + $expense_sum);
    
            if ($profit_val > 0) {
                $profit = $this->shorten($profit_val);
            } else {
                $profit = $this->shorten($profit_val);
            }
    
            $suppliers = Supplier::all();

            $purchase_sum = $this->shorten($purchase_sum);
            $sales_sum = $this->shorten($sales_sum);
            $sales_paid = $this->shorten($sales_paid);
    
    
            $customers = Customer::all();
    
            $recently_products = Product::whereNull('combo_product_ids')->orderBy('id','DESC')->take(100)->get();
            
            $categories = Category::all();

            $orders = Order::all(); $outgoingStocks = ''; $total_revenue = 0; $packages = [];
            if (count($orders) > 0) {
                $outgoingStocks = OutgoingStock::whereIn('order_id', $orders->pluck('id'));
        
                if (count($outgoingStocks->get()) > 0) {
                    $packages = []; $warehouseOrders = []; $total_revenue += $outgoingStocks->sum('amount_accrued');
                    foreach ($orders as $key => $order) {
                        $outgoingStock = $order->outgoingStocks()->orderBy('id', 'DESC');
                        if (count($outgoingStock->get()) > 0) {
                            $warehouseOrders['warehouseOrder'] = 
                            [
                                'order'=>$order,
                                'outgoingStock'=>$outgoingStock->get(),
                                'orderRevenue'=>$outgoingStock->sum('amount_accrued'),
                            ];
                            $packages[] = $warehouseOrders;
                        }
                    }
                }
            }
            //warehouse product transfers
            $transfers = ProductTransfer::all();

            
        }

        return view('pages.inventory.inventory', \compact('authUser', 'user_role', 'record', 'currency', 'selected_warehouse', 'total_products', 'out_of_stock_products', 'warehouses', 'total_expenses',
        'profit', 'profit_val', 'orders', 'suppliers', 'purchase_sum', 'customers', 'sales_sum', 'recently_products', 'purchases_amount_paid', 'sales_paid', 'categories',
        'outgoingStocks', 'total_revenue', 'packages', 'transfers'));
    }

    //today
    public function inventoryDashboardToday($warehouse_unique_key="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'today';
        /////////////////////////////////////////////////////
        $dt = Carbon::now();
        
        $selected_warehouse = '';
        if($warehouse_unique_key !== "") {
            $selected_warehouse = WareHouse::where('unique_key', $warehouse_unique_key)->first();
            if(!isset($selected_warehouse)){
                abort(404);
            }
            $warehouse_product_ids = $selected_warehouse->products()->pluck('purchase_id');
            $product_purchase_ids = Product::whereIn('id', $warehouse_product_ids)->whereNull('combo_product_ids')->pluck('purchase_id');
            $purchases_sum = Purchase::whereIn('id', $product_purchase_ids)->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->sum('amount_paid');
             
            $purchases_amount_paid = $this->shorten($purchases_sum);
            //$sales_paid = $this->shorten(Sale::sum('amount_paid'));

            $sales_paid = 0;
            $delivered_and_remitted_orders = $selected_warehouse->orders()->where('status', 'delivered_and_remitted')->pluck('id');
            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
            ->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()]);
            $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        
            $total_products = Product::whereNull('combo_product_ids')->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->whereIn('id', $warehouse_product_ids)->get();
            
            $out_of_stock_products = [];
            if(count($total_products) > 0) {
                foreach ($total_products as $key => $product) {
                    if ($product->stock_available() < 10) {
                        $out_of_stock_products[] = $product;
                    } 
                }
            }
            
            $warehouses = WareHouse::all();
    
            //$sale_revenue = $this->shorten(Sale::sum('amount_paid'));
    
            //$sales_sum = Sale::sum('amount_paid');
            $sales_sum = $sales_paid;
            $purchase_sum = Purchase::sum('amount_paid');
            $expense_sum = Expense::sum('amount');
    
            $total_expenses = $this->shorten($purchase_sum + $expense_sum);
    
            $profit_val = $sales_sum - ($purchase_sum + $expense_sum);
    
            if ($profit_val > 0) {
                $profit = $this->shorten($profit_val);
            } else {
                $profit = $this->shorten($profit_val);
            }
    
            //$orders = Order::all();
    
            $suppliers = Supplier::all();
    
            $purchase_sum = $this->shorten($purchase_sum);
            $sales_sum = $this->shorten($sales_sum);
            $sales_paid = $this->shorten($sales_paid);
    
            $customers = Customer::all();
    
            $recently_products = $selected_warehouse->products()->get();
            $recently_products = Product::whereIn('id', $recently_products->pluck('id'))->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->get();
            
            $categories = Category::all();

            //warehouse orders
            $orders = $selected_warehouse->orders()->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->get(); $outgoingStocks = ''; $total_revenue = 0; $packages = [];
            if (count($orders) > 0) {
                $outgoingStocks = OutgoingStock::whereIn('order_id', $orders->pluck('id'))->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()]);
        
                if (count($outgoingStocks->get()) > 0) {
                    $packages = []; $warehouseOrders = []; $total_revenue += $outgoingStocks->sum('amount_accrued');
                    foreach ($orders as $key => $order) {
                        $outgoingStock = $order->outgoingStocks()->orderBy('id', 'DESC');
                        if (count($outgoingStock->get()) > 0) {
                            $warehouseOrders['warehouseOrder'] = 
                            [
                                'order'=>$order,
                                'outgoingStock'=>$outgoingStock->get(),
                                'orderRevenue'=>$outgoingStock->sum('amount_accrued'),
                            ];
                            $packages[] = $warehouseOrders;
                        }
                    }
                }
            }
            
            //warehouse product transfers
            $transfers = ProductTransfer::where('from_warehouse_id', $selected_warehouse->id)->orWhere('to_warehouse_id', $selected_warehouse->id)
            ->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->get(); 
            
        } else {
            
            $selected_warehouse = '';
            $product_purchase_ids = Product::whereNull('combo_product_ids')->pluck('purchase_id');
            $purchases_sum = Purchase::whereIn('id', $product_purchase_ids)->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->sum('amount_paid');
             
            $purchases_amount_paid = $this->shorten($purchases_sum);
            //$sales_paid = $this->shorten(Sale::sum('amount_paid'));

            $sales_paid = 0;
            $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->pluck('id');
            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
            ->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()]);
            $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        
            $total_products = Product::whereNull('combo_product_ids')->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->get();
            
            $out_of_stock_products = [];
            if(count($total_products) > 0) {
                foreach ($total_products as $key => $product) {
                    if ($product->stock_available() < 10) {
                        $out_of_stock_products[] = $product;
                    } 
                }
            }
            
            $warehouses = WareHouse::all();
    
            //$sale_revenue = $this->shorten(Sale::sum('amount_paid'));
    
            //$sales_sum = Sale::sum('amount_paid');
            $sales_sum = $sales_paid;
            $purchase_sum = Purchase::sum('amount_paid');
            $expense_sum = Expense::sum('amount');
    
            $total_expenses = $this->shorten($purchase_sum + $expense_sum);
    
            $profit_val = $sales_sum - ($purchase_sum + $expense_sum);
    
            if ($profit_val > 0) {
                $profit = $this->shorten($profit_val);
            } else {
                $profit = $this->shorten($profit_val);
            }
    
            //$orders = Order::all();
    
            $suppliers = Supplier::all();
    
            $purchase_sum = $this->shorten($purchase_sum);
            $sales_sum = $this->shorten($sales_sum);
            $sales_paid = $this->shorten($sales_paid);
    
            $customers = Customer::all();

            $recently_products = Product::whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->get();
            
            $categories = Category::all();

            //warehouse orders
            $orders = Order::whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->get(); $outgoingStocks = ''; $total_revenue = 0; $packages = [];
            if (count($orders) > 0) {
                $outgoingStocks = OutgoingStock::whereIn('order_id', $orders->pluck('id'))->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()]);
        
                if (count($outgoingStocks->get()) > 0) {
                    $packages = []; $warehouseOrders = []; $total_revenue += $outgoingStocks->sum('amount_accrued');
                    foreach ($orders as $key => $order) {
                        $outgoingStock = $order->outgoingStocks()->orderBy('id', 'DESC');
                        if (count($outgoingStock->get()) > 0) {
                            $warehouseOrders['warehouseOrder'] = 
                            [
                                'order'=>$order,
                                'outgoingStock'=>$outgoingStock->get(),
                                'orderRevenue'=>$outgoingStock->sum('amount_accrued'),
                            ];
                            $packages[] = $warehouseOrders;
                        }
                    }
                }
            }
            
            //warehouse product transfers
            $transfers = ProductTransfer::whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->get();
        }

        return view('pages.inventory.inventory', \compact('authUser', 'user_role', 'record', 'currency', 'selected_warehouse', 'total_products', 'out_of_stock_products', 'warehouses', 'total_expenses',
        'profit', 'profit_val', 'orders', 'suppliers', 'purchase_sum', 'customers', 'sales_sum', 'recently_products', 'purchases_amount_paid', 'sales_paid', 'categories',
        'outgoingStocks', 'total_revenue', 'packages', 'transfers'));
    }

    //weekly
    public function inventoryDashboardWeekly($warehouse_unique_key="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'weekly';
        /////////////////////////////////////////////////////
        $dt = Carbon::now();
        
        $selected_warehouse = '';
        if($warehouse_unique_key !== "") {
            $selected_warehouse = WareHouse::where('unique_key', $warehouse_unique_key)->first();
            if(!isset($selected_warehouse)){
                abort(404);
            }
            $warehouse_product_ids = $selected_warehouse->products()->pluck('purchase_id');
            $product_purchase_ids = Product::whereIn('id', $warehouse_product_ids)->whereNull('combo_product_ids')->pluck('purchase_id');
            $purchases_sum = Purchase::whereIn('id', $product_purchase_ids)->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->sum('amount_paid');
             
            $purchases_amount_paid = $this->shorten($purchases_sum);
            //$sales_paid = $this->shorten(Sale::sum('amount_paid'));

            $sales_paid = 0;
            $delivered_and_remitted_orders = $selected_warehouse->orders()->where('status', 'delivered_and_remitted')->pluck('id');
            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
            ->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()]);
            $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        
            $total_products = Product::whereNull('combo_product_ids')->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->whereIn('id', $warehouse_product_ids)->get();
            
            $out_of_stock_products = [];
            if(count($total_products) > 0) {
                foreach ($total_products as $key => $product) {
                    if ($product->stock_available() < 10) {
                        $out_of_stock_products[] = $product;
                    } 
                }
            }
            
            $warehouses = WareHouse::all();
    
            //$sale_revenue = $this->shorten(Sale::sum('amount_paid'));
    
            //$sales_sum = Sale::sum('amount_paid');
            $sales_sum = $sales_paid;
            $purchase_sum = Purchase::sum('amount_paid');
            $expense_sum = Expense::sum('amount');
    
            $total_expenses = $this->shorten($purchase_sum + $expense_sum);
    
            $profit_val = $sales_sum - ($purchase_sum + $expense_sum);
    
            if ($profit_val > 0) {
                $profit = $this->shorten($profit_val);
            } else {
                $profit = $this->shorten($profit_val);
            }
    
            //$orders = Order::all();
    
            $suppliers = Supplier::all();
    
            $purchase_sum = $this->shorten($purchase_sum);
            $sales_sum = $this->shorten($sales_sum);
            $sales_paid = $this->shorten($sales_paid);
    
            $customers = Customer::all();
    
            $recently_products = $selected_warehouse->products()->get();
            $recently_products = Product::whereIn('id', $recently_products->pluck('id'))->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->get();
            
            $categories = Category::all();

            //warehouse orders
            $orders = $selected_warehouse->orders()->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->get(); $outgoingStocks = ''; $total_revenue = 0; $packages = [];
            if (count($orders) > 0) {
                $outgoingStocks = OutgoingStock::whereIn('order_id', $orders->pluck('id'))->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()]);
        
                if (count($outgoingStocks->get()) > 0) {
                    $packages = []; $warehouseOrders = []; $total_revenue += $outgoingStocks->sum('amount_accrued');
                    foreach ($orders as $key => $order) {
                        $outgoingStock = $order->outgoingStocks()->orderBy('id', 'DESC');
                        if (count($outgoingStock->get()) > 0) {
                            $warehouseOrders['warehouseOrder'] = 
                            [
                                'order'=>$order,
                                'outgoingStock'=>$outgoingStock->get(),
                                'orderRevenue'=>$outgoingStock->sum('amount_accrued'),
                            ];
                            $packages[] = $warehouseOrders;
                        }
                    }
                }
            }
            
            //warehouse product transfers
            $transfers = ProductTransfer::where('from_warehouse_id', $selected_warehouse->id)->orWhere('to_warehouse_id', $selected_warehouse->id)
            ->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->get(); 
            
        } else {
            
            $selected_warehouse = '';
            $product_purchase_ids = Product::whereNull('combo_product_ids')->pluck('purchase_id');
            $purchases_sum = Purchase::whereIn('id', $product_purchase_ids)->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->sum('amount_paid');
             
            $purchases_amount_paid = $this->shorten($purchases_sum);
            //$sales_paid = $this->shorten(Sale::sum('amount_paid'));

            $sales_paid = 0;
            $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->pluck('id');
            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
            ->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()]);
            $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        
            $total_products = Product::whereNull('combo_product_ids')->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->get();
            
            $out_of_stock_products = [];
            if(count($total_products) > 0) {
                foreach ($total_products as $key => $product) {
                    if ($product->stock_available() < 10) {
                        $out_of_stock_products[] = $product;
                    } 
                }
            }
            
            $warehouses = WareHouse::all();
    
            //$sale_revenue = $this->shorten(Sale::sum('amount_paid'));
    
            //$sales_sum = Sale::sum('amount_paid');
            $sales_sum = $sales_paid;
            $purchase_sum = Purchase::sum('amount_paid');
            $expense_sum = Expense::sum('amount');
    
            $total_expenses = $this->shorten($purchase_sum + $expense_sum);
    
            $profit_val = $sales_sum - ($purchase_sum + $expense_sum);
    
            if ($profit_val > 0) {
                $profit = $this->shorten($profit_val);
            } else {
                $profit = $this->shorten($profit_val);
            }
    
            //$orders = Order::all();
    
            $suppliers = Supplier::all();
    
            $purchase_sum = $this->shorten($purchase_sum);
            $sales_sum = $this->shorten($sales_sum);
            $sales_paid = $this->shorten($sales_paid);
    
            $customers = Customer::all();

            $recently_products = Product::whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->get();
            
            $categories = Category::all();

            //warehouse orders
            $orders = Order::whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->get(); $outgoingStocks = ''; $total_revenue = 0; $packages = [];
            if (count($orders) > 0) {
                $outgoingStocks = OutgoingStock::whereIn('order_id', $orders->pluck('id'))->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()]);
        
                if (count($outgoingStocks->get()) > 0) {
                    $packages = []; $warehouseOrders = []; $total_revenue += $outgoingStocks->sum('amount_accrued');
                    foreach ($orders as $key => $order) {
                        $outgoingStock = $order->outgoingStocks()->orderBy('id', 'DESC');
                        if (count($outgoingStock->get()) > 0) {
                            $warehouseOrders['warehouseOrder'] = 
                            [
                                'order'=>$order,
                                'outgoingStock'=>$outgoingStock->get(),
                                'orderRevenue'=>$outgoingStock->sum('amount_accrued'),
                            ];
                            $packages[] = $warehouseOrders;
                        }
                    }
                }
            }
            
            //warehouse product transfers
            $transfers = ProductTransfer::whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->get();
        }

        return view('pages.inventory.inventory', \compact('authUser', 'user_role', 'record', 'currency', 'selected_warehouse', 'total_products', 'out_of_stock_products', 'warehouses', 'total_expenses',
        'profit', 'profit_val', 'orders', 'suppliers', 'purchase_sum', 'customers', 'sales_sum', 'recently_products', 'purchases_amount_paid', 'sales_paid', 'categories',
        'outgoingStocks', 'total_revenue', 'packages', 'transfers'));
    }
    
    //monthly
    public function inventoryDashboardMonthly($warehouse_unique_key="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'monthly';
        /////////////////////////////////////////////////////
        $dt = Carbon::now();
        
        $selected_warehouse = '';
        if($warehouse_unique_key !== "") {
            $selected_warehouse = WareHouse::where('unique_key', $warehouse_unique_key)->first();
            if(!isset($selected_warehouse)){
                abort(404);
            }
            $warehouse_product_ids = $selected_warehouse->products()->pluck('purchase_id');
            $product_purchase_ids = Product::whereIn('id', $warehouse_product_ids)->whereNull('combo_product_ids')->pluck('purchase_id');
            $purchases_sum = Purchase::whereIn('id', $product_purchase_ids)->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->sum('amount_paid');
             
            $purchases_amount_paid = $this->shorten($purchases_sum);
            //$sales_paid = $this->shorten(Sale::sum('amount_paid'));

            $sales_paid = 0;
            $delivered_and_remitted_orders = $selected_warehouse->orders()->where('status', 'delivered_and_remitted')->pluck('id');
            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
            ->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()]);
            $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        
            $total_products = Product::whereNull('combo_product_ids')->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->whereIn('id', $warehouse_product_ids)->get();
            
            $out_of_stock_products = [];
            if(count($total_products) > 0) {
                foreach ($total_products as $key => $product) {
                    if ($product->stock_available() < 10) {
                        $out_of_stock_products[] = $product;
                    } 
                }
            }
            
            $warehouses = WareHouse::all();
    
            //$sale_revenue = $this->shorten(Sale::sum('amount_paid'));
    
            //$sales_sum = Sale::sum('amount_paid');
            $sales_sum = $sales_paid;
            $purchase_sum = Purchase::sum('amount_paid');
            $expense_sum = Expense::sum('amount');
    
            $total_expenses = $this->shorten($purchase_sum + $expense_sum);
    
            $profit_val = $sales_sum - ($purchase_sum + $expense_sum);
    
            if ($profit_val > 0) {
                $profit = $this->shorten($profit_val);
            } else {
                $profit = $this->shorten($profit_val);
            }
    
            //$orders = Order::all();
    
            $suppliers = Supplier::all();
    
            $purchase_sum = $this->shorten($purchase_sum);
            $sales_sum = $this->shorten($sales_sum);
            $sales_paid = $this->shorten($sales_paid);
    
            $customers = Customer::all();
    
            $recently_products = $selected_warehouse->products()->get();
            $recently_products = Product::whereIn('id', $recently_products->pluck('id'))->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->get();
            
            $categories = Category::all();

            //warehouse orders
            $orders = $selected_warehouse->orders()->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->get(); $outgoingStocks = ''; $total_revenue = 0; $packages = [];
            if (count($orders) > 0) {
                $outgoingStocks = OutgoingStock::whereIn('order_id', $orders->pluck('id'))->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()]);
        
                if (count($outgoingStocks->get()) > 0) {
                    $packages = []; $warehouseOrders = []; $total_revenue += $outgoingStocks->sum('amount_accrued');
                    foreach ($orders as $key => $order) {
                        $outgoingStock = $order->outgoingStocks()->orderBy('id', 'DESC');
                        if (count($outgoingStock->get()) > 0) {
                            $warehouseOrders['warehouseOrder'] = 
                            [
                                'order'=>$order,
                                'outgoingStock'=>$outgoingStock->get(),
                                'orderRevenue'=>$outgoingStock->sum('amount_accrued'),
                            ];
                            $packages[] = $warehouseOrders;
                        }
                    }
                }
            }
            
            //warehouse product transfers
            $transfers = ProductTransfer::where('from_warehouse_id', $selected_warehouse->id)->orWhere('to_warehouse_id', $selected_warehouse->id)
            ->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->get(); 
            
        } else {
            
            $selected_warehouse = '';
            $product_purchase_ids = Product::whereNull('combo_product_ids')->pluck('purchase_id');
            $purchases_sum = Purchase::whereIn('id', $product_purchase_ids)->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->sum('amount_paid');
             
            $purchases_amount_paid = $this->shorten($purchases_sum);
            //$sales_paid = $this->shorten(Sale::sum('amount_paid'));

            $sales_paid = 0;
            $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->pluck('id');
            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
            ->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()]);
            $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        
            $total_products = Product::whereNull('combo_product_ids')->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->get();
            
            $out_of_stock_products = [];
            if(count($total_products) > 0) {
                foreach ($total_products as $key => $product) {
                    if ($product->stock_available() < 10) {
                        $out_of_stock_products[] = $product;
                    } 
                }
            }
            
            $warehouses = WareHouse::all();
    
            //$sale_revenue = $this->shorten(Sale::sum('amount_paid'));
    
            //$sales_sum = Sale::sum('amount_paid');
            $sales_sum = $sales_paid;
            $purchase_sum = Purchase::sum('amount_paid');
            $expense_sum = Expense::sum('amount');
    
            $total_expenses = $this->shorten($purchase_sum + $expense_sum);
    
            $profit_val = $sales_sum - ($purchase_sum + $expense_sum);
    
            if ($profit_val > 0) {
                $profit = $this->shorten($profit_val);
            } else {
                $profit = $this->shorten($profit_val);
            }
    
            //$orders = Order::all();
    
            $suppliers = Supplier::all();
    
            $purchase_sum = $this->shorten($purchase_sum);
            $sales_sum = $this->shorten($sales_sum);
            $sales_paid = $this->shorten($sales_paid);
    
            $customers = Customer::all();

            $recently_products = Product::whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->get();
            
            $categories = Category::all();

            //warehouse orders
            $orders = Order::whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->get(); $outgoingStocks = ''; $total_revenue = 0; $packages = [];
            if (count($orders) > 0) {
                $outgoingStocks = OutgoingStock::whereIn('order_id', $orders->pluck('id'))->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()]);
        
                if (count($outgoingStocks->get()) > 0) {
                    $packages = []; $warehouseOrders = []; $total_revenue += $outgoingStocks->sum('amount_accrued');
                    foreach ($orders as $key => $order) {
                        $outgoingStock = $order->outgoingStocks()->orderBy('id', 'DESC');
                        if (count($outgoingStock->get()) > 0) {
                            $warehouseOrders['warehouseOrder'] = 
                            [
                                'order'=>$order,
                                'outgoingStock'=>$outgoingStock->get(),
                                'orderRevenue'=>$outgoingStock->sum('amount_accrued'),
                            ];
                            $packages[] = $warehouseOrders;
                        }
                    }
                }
            }
            
            //warehouse product transfers
            $transfers = ProductTransfer::whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->get();
        }

        return view('pages.inventory.inventory', \compact('authUser', 'user_role', 'record', 'currency', 'selected_warehouse', 'total_products', 'out_of_stock_products', 'warehouses', 'total_expenses',
        'profit', 'profit_val', 'orders', 'suppliers', 'purchase_sum', 'customers', 'sales_sum', 'recently_products', 'purchases_amount_paid', 'sales_paid', 'categories',
        'outgoingStocks', 'total_revenue', 'packages', 'transfers'));
    }
    
    //yearly
    public function inventoryDashboardYearly($warehouse_unique_key="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'yearly';
        /////////////////////////////////////////////////////
        $dt = Carbon::now();
        
        $selected_warehouse = '';
        if($warehouse_unique_key !== "") {
            $selected_warehouse = WareHouse::where('unique_key', $warehouse_unique_key)->first();
            if(!isset($selected_warehouse)){
                abort(404);
            }
            $warehouse_product_ids = $selected_warehouse->products()->pluck('purchase_id');
            $product_purchase_ids = Product::whereIn('id', $warehouse_product_ids)->whereNull('combo_product_ids')->pluck('purchase_id');
            $purchases_sum = Purchase::whereIn('id', $product_purchase_ids)->whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()])->sum('amount_paid');
             
            $purchases_amount_paid = $this->shorten($purchases_sum);
            //$sales_paid = $this->shorten(Sale::sum('amount_paid'));

            $sales_paid = 0;
            $delivered_and_remitted_orders = $selected_warehouse->orders()->where('status', 'delivered_and_remitted')->pluck('id');
            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
            ->whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()]);
            $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        
            $total_products = Product::whereNull('combo_product_ids')->whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()])->whereIn('id', $warehouse_product_ids)->get();
            
            $out_of_stock_products = [];
            if(count($total_products) > 0) {
                foreach ($total_products as $key => $product) {
                    if ($product->stock_available() < 10) {
                        $out_of_stock_products[] = $product;
                    } 
                }
            }
            
            $warehouses = WareHouse::all();
    
            //$sale_revenue = $this->shorten(Sale::sum('amount_paid'));
    
            //$sales_sum = Sale::sum('amount_paid');
            $sales_sum = $sales_paid;
            $purchase_sum = Purchase::sum('amount_paid');
            $expense_sum = Expense::sum('amount');
    
            $total_expenses = $this->shorten($purchase_sum + $expense_sum);
    
            $profit_val = $sales_sum - ($purchase_sum + $expense_sum);
    
            if ($profit_val > 0) {
                $profit = $this->shorten($profit_val);
            } else {
                $profit = $this->shorten($profit_val);
            }
    
            //$orders = Order::all();
    
            $suppliers = Supplier::all();
    
            $purchase_sum = $this->shorten($purchase_sum);
            $sales_sum = $this->shorten($sales_sum);
            $sales_paid = $this->shorten($sales_paid);
    
            $customers = Customer::all();
    
            $recently_products = $selected_warehouse->products()->get();
            $recently_products = Product::whereIn('id', $recently_products->pluck('id'))->whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()])->get();
            
            $categories = Category::all();

            //warehouse orders
            $orders = $selected_warehouse->orders()->whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()])->get(); $outgoingStocks = ''; $total_revenue = 0; $packages = [];
            if (count($orders) > 0) {
                $outgoingStocks = OutgoingStock::whereIn('order_id', $orders->pluck('id'))->whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()]);
        
                if (count($outgoingStocks->get()) > 0) {
                    $packages = []; $warehouseOrders = []; $total_revenue += $outgoingStocks->sum('amount_accrued');
                    foreach ($orders as $key => $order) {
                        $outgoingStock = $order->outgoingStocks()->orderBy('id', 'DESC');
                        if (count($outgoingStock->get()) > 0) {
                            $warehouseOrders['warehouseOrder'] = 
                            [
                                'order'=>$order,
                                'outgoingStock'=>$outgoingStock->get(),
                                'orderRevenue'=>$outgoingStock->sum('amount_accrued'),
                            ];
                            $packages[] = $warehouseOrders;
                        }
                    }
                }
            }
            
            //warehouse product transfers
            $transfers = ProductTransfer::where('from_warehouse_id', $selected_warehouse->id)->orWhere('to_warehouse_id', $selected_warehouse->id)
            ->whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()])->get(); 
            
        } else {
            
            $selected_warehouse = '';
            $product_purchase_ids = Product::whereNull('combo_product_ids')->pluck('purchase_id');
            $purchases_sum = Purchase::whereIn('id', $product_purchase_ids)->whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()])->sum('amount_paid');
             
            $purchases_amount_paid = $this->shorten($purchases_sum);
            //$sales_paid = $this->shorten(Sale::sum('amount_paid'));

            $sales_paid = 0;
            $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->pluck('id');
            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
            ->whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()]);
            $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        
            $total_products = Product::whereNull('combo_product_ids')->whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()])->get();
            
            $out_of_stock_products = [];
            if(count($total_products) > 0) {
                foreach ($total_products as $key => $product) {
                    if ($product->stock_available() < 10) {
                        $out_of_stock_products[] = $product;
                    } 
                }
            }
            
            $warehouses = WareHouse::all();
    
            //$sale_revenue = $this->shorten(Sale::sum('amount_paid'));
    
            //$sales_sum = Sale::sum('amount_paid');
            $sales_sum = $sales_paid;
            $purchase_sum = Purchase::sum('amount_paid');
            $expense_sum = Expense::sum('amount');
    
            $total_expenses = $this->shorten($purchase_sum + $expense_sum);
    
            $profit_val = $sales_sum - ($purchase_sum + $expense_sum);
    
            if ($profit_val > 0) {
                $profit = $this->shorten($profit_val);
            } else {
                $profit = $this->shorten($profit_val);
            }
    
            //$orders = Order::all();
    
            $suppliers = Supplier::all();
    
            $purchase_sum = $this->shorten($purchase_sum);
            $sales_sum = $this->shorten($sales_sum);
            $sales_paid = $this->shorten($sales_paid);
    
            $customers = Customer::all();

            $recently_products = Product::whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()])->get();
            
            $categories = Category::all();

            //warehouse orders
            $orders = Order::whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()])->get(); $outgoingStocks = ''; $total_revenue = 0; $packages = [];
            if (count($orders) > 0) {
                $outgoingStocks = OutgoingStock::whereIn('order_id', $orders->pluck('id'))->whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()]);
        
                if (count($outgoingStocks->get()) > 0) {
                    $packages = []; $warehouseOrders = []; $total_revenue += $outgoingStocks->sum('amount_accrued');
                    foreach ($orders as $key => $order) {
                        $outgoingStock = $order->outgoingStocks()->orderBy('id', 'DESC');
                        if (count($outgoingStock->get()) > 0) {
                            $warehouseOrders['warehouseOrder'] = 
                            [
                                'order'=>$order,
                                'outgoingStock'=>$outgoingStock->get(),
                                'orderRevenue'=>$outgoingStock->sum('amount_accrued'),
                            ];
                            $packages[] = $warehouseOrders;
                        }
                    }
                }
            }
            
            //warehouse product transfers
            $transfers = ProductTransfer::whereBetween('created_at', [$dt->copy()->startOfYear(), $dt->copy()->endOfYear()])->get();
        }

        return view('pages.inventory.inventory', \compact('authUser', 'user_role', 'record', 'currency', 'selected_warehouse', 'total_products', 'out_of_stock_products', 'warehouses', 'total_expenses',
        'profit', 'profit_val', 'orders', 'suppliers', 'purchase_sum', 'customers', 'sales_sum', 'recently_products', 'purchases_amount_paid', 'sales_paid', 'categories',
        'outgoingStocks', 'total_revenue', 'packages', 'transfers'));
    }
    
    //by major warehouse
    public function inStockProductsByWarehouse()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $products = Product::all();
        //$pro = Product::find(1);
        // return $pro->warehouses->where('type','minor')->count();
        $in_stock_products = [];
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

    public function allProductInventory($stock="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $products = Product::all();
        
        return view('pages.inventory.allProductInventory', compact('authUser', 'user_role', 'products', 'stock'));
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
            $num = number_format(abs( (int) $num / 1000), $digits, '.', '') + 0;
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
