@extends('layouts.design')
@section('title')General Setting @endsection
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
      <h1>General Setting</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">General Setting</li>
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
              
              <form class="row g-3 needs-validation" action="{{ route('generalSettingPost') }}" method="POST"
              enctype="multipart/form-data">@csrf
              <div class="col-md-12 mb-3">The field labels marked with * are required input fields.</div>

              <input type="hidden" name="generalSetting" value="{{ $generalSetting != '' ? $generalSetting->id : '' }}">

              @if ($generalSetting != '')
                <div class="gallery-uploader-wrap">
                    <label for="" class="form-label">Logo</label>
                    <br>
                    <label class="uploader-img">
                    <img src="{{ asset('/storage/generalSetting/'.$generalSetting->site_logo) }}" width="100" class="img-fluid" alt="Upload Photo"> 
                    </label>
                </div>  
              @endif
                
            
                <div class="col-md-12">
                    <label for="" class="form-label">Site Title</label>
                    <input type="text" name="site_title" class="form-control @error('site_title') is-invalid @enderror"
                    value="{{ $generalSetting != '' ? $generalSetting->site_title : 'KIPTRAK' }}">
                    @error('site_title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="" class="form-label">Site Description</label>
                    <textarea name="site_description" id="" cols="30" rows="5" class="form-control">{{ $generalSetting != '' ? $generalSetting->site_description : 'KIPTRAK CRM APPLICATION' }}</textarea>
                    @error('site_description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                  <label for="" class="form-label">Site Logo</label>
                  <input type="file" name="site_logo" class="form-control @error('site_logo') is-invalid @enderror">
                  @error('site_logo')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="" class="form-label">Site Currency<span class="text-danger fw-bolder">*</span></label>
                    <select name="currency" class="custom-select form-control border @error('currency') is-invalid @enderror" id="">
                      <option value="{{ $generalSetting != '' ? $generalSetting->country->id : '1' }}" selected>
                        {{ $generalSetting != '' ? $generalSetting->country->symbol : 'Nigerian | ₦' }}</option>
                      @foreach ($countries as $country)
                          <option value="{{ $country->id }}">
                            {{ $country->name }} | {{ $country->symbol }}
                          </option>
                      @endforeach
                    </select>
                    @error('currency')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                  <label for="" class="form-label">Site Official Receive Notification Email</label>
                  <input type="email" name="official_notification_email" class="form-control @error('official_notification_email') is-invalid @enderror"
                  value="{{ $generalSetting != '' ? $generalSetting->official_notification_email : 'ralphsunny114@gmail.com' }}">
                  @error('official_notification_email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">Attendance Time</label>
                    <input type="time" name="attendance_time" class="form-control @error('attendance_time') is-invalid @enderror"
                    value="{{ $generalSetting != '' ? $generalSetting->attendance_time : '08:00' }}">
                    @error('attendance_time')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Save Settings</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- End Multi Columns Form -->
              
            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

<!-- Modal -->
<div class="modal fade" id="addCustomer" tabindex="-1" aria-labelledby="addCustomerLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add
                    Customer</h1>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('addCustomerPost') }}" method="POST" enctype="multipart/form-data">@csrf
                <div class="modal-body">
                    
                    <div class="d-grid mb-2">
                        <label for="">First Name</label>
                        <input type="text" name="firstname" class="form-control" placeholder="">
                    </div>

                    <div class="d-grid mb-2">
                        <label for="">Last Name</label>
                        <input type="text" name="lastname" class="form-control" placeholder="">
                    </div>
                    <div class="d-grid mb-2">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="">
                    </div>

                    <div class="d-grid mb-2">
                        <label for="">Phone Number</label>
                        <input type="text" name="phone_number" class="form-control"
                            placeholder="">
                    </div>

                    <div class="d-grid mb-2">
                        <label for="">Whatsapp Number</label>
                        <input type="text" name="whatsapp_phone_number" class="form-control"
                            placeholder="">
                    </div>

                    <div class="d-grid mb-2">
                        <label for="">Address</label>
                        <input type="text" name="delivery_address" class="form-control" placeholder="">
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('extra_js')
@endsection