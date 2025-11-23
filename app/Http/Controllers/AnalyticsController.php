<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\Order;
use App\Models\OutgoingStock;
// use App\Models\Staff;
use App\Helpers\Helper;
use App\Models\User;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function analytics()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;

        // Best selling products
        $bestSellingProducts = $this->getBestSellingProducts();

        // Best customers
        $bestCustomers = $this->getBestCustomers();

        // Best performing staff
        $bestStaff = $this->getBestStaff();

        // Sales trends
        $salesTrends = $this->getSalesTrends();

        // Product performance
        $productPerformance = $this->getProductPerformance();

        // Customer insights
        $customerInsights = $this->getCustomerInsights();

        // Order statistics
        $orderStats = $this->getOrderStatistics();

        // Revenue analysis
        $revenueAnalysis = $this->getRevenueAnalysis();

        return view('pages.analytics.analytics', compact(
            'authUser',
            'user_role',
            'generalSetting',
            'currency',
            'bestSellingProducts',
            'bestCustomers',
            'bestStaff',
            'salesTrends',
            'productPerformance',
            'customerInsights',
            'orderStats',
            'revenueAnalysis'
        ));
    }

    /**
     * Return analytics data as JSON for AJAX consumers
     */
    public function data(Request $request)
    {
        $period = strtolower($request->query('period', 'all'));

        // Base datasets using existing helpers
        $bestSellingProducts = $this->getBestSellingProducts();
        $bestCustomers = $this->getBestCustomers();
        $bestStaff = $this->getBestStaff();
        $salesTrends = $this->getSalesTrends();
        $productPerformance = $this->getProductPerformance();
        $customerInsights = $this->getCustomerInsights();
        $orderStats = $this->getOrderStatistics();
        $revenueAnalysis = $this->getRevenueAnalysis();

        // Compute 'today' variants where applicable
        $todayBestSelling = $this->formatProductData(
            Sale::select('product_id', DB::raw('SUM(product_qty_sold) as total_sold'), DB::raw('SUM(amount_paid) as total_revenue'))
                ->whereDate('created_at', Carbon::today())
                ->groupBy('product_id')
                ->orderBy('total_sold', 'desc')
                ->take(5)
                ->get()
        );

        $todayBestCustomers = $this->formatCustomerData(
            Sale::select('customer_id', DB::raw('COUNT(id) as order_count'), DB::raw('SUM(amount_paid) as total_spent'))
                ->whereDate('created_at', Carbon::today())
                ->groupBy('customer_id')
                ->orderBy('total_spent', 'desc')
                ->take(5)
                ->get()
        );

        $todayBestStaff = $this->formatStaffData(
            Order::select('staff_assigned_id', DB::raw('COUNT(id) as order_count'), DB::raw('SUM(amount_realised) as total_sales'))
                ->whereDate('created_at', Carbon::today())
                ->whereNotNull('staff_assigned_id')
                ->groupBy('staff_assigned_id')
                ->orderBy('total_sales', 'desc')
                ->take(5)
                ->get()
        );

        // Attach 'today' buckets to match existing structure
        $bestSellingProducts['today'] = $todayBestSelling;
        $bestCustomers['today'] = $todayBestCustomers;
        $bestStaff['today'] = $todayBestStaff;

        // Choose selected dataset alias
        $map = [
            'today' => 'today',
            'week' => 'weekly',
            'month' => 'monthly',
            'year' => 'yearly',
            'all' => 'yearly',
        ];
        $selectedKey = $map[$period] ?? 'yearly';

        return response()->json([
            'period' => $period,
            'selected_key' => $selectedKey,
            'bestSellingProducts' => $bestSellingProducts,
            'bestCustomers' => $bestCustomers,
            'bestStaff' => $bestStaff,
            'salesTrends' => $salesTrends,
            'productPerformance' => $productPerformance,
            'customerInsights' => $customerInsights,
            'orderStats' => $orderStats,
            'revenueAnalysis' => $revenueAnalysis,
        ]);
    }

    /**
     * Get best selling products
     */
    private function getBestSellingProducts()
    {
        // Yearly best selling products
        $yearlyBestSelling = Sale::select(
            'product_id',
            DB::raw('SUM(product_qty_sold) as total_sold'),
            DB::raw('SUM(amount_paid) as total_revenue')
        )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        // Monthly best selling products
        $monthlyBestSelling = Sale::select(
            'product_id',
            DB::raw('SUM(product_qty_sold) as total_sold'),
            DB::raw('SUM(amount_paid) as total_revenue')
        )
            ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        // Weekly best selling products
        $weeklyBestSelling = Sale::select(
            'product_id',
            DB::raw('SUM(product_qty_sold) as total_sold'),
            DB::raw('SUM(amount_paid) as total_revenue')
        )
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        return [
            'yearly' => $this->formatProductData($yearlyBestSelling),
            'monthly' => $this->formatProductData($monthlyBestSelling),
            'weekly' => $this->formatProductData($weeklyBestSelling)
        ];
    }

    /**
     * Format product data with product details
     */
    private function formatProductData($products)
    {
        $formatted = [];

        foreach ($products as $product) {
            $productDetails = Product::find($product->product_id);

            if ($productDetails) {
                $formatted[] = (object)[
                    'id' => $product->product_id,
                    'name' => $productDetails->name,
                    'code' => $productDetails->code,
                    'image' => $productDetails->image,
                    'total_sold' => $product->total_sold,
                    'total_revenue' => $product->total_revenue
                ];
            }
        }

        return $formatted;
    }

    /**
     * Get best customers
     */
    private function getBestCustomers()
    {
        // Yearly best customers
        $yearlyBestCustomers = Sale::select(
            'customer_id',
            DB::raw('COUNT(id) as order_count'),
            DB::raw('SUM(amount_paid) as total_spent')
        )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('customer_id')
            ->orderBy('total_spent', 'desc')
            ->take(5)
            ->get();

        // Monthly best customers
        $monthlyBestCustomers = Sale::select(
            'customer_id',
            DB::raw('COUNT(id) as order_count'),
            DB::raw('SUM(amount_paid) as total_spent')
        )
            ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('customer_id')
            ->orderBy('total_spent', 'desc')
            ->take(5)
            ->get();

        // Weekly best customers
        $weeklyBestCustomers = Sale::select(
            'customer_id',
            DB::raw('COUNT(id) as order_count'),
            DB::raw('SUM(amount_paid) as total_spent')
        )
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy('customer_id')
            ->orderBy('total_spent', 'desc')
            ->take(5)
            ->get();

        return [
            'yearly' => $this->formatCustomerData($yearlyBestCustomers),
            'monthly' => $this->formatCustomerData($monthlyBestCustomers),
            'weekly' => $this->formatCustomerData($weeklyBestCustomers)
        ];
    }

    /**
     * Format customer data with customer details
     */
    private function formatCustomerData($customers)
    {
        $formatted = [];

        foreach ($customers as $customer) {
            $customerDetails = Customer::find($customer->customer_id);

            if ($customerDetails) {
                $formatted[] = (object)[
                    'id' => $customer->customer_id,
                    'name' => $customerDetails->first_name . ' ' . $customerDetails->last_name,
                    'email' => $customerDetails->email,
                    'phone' => $customerDetails->phone,
                    'order_count' => $customer->order_count,
                    'total_spent' => $customer->total_spent
                ];
            }
        }

        return $formatted;
    }

    /**
     * Get best performing staff
     */
    private function getBestStaff()
    {
        // Yearly best staff
        $yearlyBestStaff = Order::select(
            'staff_assigned_id',
            DB::raw('COUNT(id) as order_count'),
            DB::raw('SUM(amount_realised) as total_sales')
        )
            ->whereYear('created_at', Carbon::now()->year)
            ->whereNotNull('staff_assigned_id')
            ->groupBy('staff_assigned_id')
            ->orderBy('total_sales', 'desc')
            ->take(5)
            ->get();

        // Monthly best staff
        $monthlyBestStaff = Order::select(
            'staff_assigned_id',
            DB::raw('COUNT(id) as order_count'),
            DB::raw('SUM(amount_realised) as total_sales')
        )
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereNotNull('staff_assigned_id')
            ->groupBy('staff_assigned_id')
            ->orderBy('total_sales', 'desc')
            ->take(5)
            ->get();

        // Weekly best staff
        $weeklyBestStaff = Order::select(
            'staff_assigned_id',
            DB::raw('COUNT(id) as order_count'),
            DB::raw('SUM(amount_realised) as total_sales')
        )
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->whereNotNull('staff_assigned_id')
            ->groupBy('staff_assigned_id')
            ->orderBy('total_sales', 'desc')
            ->take(5)
            ->get();

        return [
            'yearly' => $this->formatStaffData($yearlyBestStaff),
            'monthly' => $this->formatStaffData($monthlyBestStaff),
            'weekly' => $this->formatStaffData($weeklyBestStaff)
        ];
    }

    /**
     * Format staff data with staff details
     */
    private function formatStaffData($staff)
    {
        $formatted = [];

        foreach ($staff as $staffMember) {
            $staffDetails = User::where('type', 'staff')->where("id", $staffMember->staff_assigned_id)->first();

            if ($staffDetails) {
                $formatted[] = (object)[
                    'id' => $staffMember->staff_assigned_id,
                    'name' => $staffDetails->firstname . ' ' . $staffDetails->lastname,
                    'email' => $staffDetails->email,
                    'image' => $staffDetails->profile_picture,
                    'order_count' => $staffMember->order_count,
                    'total_sales' => $staffMember->total_sales
                ];
            }
        }

        return $formatted;
    }

    /**
     * Get sales trends
     */
    private function getSalesTrends()
    {
        // Monthly sales for the current year
        $monthlySales = [];
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        foreach ($months as $index => $month) {
            $monthNumber = $index + 1;

            $sales = Order::whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', $monthNumber)
                ->sum('amount_realised');

            $monthlySales[] = [
                'month' => $month,
                'sales' => $sales
            ];
        }

        return $monthlySales;
    }

    /**
     * Get product performance
     */
    private function getProductPerformance()
    {
        // Get total products count
        $totalProducts = Product::count();

        // Get products with low stock
        $lowStockProducts = Product::where('quantity_limit', '<=', 5)
            ->count();

        // Get out of stock products
        $outOfStockProducts = Product::where('quantity_limit', 0)
            ->count();

        // Get total value of inventory
        $inventoryValue = Product::sum(DB::raw('quantity_limit * purchase_price'));

        return [
            'total_products' => $totalProducts,
            'low_stock_products' => $lowStockProducts,
            'out_of_stock_products' => $outOfStockProducts,
            'inventory_value' => $inventoryValue
        ];
    }

    /**
     * Get customer insights
     */
    private function getCustomerInsights()
    {
        // Get total customers count
        $totalCustomers = Customer::count();

        // Get new customers this month
        $newCustomersThisMonth = Customer::whereMonth('created_at', Carbon::now()->month)
            ->count();

        // Get inactive customers (no orders in the last 90 days)
        $inactiveCustomers = Customer::whereDoesntHave('sales', function ($query) {
            $query->whereBetween('created_at', [Carbon::now()->subDays(90), Carbon::now()]);
        })
            ->count();

        // Calculate average order value
        $averageOrderValue = Order::avg('amount_realised');

        // Get repeat customers (more than 1 order)
        $repeatCustomers = Customer::whereHas('sales', function ($query) {
            $query->select('customer_id')
                ->groupBy('customer_id')
                ->havingRaw('COUNT(id) > 1');
        })
            ->count();


        return [
            'total_customers' => $totalCustomers,
            'new_customers_this_month' => $newCustomersThisMonth,
            'inactive_customers' => $inactiveCustomers,
            'repeat_customers' => $repeatCustomers,
            'average_order_value' => $averageOrderValue
        ];
    }

    /**
     * Get order statistics
     */
    private function getOrderStatistics()
    {
        // Get total orders count
        $totalOrders = Order::count();

        // Get pending orders
        $pendingOrders = Order::where('status', 'pending')
            ->count();

        // Get delivered orders this month
        $deliveredThisMonth = Order::whereMonth('created_at', Carbon::now()->month)
            ->where('status', 'delivered')
            ->count();

        // Get average order value
        $averageOrderValue = Order::whereNotNull('amount_realised')
            ->avg('amount_realised');

        return [
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'delivered_this_month' => $deliveredThisMonth,
            'average_order_value' => $averageOrderValue
        ];
    }

    /**
     * Get revenue analysis
     */
    private function getRevenueAnalysis()
    {
        // Get total revenue this year
        $totalRevenueThisYear = Order::whereYear('created_at', Carbon::now()->year)
            ->sum('amount_realised');

        // Get total revenue this month
        $totalRevenueThisMonth = Order::whereMonth('created_at', Carbon::now()->month)
            ->sum('amount_realised');

        // Get total revenue this week
        $totalRevenueThisWeek = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('amount_realised');

        // Get revenue growth rate (month over month)
        $lastMonthRevenue = Order::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount_realised');

        $revenueGrowthRate = $lastMonthRevenue > 0
            ? (($totalRevenueThisMonth - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        return [
            'total_this_year' => $totalRevenueThisYear,
            'total_this_month' => $totalRevenueThisMonth,
            'total_this_week' => $totalRevenueThisWeek,
            'growth_rate' => $revenueGrowthRate,
            'total_revenue' => $totalRevenueThisYear
        ];
    }
}
