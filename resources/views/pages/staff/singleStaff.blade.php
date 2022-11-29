@extends('layouts.design')
@section('title')View Staff @endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Staff Information</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Staff Information<li>
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
                    @if (isset($staff->profile_picture))
                        <a
                        href="{{ asset('/storage/staff/'.$staff->profile_picture) }}"
                        data-caption="{{ isset($staff->name) ? $staff->name : 'no caption' }}"
                        data-fancybox
                        > 
                        <img src="{{ asset('/storage/staff/'.$staff->profile_picture) }}" width="100" class="img-thumbnail img-fluid"
                        alt="Photo"></a>
                    @else
                    <img src="{{ asset('/storage/staff/person.png') }}" width="100" class="img-thumbnail img-fluid"
                    alt="Photo"> 
                    @endif
                    
                  </div>
                  <div class="d-grid ms-lg-3">
                    <div class="display-6">{{ $staff->name }}</div>
                    <h5>{{ $staff->state }} | {{ $staff->country->name }}</h5>

                    @if ($staff->status == 'true')
                      <div class="d-flex justify-content-start">
                        <small class="text-success me-2">Active</small>
                      </div>
                    @else
                      <small class="text-danger">Inactive</small>
                    @endif
                    
                  </div>
                </div>
                <div class="float-lg-end">
                  <button class="btn btn-sm btn-success"><i class="bi bi-pencil-square"></i></button>
                </div>
              </div>

              <hr>

              <div class="row g-3">
                <div class="col-lg-3">
                  <label for="">Phone Numbers</label>
                  <div class="lead">{{ $staff->phone_1 }}
                    @if(isset($staff->phone_2))
                        <br> {{ $staff->phone_2 }}
                    @endif
                </div>
                </div>

                
                <div class="col-lg-3">
                  <label for="">City/Town</label>
                  <div class="lead">@if (isset($staff->city)){{ $staff->city }} @else N/A @endif</div>
                </div>
               
                <div class="col-lg-3">
                  <label for="">Address</label>
                  <div class="lead">@if (isset($staff->address)){{ $staff->address }} @else N/A @endif</div>
                </div>
                
                <div class="col-lg-3">
                  <label for="">Date Joined</label>
                  <div class="lead">{{ $staff->created_at }}</div>
                </div>
                
              </div>

              <!--features-->
              

            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

@endsection