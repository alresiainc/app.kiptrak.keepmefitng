@extends('layouts.design')
@section('title')
    {{ ucfirst($channel) }} Templates
@endsection

@section('extra_css')
    <style>
        /* select2 arrow */
        select {
            -webkit-appearance: listbox !important;
        }

        /* custom-select border & inline edit */
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
    </style>
@endsection

@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>{{ ucfirst($channel) }} Templates: </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Template Templates</li>
                    <li class="breadcrumb-item active">{{ $channel }}</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="users-list-wrapper">
            <div class="users-list-filter px-1">
            </div>
        </section>

        @if (Session::has('success'))
            <div class="alert alert-success mb-3 text-center">
                {{ Session::get('success') }}
            </div>
        @endif

        <section>
            <!-- The element triggering the popover -->
            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-info" id="loadPlaceholders" data-bs-toggle="popover"
                    data-bs-html="true" data-bs-content="">
                    View Template Placeholders
                </button>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body pt-3">
                            <div class="table table-responsive">
                                <table id="products-table" class="table custom-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            {{-- <th>Channel</th> --}}
                                            <th>Message</th>
                                            <th>Status</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($templates) > 0)
                                            @foreach ($templates as $template)
                                                @php
                                                    $message = $template->message;
                                                    $maxLength = 30;
                                                    $truncatedText =
                                                        strlen($message) > $maxLength
                                                            ? substr($message, 0, $maxLength) . '...'
                                                            : $message;
                                                @endphp
                                                <tr>
                                                    <td class="categoryname">{{ $template->name }}</td>
                                                    {{-- <td>{{ $template->channel }}</td> --}}
                                                    <td data-bs-toggle="popoverrrr" data-trigger="hover"
                                                        data-content="{{ $message }}" title="Message">
                                                        {{ $truncatedText }} </td>
                                                    <td>
                                                        @if ($template->is_active)
                                                            <a href="{{ route('updateTemplateStatus', ['template' => $template->id, 'status' => 'deactivate']) }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="auto"
                                                                data-bs-title="Click to Deactivate Template"
                                                                data-bs-title="Click to Deactivate Template"> <span
                                                                    class="badge badge-success">Active</span></a>
                                                        @else
                                                            <a href="{{ route('updateTemplateStatus', ['template' => $template->id, 'status' => 'activate']) }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="auto"
                                                                data-bs-title="Click to Activate Template"> <span
                                                                    class="badge badge-dark">Inactive</span></a>
                                                        @endif

                                                        {{-- {!! $template->is_active
                                                            ? '<span class="badge badge-success">Active</span>'
                                                            : '<span class="badge badge-dark">Inactive</span>' !!} --}}
                                                    </td>
                                                    <td>
                                                        <!-- Edit button to open modal -->
                                                        <button class="btn btn-sm btn-primary me-2"
                                                            onclick="editTemplateModal({{ $template->id }}, '{{ $template->subject }}', `{!! $template->message !!}`)">Edit</button>
                                                        {{-- @if ($template->is_active)
                                                            <a href="{{ route('updateTemplateStatus', ['template' => $template->id, 'status' => 'deactivate']) }}"
                                                                class="btn btn-success btn-sm"
                                                                data-bs-title="Click to Deactivate Template"> Deactivate</a>
                                                        @else
                                                            <a href="{{ route('updateTemplateStatus', ['template' => $template->id, 'status' => 'activate']) }}"
                                                                class="btn btn-primary btn-sm"
                                                                data-bs-title="Click to Activate Template"> Activate</a>
                                                        @endif --}}
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

    <!-- Edit Template Modal -->
    <div class="modal fade" id="editTemplateModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Template</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="editTemplateForm" action="" method="POST">@csrf
                    <div class="modal-body">
                        <input type="hidden" name="template_id" id="template_id">

                        <div class="mb-3">
                            <label for="subject">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control" placeholder="">
                        </div>

                        <div class="mb-2">
                            <label for="template">Message Template</label>
                            <textarea name="template" id="template" class="form-control" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Template</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Modal HTML Structure -->
    <div class="modal fade" id="placeholderModal" tabindex="-1" aria-labelledby="placeholderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="placeholderModalLabel">Template Placeholders</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Content will be dynamically loaded here -->
                    <div>
                        <h2> Templates for Orders </h2>
                        <table class='table table-bordered'>
                            <thead>
                                <tr>
                                    <th>Placeholder</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>[customer_first_name]</td>
                                    <td>First name of the customer</td>
                                </tr>
                                <tr>
                                    <td>[customer_last_name]</td>
                                    <td>Last name of the customer</td>
                                </tr>
                                <tr>
                                    <td>[customer_phone_number]</td>
                                    <td>Customer's phone number</td>
                                </tr>
                                <tr>
                                    <td>[customer_whatsapp_phone_number]</td>
                                    <td>Customer's WhatsApp phone number</td>
                                </tr>
                                <tr>
                                    <td>[customer_delivery_address]</td>
                                    <td>Customer's delivery address</td>
                                </tr>
                                <tr>
                                    <td>[customer_city]</td>
                                    <td>Customer's city</td>
                                </tr>
                                <tr>
                                    <td>[customer_state]</td>
                                    <td>Customer's state</td>
                                </tr>
                                <tr>
                                    <td>[customer_delivery_duration]</td>
                                    <td>Estimated delivery duration for the customer</td>
                                </tr>
                                <tr>
                                    <td>[customer_email]</td>
                                    <td>Customer's email address</td>
                                </tr>
                                <tr>
                                    <td>[staff_name]</td>
                                    <td>Full name of the staff member handling the order</td>
                                </tr>
                                <tr>
                                    <td>[staff_first_name]</td>
                                    <td>First name of the staff member</td>
                                </tr>
                                <tr>
                                    <td>[staff_last_name]</td>
                                    <td>Last name of the staff member</td>
                                </tr>
                                <tr>
                                    <td>[staff_phone_number]</td>
                                    <td>Phone number of the staff member</td>
                                </tr>
                                <tr>
                                    <td>[staff_address]</td>
                                    <td>Address of the staff member</td>
                                </tr>
                                <tr>
                                    <td>[staff_city]</td>
                                    <td>City of the staff member</td>
                                </tr>
                                <tr>
                                    <td>[staff_state]</td>
                                    <td>State of the staff member</td>
                                </tr>
                                <tr>
                                    <td>[product_list]</td>
                                    <td>List of products in the order</td>
                                </tr>
                                <tr>
                                    <td>[order_status]</td>
                                    <td>Current status of the order</td>
                                </tr>
                                <tr>
                                    <td>[order_id]</td>
                                    <td>ID of the order</td>
                                </tr>
                                <tr>
                                    <td>[order_delivery_address]</td>
                                    <td>Delivery address for the order</td>
                                </tr>
                                <tr>
                                    <td>[order_extra_cost_amount]</td>
                                    <td>Extra cost amount for the order</td>
                                </tr>
                                <tr>
                                    <td>[order_extra_cost_reason]</td>
                                    <td>Reason for extra cost on the order</td>
                                </tr>
                                <tr>
                                    <td>[order_order_note]</td>
                                    <td>Order notes or special instructions</td>
                                </tr>
                                <tr>
                                    <td>[order_expected_delivery_date]</td>
                                    <td>Expected delivery date</td>
                                </tr>
                                <tr>
                                    <td>[order_actual_delivery_date]</td>
                                    <td>Actual delivery date</td>
                                </tr>
                                <tr>
                                    <td>[order_url]</td>
                                    <td>URL for tracking or order information</td>
                                </tr>
                                <tr>
                                    <td>[order_discount]</td>
                                    <td>Discount applied to the order</td>
                                </tr>
                                <tr>
                                    <td>[order_amount_expected]</td>
                                    <td>Expected amount for the order</td>
                                </tr>
                                <tr>
                                    <td>[order_amount_realised]</td>
                                    <td>Amount realized from the order</td>
                                </tr>
                                <tr>
                                    <td>[order_delivery_duration]</td>
                                    <td>Estimated duration for delivery</td>
                                </tr>
                                <tr>
                                    <td>[order_delivery_going_time]</td>
                                    <td>Time when delivery started</td>
                                </tr>
                                <tr>
                                    <td>[order_delivery_meet_time]</td>
                                    <td>Time when the delivery was met</td>
                                </tr>
                                <tr>
                                    <td>[order_delivery_returning_time]</td>
                                    <td>Time when the delivery returned</td>
                                </tr>
                                <tr>
                                    <td>[order_delivery_going_distance]</td>
                                    <td>Distance covered while going for delivery</td>
                                </tr>
                                <tr>
                                    <td>[order_delivery_returning_distance]</td>
                                    <td>Distance covered while returning from delivery</td>
                                </tr>
                                <tr>
                                    <td>[order_delivery_going_cost]</td>
                                    <td>Cost of going for delivery</td>
                                </tr>
                                <tr>
                                    <td>[order_delivery_returning_cost]</td>
                                    <td>Cost of returning from delivery</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra_js')
    <script>
        function editTemplateModal(id, subject, template) {
            // Set the values in the modal

            $('#editTemplateModal').modal('show');
            $('#template_id').val(id);
            $('#subject').val(subject);
            $('#template').val(template);

            // Update form action dynamically with the correct template ID
            $('#editTemplateForm').attr('action', '/update-message-template/' + id);
        }

        $(function() {

            // Show popover when button is clicked
            $('#loadPlaceholders').on('click', function() {
                $('#placeholderModal').modal('show');
            });


            $('[data-bs-toggle="popover"]').popover()
        });
    </script>
@endsection
