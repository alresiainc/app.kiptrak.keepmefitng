@extends('layouts.design')
@section('title')
    View Customer
@endsection
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Customer Information</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('allCustomer') }}">Customers</a></li>
                    <li class="breadcrumb-item active">Customer Information
                    <li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <hr>
        <section>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-body pt-3">
                            <div class="card-title clearfix">
                                <div class="d-lg-flex d-grid align-items-center float-start">
                                    <div>
                                        @if (isset($customer->profile_picture))
                                            <a href="{{ asset('/storage/customer/' . $customer->profile_picture) }}"
                                                data-caption="{{ isset($customer->name) ? $customer->name : 'no caption' }}"
                                                data-fancybox>
                                                <img src="{{ asset('/storage/customer/' . $customer->profile_picture) }}"
                                                    width="100" class="img-thumbnail img-fluid" alt="Photo"></a>
                                        @else
                                            <img src="{{ asset('/storage/customer/person.png') }}" width="100"
                                                class="img-thumbnail img-fluid" alt="Photo">
                                        @endif

                                    </div>
                                    <div class="d-grid ms-lg-3">
                                        <div class="display-6">{{ $customer->firstname }} {{ $customer->lastname }}</div>
                                        <h5>{{ $customer->state }} | {{ $customer->country?->name ?? '' }}</h5>

                                        @if ($customer->status == 'true')
                                            <div class="d-flex justify-content-start">
                                                <small class="text-success me-2">Active</small>
                                            </div>
                                        @else
                                            <small class="text-danger">Inactive</small>
                                        @endif

                                    </div>
                                </div>
                                <div class="float-lg-end">
                                    <a href="{{ route('editCustomer', $customer->unique_key) }}">
                                        <button class="btn btn-sm btn-success"><i class="bi bi-pencil-square"></i></button>
                                    </a>
                                </div>
                            </div>

                            <hr>

                            <div class="row g-3">
                                <div class="col-lg-3">
                                    <label for="">Phone Numbers</label>
                                    <div class="lead">{{ $customer->phone_number }}
                                        @if (isset($customer->whatsapp_phone_number))
                                            <br> {{ $customer->whatsapp_phone_number }}
                                        @endif
                                    </div>
                                </div>


                                <div class="col-lg-3">
                                    <label for="">City/Town</label>
                                    <div class="lead">
                                        @if (isset($customer->city))
                                            {{ $customer->city }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <label for="">Address</label>
                                    <div class="lead">
                                        @if (isset($customer->delivery_address))
                                            {{ $customer->delivery_address }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <label for="">Date Joined</label>
                                    <div class="lead">{{ $customer->created_at }}</div>
                                </div>

                            </div>

                            <!--features-->


                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->
@endsection
