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

        // New metrics by periods
        $orderStatusCounts = [
            'yearly' => $this->getOrderStatusCounts('year'),
            'monthly' => $this->getOrderStatusCounts('month'),
            'weekly' => $this->getOrderStatusCounts('week'),
            'today' => $this->getOrderStatusCounts('today'),
        ];

        $stateCounts = [
            'yearly' => $this->getStateCounts('year'),
            'monthly' => $this->getStateCounts('month'),
            'weekly' => $this->getStateCounts('week'),
            'today' => $this->getStateCounts('today'),
        ];

        $productCounts = [
            'yearly' => $this->getProductCounts('year'),
            'monthly' => $this->getProductCounts('month'),
            'weekly' => $this->getProductCounts('week'),
            'today' => $this->getProductCounts('today'),
        ];

        $dayOfWeekCounts = [
            'yearly' => $this->getOrdersByDayOfWeek('year'),
            'monthly' => $this->getOrdersByDayOfWeek('month'),
            'weekly' => $this->getOrdersByDayOfWeek('week'),
            'today' => $this->getOrdersByDayOfWeek('today'),
        ];

        $deliveryRate = [
            'yearly' => $this->getDeliveryRate('year'),
            'monthly' => $this->getDeliveryRate('month'),
            'weekly' => $this->getDeliveryRate('week'),
            'today' => $this->getDeliveryRate('today'),
        ];

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
            'orderStatusCounts' => $orderStatusCounts,
            'stateCounts' => $stateCounts,
            'productCounts' => $productCounts,
            'dayOfWeekCounts' => $dayOfWeekCounts,
            'deliveryRate' => $deliveryRate,
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

    private function applyPeriodFilter($query, string $period, string $column = 'created_at')
    {
        switch ($period) {
            case 'today':
                return $query->whereDate($column, Carbon::today());
            case 'week':
                return $query->whereBetween($column, [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            case 'month':
                return $query->whereMonth($column, Carbon::now()->month)->whereYear($column, Carbon::now()->year);
            case 'year':
                return $query->whereYear($column, Carbon::now()->year);
            default:
                return $query;
        }
    }

    private function getOrderStatusCounts(string $period)
    {
        $q = Order::select('status', DB::raw('COUNT(id) as order_count'));
        $this->applyPeriodFilter($q, $period);
        $rows = $q->groupBy('status')->orderBy('order_count', 'desc')->get();

        // Map display labels using config if available
        $labels = config('site.order_statuses', []);
        return $rows->map(function ($row) use ($labels) {
            return [
                'status' => $labels[$row->status] ?? $row->status,
                'code' => $row->status,
                'order_count' => (int) $row->order_count,
            ];
        })->values();
    }

    private function getStateCounts(string $period)
    {
        // Count orders grouped by customer state (customers table has 'state')
        $q = DB::table('orders as o')
            ->leftJoin('customers as c', 'c.id', '=', 'o.customer_id')
            ->select(DB::raw('COALESCE(c.state, "N/A") as state_name'), DB::raw('COUNT(o.id) as total_orders'));
        // Explicitly filter on orders.created_at to avoid ambiguity
        $this->applyPeriodFilter($q, $period, 'o.created_at');
        $rows = $q->groupBy('c.state')->orderBy('total_orders', 'desc')->limit(50)->get();
        return $rows->map(function ($row) {
            return [
                'state_name' => $row->state_name,
                'total_orders' => (int) $row->total_orders,
            ];
        })->values();
    }

    private function getProductCounts(string $period)
    {
        // Use sales table as proxy for product orders
        $q = Sale::select('product_id', DB::raw('SUM(product_qty_sold) as total_orders'));
        $this->applyPeriodFilter($q, $period);
        $rows = $q->groupBy('product_id')->orderBy('total_orders', 'desc')->limit(50)->get();
        $productIds = $rows->pluck('product_id')->filter()->unique()->values();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        return $rows->map(function ($row) use ($products) {
            $p = $products->get($row->product_id);
            return [
                'product_name' => $p?->name ?? 'N/A',
                'total_orders' => (int) $row->total_orders,
            ];
        })->values();
    }

    private function getOrdersByDayOfWeek(string $period)
    {
        $q = Order::select(DB::raw('DAYNAME(created_at) as day_name'), DB::raw('DAYOFWEEK(created_at) as day_number'), DB::raw('COUNT(id) as order_count'));
        $this->applyPeriodFilter($q, $period);
        $rows = $q->groupBy('day_name', 'day_number')->orderBy('day_number')->get();
        return $rows->map(function ($row) {
            return [
                'day_name' => $row->day_name,
                'day_number' => (int) $row->day_number,
                'order_count' => (int) $row->order_count,
            ];
        })->values();
    }

    private function getDeliveryRate(string $period)
    {
        // total orders in period
        $totalQ = Order::query();
        $this->applyPeriodFilter($totalQ, $period);
        $total = (int) $totalQ->count();

        // payment received = delivered_and_remitted
        $deliveredQ = Order::where('status', 'delivered_and_remitted');
        $this->applyPeriodFilter($deliveredQ, $period);
        $paymentReceived = (int) $deliveredQ->count();

        $percentage = $total > 0 ? round(($paymentReceived / $total) * 100, 2) : 0;
        return [
            'total_orders' => $total,
            'payment_received_orders' => $paymentReceived,
            'delivery_rate_percentage' => $percentage,
        ];
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
