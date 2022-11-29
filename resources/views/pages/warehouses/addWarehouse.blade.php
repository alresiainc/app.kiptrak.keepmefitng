@extends('layouts.design')
@section('title')Add Warehouse @endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Add Warehouse</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
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

@endsection

@section('extra_js')
@endsection