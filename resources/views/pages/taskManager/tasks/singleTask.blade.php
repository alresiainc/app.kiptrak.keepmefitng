@extends('layouts.design')
@section('title')Task: {{ $task->name }} @endsection
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

  .attendance:hover{
    color: #fff;
    background-color: #04512d !important;
  }
  .primary-color{
    color: #012970;
  }
</style>
@endsection

@section('content')
    
<main id="main" class="main">
  <div class="pagetitle">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Task: {{ $task->name }}</h1>
        <div onclick="addTaskModal({{ json_encode($project) }}, '{{ \Carbon\Carbon::parse($project->end_date)->format('D, jS M Y') }}',
            '{{ \Carbon\Carbon::parse($project->start_date)->format('D, jS M Y') }}')"><span class="btn btn-sm btn-dark rounded-pill"><i class="bi bi-plus"></i> <span>Add Task</span></span></div>
    </div>
    
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('overview') }}">Overview</a></li>
        <li class="breadcrumb-item"><a href="{{ route('singleProject', $project->unique_key) }}">{{ $task->project->name }}</a></li>
        <li class="breadcrumb-item active">{{ $task->name }}</li>
      </ol>
    </nav>
  </div>
  <!-- End Page Title -->
  
  <!-- Alert -->
  <div id="liveAlertPlaceholder d-none"></div>
  <!-- /Alert -->

  @if(Session::has('success'))
    <div class="alert alert-success mb-3 text-center">
        {{Session::get('success')}}
    </div>
  @endif

  <section class="section m-0">

    <div class="row">

        <div class="col-lg-4 col-md-6">
            <div class="d-flex justify-content-center align-items-center">
                <small style="font-size: 14px;">Team Leader:</small>
                @if (isset($task->project->assignedTo->profile_picture))
                    <a
                    href="{{ asset('/storage/staff/'.$task->project->assignedTo->profile_picture) }}"
                    data-fancybox="gallery"
                    data-caption="{{ isset($task->project->assignedTo->name) ? $task->project->assignedTo->name : 'no caption' }}"
                    >   
                    <img src="{{ asset('/storage/staff/'.$task->project->assignedTo->profile_picture) }}" style="width: 50px; height:50px;" class="ms-2 img-thumbnail img-fluid rounded-circle"
                    alt="{{$task->project->assignedTo->name}}"></a>
                @else
                <img src="{{ asset('/storage/staff/person.png') }}" width="50" class="ms-2 rounded-circle img-thumbnail img-fluid"
                    alt="{{$task->project->assignedTo->name}}">
                @endif
            
                <small class="ms-2 fw-bold" style="font-size: 14px;">{{ $task->project->assignedTo->name }}</small>
                
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="d-flex justify-content-center align-items-center">
                <small style="font-size: 14px;">Duration:</small>
                
                <small class="ms-2 fw-bold" style="font-size: 14px;">
                    @if ($task->sameMonth())
                    <span class="primary-color">{{ \Carbon\Carbon::parse($task->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($task->end_date)->format('j') }}</span>
                    @else
                    <span class="primary-color">{{ \Carbon\Carbon::parse($task->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($task->end_date)->format('M j') }}</span>
                    @endif    
                </small>
                
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <small style="font-size: 14px;">Priority:</small>
                <small class="ms-2 fw-bold badge badge-@if($task->priority=='low')primary @elseif($task->priority=='medium')warning @elseif($task->priority=='high')danger @endif" style="font-size: 14px;">
                    {{ ucFirst($task->priority) }}
                </small>
            </div>
        </div>

        <!---num === 1 ? 'one' : num === 2 ? 'two' : num === 3 ? 'three' : 'unknown'--->
        

        <div class="col-lg-4 col-md-6">
            <div class="d-flex justify-content-center align-items-center">
                <small style="font-size: 14px;">Assigned to:</small>
                @if (isset($task->assignedTo->profile_picture))
                    <a
                    href="{{ asset('/storage/staff/'.$task->assignedTo->profile_picture) }}"
                    data-fancybox="gallery"
                    data-caption="{{ isset($task->assignedTo->name) ? $task->assignedTo->name : 'no caption' }}"
                    >   
                    <img src="{{ asset('/storage/staff/'.$task->assignedTo->profile_picture) }}" style="width: 50px; height:50px;" class="ms-2 img-thumbnail img-fluid rounded-circle"
                    alt="{{$task->assignedTo->name}}"></a>
                @else
                <img src="{{ asset('/storage/staff/person.png') }}" width="50" class="rounded-circle img-thumbnail img-fluid"
                    alt="{{$task->assignedTo->name}}">
                @endif
            
                <small class="ms-2 fw-bold" style="font-size: 14px;">{{ $task->assignedTo->name }}</small>
                
            </div>
        </div>
    
        <div class="d-none">
        <a href="{{ route('allProject') }}" class="btn btn-sm btn-dark rounded-pill"><i class="bi bi-card-list"></i> <span>Project List</span></a>
        <a href="{{ route('allTask') }}" class="btn btn-sm btn-dark rounded-pill"><i class="bi bi-list-task"></i> <span>Task List</span></a>
        </div>
        
    
        <div class="text-lg-end text-center mb-3 d-none">
            <div class="btn-group" role="group" aria-label="Basic example">
            
            <a href="{{ route('todayRecord') }}"><button type="button" class="btn btn-sm btn-light-success">
                Today
            </button></a>
        
            <a href="{{ route('weeklyRecord') }}"><button type="button" class="btn btn-sm btn-light-success">
                This Week
            </button></a>
        
            <a href="{{ route('monthlyRecord') }}"><button type="button" class="btn btn-sm btn-light-success">
                This Month
            </button></a>
        
            <a href="{{ route('yearlyRecord') }}"><button type="button" class="btn btn-sm btn-light-success">
                This Year
            </button></a>
            
            <a href="/"><button type="button" class="btn btn-sm btn-light-success active">
                All
            </button></a>
        
            </div>
        </div>

    </div>
          
  </section>

  <hr />
      
  <section class="section m-0">
    
  </section>

  <section class="section m-0">
    <div class="row">

      <!-- Pending -->
      <div class="col-lg-4 col-md-6">

        <div class="text-center mb-2"><h6 class="fw-bold">
            @if ($task->status=='pending')
                Pending Task
            @elseif($task->status=='in_progress')
                Task In-Progress
            @elseif($task->status=='done')
                Done Task
            @elseif($task->status=='backlog')
                Backlog Task
            @endif    
            </h6>
        </div>

        <div class="card card-right-border shadow">
            <div class="card-body p-2">
    
                <!---task-logo-side-->
                @if (isset($task->logo))
                <div class="d-flex align-items-center justify-content-center mb-3">
                <div class="border rounded shadow-sm me-0">
                    <img src="{{ asset('/storage/tasks/'.$task->logo) }}" class="w-100 img-thumbnail img-fluid"
                    alt="{{$task->name}} Image">
                </div>
                </div>
                @else
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="border rounded shadow-sm me-2">
                        <img src="{{ asset('/storage/tasks/default.png') }}" class="w-100 img-thumbnail img-fluid"
                        alt="{{$task->name}} Image">
                    </div>
                </div>
                @endif
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">

                        <div class="progress">
                        @if($task->priority=='high')

                        <div class="progress-bar p-2 bg-danger"
                            role="progressbar" style="width: 100%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">HIGH</div>

                        @elseif($task->priority=='medium')
                        <div class="progress-bar p-2 bg-warning"
                            role="progressbar" style="width: 100%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">MEDIUM</div>

                        @elseif($task->priority=='low')
                        <div class="progress-bar p-2 bg-primary"
                            role="progressbar" style="width: 100%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">LOW</div>
                        
                        @endif
                        </div>

                        <div class="btn-group">
        
                        <span class="dropdown-toggle" data-bs-toggle="dropdown" style="font-size: 20px; cursor: pointer;"></span>
                        
                        <ul class="dropdown-menu">
                            
                            <li><a class="dropdown-item" href="{{ route('updateTaskPriority', [$task->unique_key, 'high']) }}">High</a></li>
                            <li><hr class="dropdown-divider"></li>

                            <li><a class="dropdown-item" href="{{ route('updateTaskPriority', [$task->unique_key, 'medium']) }}">Medium</a></li>
                            <li><hr class="dropdown-divider"></li>

                            <li><a class="dropdown-item" href="{{ route('updateTaskPriority', [$task->unique_key, 'low']) }}">Low</a></li>
                            
                        </ul>

                        </div>
                        <!--end-btn-group---->

                    </div>
                </div>
    
                <div class="d-flex align-items-center justify-content-between">
                    <div class="task-title"><h6><a href="{{ route('singleTask', $task->unique_key) }}" class="text-dark"
                    data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="{{ $task->name }}">
                    {{ substr($task->name, 0, 21) . '...' }}</a></h6>
                    </div>

                    <div class="btn-group">
    
                        <span class="dropdown-toggle" data-bs-toggle="dropdown" style="font-size: 20px; cursor: pointer;"></span>
                        
                        <ul class="dropdown-menu">
                        
                        <li><a class="dropdown-item" href="{{ route('updateTaskStatus', [$task->unique_key, 'pending']) }}">Pending</a></li>
                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" href="{{ route('updateTaskStatus', [$task->unique_key, 'in_progress']) }}">In Progress</a></li>
                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" href="{{ route('updateTaskStatus', [$task->unique_key, 'done']) }}">Done</a></li>
                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" href="{{ route('updateTaskStatus', [$task->unique_key, 'backlog']) }}">Backlog</a></li>
                        
                        </ul>

                    <!--end-btn-group---->
                    </div>
                </div>
    
                <div class="d-flex align-items-center justify-content-between">
    
                    <div class="d-flex align-items-center justify-content-start">
                        @if (isset($task->assignedTo->profile_picture))
                        <div class="rounded shadow-sm px-1 me-1">
                        <img src="{{ asset('/storage/staff/'.$task->assignedTo->profile_picture) }}" style="width: 30px; height:30px;" class="img-thumbnail img-fluid"
                        alt="{{$task->assignedTo->name}} Image">
                        </div>
                        @else
                        <div class="border rounded shadow-sm px-2 me-2">
                        <i class="bi bi-person"></i>
                        </div>
                        @endif
                        
                        <div class="text-start">
                            <small data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="{{ $task->assignedTo->name  }}">{{ $task->assignedTo->firstname }} {{ substr($task->assignedTo->lastname, 0, 1) . '.' }}</small>
                            <small class="text-uppercase text-muted small pt-1 fw-bold">
                                [@if ($task->sameMonth())
                                    {{ \Carbon\Carbon::parse($task->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($task->end_date)->format('j') }}
                                @else
                                    {{ \Carbon\Carbon::parse($task->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($task->end_date)->format('M j') }}
                                @endif]
                            
                            </small>
                        </div>
                    </div>
                    
                    <div>{{ count($remarks) }} <i class="bi bi-chat-left"></i></div>
    
                </div>
    
            </div>
        </div>
            
      </div>
      <!-- End Pending -->

      <!-- Reviews -->
      <div class="col-lg-8 col-md-6">

        <div class="text-center mb-2"><h6 class="fw-bold">Remarks Section</h6></div>
            @if (count($remarks))
                @foreach ($remarks as $remark)
                <div class="card card-right-border shadow" style="background-color: @if($remark->createdBy==$task->assignedTo)#D2FFE8 @endif">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="image_and_remark">
                                                                  
                                    <div class="rounded shadow-sm px-1 me-1">

                                        <div class="d-flex justify-content-start align-items-center">

                                            @if (isset($remark->createdBy->profile_picture))
                                                <a
                                                href="{{ asset('/storage/staff/'.$remark->createdBy->profile_picture) }}"
                                                data-fancybox="gallery"
                                                data-caption="{{ isset($remark->createdBy->name) ? $remark->createdBy->name : 'no caption' }}"
                                                >   
                                                <img src="{{ asset('/storage/staff/'.$remark->createdBy->profile_picture) }}" style="width: 30px; height:30px;" class="img-thumbnail img-fluid rounded-circle"
                                                alt="{{$remark->createdBy->name}}"></a>
                                            @else
                                            <img src="{{ asset('/storage/staff/person.png') }}" width="30" class="rounded-circle img-thumbnail img-fluid"
                                                alt="{{$remark->createdBy->name}}">
                                            @endif
                                        
                                            <small class="ms-3 fw-bold" style="font-size: 10px;">{{ $remark->createdBy->name }}</small>
                                            <small class="ms-3 fw-bold" style="font-size: 10px;">{{ $remark->created_at->diffForHumans() }}</small>

                                        </div>

                                    </div><!--img-end--->

                                    <div class="remark"><small class="text-sm fw-bold" style="font-size: 12px; color: #262525;">{{ $remark->remark }}</small></div>
                                
                            </div>
                        </div>
                      
                    </div>
                </div>
                @endforeach
            @else
            <div class="card card-right-border shadow">
                <div class="card-body p-2 text-center">
                  No comments at the moment
                </div>
            </div>
            @endif
            
            <div class="card card-right-border shadow">
                <div class="card-body p-2">
                  <form action="{{ route('taskRemarkPost', $task->unique_key) }}" method="POST">@csrf
                    <textarea name="remark" id="" cols="30" rows="5" class="form-control @error('remark') is-invalid @enderror" placeholder="Type here...">{{ old('remark') }}</textarea>
                    @error('remark')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="text-end mt-2">
                        <button type="submit" class="btn btn-primary">Save Remark</button>
                        <button type="reset" class="btn btn-secondary">Cancel</button>
                    </div>
                  </form>
                  
                </div>
            </div>
            
        </div>
      <!-- End Reviews -->

      
    </div>
  </section>

  <hr />

</main>

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
              <input type="hidden" name="project" class="project_id">
            
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
              <label for="" class="form-label">Start Date<span class="text-danger fw-bolder">*</span> | From: <span class="start_date_limit"></span></label>
              <input type="text" name="start_date" class="start_date form-control @error('start_date') is-invalid @enderror"
              id="" value="{{ old('start_date') }}">
              @error('start_date')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>
  
            <div class="d-grid mb-3">
              <label for="" class="form-label">End Date<span class="text-danger fw-bolder">*</span> | Limit: <span class="end_date_limit"></span></label>
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