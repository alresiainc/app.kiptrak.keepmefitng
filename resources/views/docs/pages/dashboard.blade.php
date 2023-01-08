@extends('layouts.design')
@section('title')Dashboard Documentation @endsection
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
      <h1>Dashboard Documentation </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active">Dashboard Documentation </li>
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

                <div class="clearfix mb-2 d-none">
                    <div class="float-start text-start">
                        <button data-bs-target="#addFaqModal" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data">
                            <i class="bi bi-plus"></i> <span>Dashboard Documentation </span></button>
                    </div>
        
                </div>
                <hr>
                
                <!--image-files-->
                <div class="d-flex justify-content-between">
                  <div><img src="{{ asset('/storage/docs/dashboard.png') }}" width="450" class="img-thumbnail img-fluid"
                    alt="Dashboard"></div>
                  
                  <div><img src="{{ asset('/storage/docs/dashboard-1.png') }}" width="450" class="img-thumbnail img-fluid"
                    alt="Dashboard-1"></div>
                </div>
              
            </div>

          </div> 
        </div>
      </div>
    </section>

    <section>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">

                <div class="clearfix mb-2">
                    <div class="float-start text-start">
                        <button class="btn btn-sm btn-dark rounded-pill" >
                             <span>Dashbaord Details </span></button>
                    </div>
        
                </div>
                <hr>

                <div class="d-block">
                  <div class="module ms-3"><h4>Purchases</h4></div>
                  <div class="content">
                    <p>Shows total amount of <strong>purchased</strong> products, in naira.</p>
                  </div>
                </div>

                <div class="d-block">
                  <div class="module ms-3"><h4>Sales</h4></div>
                  <div class="content">
                    <p>Shows total amount of <strong>sold</strong> products, in naira</p>
                  </div>
                </div>

                <div class="d-block">
                  <div class="module ms-3"><h4>Expenses</h4></div>
                  <div class="content">
                    <p>Shows total amount of <strong>expenses</strong>, apart from the purchases, in naira</p>
                  </div>
                </div>

                <div class="d-block">
                  <div class="module ms-3"><h4>Profit</h4></div>
                  <div class="content">
                    <p><strong>Sales - (Purchases + Expenses) = Profit, in naira</strong></p>
                  </div>
                </div>

                <div class="d-block">
                  <div class="module ms-3"><h4>Customers</h4></div>
                  <div class="content">
                    <p>Total number of <strong>customers</strong> on this platform.</p>
                  </div>
                </div>
                
                <div class="d-block">
                  <div class="module ms-3"><h4>Suppliers</h4></div>
                  <div class="content">
                    <p>Total number of <strong>suppliers</strong> on this platform. Suppliers are companies or individuals you purchase from.</p>
                  </div>
                </div>

                <div class="d-block">
                  <div class="module ms-3"><h4>Purchases</h4></div>
                  <div class="content">
                    <p>Total count of <strong>purchases</strong> on this platform.</p>
                  </div>
                </div>

                <div class="d-block">
                  <div class="module ms-3"><h4>Sales</h4></div>
                  <div class="content">
                    <p>Total count of <strong>saless</strong> on this platform.</p>
                  </div>
                </div>
                
              
            </div>

          </div> 
        </div>
      </div>
    </section>

</main><!-- End #main -->

<!-- Modal -->
<div class="modal fade" id="addFaqModal" tabindex="-1" aria-labelledby="addFaqModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal_title">Add FAQ</h1>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('faqPost') }}" method="POST">@csrf
                <div class="modal-body">
                    <input type="hidden" name="faq_id" class="faq_id" id="faq_id" value="">
                    <div class="d-grid mb-2">
                        <label for="">Question</label>
                        <input type="text" name="question" id="question" class="form-control @error('question') is-invalid @enderror" value="{{ old('question') }}">
                        @error('question')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="d-grid mb-2">
                        <label for="">Answer</label>
                        <textarea name="answer" id="answer" class="form-control @error('answer') is-invalid @enderror" id="answer" cols="30" rows="10">{{ old('answer') }}</textarea>
                        @error('answer')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" id="modal_btn" class="btn btn-primary">Add FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('extra_js')
  
  <?php if(count($errors) > 0) : ?>
    <script>
        $( document ).ready(function() {
            $('#addFaqModal').modal('show');
            $('#faq_id').val('');
            $('#modal_title').text('Add FAQ');
            $('#modal_btn').text('Add FAQ');
        });
    </script>
  <?php endif ?>

  <script>
    function editFaq($faq="") {
      $('#addFaqModal').modal("show");
      $('#faq_id').val($faq.id);
      $('#modal_title').text('Edit FAQ');
      $('#modal_btn').text('Update FAQ');
      $('#question').val($faq.question);
      $('#answer').val($faq.answer);
      //alert($faq.id)
    
    }
  </script>

@endsection