@extends('layouts.design')
@section('title')Add Payroll @endsection

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
      <h1>Add Payroll</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('allPayroll') }}">Payroll List</a></li>
          <li class="breadcrumb-item active">Add Payroll</li>
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
              
              <form class="row g-3 needs-validation" action="{{ route('editPayrollPost', $payroll->unique_key) }}" method="POST" enctype="multipart/form-data">@csrf

                <div class="col-md-12">
                    <label for="" class="form-label">Select Employee</label>
                    <select name="employee" data-live-search="true" class="custom-select form-control border @error('employee') is-invalid @enderror">
                        
                      <option value="{{ $payroll->employee->id }}">{{ $payroll->employee->name }} {{ isset($payroll->employee->current_salary) ? ' | Salary: '.$payroll->employee->current_salary : '' }}</option>
                      @foreach ($staffs as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->name }} {{ isset($staff->current_salary) ? ' | Salary: '.$staff->current_salary : '' }}</option>
                      @endforeach
                      
                    </select>
                    @error('employee')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="col-md-6">
                  <label for="" class="form-label">Amount ({{ $generalSetting->country->symbol }})</label>
                  <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" id="" min="1" value="{{ $payroll->amount }}">
                  @error('amount')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="" class="form-label">Bonus ({{ $generalSetting->country->symbol }}) | Optional</label>
                  <input type="number" name="bonus" class="form-control @error('bonus') is-invalid @enderror" id="" min="1"
                  value="{{ isset($payroll->bonus) ? $payroll->bonus : '' }}">
                  @error('bonus')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-12">
                    <label for="" class="form-label">Select Method</label>
                    <select name="paying_method" data-live-search="true" class="custom-select form-control border @error('paying_method') is-invalid @enderror">
                      <option value="{{ $payroll->paying_method }}">
                        @if ($payroll->paying_method=='cash')Cash
                        @elseif($payroll->paying_method=='bank_transfer')Bank Transfer
                        @elseif($payroll->paying_method=='cheque')Cheque
                        @endif
                      </option>
                      <option value="cash">Cash</option>
                      <option value="bank_transfer">Bank Transfer</option>
                      <option value="cheque">Cheque</option>
                      
                    </select>
                    @error('paying_method')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-12">
                  <label for="" class="form-label">Note (Optional)</label>
                  <textarea name="note" id="" cols="30" rows="3" class="form-control">{{ isset($payroll->note) ? $payroll->note : '' }}</textarea>
                  @error('note')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Update Payroll</button>
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