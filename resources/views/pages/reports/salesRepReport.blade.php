@extends('layouts.design')
@section('title')Sales Representative Report @endsection

@section('extra_css')
<style>
    select{
    -webkit-appearance: listbox !important
    }
    .btn-light {
        background-color: #fff !important;
        color: #000 !important;
    }
    /* .bootstrap-select>.dropdown-toggle.bs-placeholder, .bootstrap-select>.dropdown-toggle.bs-placeholder:active, .bootstrap-select>.dropdown-toggle.bs-placeholder:focus, .bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
        color: #999;
    } */
    div.filter-option-inner-inner{
        color: #000 !important;
    }
    #loader {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      width: 100%;
      background: rgba(0,0,0,0.75) url(assets/img/loading.gif) no-repeat center center;
      z-index: 10000;
    }
    .active-tab{
      background-color: #04512d !important;
      color: #fff !important; 
    }
</style>
@endsection
@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Sales Representative Report</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Sales Representative Report</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if(Session::has('error'))
  <div class="alert alert-danger mb-3 text-center">
      {{Session::get('error')}}
  </div>
  @endif

  <!---filter--->
  <section class="users-list-wrapper">
    <div class="users-list-filter px-1">
      <form>
        <div class="row border rounded py-2 mb-2">

          <div class="col-12 col-md-6 col-lg-6 mb-3">
            <label for="">Select Staff</label>
            <fieldset class="form-group">
                <select data-live-search="true" class="custom-select border form-control" name="staff_id" id="staff_id">
                <option value="">Nothing Selected</option>
                @if (count($staffs))
                    @foreach ($staffs as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                @endif
                </select>
            </fieldset>
          </div>

          <div class="col-12 col-md-6 col-lg-3 mb-3 d-none">
            <label for="">Select Location</label>
            <fieldset class="form-group">
              <select data-live-search="true" class="custom-select border form-control" name="warehouse_id" id="">
                <option value="{{ $warehouse_selected != '' ? $warehouse_selected->id : '' }}">{{ $warehouse_selected != '' ? $warehouse_selected->name : 'Nothing Selected' }}</option>
                @if (count($warehouses))
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                @endif
              </select>
            </fieldset>
          </div>

          <div class="col-12 col-md-6 col-lg-2 mb-3">
            <label for="">Start Date</label>
            <fieldset class="form-group">
              <input type="text" name="start_date" class="form-control form_date" id="start_date" value="">
              <span id="date_error" class="text-danger" style="font-size: 12px;"></span>
            </fieldset>
          </div>
          
          <div class="col-12 col-md-6 col-lg-2 mb-3">
            <label for="">End Date</label>
            <fieldset class="form-group">
              <input type="text" name="end_date" class="form-control form_date" id="end_date" value="">
            </fieldset>
          </div>

          <div class="col-12 col-md-6 col-lg-2 mb-3">
            <fieldset class="form-group">
                <label for="" style="visibility: hidden;">Submit</label>
                <input type="button" class="form-control btn" id="btnSubmit" value="Submit">
              </fieldset>
            <div class="d-grid w-100 d-none">
              <button class="btn btn-primary btn-block glow users-list-clear mb-0"></button>
            </div>
          </div>

        </div>
      </form>
    </div>

  </section>
  <!---filter--->

  <section>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
              <div class="card-body pt-3">
                <div class="item_outer d-flex justify-content-between align-items-center p-2 mb-3" style="background-color: #f9f9f9;">
                    <p class="item">
                       <span class="text-dark">Total Revenue</span> <br> <span style="color:gray"></span>
                    </p>

                    <p><b>{{ $currency }}<span id="revenue">{{ $sales_paid }}</span></b></p>
                </div>
                  
              </div>
            </div>
            
        </div>

        <div class="col-md-6">
            <div class="card">
              <div class="card-body pt-3">
                <div class="item_outer d-flex justify-content-between align-items-center p-2 mb-3" style="background-color: #f9f9f9;">
                    <p class="item">
                       <span class="text-dark">Total Expenses</span> <br> <span style="color:gray"></span>
                    </p>

                    <p><b>{{ $currency }}<span id="expenses">{{ $expenses }}</span></b></p>
                </div>
                  
              </div>
            </div>
        </div>

    </div>
  </section>
  <!---can be in any position--->
  <div id="loader"></div>

  <!---tabs--->
  <section>
    <ul class="nav nav-tabs mb-3">
        <li class="active me-3 text-center rounded p-1 tab active-tab"><i class="bi bi-gear-fill"></i> <a data-bs-toggle="tab" href="#SalesAdded" style="color: #fff; font-size: 14px;">Sales Added</a></li>
        <li class="me-3 text-center rounded p-1 tab" style="background-color:#fff;"><i class="bi bi-gear-fill"></i> <a data-bs-toggle="tab" href="#Expenses" style="color: #000; font-size: 14px;">Expenses</a></li>
    </ul>
  </section>

  <!---table tab-content--->
  <section>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body pt-3">
            
          <div class="clearfix mb-2">
            <div class="float-end text-end d-none">
              <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                <i class="bi bi-upload"></i> <span>Import</span></button>
              <button class="btn btn-sm btn-secondary rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data"><i class="bi bi-download"></i> <span>Export</span></button>
              <button class="btn btn-sm btn-danger rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Delete All"><i class="bi bi-trash"></i> <span>Delete All</span></button>
            </div>
          </div>
          <hr>
          <div class="tab-content">

            <!---SalesAdded--->
            <div class="table table-responsive tab-pane fade" id="SalesAdded">
                <table class="table custom-table" style="width:100%">
                    <thead>
                        <tr>
                          <th>Product</th>
                          <th>Sold Amount({{$currency }})</th>
                          <th>Sold Qty</th>
                          <th>In Stock</th> 
                        </tr>
                    </thead>
                    <tbody>
                      @if (count($products) > 0)
                          @foreach ($products as $product)
                          @if ($product->revenue() > 0)
                          <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ number_format($product->revenue())}}</td>
                            <td>{{ $product->soldQty() }}</td>
                            <td>{{ $product->stock_available() }}</td>
                          </tr>
                          @endif    
                          @endforeach
                      @endif
                        
                    </tbody>
                </table>
            </div>

            <!---Expenses--->
            <div class="table table-responsive tab-pane fade" id="Expenses">
                <table class="table custom-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Expense Category</th>
                            <th>Amount ({{ $currency }})</th>
                            <th>Staff</th>  
                        </tr>
                    </thead>
                    <tbody>
                      @if (count($allExpenses) > 0)
                        @foreach ($allExpenses as $expense)
                        
                        <tr>
                          <td>{{ $expense->category->name }}</td>
                          <td>{{ $expense->amount }}</td>
                          <td>{{ isset($expense->staff_id) ? $expense->staff->name : 'None' }}</td>
                        </tr>
                          
                        @endforeach
                      @endif
                        
                    </tbody>
                </table>
            </div>

          </div>

          <!---default--->
          <div class="table table-responsive default_show">
            <table class="table custom-table" style="width:100%">
                <thead>
                    <tr>
                      <th>Product</th>
                      <th>Sold Amount({{$currency }})</th>
                      <th>Sold Qty</th>
                      <th>In Stock</th> 
                    </tr>
                </thead>
                <tbody>
                @if (count($products) > 0)
                    @foreach ($products as $product)
                    @if ($product->revenue() > 0)
                    <tr>
                      <td>{{ $product->name }}</td>
                      <td>{{ number_format($product->revenue())}}</td>
                      <td>{{ $product->soldQty() }}</td>
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

