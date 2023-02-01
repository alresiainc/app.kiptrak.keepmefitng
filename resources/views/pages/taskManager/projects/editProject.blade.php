@extends('layouts.design')
@section('title')Edit Project @endsection

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
      <h1>Edit Project</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('allProject') }}">Project List</a></li>
          <li class="breadcrumb-item active">Edit Project</li>
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
              
              <form class="row g-3 needs-validation" action="{{ route('editProjectPost', $project->unique_key) }}" method="POST" enctype="multipart/form-data">@csrf
                
                <div class="gallery-uploader-wrap">
                    <label for="" class="form-label">Logo</label>
                    <br>
                    <label class="uploader-img">
                        @if (isset($project->logo))
                            <img src="{{ asset('/storage/projects/'.$project->logo) }}" width="100" class="img-fluid" alt="Upload Photo">
                        @else
                            <img src="{{ asset('/storage/projects/default.png') }}" width="100" class="img-fluid" alt="Upload Photo">
                        @endif
                     
                    </label>
                </div>

                <div class="col-md-12">
                  <label for="" class="form-label">Project Name</label>
                  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') ? old('name') : $project->name }}">
                  @error('name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">Start Date</label>
                    <input type="text" name="start_date" id="start_date" class="project_date form-control @error('start_date') is-invalid @enderror"
                    value="{{ old('start_date') ? old('start_date') : $project->start_date }}">
                    @error('start_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">End Date</label>
                    <input type="text" name="end_date" id="end_date" class="project_date form-control @error('end_date') is-invalid @enderror"
                    value="{{ old('end_date') ? old('end_date') : $project->end_date }}">
                    @error('end_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                  <label for="" class="form-label">Assign Team Leader</label>

                  <select name="assigned_staff" data-live-search="true" class="custom-select form-control border @error('assigned_staff') is-invalid @enderror">

                    <option value="{{ $project->assigned_to }}">{{ $project->assignedTo->name }}</option>
                    
                    @if (count($employees) > 0)
                      @foreach ($employees as $employees)
                      <option value="{{ $employees->id }}">{{ $employees->name }}</option>
                      @endforeach
                    @endif
                    
                  </select>

                  @error('assigned_staff')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <!---Task---->
                <div class="col-md-6">
                  <label for="" class="form-label">Project Status<span class="text-danger fw-bolder">*</span></label>
                  <select name="status" data-live-search="true" class="custom-select form-control border @error('status') is-invalid @enderror">

                    <option value="{{ $project->status }}">{{ $project->status=='in_progess' ? 'In Progress' : ucFirst($project->status) }}</option>
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

                <div class="col-md-6">
                    <label for="" class="form-label">Select Priority</label>
                    <select name="priority" data-live-search="true" class="custom-select form-control border @error('priority') is-invalid @enderror">
                      <option value="{{ $project->priority }}">{{ ucFirst($project->priority) }}</option>
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
                    <textarea name="description" id="" cols="30" rows="5" class="tinymce-editor form-control @error('description') is-invalid @enderror">{!! old('description') ? old('description') : $project->description !!}</textarea>
                    
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Save Project</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- End Multi Columns Form -->
              
            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

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
@endsection