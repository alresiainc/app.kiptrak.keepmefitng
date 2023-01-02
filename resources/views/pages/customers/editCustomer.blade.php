@extends('layouts.design')
@section('title')Add Customer @endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Add Customer</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('allCustomer') }}">Customers</a></li>
          <li class="breadcrumb-item active">Add Customer</li>
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
              
              <form class="row g-3 needs-validation" action="{{ route('editCustomerPost', $customer->unique_key) }}" method="POST" enctype="multipart/form-data">@csrf
                
                <div class="gallery-uploader-wrap">
                    <label for="" class="form-label">Picture</label>
                    <br>
                    <label class="uploader-img">
                        @if (isset($customer->profile_picture))
                            <img src="{{ asset('/storage/customer/'.$customer->profile_picture) }}" width="100" class="img-fluid" alt="Upload Photo">
                        @else
                            <img src="{{ asset('/storage/customer/person.png') }}" width="100" class="rounded-circle img-fluid" alt="Upload Photo">
                        @endif
                     
                    </label>
                </div>

                <div class="col-md-6">
                  <label for="" class="form-label">First Name</label>
                  <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" value="{{ $customer->firstname }}">
                  @error('firstname')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="" class="form-label">Last Name</label>
                  <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" value="{{ $customer->lastname }}">
                  @error('lastname')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Email</label>
                  <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ $customer->email }}">
                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-6 d-none">
                  <label for="" class="form-label">Password</label>
                  <input type="text" name="password" class="form-control @error('password') is-invalid @enderror" id="" >
                  @error('password')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Phone 1</label>
                  <input type="tel" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ $customer->phone_number }}" >
                  @error('phone_number')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Phone 2 (Whatsapp Phone)</label>
                  <input type="tel" name="whatsapp_phone_number" class="form-control @error('whatsapp_phone_number') is-invalid @enderror" value="{{ $customer->whatsapp_phone_number }}">
                  @error('whatsapp_phone_number')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">City / Town</label>
                  <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ $customer->city }}">
                  @error('city')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">State</label>
                  <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ $customer->state }}">
                  @error('state')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Select Country</label>
                  <select name="country" class="form-select tags @error('country') is-invalid @enderror" data-allow-clear="true" data-suggestions-threshold="0">

                    <option value="{{ isset($customer->country_id) ? $customer->country_id : '' }}">{{ isset($customer->country_id) ? $customer->country->name : 'Nothing Selected' }}</option>
                    @foreach ($countries as $country)
                      <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                    
                  </select>
                  @error('country')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-8">
                  <label for="" class="form-label">Address</label>
                  <input type="text" name="delivery_address" class="form-control @error('address') is-invalid @enderror" value="{{ $customer->delivery_address }}" >
                  @error('delivery_address')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Profile Picture | Optional</label>
                  <input type="file" name="profile_picture" class="form-control @error('image') is-invalid @enderror" id="">
                  @error('profile_picture')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Update Customer</button>
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