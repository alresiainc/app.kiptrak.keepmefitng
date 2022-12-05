@extends('layouts.design')
@section('title')Dashboard @endsection
@section('extra_css')@endsection

@section('content')
    
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
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
      <!-- Sales Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card bg-1">
          <div class="card-body p-2">
            <div class="d-flex justify-content-between align-items-center">
              <div class="text-start">
                <h2 class="fw-bold">{{ $generalSetting->country->symbol }}{{ number_format((float)$purchases_amount_paid, 2, '.', ',') }}</h2>
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
                <h2 class="fw-bold">{{ $generalSetting->country->symbol }}{{ number_format((float)$sales_paid, 2, '.', ''); }}</h2>
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
                <h2 class="fw-bold">{{ $generalSetting->country->symbol }}{{ number_format((float)$expenses, 2, '.', ','); }}</h2>
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
                  @if ($profit > 0)
                  {{ $generalSetting->country->symbol }}{{ number_format((float)$profit, 2, '.', ','); }}
                  @else
                  ({{ number_format((float)abs($profit), 2, '.', ','); }})
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
            <h5 class="card-title">Purchase, Sales & Expense Chart</h5>

            <!-- Line Chart -->
            <!-- <div id="reportsChart"></div> -->
            <div>
              <canvas class="bar-chartcanvas"></canvas>
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
                  <th scope="col">Sales Price</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">
                    <img
                      src="./assets/img/product-1.jpg"
                      width="50"
                      class="img-thumbnail img-fluid"
                      alt="Product"
                    />
                  </th>
                  <td class="align-middle">Nike Shoe</td>
                  <td class="align-middle">N10,000</td>
                </tr>
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
                    <th scope="col">#</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Category Name</th>
                    <th scope="col">Brand Name</th>
                    <th scope="col">Stock</th>
                  </tr>
                </thead>
                <tbody></tbody>
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
            <div class="card-title">Recent Sales Invoice</div>

            <table class="table">
              <thead>
                <tr>
                  <th scope="col">SI.No</th>
                  <th scope="col">Date</th>
                  <th scope="col">Invoice ID</th>
                  <th scope="col">Customer</th>
                  <th scope="col">Total</th>
                  <th scope="col">Status</th>
                  <th scope="col">Created By</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
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
  //BAR CHART
  $(function () {
    //get the bar chart canvas
    var ctx = $(".bar-chartcanvas");

    //bar chart data
    var data = {
      labels: [
        "May,2022",
        "Jun,2022",
        "Jul,2022",
        "Aug,2022",
        "Sep,2022",
        "Oct,2022",
        "Nov,2022",
      ],
      datasets: [
        {
          label: "Purchase",
          data: [
            "450.0000",
            "10.0000",
            "820.0000",
            "100.0000",
            "520.0000",
            "80.0000",
            "30.0000",
          ],
          borderColor: window.chartColors.red,
          backgroundColor: window.chartColors.red,
          borderWidth: 1,
        },
        {
          label: "Sales",
          data: [
            "5.0000",
            "51.0000",
            "85.0000",
            "89.0000",
            "92.0000",
            "150.0000",
            "590.0000",
          ],
          borderColor: window.chartColors.blue,
          backgroundColor: window.chartColors.blue,
          borderWidth: 1,
        },
        {
          label: "Expense",
          data: [
            "85.0000",
            "85.0000",
            "10.0000",
            "65.0000",
            "96.0000",
            "6.0000",
            "46.0000",
          ],
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
  

    /** DOUGHNUT CHART */
    //PIE CHART

    // new Chart(document.getElementById("#doughnut-chart"), {
    //   type: "doughnut",
    //   data: {
    //     labels: [
    //       "iPhone X-64GB",
    //       "Redmi Note 10-128GB",
    //       "Inspiron 14",
    //       "iPhone X-128GB",
    //       "Redmi Note 10-32GB",
    //       "Colgate toothpaste",
    //       "Redmi Note 10-64GB",
    //     ],
    //     datasets: [
    //       {
    //         label: "Top Items",
    //         backgroundColor: [
    //           "#6666ff",
    //           "#ff3399",
    //           "#00cc99",
    //           "#cccc00",
    //           "#ff6600",
    //           window.chartColors.red, //above color settings
    //         ],
    //         data: ["7.00", "5.00", "4.00", "4.00", "2.00", "1.00", "1.00"],
    //       },
    //     ],
    //   },
    //   options: {
    //     title: {
    //       display: true,
    //       text: "Top 10 Trending Items",
    //     },
    //   },
    // });

  });
</script>

<!---trendingItemsChart-->
<script>
  var trendingItemsChart = document.getElementById("trendingItemsChart");
  var _trendingItemsChart = new Chart(trendingItemsChart, {
    type: 'doughnut',
    data: {
      labels: [
        "iPhone X-64GB",
        "Redmi Note 10-128GB",
        "Inspiron 14",
        "iPhone X-128GB",
        "Redmi Note 10-32GB",
        // "Colgate toothpaste",
        // "Redmi Note 10-64GB",
      ],
      datasets: [{
          label: 'Top Items',
          data: ["7.00", "5.00", "4.00", "4.00", "2.00"],
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
<!---trendingItemsChart---->

{{-- {!! $chart->script() !!} --}}

@endsection