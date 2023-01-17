@extends('layouts.design')
@section('title')Add Agent @endsection
@section('extra_css')
    <style>
        select{
        -webkit-appearance: listbox !important
        }
        .btn-light {
            background-color: #fff !important;
            color: #000 !important;
        }
        /* .bootstrap-select>.dropdown-toggle.bs-placeholder, .bootstrap-select>.dropdown-toggle.bs-placeholder:active, .bootstrap-select>.dropdown-toggle.bs-placeholder:focus, .bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
            color: #999;
        } */
        div.filter-option-inner-inner{
            color: #000 !important;
        }
    </style>
@endsection

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Add Agent</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('allAgent') }}">Agents</a></li>
          <li class="breadcrumb-item active">Add Agent</li>
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
              
              <form class="row g-3 needs-validation" action="{{ route('addAgentPost') }}" method="POST"
              enctype="multipart/form-data">@csrf
                <div class="col-md-6">
                  <label for="" class="form-label">First Name</label>
                  <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" value="{{ old('firstname') }}">
                  @error('firstname')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                <div class="col-md-6">
                  <label for="" class="form-label">Last Name</label>
                  <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" value="{{ old('lastname') }}">
                  @error('lastname')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="" class="form-label">Email</label>
                  <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ $email }}" >
                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="" class="form-label">Password</label>
                  <input type="text" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') ?  old('password') : 'password' }}" >
                  @error('password')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="" class="form-label">Phone 1</label>
                  <input type="tel" id="phone" name="phone_1" class="form-control phone_1 @error('phone_1') is-invalid @enderror" placeholder="" value="{{ old('phone') }}">
                  @error('phone_1')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                <div class="col-md-6">
                  <label for="" class="form-label">Phone 2</label>
                  <input type="tel" name="phone_2" class="form-control phone_2 @error('phone_2') is-invalid @enderror" placeholder="" value="{{ old('phone_2') }}">
                  @error('phone_2')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">City / Town</label>
                  <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" placeholder="" value="{{ old('city') }}">
                  @error('city')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">State</label>
                  <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" placeholder="" value="{{ old('state') }}">
                  @error('state')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Select Country</label>
                  <select name="country" data-live-search="true" class="custom-select form-control border @error('country') is-invalid @enderror">

                    <option value="1">Nigeria</option>
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
                  <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="" value="{{ old('address') }}">
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

                <div class="col-md-12">
                  <label for="" class="form-label">Assign Role (Optional)</label>
                  <select name="role_id" id="role_id" data-live-search="true" class="custom-select form-control border @error('role_id') is-invalid @enderror" id="">
                    <option value="">Nothing Selected</option>
                    @if (count($roles) > 0)
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    @endif
                      
                  </select>
                  @error('role_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Save Agent</button>
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

@section('extra_js')
<script>
//var input = document.querySelector("#phone");
  // intlTelInput(input, {
  //   // any initialisation options go here
  //   utilScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
  // });

  // var isPhoneValid = false;
  // var telInput = $("#phone");
  // var errorMsg = $("#error-msg");
  // var validMsg = $("#valid-msg");

  // // initialise plugin
  // telInput.intlTelInput({
  //   allowExtensions: true,
  //   formatOnDisplay: true,
  //   autoFormat: true,
  //   autoHideDialCode: true,
  //   autoPlaceholder: true,
  //   defaultCountry: "",
  //   ipinfoToken: "yolo",

  //   nationalMode: false,
  //   numberType: "MOBILE",
  //   //onlyCountries: ['us', 'gb', 'ch', 'ca'. 'do'],
  //   preferredCountries: ['sa', 'ae', 'qa', 'bh', 'do'],
  //   preventInvalidNumbers: true,
  //   separateDialCode: true,
  //   initialCountry: "GB",
  //   //GB
  //   geoIpLookup: function(callback) {
  //     $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
  //       var countryCode = (resp && resp.country) ? resp.country : "";

  //       callback(countryCode);
  //     });
  //   }
  // })
</script>
@endsection