@extends('layouts.design')
@section('title')
    Sent Messages
@endsection

@section('extra_css')
    <style>
        /* select2 arrow */
        select {
            -webkit-appearance: listbox !important
        }

        /* custom-select border & inline edit */
        .btn-light {
            background-color: #fff !important;
            color: #000 !important;
        }

        div.filter-option-inner-inner {
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
            <h1>Sent Messages</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Sent Messages</li>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body pt-3">

                            <div class="clearfix mb-2">
                                <div class="float-start text-start">
                                    <a href="{{ route('composeEmailMessage') }}"><button
                                            class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="tooltip"
                                            data-bs-placement="auto" data-bs-title="Compose Message">
                                            <i class="bi bi-plus"></i> <span>Compose Message</span></button></a>
                                </div>

                                <div class="float-end text-end d-none">
                                    <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill"
                                        data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                                        <i class="bi bi-upload"></i> <span>Import</span></button>
                                    <button class="btn btn-sm btn-secondary rounded-pill" data-bs-toggle="tooltip"
                                        data-bs-placement="auto" data-bs-title="Import Data"><i class="bi bi-download"></i>
                                        <span>Export</span></button>
                                    <button class="btn btn-sm btn-danger rounded-pill" data-bs-toggle="tooltip"
                                        data-bs-placement="auto" data-bs-title="Delete All"><i class="bi bi-trash"></i>
                                        <span>Delete All</span></button>
                                </div>
                            </div>
                            <hr>

                            <div class="row mb-4">
                                <div class="col-lg-3 col-md-6">
                                    <label for="">Start Date</label>
                                    <input type="text" name="start_date" id="min" class="form-control filter">
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <label for="">End Date</label>
                                    <input type="text" name="end_date" id="max" class="form-control filter">
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <label for="">Category</label>
                                    <select id="filter-categoryname" type="select"
                                        class="custom-select border form-control filter">
                                        <option value="">All</option>
                                        <option value="employees">Staff</option>
                                        <option value="customers">Customers</option>
                                        <option value="agents">Agent</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <a href="{{ route('viewMessageTemplates', 'email') }}"
                                        class="btn btn-outline-primary d-flex mt-4">
                                        <i class="bi bi-settings"></i><span class="ms-1">View
                                            Templates</span>
                                    </a>

                                </div>


                            </div>
                            <div class="table table-responsive">
                                <table id="products-table" class="table custom-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sender Name | Topic</th>
                                            <th>Recipients</th>
                                            <th>Message</th>
                                            <th>Status</th>
                                            <th>Date Sent</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($messages) > 0)
                                            @foreach ($messages as $message)
                                                <tr>

                                                    <td data-categoryname="{{ isset($message->to) ? $message->to : '' }}"
                                                        class="categoryname">{{ $message->topic }}</td>
                                                    <td>{{ implode(', ', \unserialize($message->recipients)) }}</td>
                                                    {{-- @php
                                                        $users = $message->users($message->recipients);
                                                        $customers = $message->customers($message->recipients);
                                                    @endphp
                                                    <td>
                                                        @if (isset($message->to) && $message->to == 'employees')
                                                            @foreach ($users as $user)
                                                                <span
                                                                    class="badge badge-dark mr-1">{{ $user->email }}</span>
                                                            @endforeach
                                                        @endif

                                                        @if (isset($message->to) && $message->to == 'customers')
                                                            @foreach ($customers as $customer)
                                                                <span
                                                                    class="badge badge-dark mr-1">{{ $customer->email }}</span>
                                                            @endforeach
                                                        @endif

                                                        @if (isset($message->to) && $message->to == 'agents')
                                                            @foreach ($users as $user)
                                                                <span
                                                                    class="badge badge-dark mr-1">{{ $user->email }}</span>
                                                            @endforeach
                                                        @endif

                                                    </td> --}}
                                                    <td>{{ substr($message->message, 0, 30) . '...' }} <br>
                                                        <span class="badge badge-dark"
                                                            onclick="editSentMailModal('{{ $message->id }}', `{{ $message->topic }}`, `{{ $message->message }}`)"
                                                            style="cursor: pointer;">view more</span>
                                                    </td>
                                                    <td>{!! $message->message_status == 'sent'
                                                        ? '<span class="badge badge-success">Sent</span>'
                                                        : '<span class="badge badge-dark">Draft</span>' !!}</td>
                                                    <td>{{ $message->created_at }}</td>

                                                </tr>
                                            @endforeach
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </section>

    </main><!-- End #main -->

    <!--sendMailModal -->
    <div class="modal fade" id="sendMailModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="sendMailModalLabel">View Mail to Employees</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="sendMailForm" action="{{ route('sentEmailMessageUpdate') }}" method="POST">@csrf
                    <div class="modal-body">
                        <input type="hidden" name="message_id" id="message_id" value="">

                        <div class="d-grid mb-3">
                            <label for="">Topic</label>
                            <input type="text" name="topic" id="topic" class="form-control" placeholder="">
                        </div>

                        <div class="d-grid mb-2">
                            <label for="">Message</label>
                            <textarea name="message" id="message" class="form-control" cols="30" rows="10"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary sendMailBtn">Re-send Message</button>
                    </div>
                </form>

            </div>
        </div>
    </div>



@endsection

@section('extra_js')
    <script>
        function editSentMailModal($id = "", $topic = "", $message = "") {
            $('#sendMailModal').modal("show");
            $('#message_id').val($id);
            $('#topic').val($topic);
            $('#message').val($message);
        }
    </script>

    <script>
        $('.filter').change(function() {
            filter_function();
            //calling filter function each select box value change
        });

        $('table tbody tr').show(); //intially all rows will be shown

        function filter_function() {
            $('table tbody tr').hide(); //hide all rows

            var categorynameFlag = 0;
            var categorynameValue = $('#filter-categoryname').val();

            //setting intial values and flags needed

            //traversing each row one by one
            $('table tr').each(function() {

                if (categorynameValue == 0) { //if no value then display row
                    categorynameFlag = 1;
                } else if (categorynameValue == $(this).find('td.categoryname').data('categoryname')) {
                    categorynameFlag = 1; //if value is same display row
                } else {
                    categorynameFlag = 0;
                }

                if (categorynameFlag) {
                    $(this).show(); //displaying row which satisfies all conditions
                }

            });

        }
    </script>

    <script>
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
        );
    </script>
@endsection
