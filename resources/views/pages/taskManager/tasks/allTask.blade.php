@extends('layouts.design')
@section('title')Task List @endsection

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
    <h1>Task List</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('overview') }}">Overview</a></li>
        <li class="breadcrumb-item"><a href="{{ route('allProject') }}">Project List</a></li>
        <li class="breadcrumb-item active">Task List</li>
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
                  <a href="{{ route('addTask') }}" class="btn btn-sm btn-dark rounded-pill">
                    <i class="bi bi-plus"></i> <span>Create Task</span></a>
                  
              </div>
  
              <div class="float-end text-end d-none">
                  <a href="">
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
                      <th>Assigned To</th>
                      <th>Start Date</th>
                      <th>End Date</th>
                      <th>Status</th>
                      <th>Team Leader</th>
                      <th>Priority</th>
                      <th>Created By</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                @if (count($tasks) > 0)
                    @foreach ($tasks as $task)
                    <tr>
                      <td>
                        @if (isset($task->logo))
                        <a
                        href="{{ asset('/storage/tasks/'.$task->logo) }}"
                        data-fancybox="gallery"
                        data-caption="{{ isset($task->name) ? $task->name : 'no caption' }}"
                        >   
                        <img src="{{ asset('/storage/tasks/'.$task->logo) }}" style="width: 50px; height: 50px;" class="rounded-circle img-thumbnail img-fluid"
                        alt="{{$task->name}}"></a>
                        @else
                        <img src="{{ asset('/storage/tasks/default.png') }}" width="50" class="rounded-circle img-thumbnail img-fluid"
                        alt="{{$task->name}}"></a> 
                        @endif
                        
                      </td>

                      <td>{{ $task->name }}</td>

                      <td>{{ $task->assignedTo->name }}</td>

                      <td>{{ \Carbon\Carbon::parse($task->start_date)->format('D, jS M Y') }}</td> <!----Wed, 25th Jan 2023---->
                      <td>{{ \Carbon\Carbon::parse($task->end_date)->format('D, jS M Y') }}</td>

                      <td>
                        @if ($task->status=='pending')
                            <span class="badge badge-dark">{{ ucFirst($task->status) }}</span>
                        @elseif($task->status=='in_progress')
                            <span class="badge badge-primary">{{ ucFirst($task->status) }}</span>
                        @elseif($task->status=='ready')
                            <span class="badge badge-warning">{{ ucFirst($task->status) }}</span>
                        @elseif($task->status=='done')
                            <span class="badge badge-success">{{ ucFirst($task->status) }}</span>
                        @elseif($task->status=='backlog')
                            <span class="badge badge-danger">{{ ucFirst($task->status) }}</span>
                        @endif
                      </td>
                      
                      <td>{{ $task->project->assignedTo->name }}</td>
                      <td>
                        @if ($task->priority=='high') 
                            <span class="badge badge-danger">{{ ucFirst($task->priority) }}</span>
                        @elseif($task->status=='medium')
                            <span class="badge badge-warning">{{ ucFirst($task->priority) }}</span>
                        @elseif($task->status=='low')
                            <span class="badge badge-primary">{{ ucFirst($task->priority) }}</span>
                        @endif
                      </td>
                      <td>{{ $task->createdBy->name }}</td>
                      
                      <td>
                        <div class="d-flex">
                          <a href="{{ route('singleTask', $task->unique_key) }}" class="btn btn-primary btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View"><i class="bi bi-eye"></i></a>
                          <a href="{{ route('editTask', $task->unique_key) }}" class="btn btn-success btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="bi bi-pencil-square"></i></a>
                          <a href="{{ route('deleteTask', $task->unique_key) }}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete"><i class="bi bi-trash"></i></a>
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


@endsection

@section('extra_js')

@endsection