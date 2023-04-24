@extends('layouts.design')
@section('title')View Agent @endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Agent Information</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('allAgent') }}">Agents</a></li>
          <li class="breadcrumb-item active">Agent Information<li>
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
                    @if (isset($agent->profile_picture))
                        <a
                        href="{{ asset('/storage/agent/'.$agent->profile_picture) }}"
                        data-caption="{{ isset($agent->name) ? $agent->name : 'no caption' }}"
                        data-fancybox
                        > 
                        <img src="{{ asset('/storage/agent/'.$agent->profile_picture) }}" width="100" class="img-thumbnail img-fluid"
                        alt="Photo"></a>
                    @else
                    <img src="{{ asset('/storage/agent/person.png') }}" width="100" class="img-thumbnail img-fluid"
                    alt="Photo"> 
                    @endif
                    
                  </div>

                  <div class="d-grid ms-lg-3">
                    <div class="display-6">{{ $agent->name }}</div>
                    <h5>{{ $agent->state }} | {{ $agent->country->name }} | Role: {{ $agent->hasAnyRole($agent->id) ? $agent->role($agent->id)->role->name : 'No Role' }}</h5>

                    @if ($agent->status == 'true')
                      <div class="d-flex justify-content-start">
                        <small class="text-success me-2">Active</small>
                      </div>
                    @else
                      <small class="text-danger">Inactive</small>
                    @endif
                  </div>

                  @if (isset($warehouse))
                  <div class="d-grid ms-lg-3" style="padding-left: 10px; border-left: 1px solid;">
                    <div class="display-6">{{ $warehouse->name }}</div>
                    
                    <div class="display-7">Total Products</div>
                    <h5 class="text-start">{{ count($warehouse->products) }}</h5>
                  
                  </div>
                  @endif
                  
                </div>
                <div class="float-lg-end">
                  <a href="{{ route('editAgent', $agent->unique_key) }}"><button class="btn btn-sm btn-success"><i class="bi bi-pencil-square"></i></button></a>
                </div>
              </div>

              <hr>

              <div class="row g-3">
                <div class="col-lg-3">
                  <label for="">Phone Numbers</label>
                  <div class="lead">{{ $agent->phone_1 }}
                    @if(isset($agent->phone_2))
                        <br> {{ $agent->phone_2 }}
                    @endif
                </div>
                </div>

                
                <div class="col-lg-3">
                  <label for="">City/Town</label>
                  <div class="lead">@if (isset($agent->city)){{ $agent->city }} @else N/A @endif</div>
                </div>
               
                <div class="col-lg-3">
                  <label for="">Address</label>
                  <div class="lead">@if (isset($agent->address)){{ $agent->address }} @else N/A @endif</div>
                </div>
                
                <div class="col-lg-3">
                  <label for="">Date Joined</label>
                  <div class="lead">{{ $agent->created_at }}</div>
                </div>
                
              </div>

              @if (isset($warehouse))
                  
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

              @endif

              <!--features-->
              

            </div>

          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

@endsection