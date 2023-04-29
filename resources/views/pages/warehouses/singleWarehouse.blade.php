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
                    <h5>{{ $warehouse->state }} | {{ isset($warehouse->country_id) ? '| '.$warehouse->country->name : '' }}</h5>

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
                  <div class="lead">{{ $warehouse->state }} | {{ isset($warehouse->country_id) ? '| '.$warehouse->country->name : '' }}</div>
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
                  <div class="lead">{{ $warehouse->productQtyInWarehouse($product->id) }}</div>
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

      <!---warehouse-orders--->
      @php
          $gross_revenue = 0; $order_revenue = 0;
      @endphp
      @if ($outgoingStocks != '')
      @if (count($packages) > 0)
      
      @foreach ($packages as $package)

      <div class="row">
        <div class="col-md-12">
          <div class="card">
            
            <div class="card-body pt-3">
              <div class="card-title clearfix d-none">
                <div class="d-lg-flex d-grid align-items-center float-start">
                  
                </div>
                <div class="float-lg-end">
                  <a href="{{ route('editWarehouse', $warehouse->unique_key) }}"><button class="btn btn-sm btn-success"><i class="bi bi-pencil-square"></i></button></a>
                </div>
              </div>

              <div class="row g-3 m-1">
                <div class="col-lg-3">
                    <label for="" class="fw-bolder">Order Code</label>
                    <div class="text-dark display-6">{{ $package['warehouseOrder']['order']->orderCode($package['warehouseOrder']['order']->id) }}</div>
                    <div class="float-lg-start">
                      <a href="{{ route('singleOrder', $package['warehouseOrder']['order']->unique_key) }}"><button class="btn btn-sm btn-success"><i class="bi bi-eye"></i></button></a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <label for="" class="fw-bolder">Customer</label>
                    <div class="text-dark">{{ $package['warehouseOrder']['order']->customer_id ? $package['warehouseOrder']['order']->customer->firstname : 'N/A' }} 
                      {{ $package['warehouseOrder']['order']->customer_id ? $package['warehouseOrder']['order']->customer->lastname : 'N/A' }}
                        | Email: <span class="lead">{{ $package['warehouseOrder']['order']->customer_id ? $package['warehouseOrder']['order']->customer->email : 'N/A' }}</div>
                    <div>Phone:  <span class="lead">{{ $package['warehouseOrder']['order']->customer_id ? $package['warehouseOrder']['order']->customer->phone_number : 'N/A' }}</span><br>
                        @if ($package['warehouseOrder']['order']->customer_id)
                    
                        @php
                            $whatsapp = substr($package['warehouseOrder']['order']->customer->whatsapp_phone_number, 1)
                        @endphp
                        Whatsapp:  <span class="lead"><a href="https://wa.me/{{ '234'.$whatsapp }}?text=Hi" target="_blank">
                            {{ $package['warehouseOrder']['order']->customer->whatsapp_phone_number }}</a></span>
                        @else
                            Whatsapp:  <span class="lead">None</span>
                        @endif
                        {{-- <a href="https://wa.me/2348066216874?text=Hi">Whatsapp link</a> --}}
                    </div>
                    <div>Location:  <span class="lead">{{ $package['warehouseOrder']['order']->customer_id ? $package['warehouseOrder']['order']->customer->city : 'None' }}, {{ $package['warehouseOrder']['order']->customer_id ? $package['warehouseOrder']['order']->customer->state : 'None' }}</span></div>
                    <div>Delivery Address:  <span class="lead">{{ $package['warehouseOrder']['order']->customer_id ? $package['warehouseOrder']['order']->customer->delivery_address : 'None' }}</span></div>
                    
                </div>
                <div class="col-lg-2">
                    <label for="" class="fw-bolder">Expected Revenue</label>
                    <div class="text-dark display-6">{{ $currency }}{{ $package['warehouseOrder']['orderRevenue'] }}</div>
                </div>
                <div class="col-lg-2">
                    <label for="" class="fw-bolder">Agent</label>
                    <div class="text-dark">{{ $package['warehouseOrder']['order']->agent_assigned_id ? $package['warehouseOrder']['order']->agent->name : 'None' }}</div>
                </div>
              </div>

              <hr>
              @foreach ($package['warehouseOrder']['outgoingStock'] as $outgoingStock)
              <div class="row g-3 m-1">
                
                <div class="col-lg-6">
                    <label for="" class="fw-bolder">Product Name</label>
                    <div class="text-dark" style="font-size: 14px;">{{ $outgoingStock->product->name }}</div>
                </div>

                <div class="col-lg-3">
                    <label for="" class="fw-bolder">Quantity Ordered</label>
                    <div class="text-dark d-none" style="font-size: 14px;">{{ $outgoingStock->quantity_removed.' @'. $outgoingStock->product->price }}</div>
                    <div class="text-dark" style="font-size: 14px;">{{ $outgoingStock->quantity_removed }}</div>
                </div>
                
                <div class="col-lg-3">
                    <label for="" class="fw-bolder">Revenue</label>
                    <div class="text-dark" style="font-size: 14px;">{{ $outgoingStock->amount_accrued }}</div>
                </div>
              
            
              </div>
              @php
                  $order_revenue = $order_revenue + $outgoingStock->amount_accrued 
              @endphp
              @endforeach
              
              
            </div>

          </div>
        </div>
    ``</div>

      @endforeach    
      @endif
      @endif
  </section>

</main><!-- End #main -->

@endsection