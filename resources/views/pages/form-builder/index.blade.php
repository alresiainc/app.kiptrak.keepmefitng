@extends('layouts.design')
@section('title')
    Forms
@endsection

@section('extra_css')
    <style>
        td {
            font-size: 14px;
        }

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
        .tox .tox-promotion {
            background: repeating-linear-gradient(transparent 0 1px, transparent 1px 39px) center top 39px/100% calc(100% - 39px) no-repeat;
            background-color: #fff;
            grid-column: 2;
            grid-row: 1;
            padding-inline-end: 8px;
            padding-inline-start: 4px;
            padding-top: 5px;
            display: none;
        }

        .tox:not([dir=rtl]) .tox-statusbar__branding {
            margin-left: 2ch;
            display: none;
        }
    </style>
@endsection

@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Forms</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Forms</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="users-list-wrapper">
            <div class="users-list-filter px-1">
                <form>
                    <div class="row border rounded py-2 mb-2">

                        <div class="col-12 col-md-6 col-lg-3 d-flex align-items-end">
                            <div class="d-grid w-100">
                                <a href="{{ route('newFormBuilder') }}"
                                    class="btn btn-dark rounded-pill btn-block glow users-list-clear mb-0">
                                    <i class="bx bx-plus"></i>Build Form</a>
                            </div>
                        </div>

                    </div>
                </form>
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
                                <div class="float-end text-end d-none">
                                    <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill"
                                        data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto"
                                        data-bs-title="Export Data">
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

                            <div class="table table-responsive">
                                <table id="orders-table" class="table table-striped custom-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Form Name</th>

                                            <th scope="col">Staffs Assigned</th>

                                            <th scope="col" class="">Customers</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @if (count($formHolders) > 0)
                                            @foreach ($formHolders as $key => $formHolder)
                                                <tr>
                                                    <th scope="row">{{ ++$key }}</th>
                                                    <td>{{ $formHolder->name }} <br>
                                                        @if (count($formHolder->customers) > 0)
                                                            <a class="badge badge-info"
                                                                href="{{ route('editNewFormBuilder', $formHolder->unique_key) }}">Edit</a>
                                                            <a class="badge badge-dark"
                                                                href="{{ route('allOrders', $formHolder->unique_key) }}">Entries({{ $formHolder->entries() }})</a>
                                                        @else
                                                            <a class="badge badge-info"
                                                                href="{{ route('editNewFormBuilder', $formHolder->unique_key) }}">Edit</a>
                                                            @if ($formHolder->entries() > 0)
                                                                <a href="{{ route('allOrders', $formHolder->unique_key) }}">
                                                                    <span class="badge badge-dark"
                                                                        href="">Entries({{ $formHolder->entries() }})</span>
                                                                </a>
                                                            @else
                                                                <span class="badge badge-dark"
                                                                    href="">Entries({{ $formHolder->entries() }})</span>
                                                            @endif
                                                        @endif

                                                        <a class="badge badge-success"
                                                            href="{{ route('duplicateForm', $formHolder->unique_key) }}">Duplicate</a>
                                                    </td>

                                                    @if (isset($formHolder->staffs) && $formHolder->staffs->count() > 0)
                                                        @php
                                                            $staffsString = implode(
                                                                ', ',
                                                                $formHolder->staffs->pluck('name')->toArray(),
                                                            );
                                                            $maxLength = 30;
                                                            $truncatedText =
                                                                strlen($staffsString) > $maxLength
                                                                    ? substr($staffsString, 0, $maxLength) .
                                                                        '...(' .
                                                                        $formHolder->staffs->count() .
                                                                        ')'
                                                                    : $staffsString;
                                                        @endphp
                                                        <td data-bs-toggle="popover" data-trigger="hover"
                                                            data-content="{{ $staffsString }}" title="Staffs Assigned">

                                                            {{ $truncatedText }} <br>
                                                            <span class="badge badge-dark"
                                                                onclick="agentModal('{{ $formHolder->id }}', `{{ $formHolder->staffs->pluck('id')->toJson() }}`)"
                                                                data-bs-toggle="tooltip" data-bs-placement="left"
                                                                data-bs-title="Change Staff">
                                                                <i class="bi bi-plus"></i> <span>Update Staffs</span></span>
                                                        </td>
                                                    @else
                                                        <td style="width: 120px">
                                                            <span class="badge badge-success"
                                                                onclick="agentModal('{{ $formHolder->id }}')"
                                                                data-bs-toggle="tooltip" data-bs-placement="left"
                                                                data-bs-title="Assign Staff" style="cursor: pointer;">
                                                                <i class="bi bi-plus"></i> <span>Assign Staffs</span></span>
                                                        </td>
                                                    @endif




                                                    <td class="d-nonee">
                                                        <span>{{ isset($formHolder->customers) ? $formHolder->customers->count() : '0' }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <div class="d-flex">
                                                            <a class="btn btn-info btn-sm me-2"
                                                                onclick="embedFormModal('{{ url('/') . '/' . $formHolder->url }}')"
                                                                data-bs-toggle="tooltip" data-bs-placement="left"
                                                                data-bs-title="Copy Embedded Code">
                                                                <i class="bi bi-clipboard"></i> <span>Embed</span></a>


                                                            <a href="{{ route('form.get', ['key' => $formHolder->unique_key]) }}"
                                                                class="btn btn-primary btn-sm me-2" target="_blank"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                data-bs-title="View"><i class="bi bi-eye"></i></a>
                                                            <a href="{{ route('editForm', $formHolder->unique_key) }}"
                                                                class="btn btn-success btn-sm me-2 d-none"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                data-bs-title="Edit"><i
                                                                    class="bi bi-pencil-square"></i></a>

                                                            <a href="{{ route('deleteForm', $formHolder->unique_key) }}"
                                                                onclick="return confirm('Are you sure?')"
                                                                class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" data-bs-title="Delete"><i
                                                                    class="bi bi-trash"></i></a>
                                                        </div>
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





    <!-- Modal agentModal -->
    <div class="modal fade" id="agentModal" tabindex="-1" aria-labelledby="agentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="agentModalLabel">Assigned Staff(s)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('assignStaffToForm') }}" method="POST">@csrf
                    <div class="modal-body">

                        <input type="hidden" id="form_id" class="form_id" name="form_id" value="">

                        <div class="mt-3">
                            <div class="product-clone-section clone-item">
                                <div class="col-md-12 mt-1 wrapper staffs_field">
                                    <label for="" class="form-label">Select Staffs</label>


                                </div>

                                <button type="button" class="clone btn btn-success btn-sm">
                                    <i class="bi bi-plus"></i> Add more
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary addAgentBtn">Assign Staff(s)</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal embedFormModal -->
    <div class="modal fade" id="embedFormModal" tabindex="-1" aria-labelledby="embedFormModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="embedFormModalLabel">Embed Form</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label for="">Form URL</label>
                        <div class="d-flex align-items-center product-container mb-2 w-100">
                            <input type="text" id="formEmbedUrl" class="form-control border" value="" readonly>
                            <button class="btn btn-default ms-2 copy-btn" type="button"><span
                                    class="bi bi-clipboard"></span></button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">WordPress Shortcode</label>
                        <div class="d-flex align-items-center product-container mb-2 w-100">
                            <input type="text" id="formWPShortcode" class="form-control border" value=""
                                readonly>
                            <button class="btn btn-default ms-2 copy-btn" type="button"><span
                                    class="bi bi-clipboard"></span></button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Iframe code</label>
                        <div class="d-flex align-items-center product-container mb-2 w-100">
                            <textarea type="url" id="formIframeCode" class="form-control border" readonly></textarea>
                            <button class="btn btn-default ms-2 copy-btn" type="button"><span
                                    class="bi bi-clipboard"></span></button>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>

@endsection

@section('extra_js')
    <script>
        new ClipboardJS('.clipboard-btn');
    </script>

    <?php if(count($errors) > 0) : ?>
    <script>
        $(document).ready(function() {
            $('#addOrderbump').modal('show');
            $('[data-bs-toggle="popover"]').popover()
        });
    </script>
    <?php endif ?>


    <script>
        function agentModal($formId = "", $assignStaffsIds = '') {



            const staffsSelect = $(`
            <div class="d-flex align-items-center product-container mb-2 w-100 element">
                <select name="staff_assigned_ids[]" id="" data-live-search="true"
                    class="form-control form-control-sm border">
                    <option value="">Nothing Selected</option>

                    @if (count($staffs) > 0)
                        @foreach ($staffs as $staff)
                            <option value="{{ $staff->id }}">
                                {{ $staff->name }} | {{ $staff->id }}
                            </option>
                        @endforeach
                    @endif
                </select>
                <button class="btn btn-sm btn-default ms-2 remove" type="button"><span
                        class="bi bi-x-lg"></span></button>
            </div>
            `);

            const formStaffsField = $('.staffs_field')

            const assignStaffsIds = JSON.parse($assignStaffsIds);

            if (Array.isArray(assignStaffsIds)) {
                formStaffsField.find('.element').remove();
                assignStaffsIds.forEach(id => {
                    create_package_field(formStaffsField, id)

                });
            } else {
                create_package_field(formStaffsField)
            }

            $('#agentModal').modal("show");
            $('.form_id').val($formId);
        }

        function create_package_field(element, selected = null) {
            const staffsSelect = $(`
            <div class="d-flex align-items-center product-container mb-2 w-100 element">
                <select name="staff_assigned_ids[]" id="" data-live-search="true"
                    class="form-control form-control-sm border">
                    <option value="">Nothing Selected</option>

                    @if (count($staffs) > 0)
                        @foreach ($staffs as $staff)
                            <option value="{{ $staff->id }}">
                                {{ $staff->name }} | {{ $staff->id }}
                            </option>
                        @endforeach
                    @endif
                </select>
                <button class="btn btn-sm btn-default ms-2 remove" type="button"><span
                        class="bi bi-x-lg"></span></button>
            </div>
            `);
            if (selected) {
                staffsSelect.find('select').val(selected);
            }

            // Append the complete row to the parent element

            element.append(staffsSelect)

        }

        function embedFormModal(formUrl = "") {
            let shortCode, iFrameCode;

            // Show the modal
            $('#embedFormModal').modal("show");


            $('#embedFormModal').find('#formEmbedUrl').val(formUrl);

            // Extract the unique key from the URL
            const urlParts = formUrl.split('/');
            const uniqueKey = urlParts[urlParts.length - 1]; // Get the last segment of the URL

            // WP short code
            shortCode = `[kiptrak type="form" key="${uniqueKey}"]`;
            $('#embedFormModal').find('#formWPShortcode').val(shortCode);

            // iFrame code
            iFrameCode = '<div style="width:100%; height:100vh; overflow:hidden; position:relative;">';
            iFrameCode +=
                `<iframe id="kiptrak-iframe" src="${formUrl}" style="width:100%; height:100%; border:none; position:absolute; top:0; left:0;" allowfullscreen></iframe>`;
            iFrameCode += "</div>";

            // Set the iframe code in the modal input
            $('#embedFormModal').find('#formIframeCode').val(iFrameCode);
        }
        // Event handler for the copy buttons
        $('.copy-btn').on('click', function() {
            // Find the previous input or textarea element
            var inputField = $(this).prev('input, textarea');

            // Select the text in the input or textarea
            inputField.select();
            document.execCommand('copy');

            // Optionally, give feedback to the user
            $(this).find('span').removeClass('bi-clipboard').addClass('bi-check-circle');

            // Reset the icon back after 2 seconds
            setTimeout(() => {
                $(this).find('span').removeClass('bi-check-circle').addClass('bi-clipboard');
            }, 2000);
        });


        $('.clone-item').on('click', '.clone', function() {
            // Clone the first input field, reset its value, and append it to the results
            const newElement = $(this).siblings('.wrapper').find('.element').first().clone();
            newElement.find('input').val(''); // Clear the cloned input value
            $(this).siblings('.wrapper').append(newElement)
            // newElement.appendTo('.results'); // Append cloned input to the results container
        });

        // Handle the click event for the "Remove" button
        $('.clone-item').on('click', '.remove', function(e) {
            e.stopPropagation();
            // Remove the closest input field container, but keep the first one
            if ($(this).parents('.wrapper').find('.element').length >
                1) { // Ensure there's more than one field
                $(this).closest('.element').remove(); // Remove the corresponding input field
            }
        });
    </script>



    <script>
        tinymce.init({
            selector: '.mytextarea',
            height: "200",
        });
    </script>
@endsection
