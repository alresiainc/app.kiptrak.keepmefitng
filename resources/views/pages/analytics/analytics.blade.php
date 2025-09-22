@extends('layouts.design')

@section('title')Analytics Dashboard @endsection

@section('extra_css')
@endsection

@section('content')
<main id="main" class="main">
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
      <button type="button" class="btn btn-sm btn-light-success active" onclick="filterData('all')">All Time</button>
      <button type="button" class="btn btn-sm btn-light-success" onclick="filterData('today')">Today</button>
      <button type="button" class="btn btn-sm btn-light-success" onclick="filterData('week')">This Week</button>
      <button type="button" class="btn btn-sm btn-light-success" onclick="filterData('month')">This Month</button>
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
                <h2 class="fw-bold">{{ $currency }}{{ number_format($revenueAnalysis['total_revenue'], 2, '.', ',') }}</h2>
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
                <h2 class="fw-bold">{{ $orderStats['total_orders'] }}</h2>
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
                <h2 class="fw-bold">{{ $customerInsights['total_customers'] }}</h2>
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
                <h2 class="fw-bold">{{ $productPerformance['total_products'] }}</h2>
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

  <!-- Best Selling Products -->
  <section class="section mt-4">
    <div class="row">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Best Selling Products (Monthly)</h5>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th>Quantity Sold</th>
                    <th>Revenue</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($bestSellingProducts['monthly'] as $product)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                          @if($product->image)
                          <img src="{{ asset('/storage/products/'.$product->image) }}" alt="Product Image" style="width: 40px; height: 40px;">
                          @else
                          <img src="{{ asset('/assets/img/no-image.png') }}" alt="Product Image" style="width: 40px; height: 40px;">
                          @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                          <h6 class="mb-0">{{ $product->name }}</h6>
                          <small class="text-muted">SKU: {{ $product->code }}</small>
                        </div>
                      </div>
                    </td>
                    <td>{{ number_format($product->total_sold, 0) }}</td>
                    <td>{{ $currency }}{{ number_format($product->total_revenue, 2, '.', ',') }}</td>
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
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Best Customers (Monthly)</h5>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Customer</th>
                    <th>Orders</th>
                    <th>Total Spent</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($bestCustomers['monthly'] as $customer)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                          @if($customer->image)
                          <img src="{{ asset('/storage/customers/'.$customer->image) }}" alt="Customer Image" style="width: 40px; height: 40px;">
                          @else
                          <img src="{{ asset('/assets/img/no-image.png') }}" alt="Customer Image" style="width: 40px; height: 40px;">
                          @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                          <h6 class="mb-0">{{ $customer->name }}</h6>
                          <small class="text-muted">{{ $customer->email }}</small>
                        </div>
                      </div>
                    </td>
                    <td>{{ number_format($customer->order_count, 0) }}</td>
                    <td>{{ $currency }}{{ number_format($customer->total_spent, 2, '.', ',') }}</td>
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

  <!-- Sales Trends Chart -->
  <section class="section mt-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Sales Trends</h5>
        <div id="salesTrendsChart">
          <canvas id="salesTrendsCanvas"></canvas>
        </div>
      </div>
    </div>
  </section>

  <!-- Product Performance and Customer Insights -->
  <section class="section mt-4">
    <div class="row">
      <div class="col-lg-6">
        <div class="card">
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
                    <h5 class="card-title">{{ $currency }}{{ number_format($productPerformance['inventory_value'], 2, '.', ',') }}</h5>
                    <p class="card-text">Inventory Value</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card">
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
                    <h5 class="card-title">{{ $customerInsights['new_customers_this_month'] }}</h5>
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
                    <h5 class="card-title">{{ $currency }}{{ number_format($customerInsights['average_order_value'], 2, '.', ',') }}</h5>
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
        <h5 class="card-title">Best Performing Staff (Monthly)</h5>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Staff Member</th>
                <th>Orders</th>
                <th>Total Sales</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($bestStaff['monthly'] as $staff)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                      @if($staff->image)
                      <img src="{{ asset('/storage/staff/'.$staff->image) }}" alt="Staff Image" style="width: 40px; height: 40px;">
                      @else
                      <img src="{{ asset('/assets/img/no-image.png') }}" alt="Staff Image" style="width: 40px; height: 40px;">
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
</main>

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
  // Sales Trends Chart
  const salesTrendsCtx = document.getElementById('salesTrendsCanvas').getContext('2d');

  const salesTrendsChart = new ApexCharts(salesTrendsCtx, {
    series: [{
      name: 'Sales',
      data: [
        @foreach($salesTrends as $month)
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
        @foreach($salesTrends as $month)
        '{{ $month['month'] }}',
        @endforeach
      ]
    }
  });

  salesTrendsChart.render();

  // Filter data function
  function filterData(period) {
    // Update active button
    document.querySelectorAll('.btn-group button').forEach(btn => {
      btn.classList.remove('active');
    });
    event.target.classList.add('active');

    // Here you would typically make an AJAX request to fetch filtered data
    // For now, we'll just show an alert
    console.log(`Filtering data for period: ${period}`);

    // In a real application, you would:
    // 1. Send an AJAX request to your backend with the period parameter
    // 2. Receive the updated analytics data
    // 3. Update all charts and tables with the new data
  }
</script>
@endsection
