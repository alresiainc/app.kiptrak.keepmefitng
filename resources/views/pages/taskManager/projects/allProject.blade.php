@extends('layouts.design')
@section('title')Project List @endsection

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

@if(Session::has('success'))
    <div class="alert alert-success mb-3 text-center">
        {{Session::get('success')}}
    </div>
@endif

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Project List</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('overview') }}">Overview</a></li>
        <li class="breadcrumb-item active">Project List</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

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
                  <a href="{{ route('addProject') }}" class="btn btn-sm btn-dark rounded-pill">
                    <i class="bi bi-plus"></i> <span>Add Project</span></a>
                  
              </div>
  
              <div class="float-end text-end">
                  <a href="{{ route('addTask') }}">
                    <button class="btn btn-sm btn-secondary rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Create Task">
                    <i class="bi bi-plus"></i> <span>Create Task</span></button>
                  </a>

                  <a href="{{ route('allTask') }}">
                    <button class="btn btn-sm btn-secondary rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Task List">
                    <i class="bi bi-list-task"></i> <span>Task List</span></button>
                  </a>
                
              </div>
            </div>
          <hr>
          
          <div class="table table-responsive">
            <table id="projects-table" class="table custom-table" style="width:100%">
              <thead>
                  <tr>
                      <th>logo</th>
                      <th>Name</th>
                      <th>Tasks</th>
                      <th>Start Date</th>
                      <th>End Date</th>
                      <th>Status</th>
                      <th>Project Leader</th>
                      <th>Priority</th>
                      <th>Created By</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                @if (count($projects) > 0)
                    @foreach ($projects as $project)
                    <tr>
                      <td>
                        @if (isset($project->logo))
                        <a
                        href="{{ asset('/storage/projects/'.$project->logo) }}"
                        data-fancybox="gallery"
                        data-caption="{{ isset($project->name) ? $project->name : 'no caption' }}"
                        >   
                        <img src="{{ asset('/storage/projects/'.$project->logo) }}" style="width: 50px; height: 50px;" class="rounded-circle img-thumbnail img-fluid"
                        alt="{{$project->name}}"></a>
                        @else
                        <img src="{{ asset('/storage/projects/default.png') }}" width="50" class="rounded-circle img-thumbnail img-fluid"
                        alt="{{$project->name}}"></a> 
                        @endif
                        
                      </td>

                      <td>{{ $project->name }}</td>

                      <td>
                        @if (count($project->tasks) > 0)
                            <a href="" class="badge badge-dark">{{ count($project->tasks) }}</a>
                        @else
                            None
                        @endif
                        <div class="mt-2"><button class="badge badge-dark border"
                            onclick="addTaskModal({{ json_encode($project) }}, '{{ \Carbon\Carbon::parse($project->end_date)->format('D, jS M Y') }}',
                            '{{ \Carbon\Carbon::parse($project->start_date)->format('D, jS M Y') }}')"
                            data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Add Task" style="cursor: pointer">
                            <i class="bi bi-plus"></i>Add Task</button>
                        </div>
                        
                      </td>

                      <td>{{ \Carbon\Carbon::parse($project->start_date)->format('D, jS M Y') }}</td> <!----Wed, 25th Jan 2023---->
                      <td>{{ \Carbon\Carbon::parse($project->end_date)->format('D, jS M Y') }}</td>

                      <td>
                        @if ($project->status=='pending')
                            <span class="badge badge-dark">{{ ucFirst($project->status) }}</span>
                        @elseif($project->status=='in_progress')
                            <span class="badge badge-primary">{{ ucFirst($project->status) }}</span>
                        @elseif($project->status=='ready')
                            <span class="badge badge-warning">{{ ucFirst($project->status) }}</span>
                        @elseif($project->status=='done')
                            <span class="badge badge-success">{{ ucFirst($project->status) }}</span>
                        @elseif($project->status=='backlog')
                            <span class="badge badge-danger">{{ ucFirst($project->status) }}</span>
                        @endif
                      </td>
                      
                      <td>{{ $project->assignedTo->name }}</td>
                      <td>
                        
                        @if ($project->priority=='high') 
                            <span class="badge badge-danger">{{ ucFirst($project->priority) }}</span>
                        @elseif($project->priority=='medium')
                            <span class="badge badge-warning">{{ ucFirst($project->priority) }}</span>
                        @elseif($project->priority=='low')
                            <span class="badge badge-primary">{{ ucFirst($project->priority) }}</span>
                        @endif
                      </td>
                      <td>{{ $project->createdBy->name }}</td>
                      
                      <td>
                        <div class="d-flex">
                          <a href="{{ route('singleProject', $project->unique_key) }}" class="btn btn-primary btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View"><i class="bi bi-eye"></i></a>
                          <a href="{{ route('editProject', $project->unique_key) }}" class="btn btn-success btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="bi bi-pencil-square"></i></a>
                          <a href="{{ route('deleteProject', $project->unique_key) }}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete"><i class="bi bi-trash"></i></a>
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

