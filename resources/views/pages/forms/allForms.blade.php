@extends('layouts.design')
@section('title')Forms @endsection

@section('extra_css')
    <style>
      td{
        font-size: 14px;
      }
    </style>
@endsection

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Forms</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Forms</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  
  <section class="users-list-wrapper">
    <div class="users-list-filter px-1">
      <form>
        <div class="row border rounded py-2 mb-2">

          <div class="col-12 col-md-6 col-lg-3 d-flex align-items-end">
            <div class="d-grid w-100">
              <a href="{{ route('addForm') }}" class="btn btn-primary btn-block glow users-list-clear mb-0">
                <i class="bx bx-plus"></i>Add Form</a>
            </div>
          </div>

        </div>
      </form>
    </div>

  </section>

  <section>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body pt-3">
            
          <div class="clearfix mb-2">
            <div class="float-end text-end">
              <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                <i class="bi bi-upload"></i> <span>Import</span></button>
              <button class="btn btn-sm btn-secondary rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data"><i class="bi bi-download"></i> <span>Export</span></button>
              <button class="btn btn-sm btn-danger rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Delete All"><i class="bi bi-trash"></i> <span>Delete All</span></button>
            </div>
          </div>
          <hr>
          
          <div class="table table-responsive">
            <table id="orders-table" class="table table-striped custom-table" style="width:100%">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Heading</th>
                  <th scope="col">Subheading</th>
                  <th scope="col">OrderBump</th>
                  <th scope="col">UpSell</th>
                  <th scope="col">OrderId</th>
                  <th scope="col">Customer</th>
                  <th scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>

                @if (count($formLabels) > 0)
                  @foreach ($formLabels as $key=>$label)
                    <tr>
                      <th scope="row">{{ ++$key }}</th>
                      <td>{{ $label->order_heading }}</td>
                      <td>{{ $label->order_subheading }}</td>
                      <td>
                        @if ($label->order->hasOrderbump())
                          <span class="text-success">Yes</span>
                        @else
                          <span class="text-danger">No</span>
                        @endif
                        
                      </td>
                      <td>
                        @if ($label->order->hasUpsell())
                          <span class="text-success">Yes</span>
                        @else
                        <span class="text-danger">No</span>
                        @endif
                      </td>
                      <td>
                        <a href="#" class="btn btn-light border border-success rounded-pill p-1">
                          <!-- < 10 -->
                          @if ($label->order->id < 10) 0000{{ $label->order->id }} @endif
                          <!-- > 10 < 100 -->
                          @if (($label->order->id > 10) && ($label->order->id < 100)) 000{{ $label->order->id }} @endif
                          <!-- > 100 < 1000 -->
                          @if (($label->order->id) > 100 && ($label->order->id < 100)) 00{{ $label->order->id }} @endif
                          <!-- > 1000 < 10000++ -->
                          @if (($label->order->id) > 100 && ($label->order->id < 100)) 0{{ $label->order->id }} @endif
                          
                        </a>
                      </td>
                      <td><span>No response</span></td>
                      <td>
                        {{-- <input type="hidden" id="foo" value="https://github.com/zenorocha/clipboard.js.git"> --}}
                        <div class="d-flex">
                          <a class="btn btn-secondary btn-sm me-2 clipboard-btn" data-bs-toggle="tooltip" data-bs-placement="top"
                          data-bs-title="Copy Link to clipboard" data-clipboard-text="{{ url('/').'/'.$label->order->url }}">
                          <i class="bi bi-clipboard"></i></a>
                          <a href="{{ route('singleForm', $label->unique_key) }}" class="btn btn-primary btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View"><i class="bi bi-eye"></i></a>
                          <a href="{{ route('editForm', $label->unique_key) }}" class="btn btn-success btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="bi bi-pencil-square"></i></a>
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
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Import Product CSV File</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>Download sample product CSV file <a href="#" class="btn btn-sm rounded-pill btn-primary"><i class="bi bi-download me-1"></i> Download</a></div>
        <div class="mt-3">
          <label for="formFileSm" class="form-label">Click to upload file</label>
          <input class="form-control form-control-sm" id="formFileSm" type="file">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary"><i class="bi bi-upload"></i> Upload</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('extra_js')
<script>
  new ClipboardJS('.clipboard-btn');
</script>
    
@endsection