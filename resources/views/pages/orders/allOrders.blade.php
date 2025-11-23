@extends('layouts.design')
@section('title')
    Orders
@endsection

@section('extra_css')
    <style>
        /* select2 arrow */
        select {
            -webkit-appearance: listbox !important
        }

        .btn-light {
            background-color: #fff !important;
            color: #000 !important;
        }

        div.filter-option-inner-inner {
            color: #000 !important;
        }

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

        table .dropdown-menu {
            position: fixed !important;
            top: 50% !important;
            left: 92% !important;
            transform: translate(-92%, -50%) !important;
        }

        .whatsapp-icon {
            /* font-size: 22px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                    color: #012970;
                                                                                                                                                                                                                                                                                                                                                                                                                                                    margin-right: 25px; */
            position: relative;
        }

        .whatsapp-icon .whatsapp-icon-number {
            position: absolute;
            inset: -2px -5px auto auto;
            font-weight: normal;
            font-size: 12px;
            padding: 3px 6px;
        }

        .delete_all {
            background-color: #DC3545 !important;
            border-color: #DC3545 !important;
        }
    </style>
@endsection

@section('content')

    <main id="main" class="main">

        @if ($entries)
            <div class="pagetitle">
                <h1>Order Entries for Form: {{ $formHolder->name }}</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('allOrders') }}">Orders</a></li>
                        <li class="breadcrumb-item active">Entries for Form: {{ $formHolder->name }}</li>
                    </ol>
                </nav>
            </div><!-- End Page Title -->
        @else
            <div class="pagetitle">
                <h1>
                    Orders
                    @if (!isset($status) || $status == 'new')
                        New
                    @elseif ($status == 'pending')
                        Pending
                    @elseif ($status == 'cancelled')
                        Cancelled
                    @elseif ($status == 'delivered_not_remitted')
                        Delivered not Remitted
                    @elseif ($status == 'delivered_and_remitted')
                        Delivered and Remitted
                    @elseif ($status == 'rescheduled_order')
                        Rescheduled Order
                    @elseif ($status == 'order_in_transit')
                        Order in Transit
                    @elseif ($status == 'order_confirmed')
                        Order Confirmed
                    @elseif ($status == 'order_sent_out')
                        Order Sent Out
                    @elseif ($status == 'delivery_attempted_1')
                        Delivery Attempted 1
                    @elseif ($status == 'delivery_attempted_2')
                        Delivery Attempted 2
                    @elseif ($status == 'delivery_attempted_3')
                        Delivery Attempted 3
                    @elseif ($status == 'cancelled_admin')
                        Cancelled by Admin
                    @elseif ($status == 'customer_unreachable')
                        Customer Unreachable
                    @elseif ($status == 'cancelled_customer')
                        Cancelled by Customer
                    @elseif ($status == 'rejected_customer')
                        Rejected by Customer
                    @elseif ($status == 'duplicate_order')
                        Duplicate Order
                    @endif
                </h1>

                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('allOrders') }}">Orders</a></li>
                        <li class="breadcrumb-item active">
                            @if (!isset($status) || $status == 'new')
                                New
                            @elseif($status == 'pending')
                                Pending
                            @elseif($status == 'cancelled')
                                Cancelled
                            @elseif($status == 'delivered_not_remitted')
                                Delivered not Remitted
                            @elseif($status == 'delivered_and_remitted')
                                Delivered and Remitted
                            @endif
                        </li>
                    </ol>
                </nav>
            </div><!-- End Page Title -->
        @endif

        <section class="users-list-wrapper">
            <div class="users-list-filter px-1">

            </div>

        </section>

        @if (Session::has('success'))
            <div class="alert alert-success mb-3 text-center">
                {{ Session::get('success') }}
            </div>
        @endif

        @if (Session::has('whatsapp_server_error'))
            <div class="alert alert-info mb-3 text-center">
                {{ Session::get('whatsapp_server_error') }}
            </div>
        @endif

        @if (Session::has('info'))
            <div class="alert alert-info mb-3 text-center">
                {{ Session::get('info') }}
            </div>
        @endif

        <section>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body pt-3">

                            <div class="clearfix mb-2">

                                <div class="float-start text-start d-none">
                                    <button data-bs-target="#addMoneyTransfer" class="btn btn-sm btn-dark rounded-pill"
                                        data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto"
                                        data-bs-title="Export Data">
                                        <i class="bi bi-plus"></i> <span>Add Money Transfer</span></button>
                                </div>

                                <div class="float-end text-end">
                                    <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill d-none"
                                        data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto"
                                        data-bs-title="Export Data">
                                        <i class="bi bi-upload"></i> <span>Import</span></button>
                                    <button class="btn btn-sm btn-secondary rounded-pill d-none" data-bs-toggle="tooltip"
                                        data-bs-placement="auto" data-bs-title="Import Data"><i class="bi bi-download"></i>
                                        <span>Export</span></button>
                                    <button class="btn btn-sm btn-info rounded-pill mail_all" data-bs-toggle="tooltip"
                                        data-bs-placement="auto" data-bs-title="Mail All"><i class="bi bi-chat-left"></i>
                                        <span>Mail All</span></button>
                                    <button class="btn btn-sm btn-info rounded-pill delete_all" data-bs-toggle="tooltip"
                                        data-bs-placement="auto" data-bs-title="Delete All"
                                        data-url="{{ url('/delete-all-orders') }}"><i class="bi bi-trash"></i> <span>Delete
                                            All</span></button>
                                </div>
                            </div>
                            <hr>

                            <form method="GET" action="">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">

                                    <!-- Date Filters -->
                                    <div class="d-flex flex-wrap gap-3">
                                        <div style="min-width: 200px;">
                                            <label for="start_date" class="form-label">Start Date</label>
                                            <input type="date" name="start_date" id="start_date"
                                                value="{{ request('start_date') }}" class="form-control">
                                        </div>

                                        <div style="min-width: 200px;">
                                            <label for="end_date" class="form-label">End Date</label>
                                            <input type="date" name="end_date" id="end_date"
                                                value="{{ request('end_date') }}" class="form-control">
                                        </div>
                                    </div>

                                    <!-- Pagination + Search + Button -->
                                    <div class="d-flex flex-wrap gap-3 align-items-end">
                                        <div style="min-width: 150px;">
                                            <label for="page_length" class="form-label">Items per page</label>
                                            <select name="page_length" id="page_length" class="form-control">
                                                @foreach ([10, 25, 50, 100] as $size)
                                                    <option value="{{ $size }}"
                                                        {{ request('page_length', 10) == $size ? 'selected' : '' }}>
                                                        {{ $size }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div style="min-width: 200px;">
                                            <label for="search" class="form-label">Search</label>
                                            <input type="text" name="search" id="search"
                                                value="{{ request('search') }}" class="form-control">
                                        </div>

                                        <div>
                                            <button type="submit" class="btn btn-primary">Apply</button>
                                        </div>
                                    </div>
                                </div>
                            </form>





                            <div class="table table-responsive">
                                <table id="orders-tablee" class="table table-striped custom-table2" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="users-master"></th>
                                            @if (!$entries)
                                                <th>Order Code</th>
                                            @endif
                                            <th>Customer</th>
                                            <th>Delivery Due Date</th>
                                            {{-- <th>Delivery Address</th> --}}
                                            <th>Form Name</th>
                                            <th>Staff Assigned</th>
                                            <th>Agent</th>
                                            <th>Message</th>
                                            <th>Date Created</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @if (count($orders) > 0)
                                            @foreach ($orders as $key => $order)
                                                @if (isset($order->customer_id) && isset($order->customer->id))
                                                    <tr
                                                        id="tr_{{ isset($order->customer_id) ? $order->customer->id : '' }}">

                                                        <td><input type="checkbox" class="sub_chk"
                                                                data-id="{{ isset($order->customer_id) ? $order->customer->id : '' }}"
                                                                data-order_id="{{ $order->id }}"></td>
                                                        @if (!$entries)
                                                            <td>{{ $order->orderCode($order->id) }}</td>
                                                        @endif

                                                        <td>{{ $order->customer_id ? $order->customer->firstname : 'No response' }}
                                                            {{ $order->customer_id ? $order->customer->lastname : '' }}
                                                        </td>

                                                        <!--Delivery Due Date-->
                                                        <td>

                                                            @if (isset($order->expected_delivery_date))
                                                                {{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('D, jS M Y') }}
                                                            @else
                                                                Not set
                                                            @endif

                                                            <span class="badge badge-dark"
                                                                onclick="changeDeliveryDateModal('{{ $order->id }}', '{{ $order->orderCode($order->id) }}',  `{{ ucFirst(str_replace('_', ' ', $order->status)) }}`, '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                                                                    '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}')"
                                                                style="cursor: pointer;">
                                                                <i class="bi bi-plus"></i> <span>Change Delivery
                                                                    Date</span></span>


                                                        </td>

                                                        {{-- <td>{{ $order->customer_id ? $order->customer->delivery_address : 'No response' }}
                                                        </td> --}}

                                                        <td>{{ $order->formHolder ? $order->formHolder->name : 'No form details' }}
                                                        </td>

                                                        <!--Assign Staff-->
                                                        @if (isset($order->staff_assigned_id))
                                                            <td>
                                                                {{ $order->staff->name }} <br>
                                                                <span class="badge badge-dark"
                                                                    onclick="changeStaffModal('{{ $order->id }}')"
                                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                                    data-bs-title="Change Staff" style="cursor: pointer">
                                                                    <i class="bi bi-plus"></i> <span>Change
                                                                        Staff</span></span>
                                                            </td>
                                                        @else
                                                            <td style="width: 120px">
                                                                <span class="badge badge-success"
                                                                    onclick="addStaffModal('{{ $order->id }}')"
                                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                                    data-bs-title="Assign Staff" style="cursor: pointer">
                                                                    <i class="bi bi-plus"></i> <span>Assign
                                                                        Staff</span></span>
                                                            </td>
                                                        @endif

                                                        <!--Assign Agent-->
                                                        @if (isset($order->agent_assigned_id))
                                                            <td>
                                                                {{ $order->agent->name }} <br>
                                                                <span class="badge badge-dark"
                                                                    onclick="changeAgentModal('{{ $order->id }}')"
                                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                                    data-bs-title="Change Agent" style="cursor: pointer">
                                                                    <i class="bi bi-plus"></i> <span>Change
                                                                        Agent</span></span>
                                                            </td>
                                                        @else
                                                            <td style="width: 120px">
                                                                <span class="badge badge-success"
                                                                    onclick="addAgentModal('{{ $order->id }}')"
                                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                                    data-bs-title="Assign Agent" style="cursor: pointer">
                                                                    <i class="bi bi-plus"></i> <span>Assign
                                                                        Agent</span></span>
                                                            </td>
                                                        @endif

                                                        <!--messages--->
                                                        <td>
                                                            <div class="d-flex justify-content-between border">
                                                                <a href="javascript:void(0);"
                                                                    onclick="whatsappModal({{ json_encode($order) }}, '{{ $order->whatsappNewOrderMessage($order) }}')"
                                                                    class="btn btn-success btn-sm rounded-circle m-1 whatsapp-icon"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    data-bs-title="Whatsapp">
                                                                    <i class="bi bi-whatsapp"></i>
                                                                    @if ($order->whatsappMessages() !== '')
                                                                        <span
                                                                            class="badge badge-dark whatsapp-icon-number">{{ $order->whatsappMessages()->count() }}</span>
                                                                    @endif
                                                                </a>
                                                                @if ($order->whatsappMessages() !== '')
                                                                    <a href="{{ route('sentWhatsappMessage', $order->unique_key) }}"
                                                                        class="btn btn-success btn-sm rounded-circle m-1 whatsapp-icon"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        data-bs-title="View Whatsapp Messages">
                                                                        <i class="bi bi-eye"></i></a>
                                                                @endif
                                                            </div>

                                                            <div class="d-flex justify-content-between border">
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-info btn-sm rounded-circle m-1 whatsapp-icon"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    data-bs-title="Whatsapp">
                                                                    <i class="bi bi-chat"></i>
                                                                    @if ($order->emailMessages() !== '')
                                                                        <span
                                                                            class="badge badge-dark whatsapp-icon-number">{{ $order->emailMessages()->count() }}</span>
                                                                    @endif
                                                                </a>
                                                                @if ($order->emailMessages() !== '')
                                                                    <a href="javascript:void(0);"
                                                                        class="btn btn-info btn-sm rounded-circle m-1 whatsapp-icon"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        data-bs-title="View Email Messages">
                                                                        <i class="bi bi-eye"></i></a>
                                                                @endif
                                                            </div>

                                                        </td>

                                                        <td>{{ $order->created_at->format('Y-m-d') }}</td>



                                                        <td>
                                                            <div class="btn-group">
                                                                @if (!isset($order->status) || $order->status == 'new')
                                                                    <button type="button"
                                                                        class="btn btn-info btn-sm dropdown-toggle rounded-pill fw-bolder"
                                                                        data-bs-toggle="dropdown"
                                                                        style="font-size: 10px;">
                                                                        <span>New Order</span>
                                                                    </button>
                                                                @elseif($order->status == 'pending')
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm dropdown-toggle rounded-pill fw-bolder"
                                                                        data-bs-toggle="dropdown"
                                                                        style="font-size: 10px;">
                                                                        <span>Pending Order</span>
                                                                    </button>
                                                                    {{-- @elseif($order->status == 'order_confirmed')
                                                                    <button type="button"
                                                                        class="btn btn-primary btn-sm dropdown-toggle rounded-pill fw-bolder"
                                                                        data-bs-toggle="dropdown"
                                                                        style="font-size: 10px;">
                                                                        <span>Order Confirmed</span>
                                                                    </button> --}}
                                                                @elseif($order->status == 'rescheduled_order')
                                                                    <button type="button"
                                                                        class="btn btn-warning btn-sm dropdown-toggle rounded-pill fw-bolder"
                                                                        data-bs-toggle="dropdown"
                                                                        style="font-size: 10px;">
                                                                        <span>Order Rescheduled</span>
                                                                    </button>
                                                                @elseif($order->status == 'order_sent_out')
                                                                    <button type="button"
                                                                        class="btn btn-info btn-sm dropdown-toggle rounded-pill fw-bolder"
                                                                        data-bs-toggle="dropdown"
                                                                        style="font-size: 10px;">
                                                                        <span>Order Sent Out for Delivery</span>
                                                                    </button>
                                                                @elseif(
                                                                    $order->status == 'delivery_attempted_1' ||
                                                                        $order->status == 'delivery_attempted_2' ||
                                                                        $order->status == 'delivery_attempted_3')
                                                                    <button type="button"
                                                                        class="btn btn-secondary btn-sm dropdown-toggle rounded-pill fw-bolder"
                                                                        data-bs-toggle="dropdown"
                                                                        style="font-size: 10px;">
                                                                        <span>Delivery Attempted</span>
                                                                    </button>
                                                                @elseif($order->status == 'cancelled_admin')
                                                                    <button type="button"
                                                                        class="btn btn-dark btn-sm dropdown-toggle rounded-pill fw-bolder"
                                                                        data-bs-toggle="dropdown"
                                                                        style="font-size: 10px;">
                                                                        <span>Order Cancelled</span>
                                                                    </button>
                                                                    {{-- @elseif($order->status == 'customer_unreachable')
                                                                    <button type="button"
                                                                        class="btn btn-dark btn-sm dropdown-toggle rounded-pill fw-bolder"
                                                                        data-bs-toggle="dropdown"
                                                                        style="font-size: 10px;">
                                                                        <span>Customer Unreachable</span>
                                                                    </button>
                                                                @elseif($order->status == 'cancelled_customer')
                                                                    <button type="button"
                                                                        class="btn btn-dark btn-sm dropdown-toggle rounded-pill fw-bolder"
                                                                        data-bs-toggle="dropdown"
                                                                        style="font-size: 10px;">
                                                                        <span>Order Cancelled by Customer</span>
                                                                    </button>
                                                                @elseif($order->status == 'rejected_customer')
                                                                    <button type="button"
                                                                        class="btn btn-dark btn-sm dropdown-toggle rounded-pill fw-bolder"
                                                                        data-bs-toggle="dropdown"
                                                                        style="font-size: 10px;">
                                                                        <span>Order Rejected by Customer</span>
                                                                    </button> --}}
                                                                @elseif($order->status == 'duplicate_order')
                                                                    <button type="button"
                                                                        class="btn btn-dark btn-sm dropdown-toggle rounded-pill fw-bolder"
                                                                        data-bs-toggle="dropdown"
                                                                        style="font-size: 10px;">
                                                                        <span>Order Cancelled Due to Duplicate
                                                                            Order</span>
                                                                    </button>
                                                                @elseif($order->status == 'delivered_not_remitted')
                                                                    <button type="button"
                                                                        class="btn btn-warning btn-sm dropdown-toggle rounded-pill fw-bolder"
                                                                        data-bs-toggle="dropdown"
                                                                        style="font-size: 10px;">
                                                                        <span>Delivered Not Remitted</span>
                                                                    </button>
                                                                @elseif($order->status == 'delivered_and_remitted')
                                                                    <button type="button"
                                                                        class="btn btn-success btn-sm dropdown-toggle rounded-pill fw-bolder"
                                                                        data-bs-toggle="dropdown"
                                                                        style="font-size: 10px;">
                                                                        <span>Delivered & Remitted</span>
                                                                    </button>
                                                                @endif
                                                                {{-- <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('updateOrderStatus', [$order->unique_key, 'new']) }}">New
                                                                            Order</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('updateOrderStatus', [$order->unique_key, 'pending']) }}">Pending
                                                                            Order</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('updateOrderStatus', [$order->unique_key, 'order_confirmed']) }}">Order
                                                                            Confirmed</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            onclick="UpdateOrderStatus('{{ $order->id }}', '{{ $order->orderCode($order->id) }}', `{{ ucFirst(str_replace('_', ' ', $order->status)) }}`, `{{ $order->customer->firstname . ' ' . $order->customer->lastname }}`,
                                                                 '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}')">Order
                                                                            Rescheduled</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('updateOrderStatus', [$order->unique_key, 'order_sent_out']) }}">Order
                                                                            Sent Out for Delivery</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('updateOrderStatus', [$order->unique_key, 'delivery_attempted_1']) }}">Delivery
                                                                            Attempted 1</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('updateOrderStatus', [$order->unique_key, 'delivery_attempted_2']) }}">Delivery
                                                                            Attempted 2</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('updateOrderStatus', [$order->unique_key, 'delivery_attempted_3']) }}">Delivery
                                                                            Attempted 3</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('updateOrderStatus', [$order->unique_key, 'cancelled_admin']) }}">Order
                                                                            Cancelled by Admin</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('updateOrderStatus', [$order->unique_key, 'customer_unreachable']) }}">Customer
                                                                            Unreachable</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('updateOrderStatus', [$order->unique_key, 'cancelled_customer']) }}">Order
                                                                            Cancelled by Customer</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('updateOrderStatus', [$order->unique_key, 'rejected_customer']) }}">Order
                                                                            Rejected by Customer</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('updateOrderStatus', [$order->unique_key, 'duplicate_order']) }}">Order
                                                                            Cancelled Due to Duplicate Order</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('updateOrderStatus', [$order->unique_key, 'delivered_not_remitted']) }}">Delivered
                                                                            Not Remitted</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('updateOrderStatus', [$order->unique_key, 'delivered_and_remitted']) }}">Delivered
                                                                            & Remitted</a></li>
                                                                </ul> --}}
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item"
                                                                            onclick="UpdateOrderStatus('{{ $order->id }}',
                                      '{{ $order->orderCode($order->id) }}',
                                      '{{ ucFirst(str_replace('_', ' ', $order->status)) }}',
                                      '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                                      '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}',
                                      'new')">
                                                                            New Order
                                                                        </a></li>

                                                                    <li><a class="dropdown-item"
                                                                            onclick="UpdateOrderStatus('{{ $order->id }}',
                                      '{{ $order->orderCode($order->id) }}',
                                      '{{ ucFirst(str_replace('_', ' ', $order->status)) }}',
                                      '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                                      '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}',
                                      'pending')">
                                                                            Pending Order
                                                                        </a></li>

                                                                    <li><a class="dropdown-item"
                                                                            onclick="UpdateOrderStatus('{{ $order->id }}',
                                      '{{ $order->orderCode($order->id) }}',
                                      '{{ ucFirst(str_replace('_', ' ', $order->status)) }}',
                                      '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                                      '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}',
                                      'order_confirmed')">
                                                                            Order Confirmed
                                                                        </a></li>

                                                                    <li><a class="dropdown-item"
                                                                            onclick="UpdateOrderStatus('{{ $order->id }}',
                                      '{{ $order->orderCode($order->id) }}',
                                      '{{ ucFirst(str_replace('_', ' ', $order->status)) }}',
                                      '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                                      '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}',
                                      'rescheduled_order')">
                                                                            Order Rescheduled
                                                                        </a></li>

                                                                    <li><a class="dropdown-item"
                                                                            onclick="UpdateOrderStatus('{{ $order->id }}',
                                      '{{ $order->orderCode($order->id) }}',
                                      '{{ ucFirst(str_replace('_', ' ', $order->status)) }}',
                                      '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                                      '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}',
                                      'order_sent_out')">
                                                                            Order Sent Out for Delivery
                                                                        </a></li>

                                                                    <li><a class="dropdown-item"
                                                                            onclick="UpdateOrderStatus('{{ $order->id }}',
                                      '{{ $order->orderCode($order->id) }}',
                                      '{{ ucFirst(str_replace('_', ' ', $order->status)) }}',
                                      '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                                      '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}',
                                      'delivery_attempted_1')">
                                                                            Delivery Attempted 1
                                                                        </a></li>

                                                                    <li><a class="dropdown-item"
                                                                            onclick="UpdateOrderStatus('{{ $order->id }}',
                                      '{{ $order->orderCode($order->id) }}',
                                      '{{ ucFirst(str_replace('_', ' ', $order->status)) }}',
                                      '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                                      '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}',
                                      'delivery_attempted_2')">
                                                                            Delivery Attempted 2
                                                                        </a></li>

                                                                    <li><a class="dropdown-item"
                                                                            onclick="UpdateOrderStatus('{{ $order->id }}',
                                      '{{ $order->orderCode($order->id) }}',
                                      '{{ ucFirst(str_replace('_', ' ', $order->status)) }}',
                                      '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                                      '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}',
                                      'delivery_attempted_3')">
                                                                            Delivery Attempted 3
                                                                        </a></li>

                                                                    <li><a class="dropdown-item"
                                                                            onclick="UpdateOrderStatus('{{ $order->id }}',
                                      '{{ $order->orderCode($order->id) }}',
                                      '{{ ucFirst(str_replace('_', ' ', $order->status)) }}',
                                      '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                                      '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}',
                                      'cancelled_admin')">
                                                                            Order Cancelled
                                                                        </a></li>
                                                                    {{-- 
                                                                    <li><a class="dropdown-item"
                                                                            onclick="UpdateOrderStatus('{{ $order->id }}',
                                      '{{ $order->orderCode($order->id) }}',
                                      '{{ ucFirst(str_replace('_', ' ', $order->status)) }}',
                                      '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                                      '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}',
                                      'customer_unreachable')">
                                                                            Customer Unreachable
                                                                        </a></li>

                                                                    <li><a class="dropdown-item"
                                                                            onclick="UpdateOrderStatus('{{ $order->id }}',
                                      '{{ $order->orderCode($order->id) }}',
                                      '{{ ucFirst(str_replace('_', ' ', $order->status)) }}',
                                      '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                                      '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}',
                                      'cancelled_customer')">
                                                                            Order Cancelled by Customer
                                                                        </a></li>

                                                                    <li><a class="dropdown-item"
                                                                            onclick="UpdateOrderStatus('{{ $order->id }}',
                                      '{{ $order->orderCode($order->id) }}',
                                      '{{ ucFirst(str_replace('_', ' ', $order->status)) }}',
                                      '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                                      '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}',
                                      'rejected_customer')">
                                                                            Order Rejected by Customer
                                                                        </a></li> --}}

                                                                    <li><a class="dropdown-item"
                                                                            onclick="UpdateOrderStatus('{{ $order->id }}',
                                      '{{ $order->orderCode($order->id) }}',
                                      '{{ ucFirst(str_replace('_', ' ', $order->status)) }}',
                                      '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                                      '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}',
                                      'duplicate_order')">
                                                                            Order Cancelled Due to Duplicate Order
                                                                        </a></li>

                                                                    <li><a class="dropdown-item"
                                                                            onclick="UpdateOrderStatus('{{ $order->id }}',
                                      '{{ $order->orderCode($order->id) }}',
                                      '{{ ucFirst(str_replace('_', ' ', $order->status)) }}',
                                      '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                                      '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}',
                                      'delivered_not_remitted')">
                                                                            Delivered Not Remitted
                                                                        </a></li>

                                                                    <li><a class="dropdown-item"
                                                                            onclick="UpdateOrderStatus('{{ $order->id }}',
                                      '{{ $order->orderCode($order->id) }}',
                                      '{{ ucFirst(str_replace('_', ' ', $order->status)) }}',
                                      '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                                      '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}',
                                      'delivered_and_remitted')">
                                                                            Delivered & Remitted
                                                                        </a></li>
                                                                </ul>

                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="mb-1"><a class="btn btn-success btn-sm"
                                                                    href="{{ route('singleOrder', $order->unique_key) }}">View</a>
                                                            </div>

                                                            @if (isset($order->customer_id))
                                                                <div class="mb-1"><a
                                                                        href="{{ route('editOrder', $order->unique_key) }}"
                                                                        class="btn-info btn-sm w-100 p-1">Edit</a>
                                                                </div>
                                                            @endif
                                                            <div><a href="{{ route('deleteOrder', $order->unique_key) }}"
                                                                    onclick="return confirm('Are you sure?')"
                                                                    class="btn-danger btn-sm w-100 p-1">Delete</a>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                            <!-- Laravel Pagination Links -->
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <p class="text-muted">
                                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of
                                        {{ $orders->total() }} results
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end">
                                        {{ $orders->links('pagination.custom-pagination') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- Modal addAgentModal -->
    <div class="modal fade" id="addAgentModal" tabindex="-1" aria-labelledby="addAgentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addAgentModalLabel">Assign Agent</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('assignAgentToOrder') }}" method="POST">@csrf
                    <div class="modal-body">

                        <input type="hidden" id="order_id" class="order_id" name="order_id" value="">
                        <div class="d-grid mb-3">
                            <label for="">Select Agent</label>
                            <select name="agent_id" id="" data-live-search="true"
                                class="custom-select form-control border border-dark">
                                <option value="">Nothing Selected</option>

                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->name }} | {{ $agent->id }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary addAgentBtn">Assign Agent</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal changeAgentModal -->
    <div class="modal fade" id="changeAgentModal" tabindex="-1" aria-labelledby="changeAgentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="changeAgentModalLabel">Change Assigned Agent</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('assignAgentToOrder') }}" method="POST">@csrf
                    <div class="modal-body">

                        <input type="hidden" id="order_id" class="order_id" name="order_id" value="">
                        <div class="d-grid mb-3">
                            <label for="">Select Agent</label>
                            <select name="agent_id" id="changeAgentModalSelect" data-live-search="true"
                                class="custom-select form-control border border-dark">
                                <option value="" selected>Nothing Selected</option>

                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->name }} | {{ $agent->id }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary addAgentBtn">Assign Agent</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal addStaffModal -->
    <div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addStaffModalLabel">Assign Agent</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('assignStaffToOrder') }}" method="POST">@csrf
                    <div class="modal-body">

                        <input type="hidden" id="order_id" class="order_id" name="order_id" value="">
                        <div class="d-grid mb-3">
                            <label for="">Select Staff</label>
                            <select name="staff_id" id="" data-live-search="true"
                                class="custom-select form-control border border-dark">
                                <option value="">Nothing Selected</option>

                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }} | {{ $staff->id }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary addAgentBtn">Assign Staff</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal changeStaffModal -->
    <div class="modal fade" id="changeStaffModal" tabindex="-1" aria-labelledby="changeStaffModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="changeStaffModalLabel">Change Assigned Agent</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('assignStaffToOrder') }}" method="POST">@csrf
                    <div class="modal-body">

                        <input type="hidden" id="order_id" class="order_id" name="order_id" value="">
                        <div class="d-grid mb-3">
                            <label for="">Select Agent</label>
                            <select name="staff_id" id="changeStaffModalSelect" data-live-search="true"
                                class="custom-select form-control border border-dark">
                                <option value="" selected>Nothing Selected</option>

                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }} | {{ $staff->id }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary addAgentBtn">Assign Agent</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--sendMailModal -->
    <div class="modal fade" id="sendMailModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="sendMailModalLabel">Send Mail to Customers</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="sendMailForm" action="{{ route('sendCustomerMail') }}" method="POST">@csrf
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="user_id" value="">
                        <input type="hidden" name="mail_customer_order_id" id="mail_customer_order_id" value="">

                        <div class="d-grid mb-3">
                            <label for="">Topic</label>
                            <input type="text" name="topic" class="form-control" placeholder="">
                        </div>

                        <div class="d-grid mb-2">
                            <label for="">Message</label>
                            <textarea name="message" id="" class="form-control" cols="30" rows="10"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary sendMailBtn">Send Message</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!--whatsappModal -->
    <div class="modal fade" id="whatsappModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="whatsappModalLabel">Send Whatsapp to Customer: <span></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="sendWhatsappForm" action="{{ route('sendCustomerWhatsapp') }}" method="POST">@csrf
                    <div class="modal-body">
                        <input type="hidden" name="whatsapp_customer_id" id="whatsapp_customer_id" value="">
                        <input type="hidden" name="whatsapp_customer_order_id" id="whatsapp_customer_order_id"
                            value="">

                        <div class="d-grid mb-2">
                            <label for="">Phone format: 23480xxxx</label>
                            <input type="text" name="recepient_phone_number" id="recepient_phone_number"
                                class="form-control">
                        </div>

                        <div class="d-grid mb-2">
                            <label for="">Message</label>
                            <textarea name="message" id="whatsapp_message" class="form-control" cols="30" rows="10"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary sendWhatsappBtn">Send Message</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Modal changeDeliveryDateModal -->
    <div class="modal fade" id="changeDeliveryDateModal" tabindex="-1" aria-labelledby="changeDeliveryDateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title fs-7">Change Delivery Date <br> Order: <span class="order_code"
                            style="color: #04512d"></span> &nbsp; Order Status: <span class="order_status"
                            style="color: #04512d"></span>
                        <br>Customer: <span class="order_customer text-success" style="color: #04512d"></span>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('updateOrderDateStatus') }}" method="POST">@csrf
                    <div class="modal-body">

                        <input type="hidden" id="order_id" class="order_id" name="order_id" value="">
                        <div class="d-grid mb-3">
                            <label for="">Select Delivery Date</label>
                            <input type="text" name="order_delivery_date"
                                class="order_delivery_date form-control @error('order_delivery_date') is-invalid @enderror"
                                id="" value="">
                        </div>


                        <div class="d-grid mb-3">
                            <label for="">Update Order Status | Optional</label>
                            <select name="order_status" data-live-search="true"
                                class="custom-select form-control border border-dark order-status-selector">
                                <option value="" selected>Nothing Selected</option>

                                <option value="new">New</option>
                                <option value="pending">Pending</option>
                                <option value="cancelled_by_admin">Cancelled</option>
                                <option value="delivered_not_remitted">Delivered Not Remitted</option>
                                <option value="delivered_and_remitted">Delivered and Remitted</option>
                                <option value="rescheduled_order">Rescheduled Order</option>
                                {{-- <option value="order_in_transit">Order in transit</option> --}}
                            </select>
                        </div>

                        <div class="d-grid mb-3">
                            <label for="">Note | Optional</label>
                            <textarea name="order_note" id="" cols="30" rows="3" class="form-control"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary addAgentBtn">Update Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reschdeduleOrderModal" tabindex="-1" aria-labelledby="reschdeduleOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title fs-7">Reschdedule Order <br> Order: <span class="order_code"
                            style="color: #04512d"></span> &nbsp; Order Status: <span class="order_status"
                            style="color: #04512d"></span>
                        <br>Customer: <span class="order_customer text-success" style="color: #04512d"></span>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('updateOrderDateStatus') }}" method="POST">@csrf
                    <div class="modal-body">

                        <input type="hidden" id="order_id" class="order_id" name="order_id" value="">
                        <div class="d-grid mb-3">
                            <label for="">Reschdeduled Date</label>
                            <input type="text" name="order_delivery_date"
                                class="order_delivery_date form-control @error('order_delivery_date') is-invalid @enderror"
                                id="" value="">
                        </div>
                        <div class="d-none">
                            <label for="">Update Order Status | Optional</label>
                            <select name="order_status" data-live-search="true"
                                class="custom-select form-control border border-dark order-status-selector">
                                <option value="">Nothing Selected</option>

                                <option value="new">New</option>
                                <option value="pending">Pending</option>
                                <option value="cancelled_by_admin">Cancelled</option>
                                <option value="delivered_not_remitted">Delivered Not Remitted</option>
                                <option value="delivered_and_remitted">Delivered and Remitted</option>
                                <option value="rescheduled_order" selected>Rescheduled Order</option>
                                {{-- <option value="order_in_transit">Order in transit</option> --}}
                            </select>
                        </div>

                        <div class="d-grid mb-3">
                            <label for="">Note | Optional</label>
                            <textarea name="order_note" id="" cols="30" rows="3" class="form-control"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary addAgentBtn">Reschdedule Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updateOrderModal" tabindex="-1" aria-labelledby="updateOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title fs-7">Update Order <br> Order: <span class="order_code"
                            style="color: #04512d"></span> &nbsp; Order Status: <span class="order_status"
                            style="color: #04512d"></span>
                        <br>Customer: <span class="order_customer text-success" style="color: #04512d"></span>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('updateOrderDateStatusWithMessage') }}" method="POST">@csrf
                    <div class="modal-body">

                        <input type="hidden" id="order_id" class="order_id" name="order_id" value="">
                        <div class="d-grid mb-3 date-container">
                            <label for="">Reschdeduled Date</label>
                            <input type="text" name="order_delivery_date"
                                class="order_delivery_date form-control @error('order_delivery_date') is-invalid @enderror"
                                id="" value="">
                        </div>

                        <input type="hidden" name="order_status" class="new_status">



                        <div class="d-grid mb-3">
                            <label for="">Note | Optional</label>
                            <textarea name="order_note" id="" cols="30" rows="3" class="form-control"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary addAgentBtn">Update Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('extra_js')
    <link href="{{ asset('/assets/css/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/assets/js/jquery.datetimepicker.min.js') }}"></script>
    <!--dateplugin--->
    <script>
        jQuery('.order_delivery_date').datetimepicker({
            datepicker: true,
            //showPeriod: true,
            format: 'Y-m-d',
            timepicker: false,
        });

        jQuery('.order_date').datetimepicker({
            datepicker: true,
            //showPeriod: true,
            format: 'Y-m-d',
            timepicker: false,
        });
    </script>

    <!---add & change agent, change delivery date---->
    <script>
        function addAgentModal($orderId = "") {
            $('#addAgentModal').modal("show");
            $('.order_id').val($orderId);
        }

        function changeAgentModal($orderId = "") {
            $('#changeAgentModal').modal("show");
            $('.order_id').val($orderId);
        }

        function addStaffModal($orderId = "") {
            $('#addStaffModal').modal("show");
            $('.order_id').val($orderId);
        }

        function changeStaffModal($orderId = "") {
            $('#changeStaffModal').modal("show");
            $('.order_id').val($orderId);
        }



        function changeDeliveryDateModal($orderId = "", $orderCode = "", $orderStatus = "", $orderCustomer = "",
            $orderDeliveryDate = "") {

            $('#changeDeliveryDateModal').modal("show");
            $('.order_id').val($orderId);
            $('.order_code').html($orderCode);
            $('.order_status').html($orderStatus);
            $('.order_customer').html($orderCustomer);
            $('.order_delivery_date').val($orderDeliveryDate);
        }

        function UpdateOrderStatus($orderId = "", $orderCode = "", $orderStatus = "", $orderCustomer = "",
            $orderDeliveryDate = "", $newStatus = "") {

            $('#updateOrderModal').modal("show");
            $('.order_id').val($orderId);
            $('.order_code').html($orderCode);
            $('.order_status').html($orderStatus);
            $('.order_customer').html($orderCustomer);
            $('.order_delivery_date').val($orderDeliveryDate);
            $('.new_status').val($newStatus);

            if ($newStatus != 'rescheduled_order') {
                $(".date-container").addClass("d-none");
            } else {
                $(".date-container").removeClass("d-none");
            }
        }

        function rescheduledOrder($orderId = "", $orderCode = "", $orderStatus = "", $orderCustomer = "",
            $orderDeliveryDate = "") {

            $('#reschdeduleOrderModal').modal("show");
            $('.order_id').val($orderId);
            $('.order_code').html($orderCode);
            $('.order_status').html($orderStatus);
            $('.order_customer').html($orderCustomer);
            $('.order_delivery_date').val($orderDeliveryDate);
        }
    </script>

    <!---sending multi-mail---->
    <script>
        $('.rescheduled-order').on('click', function(e) {
            $('#changeDeliveryDateModal').modal("show");
        });
        //toggle all checks
        $('#users-master').on('click', function(e) {
            if ($(this).is(':checked', true)) {
                $(".sub_chk").prop('checked', true);
            } else {
                $(".sub_chk").prop('checked', false);
            }
        });

        //mail_all
        $('.mail_all').on('click', function(e) {

            var allVals = [];
            var allOrderIds = [];
            $(".sub_chk:checked").each(function() {
                allVals.push($(this).attr('data-id')); //['2', '1']
                allOrderIds.push($(this).attr('data-order_id')); //['2', '1']
            });

            //check if any is checked
            if (allVals.length <= 0) {
                alert("Please select customer(s) to mail.");
            } else {
                var check = confirm("Are you sure you want to mail this customer(s)?");
                if (check == true) {

                    //var join_selected_values = allVals.join(","); //2,1
                    console.log(allVals) //[2,1]
                    $('#sendMailModal').modal('show');
                    $('#user_id').val(allVals);
                    $('#mail_customer_order_id').val(allOrderIds);

                }
            }
        });
    </script>

    <!---sending whatsapp---->
    <script>
        function whatsappModal($order = "", $message = "") {
            //console.log($orderId);
            $('#whatsappModal').modal("show");
            $('#whatsapp_customer_id').val($order.customer.id);
            $('#whatsapp_customer_order_id').val($order.id);
            $('#whatsapp_message').val($message);
            $part = $order.customer.whatsapp_phone_number.substring(0, 1);
            if ($part == '0') {
                $whatsapp_phone_number = '234' + $order.customer.whatsapp_phone_number.substring(1);
                $('#recepient_phone_number').val($whatsapp_phone_number);
            } else {
                $('#recepient_phone_number').val($order.customer.whatsapp_phone_number);
            }
            $name = $order.customer.firstname + ' ' + $order.customer.lastname;
            $('#whatsappModalLabel span').text($name);
            //console.log($whatsapp_phone_number)

        }
    </script>

    <!---network connect b4 sending whatsapp---->
    <script>
        $('.sendWhatsappBtn').on('click', function(e) {
            e.preventDefault();
            if (window.navigator.onLine) {
                // console.log('online')
                $('#sendWhatsappForm').submit();
            } else {
                $('#whatsappModal').modal("hide");
                alert('No Internet Connection');
                // console.log('offline')
            }

        });
    </script>

    <?php if($entries) : ?>
    {{-- <script>
        var minDate, maxDate;

        // Custom filtering function which will search data in column four between two values(dates)
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var min = minDate.val();
                var max = maxDate.val();
                var date = new Date(data[6]);

                if (
                    (min === null && max === null) ||
                    (min === null && date <= max) ||
                    (min <= date && max === null) ||
                    (min <= date && date <= max)
                ) {
                    return true;
                }
                return false;
            }
        ); --}}
    </script>
    <?php endif ?>

    <?php if(!$entries) : ?>
    {{-- <script>
        var minDate, maxDate;

        // Custom filtering function which will search data in column four between two values(dates)
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var min = minDate.val();
                var max = maxDate.val();
                var date = new Date(data[7]);

                if (
                    (min === null && max === null) ||
                    (min === null && date <= max) ||
                    (min <= date && max === null) ||
                    (min <= date && date <= max)
                ) {
                    return true;
                }
                return false;
            }
        );
    </script> --}}
    <?php endif ?>

    <script>
        //delete_all
        $('.delete_all').on('click', function(e) {

            var allVals = [];
            $(".sub_chk:checked").each(function() {
                allVals.push($(this).attr('data-order_id'));
            });

            //check if any is checked
            if (allVals.length <= 0) {
                alert("Please select row(s) to delete.");
            } else {
                var check = confirm("Are you sure you want to delete this row?");
                if (check == true) {

                    var join_selected_values = allVals.join(",");

                    $.ajax({
                        url: $(this).data('url'),
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: 'ids=' + join_selected_values,
                        success: function(data) {
                            if (data['success']) {
                                $(".sub_chk:checked").each(function() {
                                    $(this).parents("tr").remove();
                                });
                                alert(data['success']);
                            } else if (data['error']) {
                                alert(data['error']);
                            } else {
                                alert('Whoops Something went wrong!!');
                            }
                        },
                        error: function(data) {
                            alert(data.responseText);
                        }
                    });


                    $.each(allVals, function(index, value) {
                        $('table tr').filter("[data-row-id='" + value + "']").remove();
                    });
                }
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            var ordersTable = $('#orders-table-js').DataTable({
                pageLength: 10, // Default rows per page
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ], // Page length options
                ordering: true, // Enable sorting
                searching: true, // Enable search box
                paging: true, // Enable pagination
                info: true, // Show table information
                responsive: true, // Make table responsive
            });

            // Custom filtering function for date range
            $.fn.dataTable.ext.search.push(function(settings, searchData) {
                var min = $('#start_date').val();
                var max = $('#end_date').val();
                var dateStr = searchData[8]; // "Date Created" column (0-based index)

                if (!dateStr) return false; // If no date found, exclude row

                var date = new Date(dateStr); // Convert "YYYY-MM-DD" to Date object
                var minDate = min ? new Date(min) : null;
                var maxDate = max ? new Date(max) : null;

                if ((!minDate || date >= minDate) && (!maxDate || date <= maxDate)) {
                    return true;
                }
                return false;
            });

            // $.fn.dataTable.ext.search.push(function(settings, searchData) {
            //     var min = $('#min').val();
            //     var max = $('#max').val();

            //     var date = searchData[8];

            //     if ((min == '' && max == '') ||
            //         (min == '' && date <= max) ||
            //         (min <= date && max == '') ||
            //         (min <= date && date <= max)) {
            //         return true;
            //     }

            //     return false;
            // });


            $('#start_date, #end_date').on('keyup change', function() {
                ordersTable.draw();
            });

            // Apply filtering when date inputs change
            // $('#min, #max').on('change', function() {


            //     ordersTable.draw();
            // });
        });
    </script>
@endsection
