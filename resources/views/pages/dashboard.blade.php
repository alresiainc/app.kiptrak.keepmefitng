@extends('layouts.design')
@section('title')Dashboard @endsection
@section('extra_css')@endsection
<style>
  .attendance:hover{
    color: #fff;
    background-color: #04512d !important;
  }
</style>
@section('content')
    
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div>
  <!-- End Page Title -->

  <!-- Alert -->
  <div id="liveAlertPlaceholder d-none"></div>
  <!-- /Alert -->

  <div class="text-lg-end text-center mb-3">
    <div class="btn-group" role="group" aria-label="Basic example">
      
      <a href="{{ route('todayRecord') }}"><button type="button" class="btn btn-sm btn-light-success {{ $record == 'today' ? 'active' : '' }}">
        Today
      </button></a>

      <a href="{{ route('weeklyRecord') }}"><button type="button" class="btn btn-sm btn-light-success {{ $record == 'weekly' ? 'active' : '' }}">
        This Week
      </button></a>

      <a href="{{ route('monthlyRecord') }}"><button type="button" class="btn btn-sm btn-light-success {{ $record == 'monthly' ? 'active' : '' }}">
        This Month
      </button></a>

      <a href="{{ route('yearlyRecord') }}"><button type="button" class="btn btn-sm btn-light-success {{ $record == 'yearly' ? 'active' : '' }}">
        This Year
      </button></a>
      
      <a href="/"><button type="button" class="btn btn-sm btn-light-success {{ $record == 'all' ? 'active' : '' }}">
        All
      </button></a>

    </div>
  </div>
  <hr />

  @if ($authUser->isSuperAdmin)
      
  <section class="section m-0">
    <div class="row">
      <!-- Sales Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card bg-1">
          <div class="card-body p-2">
            <div class="d-flex justify-content-between align-items-center">
              <div class="text-start">
                {{-- <h2 class="fw-bold">{{ $currency }}{{ number_format((float)$purchases_amount_paid, 2, '.', ',') }}</h2> --}}
                <h2 class="fw-bold">{{ $currency }}{{ $purchases_amount_paid }}</h2>
                <small class="text-uppercase small pt-1 fw-bold"
                  >Purchases</small
                >
              </div>
              <div class="rounded-circle float-end">
                <i class="bi bi-box display-1 text-light-black"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Sales Card -->

      <!-- Sales Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card bg-2">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-between">
              <div class="text-start">
                <h2 class="fw-bold">{{ $currency }}{{ $sales_paid }}</h2>
                <small class="text-uppercase small pt-1 fw-bold">Sales</small
                >
              </div>
              <div class="rounded-circle float-end">
                <i
                  class="bi bi-calendar-minus display-1 text-light-black"
                ></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Sales Card -->

      <!-- Sales Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card bg-3">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-between">
              <div class="text-start">
                <h2 class="fw-bold">{{ $currency }}{{ $expenses }}</h2>
                <small class="text-uppercase small pt-1 fw-bold">Expenses</small
                >
              </div>
              <div class="rounded-circle float-end">
                <i class="bi bi-cart-check display-1 text-light-black"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Sales Card -->

      <!-- Sales Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card bg-4">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-between">
              <div class="text-start">
                <h2 class="fw-bold">
                  @if ($profit_val > 0)
                  {{ $currency }}{{ $profit }}
                  @else
                  {{-- ({{ number_format((float)abs($profit), 2, '.', ','); }}) --}}
                  {{ $profit == '-0' ? 0 : '-'.$profit }}
                  @endif
                  
                </h2>
                <small class="text-uppercase small pt-1 fw-bold">Profit</small
                >
              </div>
              <div class="rounded-circle float-end">
                <i class="bi bi-cash-coin display-1 text-light-black"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Sales Card -->
    </div>
  </section>

  <section class="section m-0">
    <div class="row">
      <!-- Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card border-right-warning card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="border rounded shadow-sm px-2 me-2">
                <i class="bi bi-people display-1 text-light-black"></i>
              </div>
              <div class="text-start">
                <h2 class="fw-bold">{{ $customers_count }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Customers</small
                >
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Card -->
      <!-- Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card border-right-primary card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="border rounded shadow-sm px-2 me-2">
                <i class="bi bi-truck display-1 text-light-black"></i>
              </div>
              <div class="text-start">
                <h2 class="fw-bold">{{ $suppliers_count }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Suppliers</small
                >
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Card -->

      <!-- Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card border-right-success card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="border rounded shadow-sm px-2 me-2">
                <i class="bi bi-briefcase display-1 text-light-black"></i>
              </div>
              <div class="text-start">
                <h2 class="fw-bold">{{ $purchases_count }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Purchases</small
                >
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Card -->

      <!-- Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card border-right-danger card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="border rounded shadow-sm px-2 me-2">
                <i
                  class="bi bi-receipt display-1 text-light-black"
                ></i>
              </div>
              <div class="text-start">
                <h2 class="fw-bold">{{ $sales_count }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Sales</small
                >
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Card -->
    </div>
  </section>

  <hr />

  <section class="section">
    <div class="row">
      <!-- Reports -->
      <div class="col-md-8">
        <div class="card card-top-border border-top-success">
          <div class="card-body">
            <h5 class="card-title">Revenue, Expenses & Profit Chart</h5>

            <!-- Line Chart -->
            <!-- <div id="reportsChart"></div> -->
            <div>
              {{-- <canvas class="bar-chartcanvas"></canvas> --}}
              <canvas class="bar-chartcanvas" data-sale_chart_value = "{{json_encode($yearly_sale_amount)}}" data-profit_chart_value = "{{json_encode($yearly_profit_amount)}}"
              data-expense_chart_value = "{{json_encode($yearly_expense_amount)}}" data-label1="Purchase" data-label2="Sales" data-label3="Expenses"></canvas>
              {{-- {!! $chart->container() !!} --}}

            </div>

            <!-- End Line Chart -->
          </div>
        </div>
      </div>
      <!-- End Reports -->

      <div class="col-md-4">
        <div class="card border-top-5 border-top-warning card-top-border">
          <div class="card-body">
            <div class="card-title">Recently Added Items</div>

            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Photo</th>
                  <th scope="col">Item</th>
                  <th scope="col">Purchase Price</th>
                </tr>
              </thead>
              <tbody>

                @if (count($recentProducts) > 0)
                  @foreach ($recentProducts as $product)
                  <tr>
                    <th scope="row">
                      <a
                        href="{{ asset('/storage/products/'.$product->image) }}"
                        data-fancybox="gallery"
                        data-caption="{{ isset($product->name) ? $product->name : 'no caption' }}"
                        >   
                        <img src="{{ asset('/storage/products/'.$product->image) }}" width="50" class="img-thumbnail img-fluid"
                        alt="{{$product->name}}" style="height: 30px;"></a>
                    </th>
                    <td class="align-middle fw-bold" style="font-size: 10px;">{{ $product->name }}</td>
                    <td class="align-middle">{{ $currency }}{{ $product->purchase_price }}</td>
                  </tr>
                  @endforeach
                @endif
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-top-border border-top-primary">
          <div class="card-body">
            <div class="card-title">Stock Alert</div>
            <div class="table table-responsive">
              <table
                id="stock-table"
                class="table table-striped"
                style="width: 100%"
              >
                <thead>
                  <tr>
                    <th scope="col">Item Code</th>
                    <th scope="col">Item Name</th>
                    {{-- <th scope="col">Brand Name</th> --}}
                    <th scope="col">Stock</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($products->count() > 0)
                      @foreach ($products as $product)
                          @if ($product->stock_available() < 10)
                          <tr>
                            <td>{{ $product->code }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->stock_available() }}</td>
                          </tr>
                          @endif
                      @endforeach
                  @endif
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="row">
      <!-- Reports -->
      <div class="col-lg-6">
        <div class="card card-top-border border-top-success">
          <div class="card-body">
            <h5 class="card-title">Top 5 Selling Products</h5>

            <!-- Line Chart -->
            <div>
              <canvas id="trendingItemsChart" width="100%"></canvas>
            </div>

            <!-- End Line Chart -->
          </div>
        </div>
      </div>
      <!-- End Reports -->

      <div class="col-lg-6">
        <div class="card border-top-5 border-top-warning card-top-border">
          <div class="card-body">
            <div class="card-title">Recent Orders</div>

            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Order No.</th>
                  <th scope="col">Customer</th>
                  <th scope="col">Delivery Address</th>
                  <th scope="col">Agent</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody>
                @if ($recentOrders->count() > 0)
                 @foreach ($recentOrders as $order)
                 <tr>
                  <td>{{ $order->orderCode($order->id) }}</td>
                  <td>{{ $order->customer_id ? $order->customer->firstname : 'No response' }} {{ $order->customer_id ? $order->customer->lastname : '' }}</td>
                  <td style="width: 150px;">{{ $order->customer_id ? $order->customer->delivery_address : 'No response' }}</td>

                  @if (isset($order->agent_assigned_id))
                  <td>
                    {{ $order->agent->name }}
                  </td>
                  @else
                  <td style="width: 120px">
                    None 
                  </td>
                  @endif

                  <td>
                    
                      @if (!isset($order->status) || $order->status=='pending')
                        <span class="badge badge-danger">Pending</span>
                      @endif
                      
                  </td>
                </tr> 
                 @endforeach
                
                @endif
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>

  @else

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card card-top-border border-top-success">
          <div class="card-body">
            <h5 class="card-title text-uppercase text-center">View More Features From Sidebar</h5>
            <div class="text-uppercase text-center"><a href="{{ route('allAttendance') }}" class="attendance btn btn-dark rounded-pill">Attendance</a></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  @endif
</main>

@endsection

@section('extra_js')

<script>
  $(document).ready(function () {
    $("#stock-table").DataTable({
      dom: "Bflrtip",
      buttons: {
        buttons: [
          { extend: "copy", className: "btn btn-teal btn-sm" },
          { extend: "excel", className: "btn btn-teal btn-sm" },
          { extend: "pdf", className: "btn btn-teal btn-sm" },
          { extend: "print", className: "btn btn-teal btn-sm" },
          { extend: "csv", className: "btn btn-teal btn-sm" },
        ],
      },
    });

    const alertPlaceholder = document.getElementById(
      "liveAlertPlaceholder"
    );

    const alert = (message, type) => {
      const wrapper = document.createElement("div");
      wrapper.innerHTML = [
        `<div class="alert alert-${type} alert-dismissible" role="alert">`,
        `   <div>${message}</div>`,
        '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
        "</div>",
      ].join("");

      alertPlaceholder.append(wrapper);
    };

    alert("Nice, you triggered this alert message!", "danger");
  });
</script>

@if ($yearly_best_selling_qty->count() == 5)
<script>
  //trendingItemsChart
  var trendingItemsChart = document.getElementById("trendingItemsChart");
  var _trendingItemsChart = new Chart(trendingItemsChart, {
    type: 'doughnut',
    data: {
      labels: [
        "{{ $bestSellingProductsBulk[0]['product_name'] }}",
        "{{ $bestSellingProductsBulk[1]['product_name'] }}",
        "{{ $bestSellingProductsBulk[2]['product_name'] }}",
        "{{ $bestSellingProductsBulk[3]['product_name'] }}",
        "{{ $bestSellingProductsBulk[4]['product_name'] }}",
        // "Colgate toothpaste",
        // "Redmi Note 10-64GB",
      ],
      datasets: [{
          label: 'Top Items',
          data: ["{{ $yearly_best_selling_qty[0]->sold_qty }}", "{{ $yearly_best_selling_qty[1]->sold_qty }}", "{{ $yearly_best_selling_qty[2]->sold_qty }}",
          "{{ $yearly_best_selling_qty[3]->sold_qty }}", "{{ $yearly_best_selling_qty[4]->sold_qty }}"],
          // data: [{{ $bestSellingProductsBulk[0]['sold_qty'] }}, {{ $bestSellingProductsBulk[1]['sold_qty'] }}, {{ $bestSellingProductsBulk[2]['sold_qty'] }},
          // {{ $bestSellingProductsBulk[3]['sold_qty'] }}, {{ $bestSellingProductsBulk[4]['sold_qty'] }}],
          backgroundColor: [
          'rgb(102, 102, 255)',
          'rgb(255, 51, 153)',
          'rgb(0, 204, 153)',
          'rgb(204, 204, 0)',
          'rgb(37, 195, 72)',
          // 'rgb(31, 161, 212, 1)',
          // 'rgb(238, 27, 37, 1)'
          ],
          hoverOffset: 4
      }]
    },
    //options
  });
</script>
@endif



<script>
   'use strict';

  window.chartColors = {
    red: "rgb(255, 50, 10)",
    orange: "rgb(255, 102, 64)",
    yellow: "rgb(230, 184, 0)",
    green: "rgb(0, 179, 0)",
    blue: "rgb(0, 0, 230)",
    purple: "rgb(134, 0, 179)",
    grey: "rgb(117, 117, 163)",
  };

  var MONTHS = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];

  var COLORS = [
    "#4dc9f6",
    "#f67019",
    "#f53794",
    "#537bc4",
    "#acc236",
    "#166a8f",
    "#00a950",
    "#58595b",
    "#8549ba",
  ];

  //BAR CHART, YEARLY REPORT, PURCHASE, SALES, EXPENSES
  $(function () {
    //get the bar chart canvas
    var ctx = $(".bar-chartcanvas");

    var yearly_profit_amount = ctx.data('profit_chart_value');
    var yearly_sale_amount = ctx.data('sale_chart_value');
    var yearly_expense_amount = ctx.data('expense_chart_value');
    var label1 = ctx.data('label1');
    var label2 = ctx.data('label2');

    //bar chart data
    var data = {
      labels: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ],
      datasets: [
        {
          label: "Revenue",
          data: [ yearly_sale_amount[0], yearly_sale_amount[1], yearly_sale_amount[2], yearly_sale_amount[3], yearly_sale_amount[4], yearly_sale_amount[5],
                  yearly_sale_amount[6], yearly_sale_amount[7], yearly_sale_amount[8], yearly_sale_amount[9], yearly_sale_amount[10], yearly_sale_amount[11],
                  0],
          borderColor: window.chartColors.blue,
          backgroundColor: window.chartColors.blue,
          borderWidth: 1,
        },
        {
          label: "Expense",
          data: [ yearly_expense_amount[0], yearly_expense_amount[1], yearly_expense_amount[2], yearly_expense_amount[3], yearly_expense_amount[4], yearly_expense_amount[5],
                  yearly_expense_amount[6], yearly_expense_amount[7], yearly_expense_amount[8], yearly_expense_amount[9], yearly_expense_amount[10], yearly_expense_amount[11],
                  0],
          borderColor: window.chartColors.red,
          backgroundColor: window.chartColors.red,
          borderWidth: 1,
        },
        {
          label: "Profit",
          data: [ yearly_profit_amount[0], yearly_profit_amount[1], yearly_profit_amount[2], yearly_profit_amount[3], yearly_profit_amount[4], yearly_profit_amount[5],
          yearly_profit_amount[6], yearly_profit_amount[7], yearly_profit_amount[8], yearly_profit_amount[9], yearly_profit_amount[10], yearly_profit_amount[11],
                  0],
          borderColor: window.chartColors.green,
          backgroundColor: window.chartColors.green,
          borderWidth: 1,
        },
        
        
      ],
    };

    //options
    var options = {
      responsive: true,
      title: {
        display: true,
        position: "top",
        fontSize: 18,
        fontColor: "#111",
      },
      legend: {
        display: true,
        position: "top",
        labels: {
          fontColor: "#333",
          fontSize: 16,
        },
      },
      scales: {
        yAxes: [
          {
            ticks: {
              min: 0,
            },
          },
        ],
      },
    };
    //create Chart class object
    var chart = new Chart(ctx, {
      type: "bar",
      data: data,
      options: options,
    });
    //end-bar-chartcanvas
  

  });
</script>

@endsection