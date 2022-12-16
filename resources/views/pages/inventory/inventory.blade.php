@extends('layouts.design')
@section('title')Inventory @endsection
@section('extra_css')@endsection

@section('content')
    
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Inventory Management</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Inventory Management</li>
      </ol>
    </nav>
  </div>
  <!-- End Page Title -->

  <!-- Alert -->
  <div id="liveAlertPlaceholder d-none"></div>
  <!-- /Alert -->

  <div class="text-lg-end text-center mb-3">
    <div class="btn-group" role="group" aria-label="Basic example">
      <button type="button" class="btn btn-sm btn-light-success active">
        Today
      </button>
      <button type="button" class="btn btn-sm btn-light-success">
        Weekly
      </button>
      <button type="button" class="btn btn-sm btn-light-success">
        Monthly
      </button>
      <button type="button" class="btn btn-sm btn-light-success">
        Yearly
      </button>
      <button type="button" class="btn btn-sm btn-light-success">
        All
      </button>
    </div>
  </div>
  <hr />

  <section class="section m-0">
    <div class="row">

        <!-- Total Products Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card bg-4">
            <div class="card-body p-2">
                <div class="d-flex align-items-center justify-content-between">
                <div class="text-start">
                    <h2 class="fw-bold">{{ count($total_products) }}</h2>
                    <small class="text-uppercase small pt-1 fw-bold">Total Products</small>
                </div>
                <div class="rounded-circle float-end">
                    <i class="bi bi-box display-1 text-light-black"></i>
                </div>
                </div>
            </div>
        </div>
      </div>
        <!-- End Total Products Card -->

      <!-- In-Stock-Products Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card bg-2">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-between">
              <div class="text-start">
                <h2 class="fw-bold">{{ count($total_products) - count($out_of_stock_products) }}</h2>
                <small class="text-uppercase small pt-1 fw-bold">In-Stock Products</small
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
                <h2 class="fw-bold">{{ count($out_of_stock_products) }}</h2>
                <small class="text-uppercase small pt-1 fw-bold">Out-Of-Stock Products</small
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

      <!-- Warehouses Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card bg-1">
          <div class="card-body p-2">
            <div class="d-flex justify-content-between align-items-center">
              <div class="text-start">
                <h2 class="fw-bold">{{ count($warehouses) }}</h2>
                <small class="text-uppercase small pt-1 fw-bold">Warehouses</small
                >
              </div>
              <div class="rounded-circle float-end">
                <i class="bi bi-house display-1 text-light-black"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Warehouses Card -->

      
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
                <i class="bi bi-cash-stack display-1 text-light-black"></i>
              </div>
              <div class="text-start">
                <h2 class="fw-bold">{{ $currency }}{{ $sale_revenue }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Revenue</small>
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
                <i class="bi bi-wallet2 display-1 text-light-black"></i>
              </div>
              <div class="text-start">
                <h2 class="fw-bold">{{ $currency }}{{ $total_expenses }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Expenses</small
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
                <i class="bi bi-cash-coin display-1 text-light-black"></i>
              </div>
              <div class="text-start">
                <h2 class="fw-bold">
                    @if ($profit_val > 0)
                  {{ $currency }}{{ $profit }}
                  @else
                  -{{ $profit }}
                  @endif
                </h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Profit</small
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
                <h2 class="fw-bold">{{ count($orders) }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Orders</small
                >
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Card -->
    </div>
  </section>

  <section class="section m-0">
    <div class="row">

        <!-- Total Suppliers Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card" style="border-radius: 10px; background-color: #caeb11;">
            <div class="card-body p-2">
                <div class="d-flex align-items-center justify-content-between">
                <div class="text-start">
                    <h2 class="fw-bold text-white">{{ count($suppliers) }}</h2>
                    <small class="text-uppercase text-white small pt-1 fw-bold">Suppliers</small>
                </div>
                <div class="rounded-circle float-end">
                    <i class="bi bi-truck display-1 text-light-black"></i>
                </div>
                </div>
            </div>
        </div>
      </div>
        <!-- End Total Suppliers Card -->

      <!-- In-Stock-Products Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card bg-primary" style="border-radius: 10px;">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-between">
              <div class="text-start">
                <h2 class="fw-bold text-white">{{ $currency }}{{ $purchase_sum }}</h2>
                <small class="text-uppercase text-white small pt-1 fw-bold">Purchases</small>
              </div>
              <div class="rounded-circle float-end">
                <i
                  class="bi bi-credit-card display-1 text-light-black"
                ></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Sales Card -->

      <!-- Sales Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card bg-danger" style="border-radius: 10px;">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-between">
              <div class="text-start">
                <h2 class="fw-bold text-white">{{ count($customers) }}</h2>
                <small class="text-uppercase text-white small pt-1 fw-bold">Customers</small
                >
              </div>
              <div class="rounded-circle float-end">
                <i class="bi bi-person-check display-1 text-light-black"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Sales Card -->

      <!-- Warehouses Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card bg-success" style="border-radius: 10px;">
          <div class="card-body p-2">
            <div class="d-flex justify-content-between align-items-center">
              <div class="text-start">
                <h2 class="fw-bold text-white">{{ $currency }}{{ $sales_sum }}</h2>
                <small class="text-uppercase text-white small pt-1 fw-bold">Sales</small
                >
              </div>
              <div class="rounded-circle float-end">
                <i class="bi bi-cart3 display-1 text-light-black"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Warehouses Card -->

    </div>

  </section>

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-top-border border-top-primary">
          <div class="card-body">
            <div class="card-title">Recently Added Products</div>
            <div class="table table-responsive">
              <table
                id="stock-table"
                class="table table-striped"
                style="width: 100%"
              >
                <thead>
                  <tr>
                    <th scope="col">Product Image</th>
                    <th scope="col">Product Name</th>
                    {{-- <th scope="col">Brand Name</th> --}}
                    <th scope="col">Stock</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($recently_products->count() > 0)
                      @foreach ($recently_products as $product)
                          
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
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->stock_available() }}</td>
                          </tr>
                          
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

  <hr />

  

  
</main>

@endsection

@section('extra_js')

{{-- <script>
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
</script> --}}

{{-- @if ($yearly_best_selling_qty->count() == 5) --}}
{{-- <script>
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
</script> --}}
{{-- @endif --}}

{{-- <script>
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
</script> --}}

@endsection