@extends('layouts.design')
@section('title')Add Warehouse @endsection
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
</style>
@endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      
      <div class="d-flex justify-content-between align-items-center">
        <h1>Add Warehouse</h1>
        <h1><button class="btn" data-bs-toggle="modal" data-bs-target="#productTransfer">Product Transfer</button></h1>
    </div>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('allWarehouse') }}">All Warehouses</a></li>
          <li class="breadcrumb-item active">Add Warehouse</li>
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

            <div class="card-body">
              
              <form class="row g-3" action="{{ route('addWarehousePost') }}" method="POST">@csrf
                
                <div class="col-md-9">
                  <label for="" class="form-label">Name<span class="text-danger fw-bolder">*</span></label>
                  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                  id="" value="{{ old('name') }}">
                  @error('name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-3">
                  <label for="" class="form-label">Select Agent | optional</label>
                  <select name="agent_id" class="custom-select form-control border" id="">
                    <option value="">Nothing Selected</option>
                    @if (count($agents) > 0)
                        @foreach ($agents as $agent)
                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    @endif
                  </select>
                  
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">City or Town</label>
                  <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                  id="" value="{{ old('city') }}">
                  @error('city')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">State</label>
                  <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                  id="" value="{{ old('state') }}">
                  @error('state')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Country</label>
                  <select name="country" class="custom-select form-control border" id="">
                    <option value="1">Nigeria</option>
                    @if (count($countries) > 0)
                        @foreach ($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    @endif
                  </select>
                  @error('country')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-12">
                  <label for="" class="form-label">Address | optional</label>
                  <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                  id="" value="{{ old('address') }}">
                  @error('address')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Add Warehouse</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- End Multi Columns Form -->
              
            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

<!-- Modal -->
<div class="modal fade productTransfer" id="productTransfer" tabindex="-1" aria-labelledby="productTransferLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Product Transfer Setup</h1>
              <button type="button" class="btn-close"
                  data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="productTransferForm" action="{{ route('productTransferSetupPost') }}" method="POST">@csrf
              <div class="modal-body">

                  <div class="d-grid mb-3">
                      <label for="" class="form-label text-dark">From Warehouse</label>
                      <select name="from_warehouse" class="custom-select country form-control border @error('from_warehouse') is-invalid @enderror" id="">
                        <option value="">Nothing Selected</option>
                        @if (count($warehouses) > 0)
                            @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->unique_key }}">{{ $warehouse->name }}</option>
                            @endforeach
                        @endif
                      </select>
                      @error('from_warehouse')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>

                  <div class="d-grid mb-2">
                    <label for="" class="form-label text-dark">To Warehouse</label>
                    <select name="to_warehouse" class="custom-select country form-control border @error('to_warehouse') is-invalid @enderror" id="">
                      <option value="">Nothing Selected</option>
                      @if (count($warehouses) > 0)
                          @foreach ($warehouses as $warehouse)
                          <option value="{{ $warehouse->unique_key }}">{{ $warehouse->name }}</option>
                          @endforeach
                      @endif
                    </select>
                    @error('to_warehouse')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                  
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary productTransferBtn">Submit</button>
              </div>
          </form>
      </div>
  </div>
</div>

@endsection

@section('extra_js')
@if ( $errors->has('from_warehouse') || $errors->has('to_warehouse') )
    <script type="text/javascript">
        $( document ).ready(function() {
             $('.productTransfer').modal('show');
        });
    </script>
@endif
@endsection