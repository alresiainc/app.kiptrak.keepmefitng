@extends('layouts.design')
@section('title')View Order @endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Cart Abandoned Information</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('cartAbandon') }}">Abandoned List</a></li>
          
          <li class="breadcrumb-item active">Cart Abandoned Information<li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <hr>
    <section>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            
            <div class="row g-3 m-1">
                <div class="col-lg-3">
                    <label for="" class="fw-bolder">Cart Abandoned ID</label>
                    <div class="text-dark display-6">kpcart-0{{ $cart->id }}</div>
                </div>
                
                <div class="col-lg-5">

                    <label for="" class="fw-bolder">Customer</label>

                    <div>First Name: <span class="lead">{{ isset($cart->customer_firstname) ? $cart->customer_firstname : '' }}</div>
                    <div>Last Name: <span class="lead">{{ isset($cart->customer_lastname) ? $cart->customer_lastname : '' }}</span></div>                                   
                    <div>Phone Number: <span class="lead">{{ isset($cart->customer_phone_number) ? $cart->customer_phone_number : '' }}</span></div>
                    <div>Whatsapp Phone: <span class="lead">{{ isset($cart->customer_whatsapp_phone_number) ? $cart->customer_whatsapp_phone_number : '' }}</span></div>
                    <div>Email: <span class="lead">{{ isset($cart->customer_email) ? $cart->customer_email : '' }}</span></div>
                    <div>State: <span class="lead">{{ isset($cart->customer_state) ? $cart->customer_state : '' }}</span></div>
                    <div>City: <span class="lead">{{ isset($cart->customer_city) ? $cart->customer_city : '' }}</span></div>
                    <div>Address: <span class="lead">{{ isset($cart->customer_delivery_address) ? $cart->customer_delivery_address : '' }}</span></div>
                      
                </div>

                <div class="col-lg-2">
                    <label for="" class="fw-bolder">Expected Revenue</label>
                    <div class="text-dark display-6">{{ $currency }}{{ $gross_revenue }}</div>
                </div>
                <div class="col-lg-2">
                    <label for="" class="fw-bolder">Agent</label>
                    <div class="text-dark"></div>
                </div>
                    
                    
                    
            </div>
                
            </div>

            <div class="row g-3 m-1">
                <div class="col-lg-3">

                </div>
            </div>

            @foreach ($packages as $package)
            <hr>
            <div class="row g-3 m-1">
            
                <div class="col-lg-3">
                    <label for="" class="fw-bolder">Product Name</label>
                    <div class="text-dark" style="font-size: 14px;">{{ $package['product']->name }}</div>
                    @if (in_array($package['product']->id, $package_info ))
                        <span class="badge badge-danger">selected</span>
                    @endif
                </div>

                <div class="col-lg-3">
                    <label for="" class="fw-bolder">Quantity</label>
                    <div class="text-dark" style="font-size: 14px;">{{ $package['quantity_removed'].' @'. $package['product']->sale_price }}</div>
                </div>
                
                <div class="col-lg-3">
                    <label for="" class="fw-bolder">Revenue</label>
                    <div class="text-dark" style="font-size: 14px;">{{ $package['revenue'] }}</div>
                </div>
                
                <div class="col-lg-3">
                    <label for="" class="fw-bolder">Date</label>
                    <div class="text-dark" style="font-size: 14px;">{{ $order->created_at }}</div>
                </div>
            
            </div>
            @endforeach
            

              <!--features-->
              

            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->



@endsection