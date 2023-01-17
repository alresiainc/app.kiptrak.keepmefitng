@extends('layouts.design')
@section('title')Agent @endsection

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
    <h1>Agent</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Agent</li>
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
              <a href="{{ route('addAgent') }}" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Add Agent">
                <i class="bi bi-plus"></i> <span>Add Agent</span></a>
            </div>

            <div class="float-end text-end">
              <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data">
                <i class="bi bi-upload"></i> <span>Import</span></button>
              <a href="{{ route('agentsExport') }}"><button class="btn btn-sm btn-secondary rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                <i class="bi bi-download"></i> <span>Export</span></button></a>
                <button class="btn btn-sm btn-info rounded-pill mail_all" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Mail All"><i class="bi bi-chat-left"></i> <span>Mail All</span></button>
            </div>
          </div>
          <hr>
          
          <div class="table table-responsive">
            <table id="products-table" class="table custom-table" style="width:100%">
              <thead>
                  <tr>
                      <th><input type="checkbox" id="users-master"></th>
                      <th>Photo</th>
                      <th>Name</th>
            
                      <th>City/Town</th>
                      <th>State | Country</th>
                      <th>Role</th>
                      <th>Date Joined</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                @if (count($agents) > 0)
                    @foreach ($agents as $agent)
                    <tr id="tr_{{$agent->id}}">
                      <td><input type="checkbox" class="sub_chk" data-id="{{ $agent->id }}" data-phone_number="{{ $agent->phone_1 }}"></td>
                      <td>
                        @if (isset($agent->profile_picture))
                            <a
                            href="{{ asset('/storage/agent/'.$agent->profile_picture) }}"
                            data-fancybox="gallery"
                            data-caption="{{ isset($agent->profile_picture) ? $agent->name : 'no caption' }}"
                            >   
                            <img src="{{ asset('/storage/agent/'.$agent->profile_picture) }}" width="50" class="img-thumbnail img-fluid"
                            alt="{{$agent->name}}"></a>
                        @else
                        <img src="{{ asset('/storage/agent/person.png') }}" width="50" class="img-thumbnail img-fluid"
                            alt="{{$agent->name}}">
                        @endif
                        
                      </td>
                      <td>{{ $agent->name }}</td>
                      <td>{{ isset($agent->city) ? $agent->city : 'N/A' }}</td>
                      
                      <td>{{ $agent->state }} | {{ $agent->country->name }}</td>

                      <td>
                        @if ($agent->hasAnyRole($agent->id))
                        {{ $agent->role($agent->id)->role->name }} <br>
                        <span class="badge badge-success" onclick="assignRoleModal('{{ $agent->id }}')" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Assign Role"
                          style="cursor: pointer;">
                          <i class="bi bi-plus"></i> <span>Change Role</span></span>
                        @else
                            No role <br>
                            <span class="badge badge-dark" onclick="assignRoleModal('{{ $agent->id }}')" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Assign Role"
                              style="cursor: pointer;">
                              <i class="bi bi-plus"></i> <span>Assign Role</span></span>
                        @endif
                      </td>
                      
                      <td>{{ $agent->created_at }}</td>
                      <td>
                        <div class="d-flex">
                          <a href="javascript:void(0);" onclick="whatsappModal({{ json_encode($agent) }})" class="btn btn-success btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Whatsapp">
                            <i class="bi bi-whatsapp"></i></a>
                          <a href="{{ route('singleAgent', $agent->unique_key) }}" class="btn btn-primary btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View"><i class="bi bi-eye"></i></a>
                          <a href="{{ route('editAgent', $agent->unique_key) }}" class="btn btn-success btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="bi bi-pencil-square"></i></a>
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
<div class="modal fade" id="assignRoleModal" tabindex="-1" aria-labelledby="assignRoleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Assign Role to Staff</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('assignRoleToUserPost') }}" method="POST">@csrf
        <div class="modal-body">
            
            <input type="hidden" id="user_id" class="user_id" name="user_id" value="">
            <div class="d-grid mb-3">
                <label for="">Select Role</label>
                <select name="role_id" id="changeAgentModalSelect" data-live-search="true" class="custom-select form-control border border-dark">
                    <option value="" selected>Nothing Selected</option>

                    @foreach ($roles as $role)
                      <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                    
                </select>
            </div>
        
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary addAgentBtn">Assign Role</button>
        </div>
    </form>
      
    </div>
  </div>
