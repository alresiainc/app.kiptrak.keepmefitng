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

        // Compute 'today' variants from Orders domain
        $todayBestSelling = $this->formatProductData($this->aggregateProducts('today', 5));

        // Best customers today (computed realised amount)
        $todayOrders = Order::with('outgoingStock')->whereDate('created_at', Carbon::today())->get();
        $custAgg = [];
        foreach ($todayOrders as $o) {
            if (!$o->customer_id) continue;
            $amt = $this->orderRealisedAmount($o);
            if (!isset($custAgg[$o->customer_id])) {
                $custAgg[$o->customer_id] = (object) [
                    'customer_id' => $o->customer_id,
                    'order_count' => 0,
                    'total_spent' => 0.0,
                ];
            }
            $custAgg[$o->customer_id]->order_count += 1;
            $custAgg[$o->customer_id]->total_spent += $amt;
        }
        $custList = array_values($custAgg);
        usort($custList, fn($a, $b) => ($b->total_spent <=> $a->total_spent));
        $todayBestCustomers = $this->formatCustomerData(array_slice($custList, 0, 5));

        // Best staff today (computed realised amount)
        $staffAgg = [];
        foreach ($todayOrders as $o) {
            if (!$o->staff_assigned_id) continue;
            $amt = $this->orderRealisedAmount($o);
            if (!isset($staffAgg[$o->staff_assigned_id])) {
                $staffAgg[$o->staff_assigned_id] = (object) [
                    'staff_assigned_id' => $o->staff_assigned_id,
                    'order_count' => 0,
                    'total_sales' => 0.0,
                ];
            }
            $staffAgg[$o->staff_assigned_id]->order_count += 1;
            $staffAgg[$o->staff_assigned_id]->total_sales += $amt;
        }
        $staffList = array_values($staffAgg);
        usort($staffList, fn($a, $b) => ($b->total_sales <=> $a->total_sales));
        $todayBestStaff = $this->formatStaffData(array_slice($staffList, 0, 5));

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

    /**
     * Compute realised amount for an order based on outgoingStock.package_bundle (accepted items)
     * plus extra_cost_amount. Mirrors the logic in OrderController::singleOrder().
     */
    private function orderRealisedAmount(Order $order): float
    {
        $sum = 0.0;
        if ($order->outgoingStock && $order->outgoingStock->package_bundle) {
            $bundle = $order->outgoingStock->package_bundle;
            $decoded = is_string($bundle) ? json_decode($bundle, true) : $bundle;
            if (is_array($decoded)) {
                foreach ($decoded as $item) {
                    if (!is_array($item)) continue;
                    $accepted = ($item['customer_acceptance_status'] ?? null) === 'accepted';
                    $reason = $item['reason_removed'] ?? null;
                    if ($accepted && in_array($reason, ['as_order_firstphase', 'as_orderbump', 'as_upsell'])) {
                        $sum += (float) ($item['amount_accrued'] ?? 0);
                    }
                }
            }
        }

        // Add extra cost if any (stored as string sometimes)
        $extraRaw = $order->extra_cost_amount ?? 0;
        $extra = is_numeric($extraRaw) ? (float) $extraRaw : (float) preg_replace('/[^\d.\-]/', '', (string) $extraRaw);
        return $sum + ($extra ?: 0.0);
    }

    private function ordersForPeriod(string $period)
    {
        $q = Order::with('outgoingStock');
        $this->applyPeriodFilter($q, $period);
        return $q->get();
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
        // Aggregate product quantities from Orders domain
        $agg = $this->aggregateProducts($period, 50); // returns product_id, total_sold, total_revenue
        $productIds = collect($agg)->pluck('product_id')->unique()->values();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        return collect($agg)->map(function ($row) use ($products) {
            $p = $products->get($row['product_id']);
            return [
                'product_name' => $p?->name ?? 'N/A',
                'total_orders' => (int) ($row['total_sold'] ?? 0),
            ];
        })->values();
    }

    private function aggregateProducts(string $period, int $limit = 0)
    {
        // Pull orders for the period with their outgoing stock package bundles
        $ordersQ = Order::with('outgoingStock');
        $this->applyPeriodFilter($ordersQ, $period);
        $orders = $ordersQ->get();

        $totals = [];
        foreach ($orders as $order) {
            $counted = false;
            if ($order->outgoingStock && $order->outgoingStock->package_bundle) {
                $bundle = $order->outgoingStock->package_bundle; // json cast? migration defines json column
                // Ensure array
                if (is_string($bundle)) {
                    $decoded = json_decode($bundle, true);
                } else {
                    $decoded = $bundle; // already array if casted
                }
                if (is_array($decoded)) {
                    foreach ($decoded as $item) {
                        if (!is_array($item)) continue;
                        $accepted = ($item['customer_acceptance_status'] ?? null) === 'accepted';
                        $reason = $item['reason_removed'] ?? null;
                        if ($accepted && in_array($reason, ['as_order_firstphase', 'as_orderbump', 'as_upsell'])) {
                            $pid = (int) ($item['product_id'] ?? 0);
                            if ($pid > 0) {
                                $qty = (int) ($item['quantity_removed'] ?? 1);
                                $rev = (float) ($item['amount_accrued'] ?? 0);
                                if (!isset($totals[$pid])) $totals[$pid] = ['product_id' => $pid, 'total_sold' => 0, 'total_revenue' => 0];
                                $totals[$pid]['total_sold'] += $qty;
                                $totals[$pid]['total_revenue'] += $rev;
                                $counted = true;
                            }
                        }
                    }
                }
            }
            // Fallback if no outgoing bundle: count product ids from orders.products
            if (!$counted && !empty($order->products)) {
                $ids = @unserialize($order->products);
                if ($ids && is_array($ids)) {
                    foreach ($ids as $pid) {
                        $pid = (int) $pid;
                        if ($pid > 0) {
                            if (!isset($totals[$pid])) $totals[$pid] = ['product_id' => $pid, 'total_sold' => 0, 'total_revenue' => 0];
                            $totals[$pid]['total_sold'] += 1; // no qty available, treat as 1
                        }
                    }
                }
            }
        }

        // Sort by total_sold desc and limit
        $list = array_values($totals);
        usort($list, function ($a, $b) {
            return ($b['total_sold'] <=> $a['total_sold']);
        });
        if ($limit > 0) {
            $list = array_slice($list, 0, $limit);
        }
        return $list;
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
        // Aggregate from Orders domain (orders + outgoing_stocks.package_bundle or orders.products)
        $yearly = $this->aggregateProducts('year', 5);
        $monthly = $this->aggregateProducts('month', 5);
        $weekly = $this->aggregateProducts('week', 5);

        return [
            'yearly' => $this->formatProductData($yearly),
            'monthly' => $this->formatProductData($monthly),
            'weekly' => $this->formatProductData($weekly)
        ];
    }

    /**
     * Format product data with product details
     */
    private function formatProductData($products)
    {
        // $products: collection/array of arrays or objects with product_id, total_sold, total_revenue
        $formatted = [];
        foreach ($products as $p) {
            $productId = is_array($p) ? ($p['product_id'] ?? null) : ($p->product_id ?? null);
            if (!$productId) continue;
            $productDetails = Product::find($productId);
            if ($productDetails) {
                $totalSold = is_array($p) ? ($p['total_sold'] ?? 0) : ($p->total_sold ?? 0);
                $totalRevenue = is_array($p) ? ($p['total_revenue'] ?? 0) : ($p->total_revenue ?? 0);
                $formatted[] = (object) [
                    'id' => $productId,
                    'name' => $productDetails->name,
                    'code' => $productDetails->code,
                    'image' => $productDetails->image,
                    'total_sold' => (int) $totalSold,
                    'total_revenue' => (float) $totalRevenue,
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
        $build = function (string $period) {
            $orders = $this->ordersForPeriod($period);
            $agg = [];
            foreach ($orders as $o) {
                if (!$o->customer_id) continue;
                $amt = $this->orderRealisedAmount($o);
                if (!isset($agg[$o->customer_id])) {
                    $agg[$o->customer_id] = (object) [
                        'customer_id' => $o->customer_id,
                        'order_count' => 0,
                        'total_spent' => 0.0,
                    ];
                }
                $agg[$o->customer_id]->order_count += 1;
                $agg[$o->customer_id]->total_spent += $amt;
            }
            $list = array_values($agg);
            usort($list, fn($a, $b) => ($b->total_spent <=> $a->total_spent));
            return array_slice($list, 0, 5);
        };

        return [
            'yearly' => $this->formatCustomerData($build('year')),
            'monthly' => $this->formatCustomerData($build('month')),
            'weekly' => $this->formatCustomerData($build('week'))
        ];
    }

    /**
     * Format customer data with customer details
     */
    private function formatCustomerData($customers)
    {
        $formatted = [];
        foreach ($customers as $c) {
            $customerDetails = Customer::find($c->customer_id);
            if ($customerDetails) {
                $formatted[] = (object) [
                    'id' => $c->customer_id,
                    'name' => trim(($customerDetails->firstname ?? '') . ' ' . ($customerDetails->lastname ?? '')),
                    'email' => $customerDetails->email,
                    'phone' => $customerDetails->phone_number,
                    'order_count' => (int) ($c->order_count ?? 0),
                    'total_spent' => (float) ($c->total_spent ?? 0),
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
        $build = function (string $period) {
            $orders = $this->ordersForPeriod($period);
            $agg = [];
            foreach ($orders as $o) {
                if (!$o->staff_assigned_id) continue;
                $amt = $this->orderRealisedAmount($o);
                if (!isset($agg[$o->staff_assigned_id])) {
                    $agg[$o->staff_assigned_id] = (object) [
                        'staff_assigned_id' => $o->staff_assigned_id,
                        'order_count' => 0,
                        'total_sales' => 0.0,
                    ];
                }
                $agg[$o->staff_assigned_id]->order_count += 1;
                $agg[$o->staff_assigned_id]->total_sales += $amt;
            }
            $list = array_values($agg);
            usort($list, fn($a, $b) => ($b->total_sales <=> $a->total_sales));
            return $list;
        };

        return [
            'yearly' => $this->formatStaffData(array_slice($build('year'), 0, 5)),
            'monthly' => $this->formatStaffData(array_slice($build('month'), 0, 5)),
            'weekly' => $this->formatStaffData(array_slice($build('week'), 0, 5))
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
        // Monthly realised revenue for the current year
        $monthlySales = [];
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        foreach ($months as $index => $month) {
            $monthNumber = $index + 1;
            $orders = Order::with('outgoingStock')
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', $monthNumber)
                ->get();
            $sales = 0.0;
            foreach ($orders as $o) {
                $sales += $this->orderRealisedAmount($o);
            }

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

        // Calculate average order value using realised amounts
        $allOrders = Order::with('outgoingStock')->get();
        $totalRevenue = 0.0;
        foreach ($allOrders as $o) {
            $totalRevenue += $this->orderRealisedAmount($o);
        }
        $orderCount = max((int) Order::count(), 1);
        $averageOrderValue = $totalRevenue / $orderCount;

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

        // Get delivered orders this month (delivered_* statuses)
        $deliveredThisMonth = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereIn('status', ['delivered_not_remitted', 'delivered_and_remitted'])
            ->count();

        // Get average order value using realised amounts
        $allOrders = Order::with('outgoingStock')->get();
        $totalRevenue = 0.0;
        foreach ($allOrders as $o) {
            $totalRevenue += $this->orderRealisedAmount($o);
        }
        $averageOrderValue = $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0;

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
        // Compute realised revenue across periods
        $sumFor = function (callable $filter) {
            $orders = Order::with('outgoingStock')->where($filter)->get();
            $sum = 0.0;
            foreach ($orders as $o) { $sum += $this->orderRealisedAmount($o); }
            return $sum;
        };

        $totalRevenueThisYear = $sumFor(function ($q) { $q->whereYear('created_at', Carbon::now()->year); });
        $totalRevenueThisMonth = $sumFor(function ($q) { $q->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year); });
        $totalRevenueThisWeek = $sumFor(function ($q) { $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]); });

        $lastMonthRevenue = $sumFor(function ($q) {
            $q->whereMonth('created_at', Carbon::now()->subMonth()->month)->whereYear('created_at', Carbon::now()->year);
        });

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
