<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

class ReportController extends Controller
{
    
    public function productReport()
    {
        $products = Product::all();
        $warehouses = WareHouse::all();
        $start_date = '';
        $end_date = '';
        $warehouse_selected = '';
        
        return view('pages.reports.productReport', \compact('products', 'warehouses', 'start_date', 'end_date', 'warehouse_selected'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function productReportQuery(Request $request)
    {
        $data = $request->all();

        $start_date = '';
        $end_date = '';
        $warehouse_selected = '';

        if (!empty($data['warehouse_id']) && empty($data['start_date']) && empty($data['end_date'])) {
            $warehouse_selected = WareHouse::find($data['warehouse_id']);
            $products = Product::where('warehouse_id',$data['warehouse_id'])->get();
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
        
        $warehouses = WareHouse::all();
        return view('pages.reports.productReport', \compact('products', 'warehouses', 'start_date', 'end_date', 'warehouse_selected'));
    }

    public function saleReport()
    {
        $warehouses = WareHouse::all();
        $start_date = '';
        $end_date = '';
        $warehouse_selected = '';

        // $yearly_best_selling_qty = Sale::select(DB::raw('product_id, sum(product_qty_sold) as sold_qty'))->whereDate('created_at', '>=' , date("Y").'-01-01')
        // ->whereDate('created_at', '<=' , date("Y").'-12-31')->groupBy('product_id')->orderBy('sold_qty', 'desc')->take(5)->get();
        $yearly_best_selling_qty = Sale::select(DB::raw('product_id, sum(product_qty_sold) as sold_qty'))->groupBy('product_id')->orderBy('sold_qty', 'desc')->get();
        
        $sellingProductsBulk = [];
        foreach ($yearly_best_selling_qty as $key => $sale) {
            $product = Product::find($sale->product_id);
            $sellingProducts['product_name'] = $product->name;
            $sellingProducts['sold_amount'] = $this->soldAmount($product->id);
            $sellingProducts['sold_qty'] = $sale->sold_qty;
            $sellingProducts['stock_available'] = $product->stock_available();

            $sellingProductsBulk[] = $sellingProducts;
        }

        //return $bestSellingProductsBulk;

        return view('pages.reports.saleReport', \compact('warehouses', 'start_date', 'end_date', 'warehouse_selected', 'sellingProductsBulk'));
    }

    
    public function saleReportQuery(Request $request)
    {
        $data = $request->all();

        $start_date = '';
        $end_date = '';
        $warehouse_selected = '';

        if (!empty($data['warehouse_id']) && empty($data['start_date']) && empty($data['end_date'])) {

            $warehouse_selected = WareHouse::find($data['warehouse_id']);

            $yearly_best_selling_qty = Sale::where('warehouse_id', $data['warehouse_id'])->select(DB::raw('product_id, sum(product_qty_sold) as sold_qty'))
            ->groupBy('product_id')->orderBy('sold_qty', 'desc')->get();
            
            $sellingProductsBulk = [];
            foreach ($yearly_best_selling_qty as $key => $sale) {
                $product = Product::find($sale->product_id);
                $sellingProducts['product_name'] = $product->name;
                $sellingProducts['sold_amount'] = $this->soldAmount($product->id);
                $sellingProducts['sold_qty'] = $sale->sold_qty;
                $sellingProducts['stock_available'] = $product->stock_available();

                $sellingProductsBulk[] = $sellingProducts;
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

            $yearly_best_selling_qty = Sale::select(DB::raw('product_id, sum(product_qty_sold) as sold_qty'))->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->groupBy('product_id')->orderBy('sold_qty', 'desc')->get();
            
            $sellingProductsBulk = [];
            foreach ($yearly_best_selling_qty as $key => $sale) {
                $product = Product::find($sale->product_id);
                $sellingProducts['product_name'] = $product->name;
                $sellingProducts['sold_amount'] = $this->soldAmount($product->id);
                $sellingProducts['sold_qty'] = $sale->sold_qty;
                $sellingProducts['stock_available'] = $product->stock_available();

                $sellingProductsBulk[] = $sellingProducts;
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
            
            $yearly_best_selling_qty = Sale::where('warehouse_id',$data['warehouse_id'])->select(DB::raw('product_id, sum(product_qty_sold) as sold_qty'))->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->groupBy('product_id')->orderBy('sold_qty', 'desc')->get();
            
            $sellingProductsBulk = [];
            foreach ($yearly_best_selling_qty as $key => $sale) {
                $product = Product::find($sale->product_id);
                $sellingProducts['product_name'] = $product->name;
                $sellingProducts['sold_amount'] = $this->soldAmount($product->id);
                $sellingProducts['sold_qty'] = $sale->sold_qty;
                $sellingProducts['stock_available'] = $product->stock_available();

                $sellingProductsBulk[] = $sellingProducts;
            }
        }
        
        $warehouses = WareHouse::all();
        return view('pages.reports.saleReport', \compact('warehouses', 'start_date', 'end_date', 'warehouse_selected',  'sellingProductsBulk'));
    }

    //purchaseReport
    public function purchaseReport()
    {
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

        return view('pages.reports.purchaseReport', \compact('warehouses', 'start_date', 'end_date', 'warehouse_selected', 'purchasingProductsBulk'));
    }

    public function purchaseReportQuery(Request $request)
    {
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
        return view('pages.reports.purchaseReport', \compact('warehouses', 'start_date', 'end_date', 'warehouse_selected',  'purchasingProductsBulk'));
    }

    //customerReport
    public function customerReport($type="")
    {
        $customers = Customer::all();
        $start_date = '';
        $end_date = '';
        $customer_selected = '';

        $customer_selected = Customer::inRandomOrder()->where('id', '>', 0)->first();

        $customer_sales = Sale::where('parent_id', null)->where('customer_id', $customer_selected->id)->orderBy('id', 'desc')->get();

        return view('pages.reports.customerReport', \compact('customers', 'start_date', 'end_date', 'customer_selected', 'customer_sales'));
    }

    public function customerReportQuery(Request $request, $type="")
    {
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

        return view('pages.reports.customerReport', \compact('customers', 'start_date', 'end_date', 'customer_selected', 'customer_sales'));
    }

    //supplierReport
    public function supplierReport($type="")
    {
        $suppliers = Supplier::all();
        $start_date = '';
        $end_date = '';
        $supplier_selected = '';

        $supplier_selected = Supplier::inRandomOrder()->where('id', '>', 0)->first();
        $supplier_purchases = Purchase::where('parent_id', null)->where('supplier_id', $supplier_selected->id)->orderBy('id', 'desc')->get();

        return view('pages.reports.supplierReport', \compact('suppliers', 'start_date', 'end_date', 'supplier_selected', 'supplier_purchases'));
    }

    public function supplierReportQuery(Request $request, $type="")
    {
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

        return view('pages.reports.supplierReport', \compact('suppliers', 'start_date', 'end_date', 'supplier_selected', 'supplier_purchases'));
    }

    //staffReport
    public function staffReport($type="")
    {
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

        return view('pages.reports.staffReport', \compact('staffs', 'start_date', 'end_date', 'staff_selected', 'staff_sales', 'aspect', 'staff_purchases', 'staff_expenses'));
    }

    public function staffReportQuery(Request $request, $type="")
    {
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

        return view('pages.reports.staffReport', \compact('staffs', 'start_date', 'end_date', 'staff_selected', 'staff_sales', 'aspect', 'staff_purchases', 'staff_expenses'));
    }

    public function soldAmount($product_id){
        $soldAmount = OutgoingStock::where('product_id', $product_id)->sum('amount_accrued');
        return $soldAmount;
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
