@extends('layouts.design')
@section('title')Products @endsection
@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Products</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Products</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  
  <section class="users-list-wrapper">
    <div class="users-list-filter px-1">
      <form>
        <div class="row border rounded py-2 mb-2">

          <div class="col-12 col-md-6 col-lg-3 d-none">
            <label for="">Product Name</label>
            <fieldset class="form-group">
              <select data-live-search="true" class="custom-select" name="products" id="">
              </select>
            </fieldset>
          </div>
          
          <div class="col-12 col-md-6 col-lg-3 d-none">
            <label for="">Product Code</label>
            <fieldset class="form-group">
              <select data-live-search="true" class="custom-select" name="product-code" id="">
              </select>
            </fieldset>
          </div>

          <div class="col-12 col-md-6 col-lg-3 d-flex align-items-end d-none">
            <div class="d-grid w-100">
              <button type="reset" class="btn btn-primary btn-block glow users-list-clear mb-0">Clear</button>
            </div>
          </div>

          <div class="col-12 col-md-6 col-lg-3 d-flex align-items-end">
            <div class="d-grid w-100">
              <a href="{{ route('addProduct') }}" class="btn btn-primary btn-block glow users-list-clear mb-0"><i class="bx bx-plus"></i>Add Product</a>
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
            <div class="float-end text-end">
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
                      <th>Name</th>
                      <th>Code</th>
                      {{-- <th>Colour</th>
                      <th>Size</th> --}}
                      <th>Quantity</th>
                      <th>Price</th>
                      <th>Added</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                @if (count($products) > 0)
                    @foreach ($products as $product)
                    <tr>
                      <td>
                        <a
                        href="{{ asset('/storage/products/'.$product->image) }}"
                        data-fancybox="gallery"
                        data-caption="{{ isset($product->name) ? $product->name : 'no caption' }}"
                        >   
                        <img src="{{ asset('/storage/products/'.$product->image) }}" width="50" class="img-thumbnail img-fluid"
                        alt="{{$product->name}}"></a>
                      </td>
                      <td>{{ $product->name }}</td>
                      <td>{{ $product->code  }}</td>
                      {{-- <td>{{ isset($product->color) ? $product->color : 'None' }}</td>
                      <td>{{ isset($product->size) ? $product->size : 'None' }}</td> --}}
                      <td>{{ $product->stock_available() }}</td>
                      <td>{{ $product->country->symbol }}{{ $product->purchase_price }}</td>
                      <td>{{ $product->created_at }}</td>
                      <td>
                        <div class="d-flex">
                          <a href="{{ route('singleProduct', $product->unique_key) }}" class="btn btn-primary btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View"><i class="bi bi-eye"></i></a>
                          <a href="{{ route('editProduct', $product->unique_key) }}" class="btn btn-success btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="bi bi-pencil-square"></i></a>
                          <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
                      </td>
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