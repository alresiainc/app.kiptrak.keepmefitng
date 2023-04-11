@extends('layouts.design')
@section('title')Create Product @endsection

@section('extra_css')
@endsection

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Edit Product</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('allWarehouse') }}">Warehouses</a></li>
          <li class="breadcrumb-item active">Edit Warehouse</li>
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
              
                <form class="row g-3" action="{{ route('editWarehousePost', $warehouse->unique_key) }}" method="POST">@csrf
                
                    <div class="col-md-9">
                      <label for="" class="form-label">Name<span class="text-danger fw-bolder">*</span></label>
                      <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                      id="" value="{{ $warehouse->name }}">
                      @error('name')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
    
                    <div class="col-md-3">
                      <label for="" class="form-label">Select Agent | optional</label>
                      <select name="agent_id" class="custom-select form-control border" id="">
                        
                        <option value="{{ isset($warehouse->agent_id) ? $warehouse->agent_id : '' }}" selected>
                          {{ isset($warehouse->agent_id) ? $warehouse->agent->name : 'Nothing Selected' }}
                        </option>
                        
                        
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
                      id="" value="{{ isset($warehouse->city) ? $warehouse->city : '' }}">
                      @error('city')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
    
                    <div class="col-md-4">
                      <label for="" class="form-label">State</label>
                      <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                      id="" value="{{ $warehouse->state }}">
                      @error('state')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
    
                    <div class="col-md-4">
                      <label for="" class="form-label">Country</label>
                      <select name="country" class="custom-select form-control border" id="">
                        <option value="{{ isset($warehouse->country_id) ? $warehouse->country->id : '' }}" selected>{{ isset($warehouse->country_id) ? '| '.$warehouse->country->name : 'Nothing Selected' }}</option>
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
                      id="" value="{{ isset($warehouse->address) ? $warehouse->address : '' }}">
                      @error('address')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
    
                    
                    <div class="text-end">
                      <button type="submit" class="btn btn-info">Update Warehouse</button>
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
@endsection