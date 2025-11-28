@extends('layouts.design')

@section('title')
    Analytics Dashboard
@endsection

@section('extra_css')
    <style>
        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.35);
            z-index: 2000;
            backdrop-filter: blur(1px);
        }

        #loading-overlay.show {
            display: flex;
        }

        #loading-overlay .loading-content {
            text-align: center;
        }

        #loading-overlay .loading-content .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        #loading-overlay .loading-text {
            color: #fff;
            margin-top: 10px;
            font-weight: 600;
        }

        /* Equal height and scroll for analytics sorted cards */
        .equal-card {
            display: flex;
            flex-direction: column;
            height: 420px;
            /* adjust as needed */
        }

        .equal-card .card-body {
            display: flex;
            flex-direction: column;
            min-height: 0;
            /* enable flex children to shrink */
        }

        .equal-card .table-responsive {
            flex: 1 1 auto;
            overflow-y: auto;
            /* keep horizontal behavior from Bootstrap */
        }

        .equal-card table {
            margin-bottom: 0;
            /* avoid extra bottom space inside scroll */
        }

        /* Optional: better on small screens */
        @media (max-width: 575.98px) {
            .equal-card {
                height: 360px;
            }
        }
    </style>
@endsection

@section('content')
    <main id="main" class="main">
        <!-- Loading Overlay -->
        <div id="loading-overlay" aria-hidden="true">
            <div class="loading-content">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="loading-text">Loading...</div>
            </div>
        </div>
        <div class="pagetitle">
            <h1>Analytics Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Analytics Dashboard</li>
                </ol>
            </nav>
        </div>
        <!-- End Page Title -->

        <div class="text-lg-end text-center mb-3">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-sm btn-light-success active" onclick="filterData('all')">All
                    Time</button>
                <button type="button" class="btn btn-sm btn-light-success" onclick="filterData('today')">Today</button>
                <button type="button" class="btn btn-sm btn-light-success" onclick="filterData('week')">This Week</button>
                <button type="button" class="btn btn-sm btn-light-success" onclick="filterData('month')">This
                    Month</button>
                <button type="button" class="btn btn-sm btn-light-success" onclick="filterData('year')">This Year</button>
            </div>
        </div>
        <hr />

        <!-- Analytics Overview -->
        <section class="section">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-1">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-start">
                                    <h2 id="total-revenue" class="fw-bold">
                                        {{ $currency }}{{ number_format($revenueAnalysis['total_revenue'], 2, '.', ',') }}
                                    </h2>
                                    <small class="text-uppercase small pt-1 fw-bold">Total Revenue</small>
                                </div>
                                <div class="rounded-circle float-end">
                                    <i class="bi bi-currency-dollar display-1 text-light-black"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card bg-2">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-start">
                                    <h2 id="total-orders" class="fw-bold">{{ $orderStats['total_orders'] }}</h2>
                                    <small class="text-uppercase small pt-1 fw-bold">Total Orders</small>
                                </div>
                                <div class="rounded-circle float-end">
                                    <i class="bi bi-cart3 display-1 text-light-black"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card bg-3">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-start">
                                    <h2 id="total-customers" class="fw-bold">{{ $customerInsights['total_customers'] }}</h2>
                                    <small class="text-uppercase small pt-1 fw-bold">Total Customers</small>
                                </div>
                                <div class="rounded-circle float-end">
                                    <i class="bi bi-people display-1 text-light-black"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card bg-4">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-start">
                                    <h2 id="total-products" class="fw-bold">{{ $productPerformance['total_products'] }}</h2>
                                    <small class="text-uppercase small pt-1 fw-bold">Total Products</small>
                                </div>
                                <div class="rounded-circle float-end">
                                    <i class="bi bi-box-seam display-1 text-light-black"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sales Trends Chart -->
        <section class="section mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sales Trends</h5>
                    <div id="salesTrendsChart"></div>
                </div>
            </div>
        </section>


        <!-- Best Selling Products -->
        <section class="section mt-4">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card equal-card">
                        <div class="card-body">
                            <h5 class="card-title">Best Selling Products (<span
                                    id="best-selling-period-label">Monthly</span>)</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity Sold</th>
                                            <th>Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody id="best-selling-body">
                                        @forelse ($bestSellingProducts['monthly'] as $product)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            @if ($product->image)
                                                                <img src="{{ asset('/storage/products/' . $product->image) }}"
                                                                    alt="Product Image" style="width: 40px; height: 40px;">
                                                            @else
                                                                <img src="{{ asset('/assets/img/no-image.png') }}"
                                                                    alt="Product Image" style="width: 40px; height: 40px;">
                                                            @endif
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-0">{{ $product->name }}</h6>
                                                            <small class="text-muted">SKU: {{ $product->code }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ number_format($product->total_sold, 0) }}</td>
                                                <td>{{ $currency }}{{ number_format($product->total_revenue, 2, '.', ',') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">No data available</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card equal-card">
                        <div class="card-body">
                            <h5 class="card-title">Best Customers (<span id="best-customers-period-label">Monthly</span>)
                            </h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Orders</th>
                                            <th>Total Spent</th>
                                        </tr>
                                    </thead>
                                    <tbody id="best-customers-body">
                                        @forelse ($bestCustomers['monthly'] as $customer)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        {{-- <div class="flex-shrink-0">
                          @if ($customer->image)
                          <img src="{{ asset('/storage/customers/'.$customer->image) }}" alt="Customer Image" style="width: 40px; height: 40px;">
                          @else
                          <img src="{{ asset('/assets/img/no-image.png') }}" alt="Customer Image" style="width: 40px; height: 40px;">
                          @endif
                        </div> --}}
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-0">{{ $customer->name }}</h6>
                                                            <small class="text-muted">{{ $customer->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ number_format($customer->order_count, 0) }}</td>
                                                <td>{{ $currency }}{{ number_format($customer->total_spent, 2, '.', ',') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">No data available</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Product Performance and Customer Insights -->
        <section class="section mt-4">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card equal-card">
                        <div class="card-body">
                            <h5 class="card-title">Product Performance</h5>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $productPerformance['total_products'] }}</h5>
                                            <p class="card-text">Total Products</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $productPerformance['low_stock_products'] }}</h5>
                                            <p class="card-text">Low Stock Products</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $productPerformance['out_of_stock_products'] }}</h5>
                                            <p class="card-text">Out of Stock Products</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">
                                                {{ $currency }}{{ number_format($productPerformance['inventory_value'], 2, '.', ',') }}
                                            </h5>
                                            <p class="card-text">Inventory Value</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card equal-card">
                        <div class="card-body">
                            <h5 class="card-title">Customer Insights</h5>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $customerInsights['total_customers'] }}</h5>
                                            <p class="card-text">Total Customers</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $customerInsights['new_customers_this_month'] }}
                                            </h5>
                                            <p class="card-text">New Customers This Month</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $customerInsights['inactive_customers'] }}</h5>
                                            <p class="card-text">Inactive Customers</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">
                                                {{ $currency }}{{ number_format($customerInsights['average_order_value'], 2, '.', ',') }}
                                            </h5>
                                            <p class="card-text">Average Order Value</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Best Performing Staff -->
        <section class="section mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Best Performing Staff (<span id="best-staff-period-label">Monthly</span>)</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Staff Member</th>
                                    <th>Orders</th>
                                    <th>Total Sales</th>
                                </tr>
                            </thead>
                            <tbody id="best-staff-body">
                                @forelse ($bestStaff['monthly'] as $staff)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    @if ($staff->image)
                                                        <img src="{{ asset('/storage/staff/' . $staff->image) }}"
                                                            alt="Staff Image" style="width: 40px; height: 40px;">
                                                    @else
                                                        <img src="{{ asset('/assets/img/no-image.png') }}"
                                                            alt="Staff Image" style="width: 40px; height: 40px;">
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-0">{{ $staff->name }}</h6>
                                                    <small class="text-muted">{{ $staff->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ number_format($staff->order_count, 0) }}</td>
                                        <td>{{ $currency }}{{ number_format($staff->total_sales, 2, '.', ',') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Additional Analytics: Sorted tables and Delivery Rate -->
        <section class="section mt-4">
            <div class="row">
                <!-- Sorted by Status -->
                <div class="col-md-6">
                    <div class="card equal-card">
                        <div class="card-body">
                            <h5 class="card-title">SORTED BY STATUS</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>ORDER STATUS</th>
                                            <th>COUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody id="status-counts-body">
                                        <tr>
                                            <td colspan="3" class="text-center">Loading...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sorted by States -->
                <div class="col-md-6">
                    <div class="card equal-card">
                        <div class="card-body">
                            <h5 class="card-title">SORTED BY STATES</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>LIST OF STATES</th>
                                            <th>COUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody id="state-counts-body">
                                        <tr>
                                            <td colspan="3" class="text-center">Loading...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <!-- Sorted by Products -->
                <div class="col-md-6">
                    <div class="card equal-card">
                        <div class="card-body">
                            <h5 class="card-title">SORTED BY PRODUCTS</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>PRODUCT NAME</th>
                                            <th>COUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-counts-body">
                                        <tr>
                                            <td colspan="3" class="text-center">Loading...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sorted by Days of the Week -->
                <div class="col-md-6">
                    <div class="card equal-card">
                        <div class="card-body">
                            <h5 class="card-title">SORTED BY DAYS OF THE WEEK</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>DAYS OF THE WEEK</th>
                                            <th>COUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dayofweek-counts-body">
                                        <tr>
                                            <td colspan="3" class="text-center">Loading...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <!-- Delivery Rate Analysis -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Delivery Rate Analysis</h5>
                            <div id="deliveryRateChart" style="height: 320px;"></div>
                            <div class="row text-center mt-3">
                                <div class="col-md-4">
                                    <h4 id="total-orders-count">0</h4>
                                    <p>Total Orders</p>
                                </div>
                                <div class="col-md-4">
                                    <h4 id="delivered-orders-count">0</h4>
                                    <p>Payment Received</p>
                                </div>
                                <div class="col-md-4">
                                    <h4 id="delivery-percentage">0%</h4>
                                    <p>Delivery Rate</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        let deliveryRateChart = null;
        const currencySymbol = '{{ $currency }}';
        const periodLabels = {
            today: 'Today',
            weekly: 'This Week',
            monthly: 'This Month',
            yearly: 'This Year'
        };

        function showLoading() {
            const el = document.getElementById('loading-overlay');
            if (el) el.classList.add('show');
        }

        function hideLoading() {
            const el = document.getElementById('loading-overlay');
            if (el) el.classList.remove('show');
        }

        // Sales Trends Chart (ApexCharts)
        const salesTrendsChart = new ApexCharts(document.querySelector('#salesTrendsChart'), {
            series: [{
                name: 'Sales',
                data: [
                    @foreach ($salesTrends as $month)
                        {{ $month['sales'] }},
                    @endforeach
                ]
            }],
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: true
            },
            stroke: {
                curve: 'straight'
            },
            title: {
                text: 'Monthly Sales Trends',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: [
                    @foreach ($salesTrends as $month)
                        '{{ $month['month'] }}',
                    @endforeach
                ]
            }
        });

        salesTrendsChart.render();

        // Filter data function
        function filterData(period) {
            // Update active button without relying on global event
            const buttons = document.querySelectorAll('.btn-group button');
            buttons.forEach(btn => btn.classList.remove('active'));
            const labelMap = {
                all: 'All Time',
                today: 'Today',
                week: 'This Week',
                month: 'This Month',
                year: 'This Year'
            };
            const target = Array.from(buttons).find(b => b.textContent.trim() === labelMap[period]);
            if (target) target.classList.add('active');

            // Fetch analytics data and update UI
            showLoading();
            fetch(`{{ route('analytics.data') }}?period=${encodeURIComponent(period)}`)
                .then(res => res.json())
                .then(data => {
                    const key = data.selected_key; // 'today' | 'weekly' | 'monthly' | 'yearly'
                    const label = periodLabels[key] || 'This Year';

                    // Update period labels
                    document.getElementById('best-selling-period-label').textContent = label;
                    document.getElementById('best-customers-period-label').textContent = label;
                    document.getElementById('best-staff-period-label').textContent = label;

                    // Update top stats
                    document.getElementById('total-revenue').textContent =
                        `${currencySymbol}${numberFormat(data.revenueAnalysis.total_revenue)}`;
                    document.getElementById('total-orders').textContent = numberFormat(data.orderStats.total_orders);
                    document.getElementById('total-customers').textContent = numberFormat(data.customerInsights
                        .total_customers);
                    document.getElementById('total-products').textContent = numberFormat(data.productPerformance
                        .total_products);

                    // Render tables
                    renderBestSelling(data.bestSellingProducts[key] || []);
                    renderBestCustomers(data.bestCustomers[key] || []);
                    renderBestStaff(data.bestStaff[key] || []);

                    // New sections
                    renderStatusCounts(data.orderStatusCounts[key] || []);
                    renderStateCounts(data.stateCounts[key] || []);
                    renderProductCounts(data.productCounts[key] || []);
                    renderDayOfWeekCounts(data.dayOfWeekCounts[key] || []);
                    renderDeliveryRate(data.deliveryRate[key] || {
                        total_orders: 0,
                        payment_received_orders: 0,
                        delivery_rate_percentage: 0
                    });
                })
                .catch(err => console.error('Error loading analytics data:', err))
                .finally(() => hideLoading());
        }

        const formatStatus = (value) => {
            if (!value) return 'N/A';
            return value
                .toString()
                .toLowerCase()
                .replace(/_/g, ' ')
                .replace(/\b\w/g, char => char.toUpperCase());
        };


        function renderStatusCounts(items) {
            const tbody = document.getElementById('status-counts-body');
            if (!items || items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">No data available</td></tr>';
                return;
            }
            let total = 0;
            tbody.innerHTML = items.map((item, idx) => {
                total += Number(item.order_count || 0);
                return `
        <tr>
          <td>${idx + 1}</td>
         <td>${escapeHtml(formatStatus(item.status || item.code))}</td>
          <td>${numberFormat(item.order_count || 0)}</td>
        </tr>`;
            }).join('') + `
      <tr>
        <td colspan="2" style="font-weight:600;">TOTAL</td>
        <td>${numberFormat(total)}</td>
      </tr>`;
        }

        function renderStateCounts(items) {
            const tbody = document.getElementById('state-counts-body');
            if (!items || items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">No data available</td></tr>';
                return;
            }
            let total = 0;
            tbody.innerHTML = items.map((item, idx) => {
                total += Number(item.total_orders || 0);
                return `
        <tr>
          <td>${idx + 1}</td>
          <td>${escapeHtml(item.state_name || 'N/A')}</td>
          <td>${numberFormat(item.total_orders || 0)}</td>
        </tr>`;
            }).join('') + `
      <tr>
        <td colspan="2" style="font-weight:600;">TOTAL</td>
        <td>${numberFormat(total)}</td>
      </tr>`;
        }

        function renderProductCounts(items) {
            const tbody = document.getElementById('product-counts-body');
            if (!items || items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">No data available</td></tr>';
                return;
            }
            let total = 0;
            tbody.innerHTML = items.map((item, idx) => {
                total += Number(item.total_orders || 0);
                return `
        <tr>
          <td>${idx + 1}</td>
          <td>${escapeHtml(item.product_name || 'N/A')}</td>
          <td>${numberFormat(item.total_orders || 0)}</td>
        </tr>`;
            }).join('') + `
      <tr>
        <td colspan="2" style="font-weight:600;">TOTAL</td>
        <td>${numberFormat(total)}</td>
      </tr>`;
        }

        function renderDayOfWeekCounts(items) {
            const tbody = document.getElementById('dayofweek-counts-body');
            if (!items || items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">No data available</td></tr>';
                return;
            }
            let total = 0;
            tbody.innerHTML = items.map((item, idx) => {
                total += Number(item.order_count || 0);
                return `
        <tr>
          <td>${idx + 1}</td>
          <td>${escapeHtml(item.day_name || 'N/A')}</td>
          <td>${numberFormat(item.order_count || 0)}</td>
        </tr>`;
            }).join('') + `
      <tr>
        <td colspan="2" style="font-weight:600;">TOTAL</td>
        <td>${numberFormat(total)}</td>
      </tr>`;
        }

        function renderDeliveryRate(data) {
            const delivered = Number(data.payment_received_orders || 0);
            const pending = Math.max(Number(data.total_orders || 0) - delivered, 0);
            document.getElementById('total-orders-count').textContent = numberFormat(data.total_orders || 0);
            document.getElementById('delivered-orders-count').textContent = numberFormat(delivered);
            document.getElementById('delivery-percentage').textContent = `${Number(data.delivery_rate_percentage || 0)}%`;

            const options = {
                series: [delivered, pending],
                labels: ['Payment Received', 'Pending'],
                chart: {
                    type: 'donut',
                    height: 300
                },
                colors: ['#28a745', '#dc3545'],
                legend: {
                    position: 'bottom'
                }
            };
            if (deliveryRateChart) {
                deliveryRateChart.updateOptions(options);
                deliveryRateChart.updateSeries(options.series);
            } else {
                deliveryRateChart = new ApexCharts(document.querySelector('#deliveryRateChart'), options);
                deliveryRateChart.render();
            }
        }

        function renderBestSelling(items) {
            const tbody = document.getElementById('best-selling-body');
            if (!items || items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">No data available</td></tr>';
                return;
            }
            tbody.innerHTML = items.map(item => `
      <tr>
        <td>
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              ${item.image ? `<img src="/storage/products/${item.image}" alt="Product Image" style="width: 40px; height: 40px;">` : `<img src="/assets/img/no-image.png" alt="Product Image" style="width: 40px; height: 40px;">`}
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-0">${escapeHtml(item.name || '')}</h6>
              <small class="text-muted">SKU: ${escapeHtml(item.code || '')}</small>
            </div>
          </div>
        </td>
        <td>${numberFormat(item.total_sold || 0)}</td>
        <td>${currencySymbol}${numberFormat((item.total_revenue || 0).toFixed ? item.total_revenue.toFixed(2) : item.total_revenue)}</td>
      </tr>
    `).join('');
        }

        function renderBestCustomers(items) {
            const tbody = document.getElementById('best-customers-body');
            if (!items || items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">No data available</td></tr>';
                return;
            }
            tbody.innerHTML = items.map(item => `
      <tr>
        <td>
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <img src="/assets/img/no-image.png" alt="Customer Image" style="width: 40px; height: 40px;">
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-0">${escapeHtml(item.name || '')}</h6>
              <small class="text-muted">${escapeHtml(item.email || '')}</small>
            </div>
          </div>
        </td>
        <td>${numberFormat(item.order_count || 0)}</td>
        <td>${currencySymbol}${numberFormat((item.total_spent || 0).toFixed ? item.total_spent.toFixed(2) : item.total_spent)}</td>
      </tr>
    `).join('');
        }

        function renderBestStaff(items) {
            const tbody = document.getElementById('best-staff-body');
            if (!items || items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">No data available</td></tr>';
                return;
            }
            tbody.innerHTML = items.map(item => `
      <tr>
        <td>
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              ${item.image ? `<img src="/storage/staff/${item.image}" alt="Staff Image" style="width: 40px; height: 40px;">` : `<img src="/assets/img/no-image.png" alt="Staff Image" style="width: 40px; height: 40px;">`}
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-0">${escapeHtml(item.name || '')}</h6>
              <small class="text-muted">${escapeHtml(item.email || '')}</small>
            </div>
          </div>
        </td>
        <td>${numberFormat(item.order_count || 0)}</td>
        <td>${currencySymbol}${numberFormat((item.total_sales || 0).toFixed ? item.total_sales.toFixed(2) : item.total_sales)}</td>
      </tr>
    `).join('');
        }

        function numberFormat(x) {
            if (x === null || x === undefined) return '0';
            const num = typeof x === 'string' ? parseFloat(x) : x;
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return String(text).replace(/[&<>"']/g, m => map[m]);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            filterData('all');
        });
    </script>
@endsection
