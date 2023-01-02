@extends('layouts.design')
@section('title')Exit Attendance @endsection

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
      <h1>Exit Attendance</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Exit Attendance</li>
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

    <section class="section dashboard mb-3">
      <div class="row">
        <div class="col-md-12">
          <a href="{{ route('allAttendance') }}" class="badge badge-dark">Attendance List</a>
        </div>
      </div>
    </section>

    <section>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              
              <form class="row g-3 needs-validation" action="{{ route('editAttendancePost', $attendance->unique_key) }}" method="POST" enctype="multipart/form-data">@csrf

                <div class="col-md-12">
                  <label for="" class="form-label">Staff</label>
                  <input type="text" name="employee" class="form-control @error('employee') is-invalid @enderror" value="{{ $attendance->employee->name }}" readonly>
                  @error('employee')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-12 d-none">
                  <label for="" class="form-label">Check-In</label>
                  <input type="time" name="check_in" class="form-control @error('check_in') is-invalid @enderror" id="" >
                  @error('check_in')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-12">
                    <label for="" class="form-label">Check-Out</label>
                    <input type="time" name="check_out" class="form-control @error('check_out') is-invalid @enderror" id="" >
                    @error('check_out')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-12">
                  <label for="" class="form-label">Note (Optional)</label>
                  <textarea name="" id="" cols="30" rows="3" class="form-control"></textarea>
                  @error('phone_1')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Exit Attendance</button>
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