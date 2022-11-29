@extends('layouts.design')
@section('title')View Order @endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Order Information</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Order Information<li>
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
                    <label for="" class="fw-bolder">Order Code</label>
                    <div class="text-dark display-6">{{ $order->orderCode($order->id) }}</div>
                </div>
                <div class="col-lg-5">
                    <label for="" class="fw-bolder">Customer</label>
                    <div class="text-dark">{{ $order->customer_id ? $order->customer->firstname : 'N/A' }} {{ $order->customer_id ? $order->customer->lastname : 'N/A' }}
                        | Email: <span class="lead">{{ $order->customer_id ? $order->customer->email : 'N/A' }}</div>
                    <div>Phone:  <span class="lead">{{ $order->customer_id ? $order->customer->phone_number : 'N/A' }}</span><br>
                        @if ($order->customer_id)
                    
                        @php
                            $whatsapp = substr($order->customer->whatsapp_phone_number, 1)
                        @endphp
                        Whatsapp:  <span class="lead"><a href="https://wa.me/{{ '234'.$whatsapp }}?text=Hi" target="_blank">
                            {{ $order->customer->whatsapp_phone_number }}</a></span>
                        @else
                            Whatsapp:  <span class="lead">None</span>
                        @endif
                        {{-- <a href="https://wa.me/2348066216874?text=Hi">Whatsapp link</a> --}}
                    </div>
                    <div>Location:  <span class="lead">{{ $order->customer_id ? $order->customer->city : 'None' }}, {{ $order->customer_id ? $order->customer->state : 'None' }}</span></div>
                    <div>Delivery Address:  <span class="lead">{{ $order->customer_id ? $order->customer->delivery_address : 'None' }}</span></div>
                    
                </div>
                <div class="col-lg-2">
                    <label for="" class="fw-bolder">Expected Revenue</label>
                    <div class="text-dark display-6">{{ $currency }}{{ $gross_revenue }}</div>
                </div>
                <div class="col-lg-2">
                    <label for="" class="fw-bolder">Agent</label>
                    <div class="text-dark">{{ $order->agent_assigned_id ? $order->agent->name : 'None' }}</div>
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
                </div>

                
                <div class="col-lg-3">
                    <label for="" class="fw-bolder">Quantity Ordered</label>
                    <div class="text-dark" style="font-size: 14px;">{{ $package['quantity_removed'].' @'. $package['product']->price }}</div>
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