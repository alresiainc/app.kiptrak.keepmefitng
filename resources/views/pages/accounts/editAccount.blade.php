@extends('layouts.design')
@section('title')Edit Account @endsection

@section('extra_css')
    
@endsection

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Edit Account</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Edit Account</li>
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
              
              <form class="row g-3 needs-validation" action="{{ route('editAccountPost', $account->unique_key ) }}" method="POST" enctype="multipart/form-data">@csrf
              <div class="col-md-12 mb-3">The field labels marked with * are required input fields.</div>
                
                <div class="col-md-6">
                    <label for="" class="form-label">Account No.</label>
                    <input type="text" name="account_no" class="form-control @error('account_no') is-invalid @enderror" value="{{ $account->account_no }}" readonly>
                    @error('account_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">Account Name</label>
                    <input type="text" name="account_name" class="form-control @error('account_name') is-invalid @enderror" value="{{ $account->name }}">
                    @error('account_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">Initial Balance</label>
                    <input type="text" name="initial_balance" class="form-control initial_balance @error('initial_balance') is-invalid @enderror"
                    value="{{ $account->initial_balance }}">
                    @error('initial_balance')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">Amount</label>
                    <input type="text" name="amount" class="form-control amount @error('amount') is-invalid @enderror" value="{{ $account->amount_added }}" >
                    @error('amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="" class="form-label">Note</label>
                    <textarea name="note" id="" name="note" class="form-control @error('note') is-invalid @enderror" cols="30" rows="10">{{ $account->note }}</textarea>
                    
                    @error('note')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Update Account</button>
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
<div class="modal fade" id="addCategory" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Category</h1>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="">@csrf
                <div class="modal-body">
                    
                    <div class="d-grid mb-2">
                        <label for="">Category Name</label>
                        <input type="text" name="name" class="form-control category_name" placeholder="">
                    </div>

                                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary addCategoryBtn">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection

@section('extra_js')

<script>
    $(document).on("input", ".amount", function() {
        this.value = this.value.replace(/\D/g,'');
    });
    $(document).on("input", ".initial_balance", function() {
        this.value = this.value.replace(/\D/g,'');
    });
</script>

@endsection