</main><!-- End #main -->

<!-- Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Import Product CSV File</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>Download sample product CSV file <a href="#" class="btn btn-sm rounded-pill btn-primary"><i class="bi bi-download me-1"></i> Download</a></div>
        <div class="mt-3">
          <label for="formFileSm" class="form-label">Click to upload file</label>
          <input class="form-control form-control-sm" id="formFileSm" type="file">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary"><i class="bi bi-upload"></i> Upload</button>
      </div>
    </div>
  </div>
</div>

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
    $('.tab').click(function(){
        $('.default_show').hide();

        $(".tab").removeClass("active-tab").css({'color':'black'});
        $(".tab a").css({'color':'black'});
        $(this).addClass("active-tab");
        $(this).closest('.tab').find('a').css({'color':'white'}); 
    })
   
</script>

<script>

$('#btnSubmit').click(function(e){
    e.preventDefault();
    var staff_id = $("#staff_id").val();
    //var warehouse_id = $("#warehouse_id").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    
    $("#loader").show();
    // $(this).prop('disabled', true);

    $.ajax({
        type:'get',
        url:'/reports-sales-rep-ajax',
        data:{ staff_id:staff_id, start_date:start_date, end_date:end_date },
        success:function(resp){
            //console.log(resp)
    
            if (resp.data.error) {
                var date_error = resp.data.error;
                $('#date_error').text(date_error)
            }
            if (resp.data.sales) {
                var revenue = resp.data.sales;
                $('#revenue').text(revenue)
            }
            if (resp.data.expenses) {
                var expenses = resp.data.expenses;
                $('#expenses').text(expenses)
            }
            // console.log(resp.data.products)
            if (resp.data.products) {
              $(".default_show tbody tr").html('');
                $.each(resp.data.products, function (key, product) {
                  $('.default_show tbody').append("<tr>\
                      <td>"+product.name+"</td>\
                      <td>"+product.revenue+"</td>\
                      <td>"+product.soldQty+"</td>\
                      <td>"+product.stock_available+"</td>\
                      </tr>");
              })
              $("#SalesAdded tbody tr").html('');
                $.each(resp.data.products, function (key, product) {
                  $('#SalesAdded tbody').append("<tr>\
                      <td>"+product.name+"</td>\
                      <td>"+product.revenue+"</td>\
                      <td>"+product.soldQty+"</td>\
                      <td>"+product.stock_available+"</td>\
                      </tr>");
              })
            }

            if (resp.data.allExpenses) {
              $("#Expenses tbody tr").html('');
                $.each(resp.data.allExpenses, function (key, expense) {
                  $('#Expenses tbody').append("<tr>\
                      <td>"+expense.category_name+"</td>\
                      <td>"+expense.amount+"</td>\
                      <td>"+expense.staff_name+"</td>\
                      </tr>");
              })
            }
            
            $("#loader").hide();
                
        },error:function(){
            alert("Error");
        }
    });
    
    
});

</script>
    
@endsection