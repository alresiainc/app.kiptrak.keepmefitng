@extends('layouts.design')
@section('title')Products @endsection

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
    <h1>Products</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Products</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if(Session::has('success'))
    <div class="alert alert-success mb-3 text-center">
        {{ Session::get('success') }}
    </div>
  @endif

  <section>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body pt-3">
            
            <div class="clearfix mb-2">
              <div class="float-start text-start">
                  <a href="{{ route('addProduct') }}" class="btn btn-sm btn-dark rounded-pill">
                    <i class="bi bi-plus"></i> <span>Add Product</span></a>
                  
              </div>
  
              <div class="float-end text-end">
                <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                  <i class="bi bi-upload"></i> <span>Import</span></button>
                  <a href="{{ route('productsExport') }}">
                    <button class="btn btn-sm btn-secondary rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data">
                      <i class="bi bi-download"></i> <span>Export</span></button></a>
                <button class="btn btn-sm btn-danger rounded-pill d-none" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Delete All"><i class="bi bi-trash"></i> <span>Delete All</span></button>
              </div>
            </div>
          <hr>

          <div class="row mb-3 productsTable">
            <div class="col-lg-3 col-md-6">
              <label for="min">Start Date</label>
              <input type="text" id="min" class="form-control filter form_date">
            </div>

            <div class="col-lg-3 col-md-6">
              <label for="max">End Date</label>
              <input type="text" id="max" class="form-control filter form_date">
            </div>

          </div>
          
          <div class="table table-responsive">
            <table id="products-table" class="table custom-table2" style="width:100%">
              <thead>
                  <tr>
                      <th>Photo</th>
                      <th>Name</th>
                      <th>Code</th>
                      {{-- <th>Colour</th>
                      <th>Size</th> --}}
                      <th>Quantity</th>
                      <th>Price</th>
                      <th>Date Added</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                @if (count($products) > 0)
                    @foreach ($products as $product)
                    <tr>
                      <td>
                        @if (isset($product->image))
                        <a
                        href="{{ asset('/storage/products/'.$product->image) }}"
                        data-fancybox="gallery"
                        data-caption="{{ isset($product->name) ? $product->name : 'no caption' }}"
                        >   
                        <img src="{{ asset('/storage/products/'.$product->image) }}" style="width: 50px; height: 50px;" class="rounded-circle img-thumbnail img-fluid"
                        alt="{{$product->name}}"></a>
                        @else
                        <img src="{{ asset('/storage/products/default.png') }}" width="50" class="rounded-circle img-thumbnail img-fluid"
                        alt="{{$product->name}}"></a> 
                        @endif
                        
                      </td>

                      <td>{{ $product->name }}</td>
                      <td>{{ $product->code  }}</td>
                      {{-- <td>{{ isset($product->color) ? $product->color : 'None' }}</td>
                      <td>{{ isset($product->size) ? $product->size : 'None' }}</td> --}}
                      <td>{{ $product->stock_available() }}</td>
                      <td>{{ $product->country->symbol }}{{ $product->purchase_price }}</td>
                      <td>{{ $product->created_at->format('Y-m-d') }}</td>
                      <td>
                        <div class="d-flex">
                          <a href="{{ route('singleProduct', $product->unique_key) }}" class="btn btn-primary btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View"><i class="bi bi-eye"></i></a>
                          <a href="{{ route('editProduct', $product->unique_key) }}" class="btn btn-success btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="bi bi-pencil-square"></i></a>
                          <a href="{{ route('deleteProduct', $product->unique_key) }}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
                      </td>
                  </tr>
                    @endforeach
                @endif
                  
              </tbody>
            </table>
          </div>

          <!---dup--->
          <div class="row mb-3 productsTable2">
            <div class="col-lg-3 col-md-6">
              <label for="min2">Start Date</label>
              <input type="text" id="min2" class="form-control filter form_date">
            </div>

            <div class="col-lg-3 col-md-6">
              <label for="max2">End Date</label>
              <input type="text" id="max2" class="form-control filter form_date">
            </div>

          </div>
          
          <div class="table table-responsive">
            <table id="products-table2" class="table custom-table2" style="width:100%">
              <thead>
                  <tr>
                      <th>Photo</th>
                      <th>Name</th>
                      <th>Code</th>
                      {{-- <th>Colour</th>
                      <th>Size</th> --}}
                      <th>Quantity</th>
                      <th>Price</th>
                      <th>Date Added</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                @if (count($products) > 0)
                    @foreach ($products as $product)
                    <tr>
                      <td>
                        @if (isset($product->image))
                        <a
                        href="{{ asset('/storage/products/'.$product->image) }}"
                        data-fancybox="gallery"
                        data-caption="{{ isset($product->name) ? $product->name : 'no caption' }}"
                        >   
                        <img src="{{ asset('/storage/products/'.$product->image) }}" style="width: 50px; height: 50px;" class="rounded-circle img-thumbnail img-fluid"
                        alt="{{$product->name}}"></a>
                        @else
                        <img src="{{ asset('/storage/products/default.png') }}" width="50" class="rounded-circle img-thumbnail img-fluid"
                        alt="{{$product->name}}"></a> 
                        @endif
                        
                      </td>

                      <td>{{ $product->name }}</td>
                      <td>{{ $product->code  }}</td>
                      {{-- <td>{{ isset($product->color) ? $product->color : 'None' }}</td>
                      <td>{{ isset($product->size) ? $product->size : 'None' }}</td> --}}
                      <td>{{ $product->stock_available() }}</td>
                      <td>{{ $product->country->symbol }}{{ $product->purchase_price }}</td>
                      <td>{{ $product->created_at->format('Y-m-d') }}</td>
                      <td>
                        <div class="d-flex">
                          <a href="{{ route('singleProduct', $product->unique_key) }}" class="btn btn-primary btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View"><i class="bi bi-eye"></i></a>
                          <a href="{{ route('editProduct', $product->unique_key) }}" class="btn btn-success btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="bi bi-pencil-square"></i></a>
                          <a href="{{ route('deleteProduct', $product->unique_key) }}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
                      </td>
                  </tr>
                    @endforeach
                @endif
                  
              </tbody>
            </table>
          </div>
          <!---dup-end--->

          </div>
        </div>
      </div>
    </div>
  </section>

