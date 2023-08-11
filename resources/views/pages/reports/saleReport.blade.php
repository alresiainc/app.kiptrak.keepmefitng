@extends('layouts.design')
@section('title')Sales Report @endsection

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
    <h1>Product Sales Report</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Product Sales Report</li>
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
            <label for="">Start Date</label>
            <fieldset class="form-group">
              <input type="date" name="start_date" class="form-control" id="" value="{{ $start_date != '' ? $start_date : '' }}">
            </fieldset>
          </div>
          
          <div class="col-12 col-md-6 col-lg-3 mb-3">
            <label for="">End Date</label>
            <fieldset class="form-group">
              <input type="date" name="end_date" class="form-control" id="" value="{{ $end_date != '' ? $end_date : '' }}">
            </fieldset>
          </div>

          <div class="col-12 col-md-6 col-lg-3 mb-3">
            <label for="">Select Warehouse</label>
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

          <div class="col-12 col-md-6 col-lg-3 d-flex align-items-end mb-3">
            <div class="d-grid w-100">
              <button class="btn btn-primary btn-block glow users-list-clear mb-0"><i class="bx bx-plus"></i>Submit</button>
            </div>
          </div>

        </div>
      </form>
    </div>

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
          
          <div class="row mb-3">
            <div class="col-lg-6 col-md-6">
              <label for="filter-categoryname">Category</label>
              <select id="filter-categoryname" type="select" class="custom-select border form-control filter">
                <option value="">Nothing Selected</option>
                @if (count($categories))
                    @foreach ($categories as $category)
                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                    @endforeach
                @endif
              </select>
            </div>
          </div>

          <div class="table table-responsive">
            <table id="products-table" class="table custom-table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Category</th>
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
                              <td>{{ $product['product_category'] }}
                                <input type="hidden" class="product_categoryname" value="{!! $product['product_category'] !!}">
                              </td>
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
<!--category-filter-transfers-->
<script>
    
  //////////
  $('#filter-categoryname').change(function() {
      product_filter_function();
  });

  $('#products-table tbody tr').show();

  function product_filter_function() {
      $('#products-table tbody tr').hide();
      var categorynameValue = $('#filter-categoryname').val();

      //traversing each row one by one
      $('#products-table tr').each(function() {
          var categorynameFlag = 0;
          
          if (categorynameValue == 0) {
              categorynameFlag = 1;
          } else {
              // Check if any hidden input in the row has a value matching the selected value
              $(this).find('input.product_categoryname').each(function() {
                  if ($(this).val() == categorynameValue) {
                      categorynameFlag = 1;
                      return false; // Exit the loop if a match is found
                  }
              });
          }

          //show if flag is true
          if (categorynameFlag) {
              $(this).show();
          }
      });
  }

</script>
@endsection