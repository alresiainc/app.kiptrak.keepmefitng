@extends('layouts.design')
@section('title')Compose Message @endsection
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
      <h1>Compose Message</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Compose Message</li>
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
              
              <form id="messageForm" class="row g-3 needs-validation" action="{{ route('composeSmsMessagePost') }}" method="POST"
              enctype="multipart/form-data">@csrf
              <div class="col-md-12 mb-3">The field labels marked with * are required input fields.</div>

              <input type="hidden" name="draftinput" id="draftinput" value="">

                <div class="col-md-12 mb-3">
                    <label for="" class="form-label">From: [Your Sender Name or Topic]</label>
                    <input type="text" name="topic" class="form-control @error('topic') is-invalid @enderror"
                    value="KIPTRAK">
                    @error('topic')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="" class="form-label">To [Recipients] (up to 10,000): <br>
                        Type or Paste phone numbers here (080...,080... or 23480...) JUST COMMA, NO SPACES!.</label>
                    <textarea name="recipients" id="" cols="30" rows="5" class="form-control @error('recipients') is-invalid @enderror"></textarea>
                    @error('recipients')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="" class="form-label d-flex justify-content-between">
                        <span>Message</span>
                        <span>Word Count (<span id="count">0</span> / 160) used</span>
                    </label>
                    <textarea name="message" id="message" cols="30" rows="10" class="form-control @error('message') is-invalid @enderror" maxlength="160"></textarea>
                    @error('message')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Send Message</button>
                  <button type="submit" id="saveDraftBtn" class="btn btn-info">Save As Draft</button>
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

<script>
    var max = 160;
    $("#message").keyup(function(e){
    $("#count").text(($(this).val().length));
    });
</script>

<script>
    $("#saveDraftBtn").click(function (e) {
        e.preventDefault();
        $("#draftinput").val('as_draft')

        $("#messageForm").submit();
    })
    draftinput
</script>

@endsection