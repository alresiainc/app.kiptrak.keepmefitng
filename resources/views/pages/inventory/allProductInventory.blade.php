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
    .product_img {
      width: 50px !important;
      height: 50px !important;
    }
</style>
@endsection

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>@if ($stock=="in_stock") In Stock @elseif($stock=="out_of_stock") Out Of Stock @endif Products Inventory</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('inventoryDashboard') }}">Inventory</a></li>
        <li class="breadcrumb-item active">Products</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  
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
  
              <div class="float-end text-end d-none">
                <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                  <i class="bi bi-upload"></i> <span>Import</span></button>
                <button class="btn btn-sm btn-secondary rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data"><i class="bi bi-download"></i> <span>Export</span></button>
                <button class="btn btn-sm btn-danger rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Delete All"><i class="bi bi-trash"></i> <span>Delete All</span></button>
              </div>
            </div>
          <hr>
          
          <div class="table table-responsive">
            <table id="products-table" class="table custom-table" style="width:100%">
              <thead>
                  <tr>
                      <th>Photo</th>
                      <th>Code</th>
                      <th>Name</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                @if (count($products) > 0)
                  @if ($stock=="")
                    @foreach ($products as $product)
                    <tr>
                      <td>
                        <a
                        href="{{ asset('/storage/products/'.$product->image) }}"
                        data-fancybox="gallery"
                        data-caption="{{ isset($product->name) ? $product->name : 'no caption' }}"
                        >   
                        <img src="{{ asset('/storage/products/'.$product->image) }}" width="50" class="img-thumbnail img-fluid product_img"
                        alt="{{$product->name}}"></a>
                      </td>
                      <td>{{ $product->code  }}</td>
                      <td>{{ $product->name }}</td>
                      
                      {{-- <td>{{ isset($product->color) ? $product->color : 'None' }}</td>
                      <td>{{ isset($product->size) ? $product->size : 'None' }}</td> --}}
                      
                      
                      <td>
                        <div class="d-flex">
                          <a href="{{ route('singleProductSales', $product->unique_key) }}" class="badge badge-success me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View Sales">View Sales</a>
                          <a href="{{ route('singleProductPurchases', $product->unique_key) }}" class="badge badge-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View Purchases">View Purchases</a>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  @endif

                  @if ($stock=="in_stock")
                  @foreach ($products as $product)
                  @if ($product->stock_available() > 10)
                  <tr>
                    <td>
                      <a
                      href="{{ asset('/storage/products/'.$product->image) }}"
                      data-fancybox="gallery"
                      data-caption="{{ isset($product->name) ? $product->name : 'no caption' }}"
                      >   
                      <img src="{{ asset('/storage/products/'.$product->image) }}" width="50" class="img-thumbnail img-fluid product_img"
                      alt="{{$product->name}}"></a>
                    </td>
                    <td>{{ $product->code  }}</td>
                    <td>{{ $product->name }}</td>
                    
                    {{-- <td>{{ isset($product->color) ? $product->color : 'None' }}</td>
                    <td>{{ isset($product->size) ? $product->size : 'None' }}</td> --}}
                    
                    <td>
                      <div class="d-flex">
                        <a href="{{ route('singleProductSales', $product->unique_key) }}" class="badge badge-success me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View Sales">View Sales</a>
                        <a href="{{ route('singleProductPurchases', $product->unique_key) }}" class="badge badge-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View Purchases">View Purchases</a>
                      </div>
                    </td>
                  </tr>
                  @endif
                  @endforeach
                  @endif

                  @if ($stock=="out_of_stock")
                  @foreach ($products as $product)
                  @if ($product->stock_available() < 10)
                  <tr>
                    <td>
                      <a
                      href="{{ asset('/storage/products/'.$product->image) }}"
                      data-fancybox="gallery"
                      data-caption="{{ isset($product->name) ? $product->name : 'no caption' }}"
                      >   
                      <img src="{{ asset('/storage/products/'.$product->image) }}" width="50" class="img-thumbnail img-fluid product_img"
                      alt="{{$product->name}}"></a>
                    </td>
                    <td>{{ $product->code  }}</td>
                    <td>{{ $product->name }}</td>
                    
                    {{-- <td>{{ isset($product->color) ? $product->color : 'None' }}</td>
                    <td>{{ isset($product->size) ? $product->size : 'None' }}</td> --}}
                    
                    
                    <td>
                      <div class="d-flex">
                        <a href="{{ route('singleProductSales', $product->unique_key) }}" class="badge badge-success me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View Sales">View Sales</a>
                        <a href="{{ route('singleProductPurchases', $product->unique_key) }}" class="badge badge-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View Purchases">View Purchases</a>
                      </div>
                    </td>
                  </tr>
                  @endif
                  @endforeach
                  @endif
                        
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