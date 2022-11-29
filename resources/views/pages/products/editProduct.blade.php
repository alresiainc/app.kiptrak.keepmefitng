@extends('layouts.design')
@section('title')Edit Product @endsection

@section('extra_css')
@endsection

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Edit Product</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Edit Product</li>
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

            <div class="card-body">
              
                <form class="row g-3" action="{{ route('editProductPost', $product->unique_key) }}"
                    method="POST" enctype="multipart/form-data">@csrf
                
                    <div class="gallery-uploader-wrap">
                        <label for="" class="form-label">Image</label>
                        <br>
                        <label class="uploader-img">
                          <img src="{{ asset('/storage/products/'.$product->image) }}" width="100" class="img-fluid" alt="Upload Photo"> 
                        </label>
                    </div>

                    <div class="col-md-12">
                      <label for="" class="form-label">Name<span class="text-danger fw-bolder">*</span></label>
                      <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                      id="" value="{{ $product->name }}">
                      @error('name')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
                    
                    <div class="col-md-6">
                      <label for="" class="form-label">
                        Qty Avail:<span class="text-dark fw-bolder"> ({{ $stock_available }})</span>
                        <span class="text-sm fw-bolder"> use negative numbers to decrease qty, e.g: -5</span>
                      </label>
                      <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                      value="{{ $product->quantity }}">
                      @error('quantity')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>

                    <!--reasons--->
                    {{-- <div class="col-md-3">
                      <label for="" class="form-label">Reason for increasing qty</label>
                      <select name="warehouse_id" class="custom-select form-control border" id="">
                        <option value="">Nothing Selected</option>
                        <option value="1">Warehouse 1</option>
                        <option value="2">Warehouse 2</option>
                      </select>
                    </div>

                    <div class="col-md-3">
                      <label for="" class="form-label">Reason for decreasing qty</label>
                      <select name="warehouse_id" class="custom-select form-control border" id="">
                        <option value="">Nothing Selected</option>
                        <option value="1">Warehouse 1</option>
                        <option value="2">Warehouse 2</option>
                      </select>
                    </div> --}}
                    <!--reasons--->
    
                    <!--unit taken care-of by size-->
                    {{-- <div class="col-md-4">
                      <label for="" class="form-label">Unit | optional</label>
                      <select name="unit" class="custom-select form-control border" id="" value="{{ old('unit') }}">
                        <option value="">Nothing Selected</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit['name'] }}-{{ $unit['symbol'] }}">
                              {{ $unit['name'] }} | {{ $unit['symbol'] }}</option>
                        @endforeach
                      </select>
                      
                    </div> --}}
    
                    <div class="col-md-3">
                      <label for="" class="form-label">Color | optional</label>
                      <input type="text" name="color" class="form-control" placeholder=""
                      value="{{ isset($product->color) ? $product->color : '' }}">
                      
                    </div>
                    <div class="col-md-3">
                      <label for="" class="form-label">Size or Wt | optional</label>
                      <input type="text" name="size" class="form-control" placeholder="e.g: 5kg"
                      value="{{ isset($product->size) ? $product->size : '' }}">
                      
                    </div>
    
                    <div class="col-md-4">
                      <label for="" class="form-label">Currency<span class="text-danger fw-bolder">*</span></label>
                      <select name="currency" class="custom-select form-control border @error('currency') is-invalid @enderror" id="">
                        <option value="{{ $product->country_id }}" selected>{{ $currency_nationality }} | {{ $currency_symbol }}</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}">
                              {{ $country->name }} | {{ $country->symbol }}
                            </option>
                        @endforeach
                      </select>
                      @error('currency')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
                    
                    <div class="col-md-4">
                      <label for="" class="form-label">Unit Price<span class="text-danger fw-bolder">*</span></label>
                      <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                      value="{{ $product->price }}">
                      @error('price')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
    
                    <div class="col-md-4">
                      <label for="" class="form-label">SKU Code | optional</label>
                      <input type="text" name="code" class="form-control" value="{{ $product->code }}">
                    </div>
    
                    @if ($features != '')
                      <hr>

                      <div class="col-md-12">
                        <label for="" class="form-label">Features | optional</label>
                      </div>
                    
                    @foreach ($features as $feature)
                        <div class="col-md-6 mt-1 element">
                            <input type="text" name="features[]" class="form-control" value="{{ $feature }}">
                        </div>
                    @endforeach
                        <!--add extra features-->
                        <div class="product-clone-section wrapper">
                          <div class="col-md-12 mt-1 element">
                          <label for="" class="form-label">More Features | optional</label>
                          <input type="text" name="features[]" class="form-control" placeholder="" value="">
                          </div>
      
                          <!--append elements to-->
                          <div class="results"></div>
      
                          <div class="buttons d-flex justify-content-between">
                          <button type="button" class="clone btn btn-success btn-sm rounded-pill"><i class="bi bi-plus"></i></button>
                          <button type="button" class="remove btn btn-danger btn-sm rounded-pill"><i class="bi bi-dash"></i></button>
                          </div>
                        </div>
                        
                        <hr>
                    @else
                    
                        <div class="product-clone-section wrapper">
                            <div class="col-md-12 mt-1 element">
                            <label for="" class="form-label">Features | optional</label>
                            <input type="text" name="features[]" class="form-control" placeholder="" value="">
                            </div>
        
                            <!--append elements to-->
                            <div class="results"></div>
        
                            <div class="buttons d-flex justify-content-between">
                            <button type="button" class="clone btn btn-success btn-sm rounded-pill"><i class="bi bi-plus"></i></button>
                            <button type="button" class="remove btn btn-danger btn-sm rounded-pill"><i class="bi bi-dash"></i></button>
                            </div>
                        </div>

                    @endif
                    
                    <div class="col-md-8">
                      <label for="" class="form-label">Select WareHouse | optional</label>
                      <select name="warehouse_id" class="custom-select form-control border" id="">
                        <option value="">Nothing Selected</option>
                        <option value="1">Warehouse 1</option>
                        <option value="2">Warehouse 2</option>
                      </select>
                      
                    </div>
    
                    <div class="col-md-4">
                      <label for="" class="form-label">Image<span class="text-danger fw-bolder">*</span></label>
                      <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" id="">
                      @error('image')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
                    <div class="text-end">
                      <button type="submit" class="btn btn-primary">Update Product</button>
                      <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                  </form><!-- End Multi Columns Form -->
              
            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

@endsection

@section('extra_js')
<script>
    //clone
    $('.wrapper').on('click', '.remove', function() {
        $('.remove').closest('.wrapper').find('.element').not(':first').last().remove();
    });
    $('.wrapper').on('click', '.clone', function() {
        $('.clone').closest('.wrapper').find('.element').first().clone().appendTo('.results');
    });
</script>
@endsection