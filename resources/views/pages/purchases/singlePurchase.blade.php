@extends('layouts.design')
@section('title')View Purchase @endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Purchase Information</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('allPurchase') }}">Purchases</a></li>
          <li class="breadcrumb-item active">Purchase Information<li>
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
                    @if (isset($purchase->attached_document))
                    <a
                    href="{{ asset('/storage/purchase/'.$purchase->attached_document) }}"
                    data-caption="{{ isset($purchase->note) ? $purchase->note : 'no caption' }}"
                    data-fancybox
                    > 
                    <img src="{{ asset('/storage/purchase/'.$purchase->attached_document) }}" style="width: 100px; height: 100px;" class="img-thumbnail img-fluid"
                    alt="Photo"></a>

                    @else
                      <img src="{{ asset('/storage/purchase/default.png') }}" style="width: 100px; height:100px;" class="img-thumbnail img-fluid"
                      alt="Attached File"></a> 
                    @endif
                  </div>
                  <div class="d-grid ms-lg-3">
                    <div class="display-6">{{ isset($purchase->note) ? $purchase->note : 'No Caption' }}</div>
                    <h5>Amount Paid: {{ $purchase->amountPaidAccrued($purchase->purchase_code) }}</h5>

                    @if($purchase->amountPaidAccrued($purchase->purchase_code) > $purchase->amountDueAccrued($purchase->purchase_code))
                      <div class="d-flex justify-content-start">
                        <small class="text-danger me-2"></small><small>Due</small>
                      </div>
                    @else
                      <small class="text-success">Paid</small>
                    @endif
                    
                  </div>
                </div>
        
                <div class="float-lg-end">
                  <a href="{{ route('editPurchase', $purchase->unique_key) }}"><button class="btn btn-sm btn-success"><i class="bi bi-pencil-square"></i></button></a>
                </div>
              </div>

              <hr>

              <div class="row g-3">
                <div class="col-lg-3">
                  <label for="">Product Code</label>
                  <div class="lead">{{ $purchase->product->name }}</div>
                </div>

                <div class="col-lg-3">
                  <label for="">Purchased Unit Price</label>
                  <div class="lead">{{ $purchase->product_purchase_price }}</div>
                </div>

                <div class="col-lg-3">
                  <label for="">Qty Purchased</label>
                  <div class="lead">{{ $purchase->product_qty_purchased }}</div>
                </div>
               
                <div class="col-lg-3">
                  <label for="">Amount Paid</label>
                  <div class="lead">{{ $purchase->amount_paid }}</div>
                </div>
                
              </div>

              <!--features-->
              @if ($purchase->purchases->count() > 0)

              @foreach ($purchase->purchases as $purchase)
              <hr>
              <div class="row g-3">
                <div class="col-lg-3">
                  <label for="">Product Code</label>
                  <div class="lead">{{ $purchase->product->name }}</div>
                </div>

                <div class="col-lg-3">
                  <label for="">Purchased Unit Price</label>
                  <div class="lead">{{ $purchase->product_purchase_price }}</div>
                </div>

                <div class="col-lg-3">
                  <label for="">Qty Purchased</label>
                  <div class="lead">{{ $purchase->product_qty_purchased }}</div>
                </div>
               
                <div class="col-lg-3">
                  <label for="">Amount Paid</label>
                  <div class="lead">{{ $purchase->amount_paid }}</div>
                </div>
                
              </div>

              @endforeach
                  
                
              @endif

            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

@endsection