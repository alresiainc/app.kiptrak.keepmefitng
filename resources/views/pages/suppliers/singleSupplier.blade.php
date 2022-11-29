@extends('layouts.design')
@section('title')View Supplier @endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Supplier Information</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Supplier Information<li>
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
                    @if (isset($supplier->company_logo))
                        <a
                        href="{{ asset('/storage/supplier/'.$supplier->company_logo) }}"
                        data-caption="{{ isset($supplier->company_name) ? $supplier->company_name : 'no caption' }}"
                        data-fancybox
                        > 
                        <img src="{{ asset('/storage/supplier/'.$supplier->company_logo) }}" width="100" class="img-thumbnail img-fluid"
                        alt="Photo"></a>

                    @else

                    <img src="{{ asset('/storage/supplier/default.png') }}" width="100" class="img-thumbnail img-fluid"
                    alt="Photo">

                    @endif

                    
                  </div>

                  <div class="d-grid ms-lg-3">
                    <div class="display-6">{{ $supplier->company_name }}</div>
                    <h5>Supplier: {{ $supplier->supplier_name }}</h5>

                    @if ($supplier->status == 'true')
                      <div class="d-flex justify-content-start">
                        <small class="text-success me-2">Active</small>
                      </div>
                    @else
                      <small class="text-danger">Inactive</small>
                    @endif
                    
                  </div>
                </div>
                <div class="float-lg-end">
                  <a href="{{ route('editSupplier', $supplier->unique_key) }}"><button class="btn btn-sm btn-success"><i class="bi bi-pencil-square"></i></button></a>
                </div>
              </div>

              <hr>

              <div class="row g-3 border">
                
                <div class="col-lg-4">
                  <label for="">Purchase Code</label>
                  <div class="lead">00123445566214</div>
                </div>

                
                <div class="col-lg-4">
                  <label for="">Amount</label>
                  <div class="lead">N50000</div>
                </div>
                
 
                <div class="col-lg-4">
                  <label for="">Date</label>
                  <div class="lead">12 Nov 2022</div>
                </div>
               
                
              </div>

              

            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

@endsection