@extends('layouts.design')
@section('title')
    View Order
@endsection

@section('extra_css')
    <style>
        .extra_options {
            cursor: pointer;
        }

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
    </style>
@endsection
@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>
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
                @endif Order Information
            </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('allOrders') }}">Orders</a></li>
                    <li class="breadcrumb-item active">Order Information
                    <li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <hr>
        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3 text-center" role="alert">
                {{ Session::get('success') }}
                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close"
                    style="float: right; border-radius: 50%;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <section>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <div class="row g-3 m-1">
                            <div class="col-lg-3 col-md-12">
                                @if (isset($order->customer_id) && isset($order->customer->id) && isset($order->expected_delivery_date))
                                    <div class="badge badge-dark extra_options"
                                        onclick="changeDeliveryDateModal('{{ $order->id }}', '{{ $order->orderCode($order->id) }}', '{{ ucFirst($order->status) }}', '{{ $order->customer->firstname . ' ' . $order->customer->lastname }}',
                        '{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') }}')">
                                        Change Delivery Date</div>
                                @else
                                    <span class="text-dark">No Delivery Date</span>
                                @endif
                            </div>
                            <div class="col-lg-3 col-md-12 extra_options">
                                @if (isset($order->staff_assigned_id))
                                    <div class="badge badge-dark" onclick="changeStaffModal('{{ $order->id }}')"
                                        data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Change Staff">
                                        Change Staff</div>
                                @else
                                    <div class="badge badge-dark" onclick="addStaffModal('{{ $order->id }}')"
                                        data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Assign Staff">
                                        Assign Staff</div>
                                @endif
                            </div>
                            <div class="col-lg-3 col-md-12 extra_options">
                                @if (isset($order->agent_assigned_id))
                                    <div class="badge badge-dark" onclick="changeAgentModal('{{ $order->id }}')"
                                        data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Change Agent">
                                        Change Agent</div>
                                @else
                                    <div class="badge badge-dark" onclick="addAgentModal('{{ $order->id }}')"
                                        data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Assign Agent">
                                        Assign Agent</div>
                                @endif
                            </div>
                            <div class="col-lg-3 col-md-12 extra_options">
                                <div class="badge badge-dark" data-bs-toggle="modal" data-bs-target="#extraCostModal">Update
                                    Extra Cost</div>
                            </div>
                        </div>
                        @if (isset($order->order_note))
                            <hr>
                            <div class="d-flex align-items-center gap-3 g-3 m-1 mx-3">
                                <div>

                                    <div for="" class="fw-bolder " style="font-size: 20px;"> Order Note:</div>
                                    <div>
                                        {{ $order->order_note }}
                                    </div>
                                </div>
                            </div>
                        @endif
                        <hr>

                        <div class="row g-3 m-1">
                            <div class="col-lg-3">
                                <label for="" class="fw-bolder">Order Code</label>
                                <div class="text-dark display-6">{{ $order->orderCode($order->id) }}</div>
                            </div>
                            <div class="col-lg-5">
                                <label for="" class="fw-bolder">Customer</label>
                                <div class="text-dark">{{ $order->customer_id ? $order->customer->firstname : 'N/A' }}
                                    {{ $order->customer_id ? $order->customer->lastname : 'N/A' }}
                                    | Email: <span
                                        class="lead">{{ $order->customer_id ? $order->customer->email : 'N/A' }}
                                </div>
                                <div>Phone: <span class="lead">
                                        @if ($order->customer_id)
                                            <a
                                                href="tel:{{ $order->customer->phone_number }}">{{ $order->customer->phone_number }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </span><br>
                                    @if ($order->customer_id)
                                        @php
                                            $whatsapp = substr($order->customer->whatsapp_phone_number, 1);
                                        @endphp
                                        Whatsapp: <span class="lead"><a
                                                href="https://wa.me/{{ '234' . $whatsapp }}?text=Hi" target="_blank">
                                                {{ $order->customer->whatsapp_phone_number }}</a></span>
                                    @else
                                        Whatsapp: <span class="lead">None</span>
                                    @endif
                                    {{-- <a href="https://wa.me/2348066216874?text=Hi">Whatsapp link</a> --}}
                                </div>
                                <div>Location: <span
                                        class="lead">{{ $order->customer_id ? $order->customer->city : 'None' }},
                                        {{ $order->customer_id ? $order->customer->state : 'None' }}</span></div>
                                <div>Delivery Address: <span
                                        class="lead">{{ $order->customer_id ? $order->customer->delivery_address : 'None' }}</span>
                                </div>

                                <label for="" class="fw-bolder mt-5">Form Field</label>
                                @php
                                    $form_fields = json_decode($order->form_fields ?? '', true);
                                @endphp


                                @if (count($form_fields) > 0)
                                    @foreach ($form_fields as $field => $value)
                                        <div>
                                            <span class="fw-bold">
                                                {{ ucwords(str_replace('_', ' ', $field)) }}:
                                            </span>
                                            <span class="lead text-secondary">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                @endif

                                {{-- @dd($form_fields) --}}
                            </div>
                            <div class="col-lg-2">
                                <label for="" class="fw-bolder">Expected Revenue(₦)</label>
                                <div class="text-dark display-6">{{ $currency }}{{ $gross_revenue }}</div>
                            </div>
                            <div class="col-lg-2">
                                <label for="" class="fw-bolder">Agent</label>
                                <div class="text-dark">{{ $order->agent_assigned_id ? $order->agent->name : 'None' }}
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 m-1">
                            <div class="col-lg-3">

                            </div>
                        </div>

                        @if (count((array) $outgoingStocks) > 0)
                            @foreach ($outgoingStocks as $package)
                                <hr>
                                <div class="row g-3 m-1">

                                    <div class="col-lg-3">
                                        <label for="" class="fw-bolder">Product Name</label>
                                        <div class="text-dark" style="font-size: 14px;">{{ $package->product->name }}
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="" class="fw-bolder">Quantity Ordered</label>
                                        <div class="text-dark d-none" style="font-size: 14px;">
                                            {{ $package->quantity_removed . ' @' . $package->product->price }}</div>
                                        <div class="text-dark" style="font-size: 14px;">
                                            {{ $package->quantity_removed }}
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="" class="fw-bolder">Revenue</label>
                                        <div class="text-dark" style="font-size: 14px;">
                                            {{ $package->amount_accrued }}
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="" class="fw-bolder">Date</label>
                                        <div class="text-dark" style="font-size: 14px;">{{ $order->created_at }}
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        @else
                            <div class="row">
                                <div class="col-lg-12 text-center">Awaiting Customer Response</div>
                            </div>
                        @endif



                        <!--features-->


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
                    <h1 class="modal-title fs-5" id="addStaffModalLabel">Assign Staff</h1>
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
                                class="custom-select form-control border border-dark">
                                <option value="" selected>Nothing Selected</option>

                                <option value="new">New</option>
                                <option value="pending">Pending</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="delivered_not_remitted">Delivered Not Remitted</option>
                                <option value="delivered_and_remitted">Delivered and Remitted</option>

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

    <!-- Modal extraCost -->
    <div class="modal fade" id="extraCostModal" tabindex="-1" aria-labelledby="extraCostModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title fs-7">Add Extra Cost</div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('extraCostToOrder') }}" method="POST">@csrf
                    <div class="modal-body">

                        <input type="hidden" id="order_id" class="order_id" name="order_id"
                            value="{{ $order->id }}">
                        <div class="d-grid mb-3">
                            <label for="">Amount ({{ $currency }})</label>
                            <input type="text" name="extra_cost_amount"
                                class="extra_cost_amount form-control @error('extra_cost_amount') is-invalid @enderror"
                                id=""
                                value="{{ isset($order->extra_cost_amount) ? $order->extra_cost_amount : 0 }}">
                        </div>

                        <div class="d-grid mb-3">
                            <label for="">Note | Optional</label>
                            <textarea name="extra_cost_reason" id="" cols="30" rows="3" class="form-control"></textarea>
                        </div>

                        <div class="form-check">
                            <input name="remove" id="remove" class="form-check-input" type="checkbox"
                                value="1">
                            <label class="form-check-label" for="remove">Remove extra cost</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary addAgentBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('extra_js')
    <link href="{{ asset('/assets/css/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/assets/js/jquery.datetimepicker.min.js') }}"></script>
    <script>
        jQuery('.order_delivery_date').datetimepicker({
            datepicker: true,
            timepicker: false,
            //showPeriod: true,
            //format:'Y-m-d'
        });
    </script>
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
    </script>
@endsection
