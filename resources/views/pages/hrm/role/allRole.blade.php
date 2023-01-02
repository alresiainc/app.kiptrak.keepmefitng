@extends('layouts.design')
@section('title')Roles @endsection
@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Roles</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Roles</li>
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
                    <a href="{{ route('addRole') }}"><button data-bs-target="#addMoneyTransfer" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                      <i class="bi bi-plus"></i> <span>Create Role</span></button></a>
                </div>
    
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
                      <th>Name</th>
                      <th>Permissions</th>
                      <th>Staff Assigned</th>
                      <th>Created By</th>
                      <th>Date Created</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                @if (count($roles) > 0)
                    @foreach ($roles as $role)
                    <tr>
                      
                      <td>{{ $role->name }}</td>
                      <td>
                        @if ($role->permissions->count() > 0)
                        <span class="badge badge-dark" onclick="viewRolePerms('{{ $role->name }}', {{ json_encode($role->permissions) }})" data-bs-target="#viewRolePerms" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="View Permissions"
                          style="cursor: pointer;">{{ $role->permissions->count() }}</span>
                        @else
                          0
                        @endif
                      </td>
                      <td>
                        @if ($role->users->count() > 0)
                            <span class="badge badge-dark" onclick="viewRoleUsers('{{ $role->name }}', {{ json_encode($role->users) }})" data-bs-target="#viewRoleUsers" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="View Users"
                              style="cursor: pointer;">{{ $role->users->count() }}</span>
                        @else
                          0
                        @endif
                      </td>
                      
                      <td>Ugo Sunday</td>
                      
                      <td>{{ $role->created_at }}</td>
                      <td>
                        <div class="d-flex">
                          <a href="{{ route('editRole', $role->unique_key) }}" class="btn btn-success btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="bi bi-pencil-square"></i></a>
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
<div class="modal fade" id="viewRoleUsers" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="role_name">Human Resource Manager Employees</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center roleUsers">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal rolePerms -->
<div class="modal fade" id="viewRolePerms" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="role_name_perm"></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center rolePerms">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('extra_js')
  <script>
    function viewRoleUsers($role_name="", $role_users="") {
      $('#viewRoleUsers').modal("show");
      $('#role_name').text($role_name+'s');
      $('.roleUsers').html('');
      //alert($role_users.length)
  
    $role_users.forEach(user => {
        $('.roleUsers').append("<div class='mt-3 bg-dark text-white'>"+user.name+"</div>")
      });
    }
  </script>

<script>
  function viewRolePerms($role_name="", $role_perms="") {
    $('#viewRolePerms').modal("show");
    $('#role_name_perm').text($role_name+' Permissions');
    $('.rolePerms').html('');
    //alert($role_perms.length)
  
  $role_perms.forEach(perm => {
      $('.rolePerms').append("<div class='mt-3 mr-3 badge badge-dark'>"+perm.name+"</div>")
    });
  }
</script>
@endsection