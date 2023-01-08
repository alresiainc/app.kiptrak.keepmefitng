@extends('layouts.design')
@section('title')View Warehouse @endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Warehouse Information</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('allWarehouse') }}">Warehouses</a></li>
          <li class="breadcrumb-item active">Warehouse Information<li>
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
                  
                  <div class="d-grid ms-lg-3" style="padding-right: 10px; border-right: 1px solid;">
                    <div class="display-6">{{ $warehouse->name }}</div>
                    <h5>{{ $warehouse->state }} | {{ $warehouse->country->name }}</h5>

                    @if ($warehouse->status == 'true')
                      <div class="d-flex justify-content-start">
                        <small class="text-success me-2">Active</small>
                      </div>
                    @else
                      <small class="text-danger">Inactive</small>
                    @endif
                    
                  </div>

                  <div class="d-grid ms-lg-3">
                    <div class="display-7">Total Products</div>
                    <h5 class="text-center">{{ count($warehouse->products) }}</h5>
                  </div>
                </div>
                <div class="float-lg-end">
                  <a href="{{ route('editWarehouse', $warehouse->unique_key) }}"><button class="btn btn-sm btn-success"><i class="bi bi-pencil-square"></i></button></a>
                </div>
              </div>

              <hr>

              <div class="row g-3">
                <div class="col-lg-3">
                  <label for="">Code</label>
                  <div class="lead">0{{ $warehouse->id }}</div>
                </div>

                @if (isset($warehouse->agent_id))
                <div class="col-lg-3">
                  <label for="">Agent</label>
                  <div class="lead">{{ $warehouse->agent->name }}</div>
                </div>
                @endif

                @if (isset($warehouse->city))
                <div class="col-lg-3">
                  <label for="">City / Town</label>
                  <div class="lead">{{ $warehouse->city }}</div>
                </div>
                @endif
                
                @if (isset($warehouse->state))
                <div class="col-lg-3">
                  <label for="">State</label>
                  <div class="lead">{{ $warehouse->state }} | {{ $warehouse->country->name }}</div>
                </div>
                @endif
                
                
                
              </div>

              <hr>

              <div class="row g-3">
                <div class="col-lg-12"><strong>Products</strong></div>
                @if (count($warehouse->products) > 0)

                @foreach ($warehouse->products as $product)
                <div class="col-lg-3">
                  <label for="">Code</label>
                  <div class="lead" style="font-size: 14px;">{{ $product->code }}</div>
                </div>
                <div class="col-lg-3">
                  <label for="">Name</label>
                  <div class="lead" style="font-size: 14px;">{{ $product->name }}</div>
                </div>
                <div class="col-lg-3">
                  <label for="">Stock</label>
                  <div class="lead">{{ $product->stock_available() }}</div>
                </div>
                <div class="col-lg-3">
                  <label for="">Purchase Price</label>
                  <div class="lead">{{ $product->purchase_price }}</div>
                </div>
                @endforeach
                
                @else
                <div class="col-lg-12 text-center">No Products here at the moment</div>
                @endif

              </div>

              
            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

@endsection