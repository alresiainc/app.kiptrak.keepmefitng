@extends('layouts.design')
@section('title')Expenses @endsection
@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Expenses</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Expenses</li>
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
            <div class="text-start"><a href="{{ route('addExpense') }}" class="btn btn-sm btn-secondary rounded-pill"><i class="bi bi-plus"></i>Add Expense</a></div>
            <div class="float-end text-end">
              <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill d-none" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                <i class="bi bi-upload"></i> <span>Import</span></button>
              <button class="btn btn-sm btn-secondary rounded-pill d-none" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data"><i class="bi bi-download"></i> <span>Export</span></button>
              <button class="btn btn-sm btn-info rounded-pill delete_all" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Delete All" data-url="{{ url('/delete-all-expenses') }}"><i class="bi bi-trash"></i> <span>Delete All</span></button>
            </div>
          </div>
          <hr>
          
          <div class="table table-responsive">
            <table id="products-table" class="table custom-table" style="width:100%">
              <thead>
                  <tr>
                      <th><input type="checkbox" id="users-master"></th>
                      <th>Expense Code</th>
                      <th>Warehouse</th>
                      <th>Category</th>
                      <th>Amount</th>
                      {{-- <th>Note</th> --}}
                      <th>Date</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                @if (count($expenses) > 0)
                    @foreach ($expenses as $expense)
                    
                        <tr>
                            <td><input type="checkbox" class="sub_chk" data-id="{{ $expense->id }}" ></td>
                            <td>{{ $expense->expense_code }}</td>
                            <td>{{ isset($expense->warehouse_id) ? $expense->warehouse->name : '' }}</td>
                            <td>{{ isset($expense->expense_category_id) ? $expense->category->name : '' }}</td>
                            <td>{{ $expense->amount }}</td>
                            <td>{{ $expense->created_at }}</td>
        
                            <td>
                                <div class="d-flex">
                                <a href="{{ route('singleExpense', $expense->unique_key) }}" class="btn btn-primary btn-sm me-2 d-none" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('editExpense', $expense->unique_key) }}" class="btn btn-success btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="bi bi-pencil-square"></i></a>
                                <a href="{{ route('deleteExpense', $expense->unique_key) }}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete"><i class="bi bi-trash"></i></a>
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

@section('extra_js')

<script>
  $('#users-master').on('click', function(e) {
    if($(this).is(':checked',true))  
    {
      $(".sub_chk").prop('checked', true);  
    } else {  
      $(".sub_chk").prop('checked',false);  
    }  
  });
  //delete_all
  $('.delete_all').on('click', function(e) {

  var allVals = [];  
  $(".sub_chk:checked").each(function() {  
      allVals.push($(this).attr('data-id'));
  });  

  //check if any is checked
  if(allVals.length <=0)  
  {  
    alert("Please select row(s) to delete.");  
  }  else {  
    var check = confirm("Are you sure you want to delete this row?");  
    if(check == true){  

      var join_selected_values = allVals.join(",");

      $.ajax({
          url: $(this).data('url'),
          type: 'GET',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          data: 'ids='+join_selected_values,
          success: function (data) {
            if (data['success']) {
                $(".sub_chk:checked").each(function() {  
                    $(this).parents("tr").remove();
                });
                alert(data['success']);
            } else if (data['error']) {
                alert(data['error']);
            } else {
                alert('Whoops Something went wrong!!');
            }
          },
          error: function (data) {
              alert(data.responseText);
          }
      });

      $.each(allVals, function( index, value ) {
          $('table tr').filter("[data-row-id='" + value + "']").remove();
      });
    }  
  }  
  });
</script>
    
@endsection