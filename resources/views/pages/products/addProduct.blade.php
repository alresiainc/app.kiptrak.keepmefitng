@extends('layouts.design')
@section('title')Create Product @endsection

@section('extra_css')
    <style>
        /* select2 arrow */
        select{
            -webkit-appearance: listbox !important
        }

        /* custom-select border & inline edit */
        .btn-light {
            background-color: #fff !important;
            color: #000 !important;
        }
        div.filter-option-inner-inner{
            color: #000 !important;
        }
        /* custom-select border & inline edit */

        /* select2 height proper */
        .select2-selection__rendered {
            line-height: 31px !important;
        }
        .select2-container .select2-selection--single {
            height: 35px !important;
        }
        .select2-selection__arrow {
            height: 34px !important;
        }
        /* select2 height proper */
    </style>
@endsection

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Add Product</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Add Product</li>
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
              
              <form class="row g-3" action="{{ route('addProductPost') }}" method="POST" enctype="multipart/form-data">@csrf
                
                <div class="col-md-12">
                  <label for="" class="form-label">Name<span class="text-danger fw-bolder">*</span></label>
                  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                  id="" value="{{ old('name') }}">
                  @error('name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                
                <div class="col-md-4">
                  <label for="" class="form-label">Quantity<span class="text-danger fw-bolder">*</span></label>
                  <input type="number" name="quantity" min="1" class="form-control @error('quantity') is-invalid @enderror"
                  value="{{ old('quantity') ? old('quantity') : '1' }}">
                  @error('quantity')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Product Cost of Production<span class="text-danger fw-bolder">*</span></label>
                  <input type="number" name="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror" placeholder="" value="{{ old('purchase_price') }}">
                  @error('purchase_price')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Selling Price (optional)</label>
                  <input type="number" name="sale_price" class="form-control @error('price') is-invalid @enderror" placeholder="" value="{{ old('price') }}">
                  @error('price')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

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

                <div class="col-md-4">
                  <label for="" class="form-label">Color | optional</label>
                  <input type="text" name="color" class="form-control" placeholder="" value="{{ old('color') }}">
                  
                </div>
                <div class="col-md-4">
                  <label for="" class="form-label">Size or Weight | optional</label>
                  <input type="text" name="size" class="form-control" placeholder="e.g: 5kg" value="{{ old('size') }}">
                  
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Currency<span class="text-danger fw-bolder">*</span></label>
                  <select name="currency" class="custom-select form-control border @error('currency') is-invalid @enderror" id="">
                    <option value="1" selected>Nigerian | ₦</option>
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
                  <label for="" class="form-label">Unique Code | optional</label>
                  <input type="text" name="code" class="form-control" placeholder="" value="{{ old('code') }}">
                </div>

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
                  <button type="submit" class="btn btn-primary">Add Product</button>
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