<!--addTask Modal -->
<div class="modal fade" id="addTask" tabindex="-1" aria-labelledby="addTaskLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Add Task to Project: <span class="project_name"></span></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('addTaskPost') }}" method="POST" enctype="multipart/form-data">@csrf
        <div class="modal-body">
            <input type="hidden" name="project_id" class="project_id">
          
          <div class="d-grid mb-3">
            <label for="" class="form-label">Name<span class="text-danger fw-bolder">*</span></label>
            <input type="text" name="name" class="form-control name @error('name') is-invalid @enderror"
            id="" value="{{ old('name') }}">
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>

          <div class="d-grid mb-3">
            <label for="" class="form-label">Start Date<span class="text-danger fw-bolder">*</span>| From: <span class="start_date_limit"></span></label>
            <input type="text" name="start_date" class="start_date form-control @error('start_date') is-invalid @enderror"
            id="" value="{{ old('start_date') }}">
            @error('start_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>

          <div class="d-grid mb-3">
            <label for="" class="form-label">End Date<span class="text-danger fw-bolder">*</span>| Limit: <span class="end_date_limit"></span></label>
            <input type="text" name="end_date" class="end_date form-control @error('end_date') is-invalid @enderror"
            id="" value="{{ old('end_date') }}">
            @error('end_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>

          <div class="d-grid mb-3">
            <label for="" class="form-label">Select Priority<span class="text-danger fw-bolder">*</span></label>
            <select name="priority" data-live-search="true" class="custom-select form-control border @error('priority') is-invalid @enderror">

              <option value="high">High</option>
              <option value="medium">Medium</option>
              <option value="low">Low</option>
                            
            </select>
            @error('priority')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>

          <div class="d-grid mb-3">
            <label for="" class="form-label">Assign Team Member<span class="text-danger fw-bolder">*</span></label>
            <select name="assigned_team_member" data-live-search="true" class="custom-select country form-control border" id="">
                <option value="">Nothing Selected</option>
                @if (count($employees) > 0)
                    @foreach ($employees as $employees)
                    <option value="{{ $employees->id }}">{{ $employees->name }}</option>
                    @endforeach
                @endif
              </select>
            @error('assigned_team_member')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>

          <div class="d-grid mb-3">
            <label for="" class="form-label">Select Task Category | Optional</label>
            <select name="task_category" data-live-search="true" class="custom-select form-control border @error('task_category') is-invalid @enderror">

              <option value="">Nothing to Select</option>
                            
            </select>
            @error('task_category')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>

          <div class="d-grid mb-3">
            <label for="" class="form-label">Logo | Optional</label>
            <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" id="">
            @error('logo')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>

          <div class="d-grid mb-3">
            <label for="" class="form-label">Description | Optional</label>
            <textarea name="description" id="" cols="30" rows="5" class="form-control @error('description') is-invalid @enderror"></textarea>
            
            @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            </div>
          
          </div>
      
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Add Task</button>
        </div>
      </form>

    </div>
  </div>
</div>

@endsection

@section('extra_js')

<link href="{{asset('/assets/css/jquery.datetimepicker.min.css')}}" rel="stylesheet">
<script src="{{asset('/assets/js/jquery.datetimepicker.min.js')}}"></script>
  
  <?php if(count($errors) > 0) : ?>
    <script>
        $( document ).ready(function() {
            $('#addTask').modal('show');
        });
    </script>
  <?php endif ?>

  <script>
    function addTaskModal($project="", $end_date="", $start_date="") {
        
        $('#addTask').modal('show');
        $('.project_name').text($project.name);
        $('.project_id').val($project.id);
        $('.start_date_limit').text($start_date);
        $('.end_date_limit').text($end_date);

        pickStartDate($project.start_date, $project.end_date)
        pickEndDate($project.start_date, $project.end_date)

    }
  </script>

<script>
 function pickEndDate($start_date="", $end_date="") {
    jQuery('.end_date').datetimepicker({
    datepicker:true,
    //showPeriod: true,
    format:'Y-m-d',
    timepicker:false,
    minDate: $start_date,
    maxDate: $end_date
  });
 }

 function pickStartDate($start_date="", $end_date="") {
    jQuery('.start_date').datetimepicker({
    datepicker:true,
    //showPeriod: true,
    format:'Y-m-d',
    timepicker:false,
    minDate: $start_date,
    maxDate: $end_date
  });
 }

 $(document).ready(function(){
    pickEndDate($start_date="", $end_date="");
    pickStartDate($start_date="", $end_date="");
 });
  
</script>

<script>
  jQuery('.start_date').datetimepicker({
    datepicker:true,
    //showPeriod: true,
    format:'Y-m-d',
    timepicker:false,
  });
</script>

@endsection