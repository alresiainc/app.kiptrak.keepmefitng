@extends('layouts.design')
@section('title')Edit Agent @endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Edit Agent</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Edit Agent</li>
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
              
              <form class="row g-3 needs-validation" action="{{ route('editAgentPost', $agent->unique_key) }}" method="POST"
              enctype="multipart/form-data">@csrf

                <div class="gallery-uploader-wrap">
                    <label for="" class="form-label">Picture</label>
                    <br>
                    <label class="uploader-img">
                        @if (isset($agent->profile_picture))
                            <img src="{{ asset('/storage/agent/'.$agent->profile_picture) }}" width="100" class="img-fluid" alt="Upload Photo">
                        @else
                            <img src="{{ asset('/storage/agent/person.png') }}" width="100" class="img-fluid" alt="Upload Photo">
                        @endif
                     
                    </label>
                </div>

                <div class="col-md-6">
                  <label for="" class="form-label">First Name</label>
                  <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" id=""
                  value="{{ $firstname }}">
                  @error('firstname')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="" class="form-label">Last Name</label>
                  <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" id=""
                  value="{{ $lastname }}">
                  @error('lastname')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Email</label>
                  <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" id=""
                  value="{{ $agent->email }}">
                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                
                <div class="col-md-4">
                  <label for="" class="form-label">Phone 1</label>
                  <input type="tel" name="phone_1" class="form-control @error('phone_1') is-invalid @enderror" placeholder=""
                  value="{{ $agent->phone_1 }}">
                  @error('phone_1')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                <div class="col-md-4">
                  <label for="" class="form-label">Phone 2</label>
                  <input type="tel" name="phone_2" class="form-control @error('phone_2') is-invalid @enderror" placeholder=""
                  value="{{ isset($agent->phone_2) ? $agent->phone_2 : '' }}">
                  @error('phone_2')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">City / Town</label>
                  <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" placeholder=""
                  value="{{ isset($agent->city) ? $agent->city : '' }}">
                  @error('city')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">State</label>
                  <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" placeholder=""
                  value="{{ $agent->state }}">
                  @error('state')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Select Country</label>
                  <select name="country" class="form-select tags @error('country') is-invalid @enderror" data-allow-clear="true" data-suggestions-threshold="0">

                    <option value="{{ $agent->country->id }}" selected>{{ $agent->country->name }}</option>
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
                  <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" placeholder=""
                  value="{{ isset($agent->address) ? $agent->address : '' }}">
                  @error('address')
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
                  <button type="submit" class="btn btn-info">Update Agent</button>
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