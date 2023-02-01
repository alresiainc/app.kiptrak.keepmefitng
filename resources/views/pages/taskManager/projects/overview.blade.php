@extends('layouts.design')
@section('title')Task Manger Overview @endsection
@section('extra_css')
<style>
  .attendance:hover{
    color: #fff;
    background-color: #04512d !important;
  }
</style>
@endsection

@section('content')
    
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Task Manger Overview</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Task Manger Overview</li>
      </ol>
    </nav>
  </div>
  <!-- End Page Title -->

  <!-- Alert -->
  <div id="liveAlertPlaceholder d-none"></div>
  <!-- /Alert -->

  <div class="d-flex align-items-center justify-content-between">
    <a href="{{ route('addProject') }}" class="btn btn-sm btn-dark rounded-pill"><i class="bi bi-plus"></i> <span>Create Project</span></a>

    <div>
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
  

  <hr />
      
  <section class="section m-0">
    
  </section>

  <section class="section m-0">
    <div class="row">

      <!-- Backlog -->
      <div class="col-lg-3 col-md-6">

        <div class="text-center mb-2"><h6 class="fw-bold">Backlog</h6></div>

        @if (count($backlog_tasks))
            @foreach ($backlog_tasks as $task)
            <div class="card card-right-border shadow">
                <div class="card-body p-2">
        
                    <!---task-logo-side-->
                    @if (isset($task->logo))
                    <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="border rounded shadow-sm me-0">
                        <figure class="m-0">
                          <img src="{{ asset('/storage/tasks/'.$task->logo) }}" class="w-100 img-thumbnail img-fluid" alt="{{$task->name}} Image">
                          <figcaption class="text-center">{{ $task->project->name }}</figcaption>
                        </figure>
                    </div>
                    </div>
                    @else
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="border rounded shadow-sm me-2">
                          <figure class="m-1">
                            <figcaption class="text-center">{{ $task->project->name }}</figcaption>
                          </figure>
                            
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
                                <small class="text-uppercase text-muted pt-1 fw-bold" style="font-size: 11px;" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="{{ $task->assignedTo->name  }}">{{ $task->assignedTo->firstname }} {{ substr($task->assignedTo->lastname, 0, 1) . '.' }}</small>
                                <small class="text-uppercase text-muted pt-1 fw-bold" style="font-size: 11px;">
                                    [@if ($task->sameMonth())
                                        {{ \Carbon\Carbon::parse($task->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($task->end_date)->format('j') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($task->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($task->end_date)->format('M j') }}
                                    @endif]
                                
                                </small>
                            </div>
                        </div>
                        
                        <div><small style="font-size: 11px;">{{ count($task->remarks) }} <i class="bi bi-chat-left"></small></i></div>
        
                    </div>
        
                </div>
            </div>
            @endforeach
        @else
        <div class="card card-right-border shadow">
            <div class="card-body p-2 text-center">Empty</div>
        </div>
        @endif
            
      </div>
      <!-- End Backlog -->

      <!-- Pending -->
      <div class="col-lg-3 col-md-6">

        <div class="text-center mb-2"><h6 class="fw-bold">Pending</h6></div>

        @if (count($pending_tasks))
            @foreach ($pending_tasks as $task)
            <div class="card card-right-border shadow">
                <div class="card-body p-2">
        
                    <!---task-logo-side-->
                    @if (isset($task->logo))
                    <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="border rounded shadow-sm me-0">
                        <figure class="m-0">
                          <img src="{{ asset('/storage/tasks/'.$task->logo) }}" class="w-100 img-thumbnail img-fluid" alt="{{$task->name}} Image">
                          <figcaption class="text-center">{{ $task->project->name }}</figcaption>
                        </figure>
                    </div>
                    </div>
                    @else
                    <div class="d-flex align-items-center justify-content-center mb-3">
                      <div class="border rounded shadow-sm me-2">
                        <figure class="m-1">
                          <figcaption class="text-center">{{ $task->project->name }}</figcaption>
                        </figure>
                          
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
                                <small class="text-uppercase text-muted pt-1 fw-bold" style="font-size: 11px;" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="{{ $task->assignedTo->name  }}">{{ $task->assignedTo->firstname }} {{ substr($task->assignedTo->lastname, 0, 1) . '.' }}</small>
                                <small class="text-uppercase text-muted pt-1 fw-bold" style="font-size: 11px;">
                                    [@if ($task->sameMonth())
                                        {{ \Carbon\Carbon::parse($task->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($task->end_date)->format('j') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($task->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($task->end_date)->format('M j') }}
                                    @endif]
                                
                                </small>
                            </div>
                        </div>
                        
                        <div><small style="font-size: 11px;">{{ count($task->remarks) }} <i class="bi bi-chat-left"></small></i></div>
        
                    </div>
        
                </div>
            </div>
            @endforeach
        @else
        <div class="card card-right-border shadow">
            <div class="card-body p-2 text-center">Empty</div>
        </div>
        @endif
            
      </div>
      <!-- End Pending -->

      <!-- InProgress -->
      <div class="col-lg-3 col-md-6">

        <div class="text-center mb-2"><h6 class="fw-bold">In Progress</h6></div>

        @if (count($in_progress_tasks))
            @foreach ($in_progress_tasks as $task)
            <div class="card card-right-border shadow">
                <div class="card-body p-2">
        
                    <!---task-logo-side-->
                    @if (isset($task->logo))
                    <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="border rounded shadow-sm me-0">
                        <figure class="m-0">
                          <img src="{{ asset('/storage/tasks/'.$task->logo) }}" class="w-100 img-thumbnail img-fluid" alt="{{$task->name}} Image">
                          <figcaption class="text-center">{{ $task->project->name }}</figcaption>
                        </figure>
                    </div>
                    </div>
                    @else
                    <div class="d-flex align-items-center justify-content-center mb-3">
                      <div class="border rounded shadow-sm me-2">
                        <figure class="m-1">
                          <figcaption class="text-center">{{ $task->project->name }}</figcaption>
                        </figure>
                          
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
                                <small class="text-uppercase text-muted pt-1 fw-bold" style="font-size: 11px;" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="{{ $task->assignedTo->name  }}">{{ $task->assignedTo->firstname }} {{ substr($task->assignedTo->lastname, 0, 1) . '.' }}</small>
                                <small class="text-uppercase text-muted pt-1 fw-bold" style="font-size: 11px;">
                                    [@if ($task->sameMonth())
                                        {{ \Carbon\Carbon::parse($task->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($task->end_date)->format('j') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($task->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($task->end_date)->format('M j') }}
                                    @endif]
                                
                                </small>
                            </div>
                        </div>
                        
                        <div><small style="font-size: 11px;">{{ count($task->remarks) }} <i class="bi bi-chat-left"></small></i></div>
        
                    </div>
        
                </div>
            </div>
            @endforeach
        @else
        <div class="card card-right-border shadow">
            <div class="card-body p-2 text-center">Empty</div>
        </div>
        @endif
            
      </div>
      <!-- End InProgress -->

      <!-- Done -->
      <div class="col-lg-3 col-md-6">

        <div class="text-center mb-2"><h6 class="fw-bold">Done</h6></div>

        @if (count($done_tasks))
            @foreach ($done_tasks as $task)
            <div class="card card-right-border shadow">
                <div class="card-body p-2">
        
                    <!---task-logo-side-->
                    @if (isset($task->logo))
                    <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="border rounded shadow-sm me-0">
                        <figure class="m-0">
                          <img src="{{ asset('/storage/tasks/'.$task->logo) }}" class="w-100 img-thumbnail img-fluid" alt="{{$task->name}} Image">
                          <figcaption class="text-center">{{ $task->project->name }}</figcaption>
                        </figure>
                    </div>
                    </div>
                    @else
                    <div class="d-flex align-items-center justify-content-center mb-3">
                      <div class="border rounded shadow-sm me-2">
                        <figure class="m-1">
                          <figcaption class="text-center">{{ $task->project->name }}</figcaption>
                        </figure>
                          
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
                                <small class="text-uppercase text-muted pt-1 fw-bold" style="font-size: 11px;" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="{{ $task->assignedTo->name  }}">{{ $task->assignedTo->firstname }} {{ substr($task->assignedTo->lastname, 0, 1) . '.' }}</small>
                                <small class="text-uppercase text-muted pt-1 fw-bold" style="font-size: 11px;">
                                    [@if ($task->sameMonth())
                                        {{ \Carbon\Carbon::parse($task->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($task->end_date)->format('j') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($task->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($task->end_date)->format('M j') }}
                                    @endif]
                                
                                </small>
                            </div>
                        </div>
                        
                        <div><small style="font-size: 11px;">{{ count($task->remarks) }} <i class="bi bi-chat-left"></small></i></div>
        
                    </div>
        
                </div>
            </div>
            @endforeach
        @else
        <div class="card card-right-border shadow">
            <div class="card-body p-2 text-center">Empty</div>
        </div>
        @endif
            
      </div>
      <!-- End Done -->
      
    </div>
  </section>

  <hr />

</main>

@endsection

@section('extra_js')

@endsection