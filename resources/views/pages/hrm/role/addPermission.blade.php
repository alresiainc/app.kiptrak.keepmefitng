@extends('layouts.design')
@section('title')Create Permissions @endsection

@section('extra_css')
  <style>
      /* select2 arrow */
      select{
          -webkit-appearance: listbox !important
      }

      /* custom-select border & inline edit */
      .btn-light {
          background-color: #fff !important;
          color: #000 !important;
      }
      div.filter-option-inner-inner{
          color: #000 !important;
      }
      /* custom-select border & inline edit */

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
      <h1>Create Permissions</h1>
      <div class="float-end text-end"><a href="{{ route('addRole') }}"><button class="btn btn-sm btn-dark rounded-pill">
        <i class="bi bi-plus"></i> <span>Create Role</span></button></a>
      </div>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('allRole') }}">All Roles</a></li>
          <li class="breadcrumb-item active">Create Permissions</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    @if(Session::has('success'))
    <div class="alert alert-success mb-3 text-center">
        {{Session::get('success')}}
    </div>
    @endif

    @if(Session::has('error'))
    <div class="alert alert-danger mb-3 text-center">
        {{Session::get('error')}}
    </div>
    @endif

    <section>
      <div class="row">
        <div class="col-md-12">
          <div class="card">

            <div class="card-body">
              
              <form id="createForm" class="row g-3" action="{{ route('addPermissionPost') }}" method="POST">@csrf
                
                <!---main menu, parent permission--->
                <div class="col-md-12">
                  <label for="" class="form-label">Select Main Menu *</label>

                  <div class="d-flex @error('main_menu') is-invalid @enderror">

                    <select name="main_menu" id="addPermissionSelect" class="select2 form-control @error('main_menu') is-invalid @enderror">
                      <option value="">Nothing Selected</option>
                      
                      @foreach ($mainPerms as $perm)
                          <option value="{{ $perm->id }}">
                              {{ $perm->name }}
                          </option>
                      @endforeach
                      
                   </select>
                      
                   <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addPermission">
                    <i class="bi bi-plus"></i></button>
                  </div>
                  @error('main_menu')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>

                <div class="product-clone-section wrapper">
                  <div class="col-md-12 mt-1 element">
                    <label for="" class="form-label">New Permission Names *</label>
                    <input type="text" name="permission_names[]" class="form-control @error('permission_names') is-invalid @enderror" placeholder="" value="">
                    @error('permission_names')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  </div>

                  <!--append elements to-->
                  <div class="results"></div>

                  <div class="buttons d-flex justify-content-between">
                    <button type="button" class="clone btn btn-success btn-sm rounded-pill"><i class="bi bi-plus"></i></button>
                    <button type="button" class="remove btn btn-danger btn-sm rounded-pill"><i class="bi bi-dash"></i></button>
                  </div>
                </div>
                
                
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Add Permissions</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- End Multi Columns Form -->
              
            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

<!-- ModalCategory -->
<div class="modal fade" id="addPermission" tabindex="-1" aria-labelledby="addPermissionLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Main Menu</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="addPermissionForm" action="">@csrf
              <div class="modal-body">
                  
                  <div class="d-grid mb-2">
                      <label for="">Menu Name</label>
                      <input type="text" name="menu_name" class="form-control menu_name" placeholder="">
                  </div>
                                  
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary addCategoryBtn">Add Main Menu</button>
              </div>
          </form>
      </div>
  </div>
</div>

@endsection

@section('extra_js')

<script>
//clone
$('.wrapper').on('click', '.remove', function() {
    $('.remove').closest('.wrapper').find('.element').not(':first').last().remove();
});
$('.wrapper').on('click', '.clone', function() {
    $('.clone').closest('.wrapper').find('.element').first().clone().appendTo('.results');
});
</script>

<script>
//addPermission Modal
$('#addPermissionForm').submit(function(e){
    e.preventDefault();
    var menu_name = $("form .menu_name").val();
    // alert(category_name)
    if (menu_name != '') {
        $('#addPermission').modal('hide');

        $.ajax({
            type:'get',
            url:'/ajax-create-permission-main-menu',
            // data:{ category_name:category_name },
            data: $(this).serialize(),
            success:function(resp){

                if (resp.data.data_error) {
                    alert(resp.data.data_error)
                }
                
                if (resp.data.permission) {
                    
                    var datas = {
                        id: resp.data.permission.id,
                        text: resp.data.permission.name
                    };
                    var newOption = new Option(datas.text, datas.id, false, false);
                    $('#addPermissionSelect').prepend(newOption).trigger('change');
                    
                    alert('Main Menu Added Successfully')
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