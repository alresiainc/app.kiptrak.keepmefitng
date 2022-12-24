@extends('layouts.design')
@section('title')Sent Messages @endsection
@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Sent Messages</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Sent Messages</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="users-list-wrapper">
    <div class="users-list-filter px-1">
    </div>
  </section>

  <section>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body pt-3">
            
          <div class="clearfix mb-2">
            <div class="float-start text-start">
                <a href="{{ route('composeEmailMessage') }}"><button class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Compose Message">
                  <i class="bi bi-plus"></i> <span>Compose Message</span></button></a>
            </div>

            <div class="float-end text-end">
              <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
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
                      <th>Sender Name | Topic</th>
                      <th>Recipients</th>
                      <th>Message</th>
                      <th>Status</th>
                      <th>Date Sent</th>
                  </tr>
              </thead>
              <tbody>
                @if (count($messages) > 0)
                    @foreach ($messages as $message)
                    
                        <tr>
                    
                            <td>{{ $message->topic }}</td>

                            @php
                                $users = $message->users($message->recipients);
                                $customers = $message->customers($message->recipients)
                            @endphp
                            <td>
                              @if (isset($message->to) && $message->to=='users')
                                @foreach ($users as $user)
                                <span class="badge badge-dark mr-1">{{ $user->email }}</span>
                                @endforeach
                              @endif

                              @if (isset($message->to) && $message->to=='customers')
                                @foreach ($customers as $customers)
                                <span class="badge badge-dark mr-1">{{ $customers->email }}</span>
                                @endforeach
                              @endif
                              
                            </td>
                            <td>{{ $message->message }}</td>
                            <td>{!! $message->message_status == 'sent' ? '<span class="badge badge-success">Sent</span>' : '<span class="badge badge-dark">Draft</span>' !!}</td>
                            <td>{{ $message->created_at }}</td>
                            
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

<!-- Modal addCategory -->
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
    $('.addCategoryBtn').click(function(e){
        e.preventDefault();
        var category_name = $("form .category_name").val();
        // alert(category_name)
        if (category_name != '') {
            $('#addCategory').modal('hide');

            $.ajax({
                type:'get',
                url:'/ajax-create-expense-category',
                data:{ category_name:category_name },
                success:function(resp){
                    
                    if (resp.status) {
                        
                        alert('Category Added Successfully')
                        window.location.reload()
                        // return false;
                    } 
                        
                },error:function(){
                    alert("Error");
                }
            });
        
        } else {
            alert('Error: Something went wrong')
        }
    });
</script>
@endsection