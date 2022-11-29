@extends('layouts.design')
@section('title')Expense Category @endsection
@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Expense Category</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Expense Category</li>
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
                <button data-bs-target="#addCategory" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                  <i class="bi bi-plus"></i> <span>Add Expense Category</span></button>
                
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
                      <th>Code</th>
                      <th>Name</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                @if (count($categories) > 0)
                    @foreach ($categories as $category)
                    
                        <tr>
                    
                            <td>{{ $category->category_code }}</td>
                            <td>{{ $category->name }}</td>
                            
                            <td>
                                <div class="d-flex">
                                    <button class="btn btn-success btn-sm me-2" data-bs-target="#editCategory{{ $category->id }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="Edit"><i class="bi bi-pencil-square"></i></button>

                                    <!-- Modal editCategory -->
                                    <div class="modal fade" id="editCategory{{ $category->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="editCategory{{ $category->id }}Label">Edit Category</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="">@csrf
                                                    <div class="modal-body">
                                                        
                                                        <div class="d-grid mb-2">
                                                            <label for="">Category Name</label>
                                                            <input type="text" name="name" class="form-control category_name" value="{{ $category->name }}">
                                                        </div>

                                                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary addCategoryBtn">Add Category</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                    data-bs-placement="top" data-bs-title="Delete"><i class="bi bi-trash"></i></a>
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