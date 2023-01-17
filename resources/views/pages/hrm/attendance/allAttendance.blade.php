@extends('layouts.design')
@section('title')Attendance @endsection
@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Attendance List</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Attendance List</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="users-list-wrapper">
    <div class="users-list-filter px-1">
    </div>
  </section>

  @if(Session::has('info'))
    <div class="alert alert-info mb-3 text-center">
        {{Session::get('info')}}
    </div>
  @endif

  <section>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body pt-3">
            
            <div class="clearfix mb-2">

              <div class="float-start text-start">
                  <a href="{{ route('addAttendance') }}"><button data-bs-target="#addMoneyTransfer" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                    <i class="bi bi-arrow-up"></i> <span>On Arrival</span></button></a>

                    <a href="{{ route('addAttendance') }}" class="d-none"><button data-bs-target="#addMoneyTransfer" class="btn btn-sm btn-danger rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                      <i class="bi bi-arrow-down"></i> <span>On Exit</span></button></a>
              </div>
  
              <div class="float-end text-end d-none">
                <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                  <i class="bi bi-upload"></i> <span>Import</span></button>
                <button class="btn btn-sm btn-secondary rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data"><i class="bi bi-download"></i> <span>Export</span></button>
                <button class="btn btn-sm btn-danger rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Delete All"><i class="bi bi-trash"></i> <span>Delete All</span></button>
              </div>
            </div>
            <hr>
            
            <div class="row mb-3">
              <div class="col-lg-3 col-md-6">
                <label for="">Start Date</label>
                <input type="text" name="start_date" id="min" class="form-control filter">
              </div>

              <div class="col-lg-3 col-md-6">
                <label for="">End Date</label>
                <input type="text" name="end_date" id="max" class="form-control filter">
              </div>
            </div>

            <div class="table table-responsive">
              <table id="products-table" class="table custom-table" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Employee</th>
              
                        <th>Check-In</th>
                        <th>Check-Out</th>

                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  @if (count($attendances) > 0)
                      @foreach ($attendances as $attendance)
                      @if ($authUser->isSuperAdmin)
                      <tr>
                        {{-- $date = Carbon::parse('2016-11-24 11:59:56')->addHour(); --}}
                        <td>{{ $attendance->created_at->format('D, M j, Y') }}</td>
                        <td>{{ $attendance->employee->name }}</td>
                        
                        <td>{{ $attendance->check_in }} <br> <span class="badge badge-dark">{{ \Carbon\Carbon::parse($attendance->created_at->addHour(1))->format('H:i') }}</span> </td>

                        <td>
                          @if (isset($attendance->check_out))                           
                          {{ $attendance->check_out }}
                          <br> <span class="badge badge-danger">{{ \Carbon\Carbon::parse($attendance->updated_at->addHour(1))->format('H:i') }}</span>
                          @endif
                        </td>
                        
                        <td>
                          @if ( $attendance->daily_status == 'on_time' )
                            <span class="badge badge-success">Present, early</span>
                          @elseif( $attendance->daily_status == 'late' )
                            <span class="badge badge-danger">Late</span>
                          @endif
                        </td>

                        <td>
                          <a href="{{ route('editAttendance', $attendance->unique_key) }}"><button data-bs-target="#addMoneyTransfer" class="btn btn-sm btn-danger rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                            <i class="bi bi-arrow-down"></i> <span>On Exit</span></button></a>
                        </td>
                      </tr>

                      @elseif($authUser->id==$attendance->employee_id)

                      <tr>
                        {{-- $date = Carbon::parse('2016-11-24 11:59:56')->addHour(); --}}
                        <td>{{ $attendance->created_at->format('D, M j, Y') }}</td>
                        <td>{{ $attendance->employee->name }}</td>
                        
                        <td>{{ $attendance->check_in }}
                          @if (isset($attendance->check_in_note))<br><span class="badge badge-dark" onclick="checkInNote({{ json_encode($attendance) }}, '{{ $attendance->created_at->format('D, M j, Y') }}')"
                            style="cursor: pointer">note</span> @endif
                        </td>

                        <td>
                          @if (isset($attendance->check_out)) {{ $attendance->check_out }} @endif                        
                          @if (isset($attendance->check_out_note))<br><span class="badge badge-dark" onclick="checkOutNote({{ json_encode($attendance) }}, '{{ $attendance->created_at->format('D, M j, Y') }}')"
                            style="cursor: pointer">note</span> @endif
                        </td>
                        
                        <td>
                          @if ( $attendance->daily_status == 'on_time' )
                            <span class="badge badge-success">Present, early</span>
                          @elseif( $attendance->daily_status == 'late' )
                            <span class="badge badge-danger">Late</span>
                          @endif
                        </td>

                        <td>
                          <a href="{{ route('editAttendance', $attendance->unique_key) }}"><button data-bs-target="#addMoneyTransfer" class="btn btn-sm btn-danger rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                            <i class="bi bi-arrow-down"></i> <span>On Exit</span></button></a>
                        </td>
                      </tr>
                      @endif
                      
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

<!-- CheckInNoteModal -->
<div class="modal fade" id="checkInNoteModal" tabindex="-1" aria-labelledby="checkInNoteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div><span>Check-In:</span> <span class="check_in_name"></span> | <span class="check_in_date"></span></div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mt-3 check_in_note">
          
        </div>
      </div>
      
    </div>
  </div>
</div>

<!-- CheckOutNoteModal -->
<div class="modal fade" id="checkOutNoteModal" tabindex="-1" aria-labelledby="checkOutNoteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div><span>Check-Out:</span> <span class="check_out_name"></span> | <span class="check_out_date"></span></div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mt-3 check_out_note">
          
        </div>
      </div>
      
    </div>
  </div>
</div>

@endsection

@section('extra_js')

  <script>
    function checkInNote($attendance="", $attendance_date="") {
      $('#checkInNoteModal').modal("show");
      $('.check_in_name').text($attendance.employee.name);
      $('.check_in_date').text($attendance_date);
      $('.check_in_note').text($attendance.check_in_note);
    }

    function checkOutNote($attendance="", $attendance_date="") {
      $('#checkOutNoteModal').modal("show");
      $('.check_out_name').text($attendance.employee.name);
      $('.check_out_date').text($attendance_date);
      $('.check_out_note').text($attendance.check_out_note);
    }

    var minDate, maxDate;
 
    // Custom filtering function which will search data in column four between two values(dates)
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var min = minDate.val();
            var max = maxDate.val();
            var date = new Date( data[6] );
      
            if (
                ( min === null && max === null ) ||
                ( min === null && date <= max ) ||
                ( min <= date   && max === null ) ||
                ( min <= date   && date <= max )
            ) {
                return true;
            }
            return false;
        }
    );
  </script>

@endsection