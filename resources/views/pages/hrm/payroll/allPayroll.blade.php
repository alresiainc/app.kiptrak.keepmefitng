@extends('layouts.design')
@section('title')Payroll @endsection
@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Payroll List</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Payroll List</li>
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

              @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'create-payroll')) ))
              <div class="float-start text-start">
                  <a href="{{ route('addPayroll') }}"><button data-bs-target="#addMoneyTransfer" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                    <i class="bi bi-plus"></i> <span>Add Payroll</span></button></a>
              </div>
              @endif
  
              <div class="float-end text-end d-none">
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
                      <th>Date Paid</th>
                      <th>Employee</th>
            
                      <th>Amount</th>
                      <th>Method</th>

                      <th>Status</th>
                      <th>Created By</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                @if (count($payrolls) > 0)
                    @foreach ($payrolls as $payroll)
                    <tr>
                      
                      <td>{{ \Carbon\Carbon::parse($payroll->updated_at->addHour(1)) }}</td>
                      <td>{{ $payroll->employee->name }}</td>
                      
                      <td>{{ $payroll->amount }} </td>

                      <td>
                        @if ($payroll->paying_method=='cash')
                            Cash
                        @elseif($payroll->paying_method=='bank_transfer')
                            Bank Transfer
                        @elseif($payroll->paying_method=='cheque')
                            Cheque
                        @endif
                      </td>
                      
                      <td>
                        <span class="badge badge-success">Paid</span>
                      </td>
                      <td>
                        Ugo Sunday
                      </td>

                      <td>
                        <div class="d-flex">
                          <a href="{{ route('editPayroll', $payroll->unique_key) }}" class="btn btn-success btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="bi bi-pencil-square"></i></a>
                          <a href="{{ route('deletePayroll', $payroll->unique_key) }}" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete"><i class="bi bi-trash"></i></a>
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

@endsection