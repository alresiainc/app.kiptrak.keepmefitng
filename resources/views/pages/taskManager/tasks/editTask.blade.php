@extends('layouts.design')
@section('title')Edit Task @endsection

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

      .tox .tox-promotion{
        display: none !important;
      }
  </style>
@endsection

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Edit Task</h1>
            <div><a href="{{ route('allProject') }}" class="btn btn-sm btn-dark rounded-pill"><i class="bi bi-card-list"></i> <span>Project List</span></a></div>
        </div>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('allTask') }}">Task List</a></li>
          <li class="breadcrumb-item active">Edit Task</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

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
            <div class="card-body">
              
              <form class="row g-3 needs-validation" action="{{ route('editTaskPost', $task->unique_key) }}" method="POST" enctype="multipart/form-data">@csrf

                <div class="gallery-uploader-wrap">
                    <label for="" class="form-label">Logo</label>
                    <br>
                    <label class="uploader-img">
                        @if (isset($task->logo))
                            <img src="{{ asset('/storage/tasks/'.$task->logo) }}" width="100" class="img-fluid" alt="Upload Photo">
                        @else
                            <img src="{{ asset('/storage/tasks/default.png') }}" width="100" class="img-fluid" alt="Upload Photo">
                        @endif
                     
                    </label>
                </div>

                <div class="col-md-12">
                  <label for="" class="form-label">Task Name<span class="text-danger fw-bolder">*</span></label>
                  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $task->name }}">
                  @error('name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <!---Select Project---->
                <div class="col-md-12">
                    <label for="" class="form-label">Select Project<span class="text-danger fw-bolder">*</span></label>
                    <select name="project" data-live-search="true" class="custom-select form-control border @error('project') is-invalid @enderror" id="">
                        <option value="{{ $task->project_id }}">{{ $task->project->name }}</option>
                        @if (count($projects) > 0)
                            @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }} | Duration:
                                {{ \Carbon\Carbon::parse($project->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($project->end_date)->format('M j') }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('project')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!---Start Date---->
                <div class="col-md-6">
                    <label for="" class="form-label">Start Date<span class="text-danger fw-bolder">*</span></label>
                    <input type="text" name="start_date" id="start_date" class="project_date form-control @error('start_date') is-invalid @enderror" value="{{ $task->start_date }}">
                    @error('start_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!---End Date---->
                <div class="col-md-6">
                    <label for="" class="form-label">End Date<span class="text-danger fw-bolder">*</span></label>
                    <input type="text" name="end_date" id="end_date" class="project_date form-control @error('end_date') is-invalid @enderror" value="{{ $task->end_date }}">
                    @error('end_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!---Assign Team Member---->
                <div class="col-md-6">
                    <label for="" class="form-label">Assign Team Member<span class="text-danger fw-bolder">*</span></label>
                    <select name="assigned_team_member" data-live-search="true" class="custom-select form-control border @error('assigned_team_member') is-invalid @enderror" id="">
                        <option value="{{ $task->assigned_to }}">{{ $task->assignedTo->name }}</option>
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

                <!---Priority---->
                <div class="col-md-3">
                    <label for="" class="form-label">Select Priority<span class="text-danger fw-bolder">*</span></label>
                    <select name="priority" data-live-search="true" class="custom-select form-control border @error('priority') is-invalid @enderror">
  
                      <option value="{{ $task->priority }}">{{ ucFirst($task->priority) }}</option>
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

                <!---Task---->
                <div class="col-md-3">
                    <label for="" class="form-label">Task Status<span class="text-danger fw-bolder">*</span></label>
                    <select name="status" data-live-search="true" class="custom-select form-control border @error('status') is-invalid @enderror">
  
                      <option value="{{ $task->status }}">{{ $task->status=='in_progress' ? 'In Progress' : ucFirst($task->status) }}</option>
                      <option value="pending">Pending</option>
                      <option value="in_progress">In Progess</option>
                      <option value="done">Done</option>
                      <option value="backlog">Done</option>
                                    
                    </select>
  
                    @error('priority')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!---Task Category---->
                <!---categories--->
                <div class="col-md-6">
                    <label for="" class="form-label">Select Category *</label>
  
                    <div class="d-flex @error('category') is-invalid @enderror">
  
                      <select name="task_category" id="addCategorySelect" class="select2 form-control @error('task_category') is-invalid @enderror">
                        <option value="{{ isset($task->category_id) ? $task->category->name : '' }}">{{ isset($task->category_id) ? $task->category->name : 'Nothing Selected' }}</option>
                        
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                        
                     </select>
                        
                     <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCategory">
                      <i class="bi bi-plus"></i></button>
                    </div>
                    @error('task_category')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                <!---Logo---->
                <div class="col-md-6">
                  <label for="" class="form-label">Logo | Optional</label>
                  <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" id="">
                  @error('logo')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-12">
                    <label for="" class="form-label">Description | Optional</label>
                    <textarea name="description" id="" cols="30" rows="5" class="tinymce-editor form-control @error('description') is-invalid @enderror">{!! $task->description !!}</textarea>
                    
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Update Task</button>
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
<div class="modal fade" id="addCategory" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Category</h1>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCategoryForm" action="">@csrf
                <div class="modal-body">
                    
                    <div class="d-grid mb-2">
                        <label for="">Category Name</label>
                        <input type="text" name="category_name" class="form-control category_name" placeholder="">
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

<link href="{{asset('/assets/css/jquery.datetimepicker.min.css')}}" rel="stylesheet">
<script src="{{asset('/assets/js/jquery.datetimepicker.min.js')}}"></script>
<script>
  jQuery('.project_date').datetimepicker({
    datepicker:true,
    //showPeriod: true,
    format:'Y-m-d',
    timepicker:false,
  });
</script>

<!---addCategory by serialize--->
<script>
    //addCategory Modal
   $('#addCategoryForm').submit(function(e){
        e.preventDefault();
        var category_name = $("form .category_name").val();
        // alert(category_name)
        if (category_name != '') {
            $('#addCategory').modal('hide');

            $.ajax({
                type:'get',
                url:'/ajax-create-task-category',
                // data:{ category_name:category_name },
                data: $(this).serialize(),
                success:function(resp){
                    
                    if (resp.status) {
                        
                        var datas = {
                            id: resp.data.category.id,
                            text: resp.data.category.name
                        };
                        var newOption = new Option(datas.text, datas.id, false, false);
                        $('#addCategorySelect').prepend(newOption).trigger('change');
                        
                        //$('#addCategorySelect').prepend('<option value='+resp.data.category.id+'>'+resp.data.category.name+'</option>')
                        alert('Category Added Successfully')
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