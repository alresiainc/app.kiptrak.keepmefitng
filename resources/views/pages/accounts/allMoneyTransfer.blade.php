@extends('layouts.design')
@section('title')Accounts @endsection

@section('extra_css')
    <style>
        /* select2 arrow */
        select{
            -webkit-appearance: listbox !important
        }

        .btn-light {
            background-color: #fff !important;
            color: #000 !important;
        }
    
        div.filter-option-inner-inner{
            color: #000 !important;
        }
          
        /* select2 height proper */
        .select2-selection__rendered {
            line-height: 31px !important;
        }
        .select2-container .select2-selection--single {
            height: 35px !important;
        }
        .select2-selection__arrow {
            height: 34px !important;
        }
        /* select2 height proper */
    </style>
@endsection
@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Money Transfers</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Money Transfers</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  
  <section class="users-list-wrapper">
    <div class="users-list-filter px-1">
      
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
          <div class="card-body pt-3">
            
          <div class="clearfix mb-2">

            <div class="float-start text-start">
                <button data-bs-target="#addMoneyTransfer" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                  <i class="bi bi-plus"></i> <span>Add Money Transfer</span></button>
            </div>

            <div class="float-end text-end">
              <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                <i class="bi bi-upload"></i> <span>Import</span></button>
              <button class="btn btn-sm btn-secondary rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data"><i class="bi bi-download"></i> <span>Export</span></button>
              <button class="btn btn-sm btn-danger rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Delete All"><i class="bi bi-trash"></i> <span>Delete All</span></button>
            </div>
          </div>
          <hr>
          
          <div class="table table-responsive">
            <table id="products-table" class="table custom-table" style="width:100%">
              <thead>
                  <tr>
                      <th>Reference No.</th>
                      <th>From Account</th>
                      <th>To Account</th>
                      <th>Amount Transferred</th>
                      <th>Date</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                @if (count($transfers) > 0)
                    @foreach ($transfers as $transfer)
                    
                        <tr>
                    
                            <td>{{ $transfer->code }}</td>
                            <td>{{ $transfer->fromAccount->name }}</td>
                            <td>{{ $transfer->toAccount->name }}</td>
                            <td>{{ $transfer->amount }}</td>
                            <td>{{ $transfer->created_at }}</td>
                            
                            <td>
                                <div class="d-flex">
                                <a href="" class="btn btn-primary btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('editAccount', $transfer->unique_key) }}" class="btn btn-success btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="bi bi-pencil-square"></i></a>
                                <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                            
                    @endforeach
                @endif
                  
              </tbody>
          </table>
          </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</main><!-- End #main -->

<!-- Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Import Product CSV File</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>Download sample product CSV file <a href="#" class="btn btn-sm rounded-pill btn-primary"><i class="bi bi-download me-1"></i> Download</a></div>
        <div class="mt-3">
          <label for="formFileSm" class="form-label">Click to upload file</label>
          <input class="form-control form-control-sm" id="formFileSm" type="file">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary"><i class="bi bi-upload"></i> Upload</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal addMoneyTransfer -->
<div class="modal fade" id="addMoneyTransfer" tabindex="-1" aria-labelledby="addMoneyTransferLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Money Transfer</h1>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('addMoneyTransferPost') }}" method="POST">@csrf
                <div class="modal-body">
                    
                    <input type="hidden" name="code" value="{{ $code }}">
                    <div class="d-grid mb-3">
                        <label for="">From Account</label>
                        <select name="from_account" id="" data-live-search="true" class="custom-select form-control border border-dark">
                            <option value="">Nothing Selected</option>

                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                            
                        </select>
                    </div>

                    <div class="d-grid mb-3">
                        <label for="">To Account</label>
                        <select name="to_account" id="" data-live-search="true" class="custom-select form-control border border-dark">
                            <option value="">Nothing Selected</option>

                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                            
                        </select>
                    </div>

                    <div class="d-grid mb-3">
                        <label for="">Amount</label>
                        <input type="number" name="amount" class="form-control border border-dark" min="1">
                    </div>

                    <div class="d-grid mb-3">
                        <label for="">Note | optional</label>
                        <input type="text" name="note" class="form-control">
                    </div>

                                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary addMoneyTransferBtn">Submit Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection