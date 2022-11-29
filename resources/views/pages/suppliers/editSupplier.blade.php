@extends('layouts.design')
@section('title')Edit Supplier @endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Edit Supplier</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Edit Supplier</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

      </div>
    </section>

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
              
              <form class="row g-3 needs-validation" action="{{ route('editSupplierPost', $supplier->unique_key) }}" method="POST"
              enctype="multipart/form-data">@csrf

              <div class="gallery-uploader-wrap">
                <label for="" class="form-label">Logo</label>
                <br>
                <label class="uploader-img">
                  @if (isset($supplier->company_logo))
                    <img src="{{ asset('/storage/supplier/'.$supplier->company_logo) }}" width="100" class="img-fluid" alt="Upload Logo">
                  @else
                    <img src="{{ asset('/storage/supplier/default.png') }}" width="100" class="img-fluid" alt="Upload Logo">
                  @endif 
                </label>
              </div>

                <div class="col-md-12">
                  <label for="" class="form-label">Company Name</label>
                  <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror"
                  value="{{ $supplier->company_name }}">
                  @error('company_name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                <div class="col-md-12">
                  <label for="" class="form-label">Supplier Full Name</label>
                  <input type="text" name="supplier_name" class="form-control @error('supplier_name') is-invalid @enderror"
                  value="{{ $supplier->supplier_name }}">
                  @error('supplier_name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="" class="form-label">Email</label>
                  <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                  value="{{ $supplier->email }}">
                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="" class="form-label">Phone</label>
                  <input type="tel" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" placeholder=""
                  value="{{ $supplier->phone_number }}">
                  @error('phone_1')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
            
                
                

                <div class="col-md-6">
                  <label for="" class="form-label">Company Logo | Optional</label>
                  <input type="file" name="company_logo" class="form-control @error('company_logo') is-invalid @enderror" id="">
                  @error('company_logo')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Update Supplier</button>
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