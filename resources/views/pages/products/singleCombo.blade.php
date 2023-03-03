@extends('layouts.design')
@section('title')View Combo Product @endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Combo Product Information</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('allCombo') }}">View Combo Products</a></li>
          <li class="breadcrumb-item active">Combo Product Information<li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <hr>
    <section>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            
            <div class="card-body pt-3">
              <div class="card-title clearfix">
                <div class="d-lg-flex d-grid align-items-center float-start">
                  <div>
                    @if (isset($product->image))
                    <a
                    href="{{ asset('/storage/products/'.$product->image) }}"
                    data-caption="{{ isset($product->name) ? $product->name : 'no caption' }}"
                    data-fancybox
                    > 
                    <img src="{{ asset('/storage/products/'.$product->image) }}" style="width: 100px; height: 100px;" class="img-thumbnail img-fluid"
                    alt="Photo"></a>

                    @else
                      <img src="{{ asset('/storage/products/default.png') }}" width="50" class="rounded-circle img-thumbnail img-fluid"
                      alt="{{$product->name}}"></a> 
                    @endif
                  </div>
                  <div class="d-grid ms-lg-3">
                    <div class="display-6">{{ $product->name }}</div>
                    <h5>{{ $currency_symbol }}{{ $product->price }}</h5>

                    @if ($stock_available > 0)
                      <div class="d-flex justify-content-start">
                        <small class="text-success me-2">(In-Stock)</small><small>Lagos Warehouse</small>
                      </div>
                    @else
                      <small class="text-danger">(Out-Of-Stock) | Lagos Warehouse</small>
                    @endif
                    
                  </div>
                </div>
                <div class="float-lg-end">
                  <a href="{{ route('editCombo', $product->unique_key) }}"><button class="btn btn-sm btn-success"><i class="bi bi-pencil-square"></i></button></a>
                </div>
              </div>

              <hr>

              <div class="row g-3">
                <div class="col-lg-3">
                  <label for="">SKU Code</label>
                  <div class="lead">{{ $product->code }}</div>
                </div>

                @if (isset($product->color))
                <div class="col-lg-3">
                  <label for="">Color</label>
                  <div class="lead">{{ $product->color }}</div>
                </div>
                @endif
                
                @if (isset($product->size))
                <div class="col-lg-3">
                  <label for="">Size</label>
                  <div class="lead">{{ $product->size }}</div>
                </div>
                @endif
                
                <div class="col-lg-3">
                  <label for="">Quantity</label>
                  <div class="lead">{{ $stock_available }}</div>
                </div>
                
              </div>

              @if (isset($product->combo_product_ids))
              <hr>
              <div class="row g-3">
                <div class="col-lg-3">
                  <label for="">Original Selling Price</label>
                  <div class="lead">{{ $product->country->symbol }}{{ $product->comboOriginalSalePrice() }}</div>
                </div>

                <div class="col-lg-3">
                  <label for="">Discount Type</label>
                  <div class="lead">{{ ucFirst($product->discount_type) }}</div>
                </div>
                
                <div class="col-lg-3">
                  <label for="">Discount</label>
                  <div class="lead">{{ $product->discount_type == 'fixed' ? $product->country->symbol.''.$product->discount : $product->discount.'%' }}</div>
                </div>
                
                <div class="col-lg-3">
                  <label for="">After Discount Sale Price</label>
                  <div class="lead">{{ $product->country->symbol }}{{ $product->sale_price }}</div>
                </div>
                
              </div>
              <hr>
              
              <div class="row g-3">
                <div class="col-lg-12 text-center">
                    Products Combined
                </div>
              </div>
              @foreach ($product->comboProducts() as $product)
              <hr>
              <div class="row g-3">
                
                <div class="col-lg-3">
                  <label for="">Product Name</label>
                  <div class="lead">{{ $product->name }}</div>
                </div>

                <div class="col-lg-3">
                  <label for="">Quantity Combined</label>
                  <div class="lead">{{ ucFirst($product->quantity_combined) }}</div>
                </div>
                
                <div class="col-lg-3">
                  <label for="">Unit Purchase Price</label>
                  <div class="lead">{{ $product->country->symbol }}{{ $product->purchase_price }}</div>
                </div>
                
                <div class="col-lg-3">
                  <label for="">Unit Sale Price</label>
                  <div class="lead">{{ $product->country->symbol }}{{ $product->sale_price }}</div>
                </div>
                
              </div>
              @endforeach
              
              @endif

              <!--features-->
              @if ($features != '')
                  
                <hr>
                <div class="row g-1">

                  <div class="col-lg-12">
                    <label for="">Features</label>
                  </div>

                  @foreach ($features as $feature)
                    <div class="col-lg-4">
                      {{ $feature }}
                    </div>
                  @endforeach
                
                </div>

              @endif

            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

@endsection