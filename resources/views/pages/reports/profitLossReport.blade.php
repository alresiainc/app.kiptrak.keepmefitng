@extends('layouts.design')
@section('title')Profit & Loss Report @endsection

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
    <div>
      <h1 class="text-start">Profit & Loss Report</h1>
      <h1 class="text-end"><span>Profit({{ $currency }}):</span> <span id="profit">{{ number_format($profit_val) }}</span></h1>
    </div>
    
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Profit & Loss Report</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if(Session::has('error'))
  <div class="alert alert-danger mb-3 text-center">
      {{Session::get('error')}}
  </div>
  @endif

  <section class="users-list-wrapper">
    <div class="users-list-filter px-1">
      <form>
        <div class="row border rounded py-2 mb-2">

          <div class="col-12 col-md-6 col-lg-3 mb-3">
            <label for="">Start Date</label>
            <fieldset class="form-group">
              <input type="text" name="start_date" class="form-control form_date" id="start_date" value="">
              <span id="date_error" class="text-danger" style="font-size: 12px;"></span>
            </fieldset>
          </div>
          
          <div class="col-12 col-md-6 col-lg-3 mb-3">
            <label for="">End Date</label>
            <fieldset class="form-group">
              <input type="text" name="end_date" class="form-control form_date" id="end_date" value="">
            </fieldset>
          </div>

          <div class="col-12 col-md-6 col-lg-4 mb-3">
            <label for="">Select Location</label>
            <fieldset class="form-group">
              <select data-live-search="true" class="custom-select border form-control" name="warehouse_id" id="warehouse_id">
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
            <fieldset class="form-group">
                <label for="" style="visibility: hidden;">Select Location</label>
                <input type="button" name="end_date" class="form-control btn" id="btnSubmit" value="Submit">
              </fieldset>
            <div class="d-grid w-100 d-none">
              <button class="btn btn-primary btn-block glow users-list-clear mb-0"></button>
            </div>
          </div>

        </div>
      </form>
    </div>

  </section>
  <!---can be in any position--->
  <div id="loader"></div>

  <section>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
              <div class="card-body pt-3">
                <div class="item_outer d-flex justify-content-between align-items-center p-2 mb-3" style="background-color: #f9f9f9;">
                    <p class="item">
                       <span class="text-dark">Openning Stock</span> <i class="bi bi-info-circle-fill" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Remaining Unsold Stock By Purchase Price, As Of Yesterday"></i> <br> <span style="color:gray">(By Purchase Price)</span>
                    </p>

                    <p>{{ $currency }}<span id="openningStock_by_purchasePrice">{{ number_format($openningStock_by_purchasePrice) }}</span></p>
                </div>

                <div class="item_outer d-flex justify-content-between align-items-center p-2 mb-3" style="background-color: #f9f9f9;">
                    <p class="item">
                       <span class="text-dark">Openning Stock</span> <i class="bi bi-info-circle-fill" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Remaining Unsold Stock By Sale Price, As Of Yesterday"></i> <br> <span style="color:gray">(By Sale Price)</span>
                    </p>

                    <p>{{ $currency }}<span id="openningStock_by_salePrice">{{ number_format($openningStock_by_salePrice) }}</span></p>
                </div>

                <div class="item_outer d-flex justify-content-between align-items-center p-2 mb-3" style="background-color: #f9f9f9;">
                    <p class="item">
                       <span class="text-dark">Total Purchase</span> <br> <span style="color:gray"></span>
                    </p>

                    <p>{{ $currency }}<span id="purchases_amount_paid">{{ number_format($purchases_amount_paid) }}</span></p>
                </div>

                <div class="item_outer d-flex justify-content-between align-items-center p-2 mb-3" style="background-color: #f9f9f9;">
                    <p class="item">
                       <span class="text-dark">Other Expenses</span> <br> <span style="color:gray"></span>
                    </p>

                    <p>{{ $currency }}<span id="other_espenses">{{ number_format($other_espenses) }}</span></p>
                </div>

                <div class="item_outer d-flex justify-content-between align-items-center p-2 mb-3" style="background-color: #f9f9f9;">
                    <p class="item">
                       <span class="text-dark">Total Payroll</span> <br> <span style="color:gray"></span>
                    </p>

                    <p>{{ $currency }}<span id="payroll">{{ number_format($payroll) }}</span></p>
                </div>

                <div class="item_outer d-flex justify-content-between align-items-center p-2 mb-3" style="background-color: #f9f9f9;">
                    <p class="item">
                       <span class="text-dark">Total Expenses</span> <i class="bi bi-info-circle-fill" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Total Purchase + Other Expenses + Total Payroll"></i> <br> <span style="color:gray"></span>
                    </p>

                    <p>{{ $currency }}<span id="total_expenses">{{ number_format($total_expenses) }}</span></p>
                </div>

                <div class="item_outer d-flex justify-content-between align-items-center p-2 mb-3" style="background-color: #f9f9f9;">
                    <p class="item">
                       <span class="text-dark">Total Sell Discount</span> <br> <span style="color:gray"></span>
                    </p>

                    <p>{{ $currency }}0.00</p>
                </div>

                <div class="item_outer d-flex justify-content-between align-items-center p-2 mb-3" style="background-color: #f9f9f9;">
                    <p class="item">
                       <span class="text-dark">Total Sell Return</span> <br> <span style="color:gray"></span>
                    </p>

                    <p>{{ $currency }}0.00</p>
                </div>
                  
              </div>
            </div>
        </div>

        <!---closing stock side--->
        <div class="col-md-6">
            <div class="card">
              <div class="card-body pt-3">
                <div class="item_outer d-flex justify-content-between align-items-center p-2 mb-3" style="background-color: #f9f9f9;">
                    <p class="item">
                       <span class="text-dark">Closing Stock</span> <i class="bi bi-info-circle-fill" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Currently Unsold Stock By Purchase Price"></i> <br> <span style="color:gray">(By Purchase Price)</span>
                    </p>

                    <p>{{ $currency }}<span id="closingStock_by_purchasePrice">{{ number_format($closingStock_by_purchasePrice) }}</span></p>
                </div>

                <div class="item_outer d-flex justify-content-between align-items-center p-2 mb-3" style="background-color: #f9f9f9;">
                    <p class="item">
                       <span class="text-dark">Closing Stock</span> <i class="bi bi-info-circle-fill" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Currently Unsold Stock By Sale Price"></i> <br> <span style="color:gray">(By Sale Price)</span>
                    </p>

                    <p>{{ $currency }}<span id="closingStock_by_salePrice">{{ number_format($closingStock_by_salePrice) }}</span></p>
                </div>

                <div class="item_outer d-flex justify-content-between align-items-center p-2 mb-3" style="background-color: #f9f9f9;">
                    <p class="item">
                       <span class="text-dark">Total Sales</span> <br> <span style="color:gray">(By Sale Price)</span>
                    </p>

                    <p>{{ $currency }}<span id="sales_paid">{{ number_format($sales_paid) }}</span></p>
                </div>
                  
              </div>
            </div>
        </div>

    </div>
  </section>

  <!---tabs--->
  <section>
    <ul class="nav nav-tabs mb-3">
        <li class="active me-3 text-center rounded p-1 tab active-tab"><i class="bi bi-gear-fill"></i> <a data-bs-toggle="tab" href="#profitByProducts" style="color: #fff; font-size: 14px;">Profit By Products</a></li>
        <li class="me-3 text-center rounded p-1 tab"><i class="bi bi-gear-fill"></i> <a data-bs-toggle="tab" href="#profitByCategories" style="color: #000; font-size: 14px;">Profit By Categories</a></li>
        <li class="me-3 text-center rounded p-1 tab"><i class="bi bi-gear-fill"></i> <a data-bs-toggle="tab" href="#profitByLocations" style="color: #000; font-size: 14px;">Profit By Locations</a></li>
        <li class="me-3 text-center rounded p-1 tab" style="background-color:#fff;"><i class="bi bi-gear-fill"></i> <a data-bs-toggle="tab" href="#profitByDate" style="color: #000; font-size: 14px;">Profit By Date</a></li>
    </ul>
  </section>

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

            <!---profitByProducts--->
            <div class="table table-responsive tab-pane fade" id="profitByProducts">
                <table class="table custom-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Gross Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                      @if (count($products))
                          @foreach ($products as $product)
                          <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->revenue() }}</td>
                        </tr>
                          @endforeach
                      @endif
                      
                    </tbody>
                </table>
            </div>

            <!---profitByCategories--->
            <div class="table table-responsive tab-pane fade" id="profitByCategories">
              <table class="table custom-table" style="width:100%">
                <thead>
                    <tr>
                        <th>Categories</th>
                        <th>Gross Profit</th>
                    </tr>
                </thead>
                <tbody>
                
                  @if (count($categories))
                      @foreach ($categories as $category)
                      <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->revenue() }}</td>
                      </tr>
                      @endforeach
                  @endif
                
                </tbody>
              </table>
            </div>

            <!---profitByLocations--->
            <div class="table table-responsive tab-pane fade" id="profitByLocations">
              <table class="table custom-table" style="width:100%">
                <thead>
                    <tr>
                        <th>Locations</th>
                        <th>Gross Profit</th>
                    </tr>
                </thead>
                <tbody>
                
                  <tr>
                      <td></td>
                      <td></td>
                  </tr>
                
                </tbody>
            </table>
            </div>

            <!---profitByDate--->
            <div class="table table-responsive tab-pane fade" id="profitByDate">
              <table class="table custom-table" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Gross Profit</th>
                    </tr>
                </thead>
                <tbody>
                
                  <tr>
                      <td></td>
                      <td></td>
                  </tr>
                
                </tbody>
            </table>
            </div>

          </div>

          <div class="table table-responsive default_show">
            <table class="table custom-table" style="width:100%">
              <thead>
                  <tr>
                      <th>Product</th>
                      <th>Gross Profit</th>
                  </tr>
              </thead>
              <tbody>
              
                @if (count($products))
                    @foreach ($products as $product)
                    <tr>
                      <td>{{ $product->name }}</td>
                      <td>{{ $product->revenue() }}</td>
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
    var warehouse_id = $("#warehouse_id").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    
    $("#loader").show();
    // $(this).prop('disabled', true);

    $.ajax({
        type:'get',
        url:'/reports-profit-and-loss-ajax',
        data:{ start_date:start_date, end_date:end_date, warehouse_id:warehouse_id },
        success:function(resp){
            //console.log(resp)
    
            if (resp.data.error) {
                var date_error = resp.data.error;
                $('#date_error').text(date_error)
            }
            if (resp.data.openningStock_by_purchasePrice) {
                var openningStock_by_purchasePrice = resp.data.openningStock_by_purchasePrice;
                $('#openningStock_by_purchasePrice').text(openningStock_by_purchasePrice)
            }
            if (resp.data.openningStock_by_salePrice) {
                var openningStock_by_salePrice = resp.data.openningStock_by_salePrice;
                $('#openningStock_by_salePrice').text(openningStock_by_salePrice)
            }
            if (resp.data.purchases_amount_paid) {
                var purchases_amount_paid = resp.data.purchases_amount_paid;
                $('#purchases_amount_paid').text(purchases_amount_paid)
            }
            if (resp.data.other_espenses) {
                var other_espenses = resp.data.other_espenses;
                $('#other_espenses').text(other_espenses)
            }
            if (resp.data.payroll) {
                var payroll = resp.data.payroll;
                $('#total_expenses').text(payroll)
            }
            if (resp.data.total_expenses) {
                var total_expenses = resp.data.total_expenses;
                $('#total_expenses').text(total_expenses)
            }
            if (resp.data.closingStock_by_purchasePrice) {
                var closingStock_by_purchasePrice = resp.data.closingStock_by_purchasePrice;
                $('#closingStock_by_purchasePrice').text(closingStock_by_purchasePrice)
            }
            if (resp.data.closingStock_by_salePrice) {
                var closingStock_by_salePrice = resp.data.closingStock_by_salePrice;
                $('#closingStock_by_salePrice').text(closingStock_by_salePrice)
            }
            if (resp.data.sales_paid) {
                var sales_paid = resp.data.sales_paid;
                $('#sales_paid').text(sales_paid)
            }
            if (resp.data.profit) {
                var profit = resp.data.profit;
                $('#profit').text(profit)
            }
            
            // console.log(resp.data.products)
            if (resp.data.products) {
              $(".default_show tbody tr").html('');
                $.each(resp.data.products, function (key, product) {
                  $('.default_show tbody').append("<tr>\
                      <td>"+product.name+"</td>\
                      <td>"+product.revenue+"</td>\
                      </tr>");
              })
              
              $("#profitByProducts tbody tr").html('');
                $.each(resp.data.products, function (key, product) {
                  $('#profitByProducts tbody').append("<tr>\
                      <td>"+product.name+"</td>\
                      <td>"+product.revenue+"</td>\
                      </tr>");
              })
            }

            if (resp.data.categories) {
              $("#profitByCategories tbody tr").html('');
                $.each(resp.data.categories, function (key, category) {
                  $('#profitByCategories tbody').append("<tr>\
                      <td>"+category.name+"</td>\
                      <td>"+category.revenue+"</td>\
                      </tr>");
              })
            }

            // if (resp.data.allExpenses) {
            //   $("#Expenses tbody tr").html('');
            //     $.each(resp.data.allExpenses, function (key, expense) {
            //       $('#Expenses tbody').append("<tr>\
            //           <td>"+expense.category_name+"</td>\
            //           <td>"+expense.amount+"</td>\
            //           <td>"+expense.staff_name+"</td>\
            //           </tr>");
            //   })
            // }
            
            $("#loader").hide();
                
        },error:function(){
            alert("Error");
        }
    });
    
    
  });
</script>
    
@endsection