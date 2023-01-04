@extends('layouts.design')
@section('title')View Sale @endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Sale Information</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('allSale') }}">Sales</a></li>
          <li class="breadcrumb-item active">Sale Information<li>
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
                    @if (isset($sale->attached_document))
                    <a
                    href="{{ asset('/storage/sale/'.$sale->attached_document) }}"
                    data-caption="{{ isset($sale->note) ? $sale->note : 'no caption' }}"
                    data-fancybox
                    > 
                    <img src="{{ asset('/storage/sale/'.$sale->attached_document) }}" style="width: 100px; height: 100px;" class="img-thumbnail img-fluid"
                    alt="Photo"></a>

                    @else
                      <img src="{{ asset('/storage/sale/default.png') }}" style="width: 100px; height:100px;" class="img-thumbnail img-fluid"
                      alt="Attached File"></a> 
                    @endif
                  </div>
                  <div class="d-grid ms-lg-3">
                    <div class="display-6">{{ isset($sale->note) ? $sale->note : 'No Caption' }}</div>
                    <h5>Amount Paid: {{ $sale->amountPaidAccrued($sale->sale_code) }}</h5>

                    @if($sale->amountPaidAccrued($sale->sale_code) > $sale->amountDueAccrued($sale->sale_code))
                      <div class="d-flex justify-content-start">
                        <small class="text-danger me-2"></small><small>Due</small>
                      </div>
                    @else
                      <small class="text-success">Paid</small>
                    @endif
                    
                  </div>
                </div>
        
                <div class="float-lg-end">
                  <a href="{{ route('editSale', $sale->unique_key) }}"><button class="btn btn-sm btn-success"><i class="bi bi-pencil-square"></i></button></a>
                </div>
              </div>

              <hr>

              <div class="row g-3">
                <div class="col-lg-3">
                  <label for="">Product Code</label>
                  <div class="lead">{{ $sale->product->name }}</div>
                </div>

                <div class="col-lg-3">
                  <label for="">Sold Unit Price</label>
                  <div class="lead">{{ $sale->product_selling_price }}</div>
                </div>

                <div class="col-lg-3">
                  <label for="">Qty Sold</label>
                  <div class="lead">{{ $sale->product_qty_sold }}</div>
                </div>
               
                <div class="col-lg-3">
                  <label for="">Amount Paid</label>
                  <div class="lead">{{ $sale->amount_paid }}</div>
                </div>
                
              </div>

              <!--others-->
              @if ($sale->sales->count() > 0)

              @foreach ($sale->sales as $sale)
              <hr>
              <div class="row g-3">
                <div class="col-lg-3">
                  <label for="">Product Code</label>
                  <div class="lead">{{ $sale->product->name }}</div>
                </div>

                <div class="col-lg-3">
                  <label for="">Sold Unit Price</label>
                  <div class="lead">{{ $sale->product_selling_price }}</div>
                </div>

                <div class="col-lg-3">
                  <label for="">Qty Saled</label>
                  <div class="lead">{{ $sale->product_qty_sold }}</div>
                </div>
               
                <div class="col-lg-3">
                  <label for="">Amount Paid</label>
                  <div class="lead">{{ $sale->amount_paid }}</div>
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