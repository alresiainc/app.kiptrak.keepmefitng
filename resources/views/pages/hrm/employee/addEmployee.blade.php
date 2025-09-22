@extends('layouts.design')
@section('title')
    Add Employee
@endsection

@section('extra_css')
    <style>
        select {
            -webkit-appearance: listbox !important
        }

        .btn-light {
            background-color: #fff !important;
            color: #000 !important;
        }

        /* .bootstrap-select>.dropdown-toggle.bs-placeholder, .bootstrap-select>.dropdown-toggle.bs-placeholder:active, .bootstrap-select>.dropdown-toggle.bs-placeholder:focus, .bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
                                                                                                                    color: #999;
                                                                                                                } */
        div.filter-option-inner-inner {
            color: #000 !important;
        }
    </style>
@endsection

@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Add Employee</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('allStaff') }}">Employee List</a></li>
                    <li class="breadcrumb-item active">Add Employee</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">

            </div>
        </section>

        @if (Session::has('success'))
            <div class="alert alert-success mb-3 text-center">
                {{ Session::get('success') }}
            </div>
        @endif

        <section>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            <form class="row g-3 needs-validation" action="{{ route('addStaffPost') }}" method="POST"
                                enctype="multipart/form-data">@csrf
                                <div class="col-md-6">
                                    <label for="" class="form-label">First Name</label>
                                    <input type="text" name="firstname"
                                        class="form-control @error('firstname') is-invalid @enderror"
                                        value="{{ old('firstname') }}">
                                    @error('firstname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">Last Name</label>
                                    <input type="text" name="lastname"
                                        class="form-control @error('lastname') is-invalid @enderror"
                                        value="{{ old('lastname') }}">
                                    @error('lastname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">Email</label>
                                    <input type="text" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">Password</label>
                                    <input type="text" name="password"
                                        class="form-control @error('password') is-invalid @enderror" id="">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">Phone 1</label>
                                    <input type="tel" name="phone_1"
                                        class="form-control @error('phone_1') is-invalid @enderror"
                                        value="{{ old('phone_1') }}">
                                    @error('phone_1')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">Phone 2</label>
                                    <input type="tel" name="phone_2"
                                        class="form-control @error('phone_2') is-invalid @enderror"
                                        value="{{ old('phone_2') }}">
                                    @error('phone_2')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">City / Town</label>
                                    <input type="text" name="city"
                                        class="form-control @error('city') is-invalid @enderror"
                                        value="{{ old('city') }}">
                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">State</label>
                                    <input type="text" name="state"
                                        class="form-control @error('state') is-invalid @enderror"
                                        value="{{ old('state') }}">
                                    @error('state')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Select Country</label>
                                    <select name="country" data-live-search="true"
                                        class="custom-select form-control border tags @error('country') is-invalid @enderror">

                                        <option value="1">Nigeria</option>
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

                                <div class="col-md-8">
                                    <label for="" class="form-label">Address</label>
                                    <input type="text" name="address"
                                        class="form-control @error('address') is-invalid @enderror"
                                        value="{{ old('address') }}">
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Profile Picture | Optional</label>
                                    <input type="file" name="profile_picture"
                                        class="form-control @error('image') is-invalid @enderror" id="">
                                    @error('profile_picture')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">Salary | Optional</label>
                                    <input type="number" name="current_salary"
                                        class="form-control @error('current_salary') is-invalid @enderror"
                                        value="{{ old('current_salary') }}">
                                    @error('current_salary')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">Assign Role (Optional)</label>
                                    <select name="role_id" id="role_id" data-live-search="true"
                                        class="custom-select form-control border @error('role_id') is-invalid @enderror"
                                        id="">
                                        <option value="">Nothing Selected</option>
                                        @if (count($roles) > 0)
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                    @error('role_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div class="col-md-6">
                                    @php
                                        $errorMessage = '';
                                        $accounts = [];
                                        try {
                                            $apiKey = \App\Models\GeneralSetting::first()?->serlzo_api_key;
                                            $response = Http::withHeaders(['x-serlzo-api-key' => $apiKey])->get(
                                                'https://whatsapp-reseller.serlzo.com/whatsapp/get-all-whatsapp-accounts',
                                            );

                                            if ($response->status() === 200) {
                                                // $accounts = collect($response->json()['data'] ?? [])->filter(function (
                                                //     $account,
                                                // ) {
                                                //     return $account['status'] == 'active';
                                                // });
                                                $accounts = $response->json()['data'] ?? [];
                                                if (count($accounts) == 0) {
                                                    $errorMessage = 'No active account found';
                                                }
                                            } else {
                                                $errorMessage = 'No account found';
                                            }
                                        } catch (\Throwable $e) {
                                            $errorMessage = $e->getMessage();
                                        }

                                    @endphp
                                    <label for="" class="form-label">WhatsApp (serlzo) Account Token @if ($errorMessage)
                                            <span class="text-warning text-sm">
                                                <strong>{{ $errorMessage }}</strong>
                                            </span>
                                        @endif
                                    </label>

                                    <select name="serlzo_account_token" data-live-search="true"
                                        class="custom-select form-control border tags @error('country') is-invalid @enderror">

                                        <option value="">Select WhatsApp Account</option>

                                        @foreach ($accounts as $account)
                                            <option value="{{ $account['token'] }}">{{ $account['username'] }}
                                            </option>
                                        @endforeach
                                        <option value="">None</option>


                                    </select>


                                    @error('serlzo_account_token')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Save Employee</button>
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
