@extends('layouts.design')
@section('title')Staff Dashboard @endsection
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
    <h1>Staff Dashboard | For: {{ $start_date }} To {{ $end_date }}</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('staffDashboard') }}">Staff Dashboard</a></li>
        <li class="breadcrumb-item">Hi, <span class="text-dark">{{ $authUser->name }}</span></li>
      </ol>
    </nav>
  </div>
  <!-- End Page Title -->

  <!-- Alert -->
  <div id="liveAlertPlaceholder d-none"></div>
  <!-- /Alert -->

  <section class="d-flex justify-content-between align-items-center">
    <div class="">
      <form class="row g-3 needs-validation" action="{{ route('staffDashboardFilterPost') }}" method="POST" enctype="multipart/form-data">@csrf
        
        <div class="col-12 col-md-6 col-lg-5 mb-3">
            <label for="" class="form-label">Start Date</label>
            <input type="text" name="start_date" id="start_date" class="form_date form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
            @error('start_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
  
        <div class="col-12 col-md-6 col-lg-5 mb-3">
            <label for="" class="form-label">End Date</label>
            <input type="text" name="end_date" id="end_date" class="form_date form-control @error('end_date') is-invalid @enderror"value="{{ old('end_date') }}">
            @error('end_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="col-12 col-md-6 col-lg-2 d-flex align-items-end mb-3">
          <div class="d-grid w-100">
            <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">Submit</button>
          </div>
        </div>
  
      </form><!-- End Multi Columns Form -->
    </div>
  
    <div class="">
      <div class="btn-group">
      
        <button type="button" class="btn btn-info btn-sm dropdown-toggle fw-bolder" data-bs-toggle="dropdown" style="font-size: 10px;">
          <span>All</span>
        </button>
        
        <ul class="dropdown-menu">
          
          <li><a class="dropdown-item" href="{{ route('staffTodayRecord') }}">Today</a></li>
          <li><hr class="dropdown-divider"></li>

          <li><a class="dropdown-item" href="{{ route('staffYesterdayRecord') }}">Yesteday</a></li>
          <li><hr class="dropdown-divider"></li>
    
          <li><a class="dropdown-item" href="{{ route('staffLast7DaysRecord') }}">Last 7 days</a></li>
          <li><hr class="dropdown-divider"></li>
    
          <li><a class="dropdown-item" href="{{ route('staffLast14DaysRecord') }}">Last 14 days</a></li>
          <li><hr class="dropdown-divider"></li>
    
          <li><a class="dropdown-item" href="{{ route('staffLast30DaysRecord') }}">Last 30 days</a></li>
          <li><hr class="dropdown-divider"></li>
  
          <li><a class="dropdown-item" href="{{ route('staffWeeklyRecord') }}">This Week</a></li>
          <li><hr class="dropdown-divider"></li>
  
          <li><a class="dropdown-item" href="{{ route('staffLastWeekRecord') }}">Last Week</a></li>
          <li><hr class="dropdown-divider"></li>
  
          <li><a class="dropdown-item" href="{{ route('staffMonthlyRecord') }}">This Month</a></li>
          <li><hr class="dropdown-divider"></li>
  
          <li><a class="dropdown-item" href="{{ route('staffLastMonthRecord') }}">Last Month</a></li>
          <li><hr class="dropdown-divider"></li>
  
          <li><a class="dropdown-item" href="{{ route('staffDashboard') }}">All</a></li>
          <li><hr class="dropdown-divider"></li>
          
    
        </ul>
      </div>
    </div>
  </section>

  <hr />

  <section class="section m-0">
    <div class="row">
      

      <!-- Sales Card -->
      <div class="col-lg-4 col-md-6">
        <div class="card bg-2">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-between">
              <div class="text-start">
                <h2 class="fw-bold">{{ $currency }}{{ $sales_paid }}</h2>
                <small class="text-uppercase small pt-1 fw-bold">Total Revenue</small
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
      <div class="col-lg-4 col-md-6">
        <div class="card bg-3">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-between">
              <div class="text-start">
                <h2 class="fw-bold">{{ $currency }}{{ $expenses }}</h2>
                <small class="text-uppercase small pt-1 fw-bold">Total Expenses</small
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
      
    </div>
  </section>
  <hr>
    
  <section class="section m-0">
    <div class="row">
      <!-- All Orders Card -->
      <div class="col-lg-2 col-md-6">
        <div class="card border-right-success card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="text-start">
                <h2 class="fw-bold">{{ $allOrders }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">All <br> Orders</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- All Orders Card -->

      <!--New Card -->
      <div class="col-lg-2 col-md-6">
        <div class="card border-right-success card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="text-start">
                <h2 class="fw-bold">{{ $newOrders }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">New <br> Orders</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- New Card -->

      <!--Pending Card -->
      <div class="col-lg-2 col-md-6">
        <div class="card border-right-success card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="text-start">
                <h2 class="fw-bold">{{ $pendingOrders }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Pending <br> Orders</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Pending Card -->

      <!--Delivered Card -->
      <div class="col-lg-2 col-md-6">
        <div class="card border-right-success card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="text-start">
                <h2 class="fw-bold">{{ $deliveredOrders }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Delivered <br> Orders</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Delivered Card -->

      <!--Cancelled Card -->
      <div class="col-lg-2 col-md-6">
        <div class="card border-right-success card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="text-start">
                <h2 class="fw-bold">{{ $cancelledOrders }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Cancelled <br> Orders</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Cancelled Card -->

      <!--Remitted Card -->
      <div class="col-lg-2 col-md-6">
        <div class="card border-right-success card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="text-start">
                <h2 class="fw-bold">{{ $remittedOrders }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Remitted <br> Orders</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Remitted Card -->

      <!--Not Remitted Card -->
      <div class="col-lg-2 col-md-6">
        <div class="card border-right-success card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="text-start">
                <h2 class="fw-bold">{{ $notRemittedOrders }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Not Remitted <br> Orders</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Not Remitted Card -->

      <!--Total Follow-up Card -->
      <div class="col-lg-2 col-md-6">
        <div class="card border-right-success card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="text-start">
                <h2 class="fw-bold">{{ $totalFollowUpOrders }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Total Follow-up Orders</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Total Follow-up Card -->

      <!--Today Follow-up Card -->
      <div class="col-lg-2 col-md-6">
        <div class="card border-right-success card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="text-start">
                <h2 class="fw-bold">{{ $todayFollowUpOrders }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Today Follow-up Orders</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Today Follow-up Card -->

      <!--Tommorrow Follow-up Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card border-right-success card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="text-start">
                <h2 class="fw-bold">{{ $tomorrowFollowUpOrders }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Tomorrow Follow-up Orders</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Tommorrow Follow-up Card -->

      <!--Other Follow-up Card -->
      <div class="col-lg-2 col-md-6">
        <div class="card border-right-success card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="text-start">
                <h2 class="fw-bold">{{ $otherOrders }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Other Follow-up Orders</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Other Follow-up Card -->

    </div>
  </section>

  <hr />

  <section class="section">
    <div class="row">
      <!-- Reports -->
      <div class="col-md-12">
        <div class="card card-top-border border-top-success">
          <div class="card-body">
            <h5 class="card-title">Sales Chart</h5>

            <!-- Line Chart -->
            <!-- <div id="reportsChart"></div> -->
            <div>
              {{-- <canvas class="bar-chartcanvas"></canvas> --}}
              <canvas class="bar-chartcanvas" data-sale_chart_value = "{{json_encode($yearly_sale_amount)}}"
               data-label1="Purchase" data-label2="Sales" data-label3="Expenses"></canvas>
              {{-- {!! $chart->container() !!} --}}

            </div>

            <!-- End Line Chart -->
          </div>
        </div>
      </div>
    </div>
  </section>



@endsection

@section('extra_js')

<link href="{{asset('/assets/css/jquery.datetimepicker.min.css')}}" rel="stylesheet">
<script src="{{asset('/assets/js/jquery.datetimepicker.min.js')}}"></script>
<script>
  jQuery('.form_date').datetimepicker({
    datepicker:true,
    //showPeriod: true,
    format:'Y-m-d',
    timepicker:false,
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

 //BAR CHART, YEARLY REPORT, PURCHASE, SALES, EXPENSES
 $(function () {
   //get the bar chart canvas
   var ctx = $(".bar-chartcanvas");
   var yearly_sale_amount = ctx.data('sale_chart_value');
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
         label: "Sales",
         data: [ yearly_sale_amount[0], yearly_sale_amount[1], yearly_sale_amount[2], yearly_sale_amount[3], yearly_sale_amount[4], yearly_sale_amount[5],
                 yearly_sale_amount[6], yearly_sale_amount[7], yearly_sale_amount[8], yearly_sale_amount[9], yearly_sale_amount[10], yearly_sale_amount[11],
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