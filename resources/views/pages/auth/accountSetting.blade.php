@extends('layouts.design')
@section('title')Account Setting @endsection

@section('extra_css')
<style>
    select{
    -webkit-appearance: listbox !important
    }
    .btn-light {
        background-color: #fff !important;
        color: #000 !important;
    }
    div.filter-option-inner-inner{
        color: #000 !important;
    }
</style>
@endsection

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Account Setting</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active">Account Setting</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section>
        <div class="row g-3 accordion" id="accordionMain">

            <div class="col-lg-3">
                <div class="card">
                <div class="card-header lead text-center">
                    <div>
                        @if (isset($staff->profile_picture))
                            <a
                            href="{{ asset('/storage/staff/'.$staff->profile_picture) }}"
                            data-caption="{{ isset($staff->name) ? $staff->name : 'no caption' }}"
                            data-fancybox
                            > 
                            <img src="{{ asset('/storage/staff/'.$staff->profile_picture) }}" width="100" class="img-thumbnail img-fluid"
                            alt="Photo"></a>
                        @else
                        <img src="{{ asset('/storage/staff/person.png') }}" width="100" class="rounded-circle img-thumbnail img-fluid"
                        alt="Photo"> 
                        @endif
                        
                    </div>
                </div>
                
                    <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <button class="list-group-item list-group-item-action small d-flex justify-content-between align-items-center bg-primary" type="button"
                        data-bs-toggle="collapse" data-bs-target="#accountProfile" aria-expanded="true" aria-controls="accountProfile">
                        {{-- <a href="?messaging=email" class="list-group-item list-group-item-action small d-flex justify-content-between align-items-center bg-primary">                       --}}
                            <div>
                                <i class="bi bi-house me-1"></i>
                                <span>Account</span>
                            </div>
                            <i class="bi bi-chevron-right"></i>
                        {{-- </a> --}}
                        </button>

                        <button class="list-group-item list-group-item-action small d-flex justify-content-between align-items-center" type="button"
                        data-bs-toggle="collapse" data-bs-target="#accountPassword" aria-expanded="true" aria-controls="accountPassword">
                        {{-- <a href="?messaging=sms" class="list-group-item list-group-item-action small d-flex justify-content-between align-items-center">                       --}}
                            <div>
                                <i class="bi bi-key me-1"></i>
                                <span>Password</span>
                            </div>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                @if(Session::has('success'))
                    <div class="alert alert-success mb-3 text-center">
                        {{Session::get('success')}}
                    </div>
                @endif

                @if(Session::has('current_password_error'))
                    <div class="alert alert-danger mb-3 text-center">
                        {{Session::get('current_password_error')}}
                    </div>
                @endif

                <div id="accountProfile" class="accordion-collapse collapse
                @if(!Session::has('current_password')) show @endif" data-bs-parent="#accordionMain">
                    <div class="card">
                        <div class="card-header lead">Account Settings</div>
                        <div class="card-body">
                    
                            <form class="row g-3 needs-validation" action="{{ route('editProfilePost') }}" method="POST" enctype="multipart/form-data">@csrf
                
                                <div class="col-md-6">
                                <label for="" class="form-label">First Name</label>
                                <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" id=""
                                value="{{ $staff->firstname }}">
                                @error('firstname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                
                                <div class="col-md-6">
                                <label for="" class="form-label">Last Name</label>
                                <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" id=""
                                value="{{ $staff->lastname }}">
                                @error('lastname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                
                                <div class="col-md-4">
                                <label for="" class="form-label">Email</label>
                                <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" id=""
                                value="{{ $staff->email }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                
                                <div class="col-md-4">
                                <label for="" class="form-label">Phone 1</label>
                                <input type="tel" name="phone_1" class="form-control @error('phone_1') is-invalid @enderror" placeholder=""
                                value="{{ $staff->phone_1 }}">
                                @error('phone_1')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                                
                                <div class="col-md-4">
                                <label for="" class="form-label">Phone 2</label>
                                <input type="tel" name="phone_2" class="form-control @error('phone_2') is-invalid @enderror" placeholder=""
                                value="{{ isset($staff->phone_2) ? $staff->phone_2 : '' }}">
                                @error('phone_2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                
                                <div class="col-md-4">
                                <label for="" class="form-label">City / Town</label>
                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" placeholder=""
                                value="{{ isset($staff->city) ? $staff->city : '' }}">
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                
                                <div class="col-md-4">
                                <label for="" class="form-label">State</label>
                                <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" placeholder=""
                                value="{{ $staff->state }}">
                                @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                
                                <div class="col-md-4">
                                <label for="" class="form-label">Select Country</label>
                                <select name="country" data-live-search="true" class="custom-select form-control border @error('country') is-invalid @enderror">
                
                                    <option value="{{ $staff->country->id }}" selected>{{ $staff->country->name }}</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                    
                                </select>
                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                
                                <div class="col-md-12">
                                <label for="" class="form-label">Address</label>
                                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" placeholder=""
                                value="{{ isset($staff->address) ? $staff->address : '' }}">
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                
                                <div class="col-md-6">
                                <label for="" class="form-label">Profile Picture | Optional</label>
                                <input type="file" name="profile_picture" class="form-control @error('image') is-invalid @enderror" id="">
                                @error('profile_picture')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                
                                @if ($staff->hasAnyRole($staff->id))
                                <div class="col-md-6">
                                <label for="" class="form-label">Designation</label>
                                <input type="text" name="role" class="form-control" id="" value="{{ $staff->role($staff->id)->role->name }}" readonly>
                                </div>
                                @endif
                
                                <div class="text-end">
                                <button type="submit" class="btn btn-info">Update Profile</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <div id="accountPassword" class="accordion-collapse collapse" data-bs-parent="#accordionMain">
                    <div class="card">
                        <div class="card-header lead">Change Password</div>
                        <div class="card-body">
                    
                            <form class="row g-3 needs-validation" action="{{ route('editPasswordPost') }}" method="POST">@csrf
                
                                <input type="hidden" name="email" value="{{  Auth::user()->email }}">
                                <div class="col-md-6">
                                <label for="" class="form-label">Current Password</label>
                                <input type="text" name="current_password" class="form-control @error('current_password') is-invalid @enderror" id=""
                                value="">
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                
                                <div class="col-md-6">
                                <label for="" class="form-label">New Password</label>
                                <input type="text" name="new_password" class="form-control @error('new_password') is-invalid @enderror" id=""
                                value="">
                                @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-info">Update Password</button>
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

</main>

@endsection