</div>

<!--Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Import Agents CSV File</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('agentsImport') }}" method="POST" enctype="multipart/form-data">@csrf
        <div class="modal-body">
          <div>Download sample Excel file <a href="{{ route('agentsSampleExport') }}" class="btn btn-sm rounded-pill btn-primary"><i class="bi bi-download me-1"></i> Download</a></div>

          @if (count($errors) > 0)
          <div class="row mt-3">
              <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
                    <h4> Error!</h4>
                    @foreach($errors->all() as $error)
                    {{ $error }} <br>
                    @endforeach      
                </div>
              </div>
          </div>
          @endif

          <div class="mt-3">
            <label for="formFileSm" class="form-label">Click to upload file</label>
            <input type="file" class="form-control form-control-sm" name="file" id="formFileSm">
          </div>
        </div>
      
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Upload</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!--sendMailModal -->
<div class="modal fade" id="sendMailModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="sendMailModalLabel">Send Mail to Agents</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="sendMailForm" action="{{ route('sendAgentMail') }}" method="POST">@csrf
        <div class="modal-body">
            <input type="hidden" name="agent_id" id="agent_id" value="">

            <div class="d-grid mb-3">
                <label for="">Topic</label>
                <input type="text" name="topic" class="form-control" placeholder="">
            </div>

            <div class="d-grid mb-2">
                <label for="">Message</label>
                <textarea name="message" id="" class="form-control" cols="30" rows="10"></textarea>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary sendMailBtn">Send Message</button>
        </div>
    </form>

    </div>
  </div>
</div>

<!--whatsappModal -->
<div class="modal fade" id="whatsappModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="whatsappModalLabel">Send Whatsapp to Agent: <span></span></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="sendMailForm" action="{{ route('sendAgentWhatsapp') }}" method="POST">@csrf
        <div class="modal-body">
            <input type="hidden" name="whatsapp_agent_id" id="whatsapp_agent_id" value="">

            <div class="d-grid mb-2">
              <label for="">Phone format: 23480xxxx</label>
              <input type="text" name="recepient_phone_number" id="recepient_phone_number" class="form-control">
            </div>

            <div class="d-grid mb-2">
                <label for="">Message</label>
                <textarea name="message" id="" class="form-control" cols="30" rows="10"></textarea>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary sendMailBtn">Send Message</button>
        </div>
    </form>

    </div>
  </div>
</div>

@endsection

@section('extra_js')

<script>
  function assignRoleModal($userId="") {
    $('#assignRoleModal').modal("show");
    $('.user_id').val($userId);
  }
</script>
  
  <?php if(count($errors) > 0) : ?>
    <script>
        $( document ).ready(function() {
            $('#importModal').modal('show');
        });
    </script>
  <?php endif ?>

  <script>
    $('#users-master').on('click', function(e) {
      if($(this).is(':checked',true))  
      {
        $(".sub_chk").prop('checked', true);  
      } else {  
        $(".sub_chk").prop('checked',false);  
      }  
    });

    //mail_all
    $('.mail_all').on('click', function(e) {

        var allVals = [];  
        $(".sub_chk:checked").each(function() {  
            allVals.push($(this).attr('data-id')); //['2', '1']
        });  

        //check if any is checked
        if(allVals.length <= 0)
        {  
          alert("Please select employee(s) to mail.");  
        }  else {  
            var check = confirm("Are you sure you want to mail this employee(s)?");  
            if(check == true){  

              //var join_selected_values = allVals.join(",");
              console.log(allVals) //2,1
              $('#sendMailModal').modal('show');
              $('#agent_id').val(allVals);
            
            }  
        }  
    }); 
  </script>

<script>
  function whatsappModal($agent="") {
    $('#whatsappModal').modal("show");
    $('#whatsapp_agent_id').val($agent.id);
    $part = $agent.phone_1.substring(0,1);
    if ($part == '0') {
      $whatsapp_phone_number = '234'+$agent.phone_1.substring(1);
      $('#recepient_phone_number').val($whatsapp_phone_number);
    } else {
      $('#recepient_phone_number').val($agent.phone_1);
    }
    $name = $agent.name;
    $('#whatsappModalLabel span').text($name);
    //console.log($whatsapp_phone_number)
    
  }
</script>

@endsection