</main><!-- End #main -->

<!--Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Import Products CSV File</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('productsImport') }}" method="POST" enctype="multipart/form-data">@csrf
        <div class="modal-body">
          <div>Download sample Excel file <a href="{{ route('productsSampleExport') }}" class="btn btn-sm rounded-pill btn-primary"><i class="bi bi-download me-1"></i> Download</a></div>

          @if (count($errors) > 0)
          <div class="row mt-3">
              <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
                    <h4> Error!</h4>
                    @foreach($errors->all() as $error)
                    {{ $error }} <br>
                    @endforeach      
                </div>
              </div>
          </div>
          @endif

          <div class="mt-3">
            <label for="formFileSm" class="form-label">Click to upload file</label>
            <input type="file" class="form-control form-control-sm" name="file" id="formFileSm">
          </div>
        </div>
      
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Upload</button>
        </div>
      </form>

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
  

  $(document).ready(function() {
    var table1 = $('#products-table').DataTable({
      // DataTable configuration options for the first table
    });

    var table2 = $('#products-table2').DataTable({
      // DataTable configuration options for the second table
    });

    // Event handler for datetimepicker value change on the first table
    $('#min, #max').on('change', function() {
      table1.draw();
    });

    // Event handler for datetimepicker value change on the second table
    $('#min2, #max2').on('change', function() {
      table2.draw();
    });

    // Add custom filtering functions for the first table
    $.fn.dataTable.ext.search.push(function(settings, searchData) {
      var min = $('#min').val();
      var max = $('#max').val();
      var date = searchData[5]; // Assuming the date column is at index 5

      if ((min === '' && max === '') ||
          (min === '' && date <= max) ||
          (min <= date && max === '') ||
          (min <= date && date <= max)) {
        return true;
      }

      return false;
    });

    // Add custom filtering functions for the second table
    $.fn.dataTable.ext.search.push(function(settings, searchData) {
      var min = $('#min2').val();
      var max = $('#max2').val();
      var date = searchData[5]; // Assuming the date column is at index 5

      if ((min === '' && max === '') ||
          (min === '' && date <= max) ||
          (min <= date && max === '') ||
          (min <= date && date <= max)) {
        return true;
      }

      return false;
    });

    // Apply filtering on the first table
    $('#min, #max').on('keyup', function() {
      table1.draw();
    });

    // Apply filtering on the second table
    $('#min2, #max2').on('keyup', function() {
      table2.draw();
    });
});

</script>

  
  <?php if(count($errors) > 0) : ?>
    <script>
        $( document ).ready(function() {
            $('#importModal').modal('show');
        });
    </script>
  <?php endif ?>

@endsection