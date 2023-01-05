@extends('layouts.design')
@section('title')FAQs @endsection
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
      <h1>Frequently Asked Questions</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active">FAQs</li>
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
          {{-- <div class="card">
            <div class="card-body"> --}}

                <div class="clearfix mb-2">
                    <div class="float-start text-start">
                        <button data-bs-target="#addFaqModal" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data">
                            <i class="bi bi-plus"></i> <span>Add FAQ</span></button>
                    </div>
        
                </div>
                <hr>
              
                <div class="accordion" id="accordionExample">
                    @if (count($faqs))
                        @foreach ($faqs as $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$faq->id}}" aria-expanded="true" aria-controls="collapseOne">
                                <span class="question me-3"><strong>{{ $faq->question }}</strong></span>
                                <span class="btn btn-sm btn-success me-3" onclick="editFaq({{ json_encode($faq) }})"><i class="bi bi-pencil-square"></i></span>
                                <a href="{{ route('deleteFaq', $faq->unique_key) }}"><span class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></span></a>
                              </button>
                            </h2>
                            <div id="collapse{{$faq->id}}" class="accordion-collapse collapse" aria-labelledby="heading{{$faq->id}}" data-bs-parent="#accordionExample">
                              <div class="accordion-body">
                                 {{ $faq->answer }}
                              </div>
                            </div>
                          </div>
                        @endforeach
                    @else
                        <div class="text-center">No contents at the moment</div>
                    @endif
                    

                    
                </div>
              
            {{-- </div>
          </div> --}}
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