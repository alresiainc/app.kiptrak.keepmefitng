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

  <section class="users-list-wrapper">
    <div class="users-list-filter px-1">
      <form action="{{ route('saleReportQuery') }}" method="POST">@csrf
        <div class="row border rounded py-2 mb-2">

          <div class="col-12 col-md-6 col-lg-3 mb-3">
            <label for="">Select Staff</label>
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

          <div class="col-12 col-md-6 col-lg-3 mb-3">
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
              <input type="text" name="start_date" class="form-control form_date" id="" value="">
            </fieldset>
          </div>
          
          <div class="col-12 col-md-6 col-lg-2 mb-3">
            <label for="">End Date</label>
            <fieldset class="form-group">
              <input type="text" name="end_date" class="form-control form_date" id="" value="">
            </fieldset>
          </div>

          

          <div class="col-12 col-md-6 col-lg-2 mb-3">
            <fieldset class="form-group">
                <label for="" style="visibility: hidden;">Submit</label>
                <input type="button" name="end_date" class="form-control btn" id="" value="Submit">
              </fieldset>
            <div class="d-grid w-100 d-none">
              <button class="btn btn-primary btn-block glow users-list-clear mb-0"></button>
            </div>
          </div>

        </div>
      </form>
    </div>

  </section>

  <section>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
              <div class="card-body pt-3">
                <div class="item_outer d-flex justify-content-between align-items-center p-2 mb-3" style="background-color: #f9f9f9;">
                    <p class="item">
                       <span class="text-dark">Total Revenue</span> <br> <span style="color:gray"></span>
                    </p>

                    <p>{{ $currency }}0.00</p>
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

                    <p>{{ $currency }}0.00</p>
                </div>
                  
              </div>
            </div>
        </div>

    </div>
  </section>

  <!---tabs--->
  <section>
    <ul class="nav nav-tabs mb-3">
        <li class="active me-3 text-center p-1 tab" style="background-color:#fff;"><i class="bi bi-gear-fill"></i> <a data-bs-toggle="tab" href="#SalesAdded" class="text-dark">Sales Added</a></li>
        <li class="me-3 text-center p-1 tab" style="background-color:#fff;"><i class="bi bi-gear-fill"></i> <a data-bs-toggle="tab" href="#Expenses" class="text-dark">Expenses</a></li>
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

            <!---SalesAdded--->
            <div class="table table-responsive tab-pane fade" id="SalesAdded">
                <table class="table custom-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Salll</th>
                            <th>Sold Amount</th>
                            <th>Sold Qty</th>
                            <th>In Stock</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    @if (count($sellingProductsBulk) > 0)
                        @foreach ($sellingProductsBulk as $product)
                        
                            <tr>
                                <td>{{ $product['product_name'] }}</td>
                                <td>{{ $product['sold_amount'] }}</td>
                                <td>{{ $product['sold_qty'] }}</td>
                                <td>{{ $product['stock_available'] }}</td>
                            </tr>
                                
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
                            <th>Exp Name2</th>
                            <th>Sold Amount</th>
                            <th>Sold Qty</th>
                            <th>In Stock</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    @if (count($sellingProductsBulk) > 0)
                        @foreach ($sellingProductsBulk as $product)
                        
                            <tr>
                                <td>{{ $product['product_name'] }}</td>
                                <td>{{ $product['sold_amount'] }}</td>
                                <td>{{ $product['sold_qty'] }}</td>
                                <td>{{ $product['stock_available'] }}</td>
                            </tr>
                                
                        @endforeach
                    @endif
                        
                    </tbody>
                </table>
            </div>

          </div>

          <div class="table table-responsive default_show">
            <table class="table custom-table" style="width:100%">
                <thead>
                    <tr>
                        <th>Product Default</th>
                        <th>Sold Amount</th>
                        <th>Sold Qty</th>
                        <th>In Stock</th>
                        
                    </tr>
                </thead>
                <tbody>
                @if (count($sellingProductsBulk) > 0)
                    @foreach ($sellingProductsBulk as $product)
                    
                        <tr>
                            <td>{{ $product['product_name'] }}</td>
                            <td>{{ $product['sold_amount'] }}</td>
                            <td>{{ $product['sold_qty'] }}</td>
                            <td>{{ $product['stock_available'] }}</td>
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
    })
</script>
    
@endsection