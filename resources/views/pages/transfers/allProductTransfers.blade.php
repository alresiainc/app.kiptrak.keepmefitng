@extends('layouts.design')
@section('title')All Product Transfer @endsection

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
    <h1>Products Transfer</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Products Transfer</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if(Session::has('success'))
    <div class="alert alert-success mb-3 text-center">
        {{Session::get('success')}}
    </div>
  @endif

  <section>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body pt-3">
            
            <div class="clearfix mb-2">
              <div class="float-start text-start">
                  <a href="javascript:void(0)" class="btn btn-sm btn-dark rounded-pill"  data-bs-toggle="modal" data-bs-target="#productTransfer" style="cursor: pointer;">
                    <i class="bi bi-plus"></i> <span>Create Product Transfer</span></a>
                  
              </div>
  
              <div class="float-end text-end d-none">
                <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                  <i class="bi bi-upload"></i> <span>Import</span></button>
                  <a href="{{ route('productsExport') }}">
                    <button class="btn btn-sm btn-secondary rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data">
                      <i class="bi bi-download"></i> <span>Export</span></button></a>
                <button class="btn btn-sm btn-danger rounded-pill d-none" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Delete All"><i class="bi bi-trash"></i> <span>Delete All</span></button>
              </div>
            </div>
          <hr>

          <div class="row mb-3">
            <div class="col-lg-3 col-md-6">
              <label for="">Start Date</label>
              <input type="text" id="min" class="form-control filter">
            </div>

            <div class="col-lg-3 col-md-6">
              <label for="">End Date</label>
              <input type="text" id="max" class="form-control filter">
            </div>
          </div>
          
          <div class="table table-responsive">
            <table id="products-table" class="table custom-table" style="width:100%">
              <thead>
                  <tr>
                      <th>From Warehouse</th>
                      <th>Products Transferred</th>
                      <th>To Warehouse</th>
                      <th>Added By</th>
                      <th>Date Added</th>
                  </tr>
              </thead>
              <tbody>
                @if (count($transfers) > 0)
                    @foreach ($transfers as $transfer)
                    <tr>
                     
                      <td>{{ $transfer->fromWarehouse->name }}</td>
                      <td>
                        @php
                            $product_qty_transferred = $transfer->product_qty_transferred
                        @endphp
                        @foreach ($product_qty_transferred as $productQty)
                            <div class="badge badge-secondary">{!! isset($productQty['each_product'][0]) ? $productQty['each_product'][0] : '' !!}</div>
                        @endforeach
                      </td>
                      <td>{{ $transfer->toWarehouse->name }}</td>
                      <td>{{ $transfer->createdBy->name }}</td>
                      <td>{{ $transfer->created_at }}</td>
                      
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
<div class="modal fade productTransfer" id="productTransfer" tabindex="-1" aria-labelledby="productTransferLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Product Transfer Setup</h1>
              <button type="button" class="btn-close"
                  data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="productTransferForm" action="{{ route('productTransferSetupPost') }}" method="POST">@csrf
              <div class="modal-body">

                  <div class="d-grid mb-3">
                      <label for="" class="form-label text-dark">From Warehouse</label>
                      <select name="from_warehouse" class="custom-select country form-control border @error('from_warehouse') is-invalid @enderror" id="">
                        <option value="">Nothing Selected</option>
                        @if (count($warehouses) > 0)
                            @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->unique_key }}">{{ $warehouse->name }}</option>
                            @endforeach
                        @endif
                      </select>
                      @error('from_warehouse')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>

                  <div class="d-grid mb-2">
                    <label for="" class="form-label text-dark">To Warehouse</label>
                    <select name="to_warehouse" class="custom-select country form-control border @error('to_warehouse') is-invalid @enderror" id="">
                      <option value="">Nothing Selected</option>
                      @if (count($warehouses) > 0)
                          @foreach ($warehouses as $warehouse)
                          <option value="{{ $warehouse->unique_key }}">{{ $warehouse->name }}</option>
                          @endforeach
                      @endif
                    </select>
                    @error('to_warehouse')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                  
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary productTransferBtn">Submit</button>
              </div>
          </form>
      </div>
  </div>
</div>


@endsection

@section('extra_js')
  
  <?php if(count($errors) > 0) : ?>
    <script>
        $( document ).ready(function() {
            $('#importModal').modal('show');
        });
    </script>
  <?php endif ?>

  <script>
    var minDate, maxDate;
 
    // Custom filtering function which will search data in column four between two values(dates)
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var min = minDate.val();
            var max = maxDate.val();
            var date = new Date( data[4] ); //4 is the date column on datatable, starting from 0
      
            if (
                ( min === null && max === null ) ||
                ( min === null && date <= max ) ||
                ( min <= date && max === null ) ||
                ( min <= date && date <= max )
            ) {
                return true;
            }
            return false;
        }
    );
  </script>

@if ( $errors->has('from_warehouse') || $errors->has('to_warehouse') )
<script type="text/javascript">
    $( document ).ready(function() {
         $('.productTransfer').modal('show');
    });
</script>
@endif

@endsection