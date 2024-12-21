@extends('layouts.design')
@section('title')
    General Setting
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
            <h1>Header and footer Scripts</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Scripts</li>
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

                            <form class="row g-3 needs-validation" action="{{ route('update.scripts') }}" method="POST"
                                enctype="multipart/form-data">@csrf




                                <div class="col-md-12">
                                    <label for="" class="form-label">Header Scripts</label>
                                    <textarea id="header_scripts" name="header_scripts" class="form-control" rows="5">{{ old('header_scripts', $headerScripts) }}</textarea>
                                    @error('header_scripts')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="" class="form-label">Footer Scripts</label>
                                    <textarea id="footer_scripts" name="footer_scripts" class="form-control" rows="5">{{ old('footer_scripts', $footerScripts) }}</textarea>

                                    @error('footer_scripts')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>



                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Save Settings</button>
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
    <div class="modal fade" id="addCustomer" tabindex="-1" aria-labelledby="addCustomerLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add
                        Customer</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('addCustomerPost') }}" method="POST" enctype="multipart/form-data">@csrf
                    <div class="modal-body">

                        <div class="d-grid mb-2">
                            <label for="">First Name</label>
                            <input type="text" name="firstname" class="form-control" placeholder="">
                        </div>

                        <div class="d-grid mb-2">
                            <label for="">Last Name</label>
                            <input type="text" name="lastname" class="form-control" placeholder="">
                        </div>
                        <div class="d-grid mb-2">
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="">
                        </div>

                        <div class="d-grid mb-2">
                            <label for="">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" placeholder="">
                        </div>

                        <div class="d-grid mb-2">
                            <label for="">Whatsapp Number</label>
                            <input type="text" name="whatsapp_phone_number" class="form-control" placeholder="">
                        </div>

                        <div class="d-grid mb-2">
                            <label for="">Address</label>
                            <input type="text" name="delivery_address" class="form-control" placeholder="">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
@endsection
