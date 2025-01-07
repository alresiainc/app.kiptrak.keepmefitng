@extends('layouts.design')
@section('title')
    Form Builder
@endsection
{{-- @php
    $form = !isset($form) ? (object) [] : $form;
@endphp --}}

@section('extra_css')
    <style>
        select {
            -webkit-appearance: listbox !important;
            /* for arrow in select-field */
        }

        .select-checkbox option::before {
            content: "\2610";
            width: 1.3em;
            text-align: center;
            display: inline-block;
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

        .card.question-item .item-move {
            position: absolute;
            left: 3px;
            top: 50%;
            z-index: 2;
            content: "";
            width: 20px;
            height: 30px;
            background-repeat: no-repeat;
            opacity: 0.5;
            cursor: move;
        }
    </style>

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
    <style>
        .canvas-container {
            overflow: hidden;
            overflow-y: scroll;
            max-height: calc(100vh - 250px);
            position: relative;
            margin-bottom: 30px;
            border: none;
            border-radius: 5px;
            box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1);
            padding: 0;
            background-color: #ffffff;
            border-radius: 8px;
            /* padding-top: 26px; */
        }

        .canvas-container {
            background-color: {{ old('form_bg_color', isset($form) ? $form?->form_bg_color : '#ffffff') }};
            background-image: url({{ old('form_bg_url', isset($form) ? $form?->form_bg_url : '') }});
            background-size: cover;
            background-repeat: no-repeat;
        }

        .canvas-container *:not(.element-wrapper):not(.element-wrapper *) {
            color: {{ old('form_bg_text_color', isset($form) ? $form?->form_bg_text_color : '') }};

        }

        .form-builder-sample-popover {
            max-width: 100%;
            width: 650px;
        }

        .form-builder-sample-popover .popover-body img,
        .form-builder-sample-popover .popover-body video {
            max-width: 100%;
            width: 100%
                /* Ensure the image fits within the popover */
        }

        .full-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 9999;
            max-height: 100vh;
        }

        .builder-option {
            position: absolute;
            top: 0;
            right: 0;
        }

        .builder-option .trigger {
            border-left: 1px solid #dde1e5;
            padding: 8px 16px;
            cursor: pointer;
            border-top-right-radius: 10px;
        }

        .form-tab .tab-content {
            overflow: hidden;
            overflow-y: scroll;
            max-height: calc(100vh - 250px);
            position: relative;
            margin-bottom: 30px;
            border-top: 0;
            border-radius: 0 5px 5px !important;
            box-shadow: 0px 20px 20px 0px rgba(1, 41, 112, 0.1);
            padding: 0;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 15px;
            min-height: 494px;
            border-left: 1px solid #dee2e6;
            border-right: 1px solid #dee2e6;
        }

        .form-tab .nav-tabs .nav-item.show .nav-link,
        .form-tab .nav-tabs .nav-link.active {
            background-color: #ffffff;
        }

        .form-tab .nav-link {
            color: #000000;
            font-weight: 600;
            text-transform: uppercase;
        }

        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
        }

        .builder {
            display: flex;
            height: 100vh;
        }

        .properties {
            padding: 10px;
            border-right: 1px solid #ddd;
            overflow: hidden;
            overflow-y: scroll;
            max-height: calc(100vh - 250px);
        }

        .canvas {
            flex: 1;
            background: #ffffff;

            border-left: 1px solid #ddd;
            min-height: 500px;
            position: relative;

            background: transparent;
            font-weight: normal;
            color: #454545;
            margin: 0px 30px;
        }

        .element-wrapper {
            position: sticky;
            top: 0;
            z-index: 1;
            background: #ffffff;
        }

        .draggable-container {
            border-top: 1px solid #cfd3d9;
            border-bottom: 1px solid #cfd3d9;
        }

        .draggable {
            padding: 5px 20px;
            border: 0;
            border-right: 1px solid #cfd3d9;
            background-color: #ffffff;
            cursor: move;
        }

        .draggable:last-child {
            border-right: 0;
        }

        .draggable:hover {
            background-color: #f8f9fa;
            /* Optional: change background on hover */
        }

        .drop-container {
            min-height: 300px;
            background-color: inherit;
            border: 2px dashed #ccc;
            margin: 30px 0;
            padding-bottom: 30px;
        }

        /* Placeholder Styles */
        .sortable-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            opacity: 0.3;
            height: 300px;
        }

        .properties-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            opacity: 0.3;
            height: 300px;
        }


        /* .text-field-content {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                pointer-events: auto;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                user-select: text;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            } */

        .text-field-content,
        .form-submit-btn {
            pointer-events: auto;
            cursor: text;
            user-select: text;
        }

        .text-field-content:focus-visible {
            outline: none;
            border: 0
        }

        .canvas-element {
            padding: 8px;
            align-items: center;
            position: relative;
        }

        .canvas-element .item-move i {
            cursor: move;
        }

        .canvas-element .item-remove i {
            cursor: pointer;
        }

        .canvas-element .item-remove {
            display: none;
            position: absolute;
            right: -15px;
            top: calc(50% - 12px);
            background-color: red;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
            color: #fff;
            justify-content: center;
            align-items: center;
            padding: 4px 2px;
            font-size: 11px;
            font-weight: 600;

        }

        .canvas-element .item-move {
            display: none;
            position: absolute;
            left: -15px;
            top: calc(50% - 12px);
            background-color: #ccc;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
            /* color: #fff; */
            justify-content: center;
            align-items: center;
            padding: 4px 2px;
            font-size: 11px;
            font-weight: 600;
        }

        .canvas-element:hover {
            border: 1px dashed #ccc;
            border-radius: 3px;
        }

        .canvas-element:hover .item-move,
        .canvas-element:hover .item-remove {
            display: flex;
        }

        .canvas-element-seperator {
            border-top: 1px solid #333;
            margin: 10px 0;
            width: 100%;
            height: 1px;
        }

        .canvas-element-submit {
            padding: 10px 20px;
            background-color: #04512d;
            color: white;
            border: none;
            cursor: pointer;
        }

        .accordion-item {
            border: 0;
        }

        .accordion-body {
            padding: 0;
        }

        .accordion-item .tab-item-header {
            font-size: 14px !important;
            text-transform: capitalize;
            font-weight: 600;
            color: #000000;
            margin-top: 5px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #dee2e6;
            padding: 5px 0;
        }

        .accordion-item .tab-item-header i {
            transform: rotate(45deg);
        }

        .accordion-item .tab-item-header.collapsed i {
            transform: rotate(0deg);
        }

        .form-tab label {
            font-size: 12px !important;
            text-transform: capitalize;
            font-weight: 400;
            color: #000000;
            margin-top: 5px;
            margin-bottom: 5px;
        }


        .form-tab h6 {
            font-size: 14px !important;
            text-transform: uppercase;
            margin-top: 10px;
            color: #000000;
            font-weight: 600;
            border-bottom: 1px solid #a0aec9;
        }

        .form-tab .input-group span {
            background: #f6f6f6;
            font-size: 15px;
            padding: 4px;
            border: 1px solid #dedede;
        }

        /* General Styling */
        .product-item {
            border: 1px solid #ddd;
            transition: all 0.3s ease;
            cursor: pointer;
            background-color: #ffffff;
        }

        .product-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }

        /* Image Styling */
        .product-item .product-img-container {
            display: flex;
            /* flex: 1 1 150px; */
            width: 100px;
            height: 100px;
            overflow: hidden;
        }

        .product-item .product-img {
            object-fit: cover;
            max-width: 100%;
            max-height: 100%;
            border-radius: 8px;
        }

        /* Product Info Styling */
        .product-item .product-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 100%;
            flex: 1 1;
        }

        .product-item .product-title {
            font-size: 15px;
            color: #333;
        }

        /* Select Box */
        .product-item .select_product_qty {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px 10px;
            font-size: 0.9rem;
            margin-top: 5px;
            max-width: 180px;
            transition: border-color 0.2s;
        }

        .product-item .select_product_qty:focus {
            border-color: #04512d;
            outline: none;
        }

        /* Input Radio Styling */
        .product-item .product-package {
            margin-top: 8px;
            accent-color: #04512d;
        }

        /* Container Styling */
        .product-item .color-options,
        .size-options {
            gap: 10px;
        }

        /* Hidden Radio Inputs */
        .product-item .color-radio,
        .product-item .size-radio {
            display: none;
        }

        /* Color Circle */
        .product-item .color-circle {
            border-radius: 50%;
            border: 2px solid transparent;
            cursor: pointer;
            transition: border-color 0.3s;
            font-weight: 600;
        }



        /* Checked State for Color Circles */
        .color-radio:checked+.color-circle {
            width: 18px;
            height: 18px;
        }

        .no-product i {
            font-size: 42px;
            border: 1px solid #dee1e6;
            padding: 6px 20px;
        }

        .no-product {
            font-size: 16px;
            opacity: 0.4;
            font-weight: 900;
            display: flex;
            align-items: center;
            gap: 11px;
            border: 1px solid #dee1e6;
            padding: 7px;
        }

        /* Size Box */
        .product-item .size-box {
            display: flex;
            border: 1px solid;
            align-items: center;
            justify-content: center;
            padding: 2px 10px;
            font-weight: 600 !important;
            font-size: 10px !important;
            border-radius: 4px;
            cursor: pointer;
            transition: border-color 0.3s, background-color 0.3s;
            border-color: #a1aec9;
            background-color: #ffffff;
        }

        .product-item .size-box:hover {
            border-color: #aaa;
        }

        /* Checked State for Size Boxes */
        .size-radio:checked+.size-box {
            border-color: #04512d;
            background-color: #04512d;
            color: white;
        }

        .product_field {
            border: 2px solid #d2d2d2;
            position: relative;
            transition: all 0.3s ease;
            text-transform: none;
        }

        .product_field::after {
            content: "✓ Selected ";
            position: absolute;
            top: -12px;
            right: 12px;
            border-radius: 15px;
            padding: 2px 15px;
            color: white;
            background-color: #04512d;
            font-size: 12px;
            font-weight: 600;
            display: none
        }

        /* Checked State for Size Boxes */
        .product-checker {
            display: none;
        }

        .product-checker:checked+.product_field {
            border-color: #04512d;
        }

        .product-checker:checked+.product_field::after {
            display: block;
        }


        .product-qty input,
        .product-qty button {
            width: 25px !important;
            height: 25px !important;
            padding: 2px !important;
            font-size: 15px;
            font-weight: 400;
            flex: none !important;
        }



        .product-checker:checked+.product_field .product-qty button {
            background-color: #04512d !important;
            color: white;
            border-color: #04512d;
        }
    </style>
@endsection

@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1 id="preview-code">Form Builder</h1>
            <nav>
                <div class="d-flex justify-content-between align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('allNewFormBuilders') }}">All Forms</a></li>
                        <li class="breadcrumb-item active">Add Form</li>
                    </ol>

                    <button type="button" id="saveData" class="btn btn-success d-none" style="width: 30%;">Save
                        Form</button>
                </div>
            </nav>
        </div><!-- End Page Title -->

        @if (Session::has('success'))
            <div class="alert alert-success mb-3 text-center">
                {{ Session::get('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (Session::has('field_error'))
            <div class="alert alert-danger mb-3 text-center">
                {{ Session::get('field_error') }}
            </div>
        @endif


        {{-- @dd($form->form_data) --}}
        <section class="mt-5">
            <div class="container" id="form">
                <form id="form-data"
                    action="{{ isset($toUpdate) && isset($form) && $toUpdate == true ? route('editNewFormBuilderPost', $form->unique_key) : route('newFormBuilderPost') }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="form_data_json" id="form_data_json"
                        value="{{ old('form_data_json', json_encode(isset($form) ? $form?->form_data : [])) }}">

                    <h5 title="Unique Form Code" class="text-center mb-3">Form Code:
                        {{ $form_code }}</h5>
                    <input type="hidden" name="form_code" value="{{ $form_code }}">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="">
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" id=""
                                    placeholder="Enter Form Name"
                                    value="{{ old('name', isset($form) ? $form?->name : '') }}" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>

                        <div class="col-md-4" id="form-buidler-action">
                            <button type="submit" class="btn btn-default w-100" type="button" id="save_new_form"><i
                                    class="fa fa-save"></i> Save Form</button>
                        </div>
                    </div>

                    <div class="row builder">
                        <div class="col-sm-8">
                            <div class="canvas-container" style="">
                                <div class="element-wrapper">
                                    <div class="text-muted text-xs ps-2 py-2">
                                        Drag items from the list below to the form area <i
                                            class="bi bi-info-circle-fill ms-2" id="form-builder-sample"></i>
                                        <div class="builder-option d-flex">
                                            <span class="trigger" data-bs-toggle="tooltip" id="open-code-modal"
                                                data-bs-placement="auto" data-bs-title="View form data">
                                                <i class="bi bi-code-slash"></i>
                                            </span>



                                            <span class="trigger trigger-fullscreen" data-bs-toggle="tooltip"
                                                data-bs-placement="auto"
                                                data-bs-title="Preview the form in full screen mode">
                                                <i class="bi bi-eye-fill"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="draggable-container">

                                        <div class="d-flex align-items-center flex-wrap">

                                            <div class="draggable d-flex align-items-center" data-type="text"
                                                data-bs-toggle="tooltip" data-bs-placement="auto"
                                                data-bs-title="Insert Text into the form">
                                                <i class="bi bi-type me-2"></i> <span class="d-none d-md-block">Text</span>
                                            </div>
                                            <div class="draggable d-flex align-items-center" data-type="form"
                                                data-bs-toggle="tooltip" data-bs-placement="auto"
                                                data-bs-title="Insert Form field into the form">
                                                <i class="bi bi-input-cursor me-2"></i><span class="d-none d-md-block">Form
                                                    Field</span>
                                            </div>
                                            <div class="draggable d-flex align-items-center" data-type="image"
                                                data-bs-toggle="tooltip" data-bs-placement="auto"
                                                data-bs-title="Insert an image to the form">
                                                <i class="bi bi-image me-2"></i> <span
                                                    class="d-none d-md-block">Image</span>
                                            </div>
                                            <div class="draggable d-flex align-items-center" data-type="product"
                                                data-bs-toggle="tooltip" data-bs-placement="auto"
                                                data-bs-title="Add/update product">
                                                <i class="bi bi-box me-2"></i> <span
                                                    class="d-none d-md-block">Product</span>
                                            </div>
                                            <div class="draggable d-flex align-items-center"
                                                data-type="additional-product" data-bs-toggle="tooltip"
                                                data-bs-placement="auto" data-bs-title="Add extra optional product">
                                                <i class="bi bi-box me-2"></i> <span class="d-none d-md-block">Other
                                                    Product</span>
                                            </div>
                                            <div class="draggable d-flex align-items-center" data-type="seperator"
                                                data-bs-toggle="tooltip" data-bs-placement="auto"
                                                data-bs-title="Add a line to seprate form content">
                                                <i class="bi bi-dash me-2"></i> <span
                                                    class="d-none d-md-block">Seperator</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="canvas" id="canvas">
                                    <div class="drop-container row">
                                        <div class="sortable-placeholder">
                                            <div class="text-center">
                                                <i class="bi bi-menu-button-fill" style="font-size: 50px;"></i>
                                                <h5>Work Space. </h5>
                                                <p>Drag items here.</p>
                                            </div>
                                        </div>
                                        <!-- Dropped elements will appear here -->
                                    </div>
                                    <div class="my-3 form-submit-btn-container"
                                        style="text-align: {{ strtolower(old('form_button_alignment', isset($form) ? $form->form_button_alignment : 'center')) }};">
                                        <button type="button" data-bs-toggle="tooltip" data-bs-placement="auto"
                                            data-bs-title="Click to edit form button"
                                            class="{{ old('form_button_type', isset($form) ? $form->form_button_type : 'Rounded') == 'Rounded' ? 'rounded-pill' : '' }} w-50 p-2 form-submit-btn text-field-content"
                                            style="background-color: {{ old('form_button_bg', isset($form) ? $form->form_button_bg : '#04512d') }}; color: {{ old('form_button_color', isset($form) ? $form->form_button_color : '#ffffff') }}; border:0; cursor: text;"
                                            contenteditable="true">{{ old('form_button_text', isset($form) ? $form->form_button_text : 'Submit Order') }}</button>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="col-sm-4">
                            <div class="form-tab">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="form-properties-tab" data-bs-toggle="tab"
                                            data-bs-target="#form-properties-content" type="button" role="tab"
                                            aria-controls="form-properties-content"
                                            aria-selected="true">Properties</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="customisation-tab" data-bs-toggle="tab"
                                            data-bs-target="#customisation" type="button" role="tab"
                                            aria-controls="customisation" aria-selected="false">Design</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="settings-tab" data-bs-toggle="tab"
                                            data-bs-target="#settings" type="button" role="tab"
                                            aria-controls="settings" aria-selected="false">Settings</button>
                                    </li>

                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade active show" id="form-properties-content" role="tabpanel"
                                        aria-labelledby="form-properties-tab">
                                        <div class="properties-placeholder">
                                            <div class="text-center">
                                                <i class="bi bi-list" style="font-size: 50px;"></i>

                                                <p class="text-sm">Select an item in the workspace to show it properties
                                                    here.</p>
                                            </div>
                                        </div>
                                        <div id="form-properties">

                                        </div>
                                        <!-- Dynamic form fields for properties will go here -->

                                    </div>

                                    <div class="tab-pane fade" id="customisation" role="tabpanel"
                                        aria-labelledby="customisation-tab">
                                        <div class="accordion" id="customisationAccordion">
                                            <div class="accordion-item">
                                                <div class="accordion-header" id="buttonSettings">
                                                    <div class="tab-item-header" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseButtonSettings" aria-expanded="true"
                                                        aria-controls="collapseButtonSettings">
                                                        <div>Button Settings</div>
                                                        <i class="bi bi-caret-right-fill"></i>
                                                    </div>
                                                </div>
                                                <div id="collapseButtonSettings" class="accordion-collapse collapse show"
                                                    aria-labelledby="buttonSettings"
                                                    data-bs-parent="#customisationAccordion">
                                                    <div class="accordion-body">
                                                        @include('pages.form-builder.components.button-settings-form')
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <div class="accordion-header" id="bgSettings">
                                                    <div class="tab-item-header collapsed" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseBgSettings" aria-expanded="false"
                                                        aria-controls="collapseBgSettings">
                                                        <div>Background Settings</div>
                                                        <i class="bi bi-caret-right-fill"></i>
                                                    </div>
                                                </div>
                                                <div id="collapseBgSettings" class="accordion-collapse collapse"
                                                    aria-labelledby="bgSettings" data-bs-parent="#customisationAccordion">
                                                    <div class="accordion-body">
                                                        @include('pages.form-builder.components.background-settings-form')

                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="settings" role="tabpanel"
                                        aria-labelledby="settings-tab">
                                        <div class="accordion" id="settingsAccordion">
                                            <div class="accordion-item">
                                                <div class="accordion-header" id="Thank You Page">
                                                    <div class="tab-item-header" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseThanYouPage" aria-expanded="true"
                                                        aria-controls="collapseThanYouPage">
                                                        <div>Thank You Page Settings</div>
                                                        <i class="bi bi-caret-right-fill"></i>
                                                    </div>
                                                </div>
                                                <div id="collapseThanYouPage" class="accordion-collapse collapse show"
                                                    aria-labelledby="Thank You Page" data-bs-parent="#settingsAccordion">
                                                    <div class="accordion-body">
                                                        @include('pages.form-builder.components.thank-you-page-settings-form')
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="accordion-item">
                                                <div class="accordion-header" id="staff">
                                                    <div class="tab-item-header collapsed" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseStaff" aria-expanded="false"
                                                        aria-controls="collapseStaff">
                                                        <div>Assign Staff</div>
                                                        <i class="bi bi-caret-right-fill"></i>
                                                    </div>
                                                </div>
                                                <div id="collapseStaff" class="accordion-collapse collapse"
                                                    aria-labelledby="staff" data-bs-parent="#settingsAccordion">
                                                    <div class="accordion-body">
                                                        @include('pages.form-builder.components.staff-settings-form')
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="accordion-item">
                                                <div class="accordion-header" id="orderBump">
                                                    <div class="tab-item-header collapsed" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseorderBump" aria-expanded="false"
                                                        aria-controls="collapseorderBump">
                                                        <div>Order Bump</div>
                                                        <i class="bi bi-caret-right-fill"></i>
                                                    </div>
                                                </div>
                                                <div id="collapseorderBump" class="accordion-collapse collapse"
                                                    aria-labelledby="orderBump" data-bs-parent="#settingsAccordion">
                                                    <div class="accordion-body">
                                                        @include('pages.form-builder.components.order-bump-settings-form')
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="accordion-item">
                                                <div class="accordion-header" id="upSale">
                                                    <div class="tab-item-header collapsed" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseUpSale" aria-expanded="false"
                                                        aria-controls="collapseUpSale">
                                                        <div>UpSell</div>
                                                        <i class="bi bi-caret-right-fill"></i>
                                                    </div>
                                                </div>
                                                <div id="collapseUpSale" class="accordion-collapse collapse"
                                                    aria-labelledby="upSale" data-bs-parent="#settingsAccordion">
                                                    <div class="accordion-body">
                                                        @include('pages.form-builder.components.upsell-settings-form')
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="accordion-item">
                                                <div class="accordion-header" id="downSale">
                                                    <div class="tab-item-header collapsed" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseDownSale" aria-expanded="false"
                                                        aria-controls="collapseDownSale">
                                                        <div>DownSell</div>
                                                        <i class="bi bi-caret-right-fill"></i>
                                                    </div>
                                                </div>
                                                <div id="collapseDownSale" class="accordion-collapse collapse"
                                                    aria-labelledby="downSale" data-bs-parent="#settingsAccordion">
                                                    <div class="accordion-body">
                                                        @include('pages.form-builder.components.downsell-settings-form')

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <div class="accordion-header" id="scripts">
                                                    <div class="tab-item-header collapsed" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseScripts" aria-expanded="false"
                                                        aria-controls="collapseScripts">
                                                        <div>Header & Footer Scripts</div>
                                                        <i class="bi bi-caret-right-fill"></i>
                                                    </div>
                                                </div>
                                                <div id="collapseScripts" class="accordion-collapse collapse"
                                                    aria-labelledby="scripts" data-bs-parent="#settingsAccordion">
                                                    <div class="accordion-body">
                                                        @include('pages.form-builder.components.scripts-settings')

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>


                            </div>

                        </div>
                    </div>

                    <!---used in my-form-builder.js--->
                    <input type="hidden" class="package_select" value="{{ $package_select }}">




                </form>
            </div>
        </section>


        <!-- Modal Structure -->
        <div class="modal fade" id="codeModal" tabindex="-1" aria-labelledby="codeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="codeModalLabel">Form Builder</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="form-group">
                            <label for="" class="form-label">Json code <span class="ms-2 trigger-copy"
                                    type="button"><i class="bi bi-copy"></i></span></label>
                            <textarea id="jsonInput" rows="10" class="form-control" placeholder="Paste your JSON data here..."></textarea>

                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="paste-form-data">Save</button>
                    </div>
                </div>
            </div>
        </div>


    </main>


@endsection

@section('extra_js')
    <script src="{{ asset('/assets/js/jquery-ui.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script src="{{ asset('/myassets/js/form-builder/form-builder.js') }}?{{ time() }}"></script>


    <script>
        $(document).ready(function() {



            $('.clone-item').on('click', '.clone', function() {
                // Clone the first input field, reset its value, and append it to the results
                const newElement = $(this).siblings('.wrapper').find('.element').first().clone();
                newElement.find('input').val(''); // Clear the cloned input value
                $(this).siblings('.wrapper').append(newElement)
                // newElement.appendTo('.results'); // Append cloned input to the results container
            });

            // Handle the click event for the "Remove" button
            $('.clone-item').on('click', '.remove', function() {
                console.log($(this));
                console.log($(this).parents('.wrapper').find('.element').length);
                // Remove the closest input field container, but keep the first one
                if ($(this).parents('.wrapper').find('.element').length >
                    1) { // Ensure there's more than one field
                    $(this).closest('.element').remove(); // Remove the corresponding input field
                }
            });


            $('#form-builder-sample').popover({
                html: true,
                trigger: 'click',
                placement: 'auto', // Can be 'top', 'bottom', 'left', 'right'
                customClass: 'form-builder-sample-popover', // Custom class added to the popover
                content: '<div class="popover-video-container"></div>' // Placeholder content
            }).on('shown.bs.popover', function() {
                // When the popover is shown, dynamically add the video content
                const videoHTML = `
                    <video controls autoplay muted loop style="width: 100%;">
                        <source src="/media/form-builder-sample.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                `;
                // Append the video to the popover's body
                $('.popover-body').html(videoHTML);
            });

            // Hide popover when clicking outside of it
            $(document).on('click', function(e) {
                var target = $(e.target);
                if (!target.closest('#form-builder-sample').length && $('.popover').length) {
                    $('#form-builder-sample').popover('hide');
                }
            });
            // Update button settings dynamically
            $('#form-button-text').on('input', function() {
                const val = $(this).val();
                $('.form-submit-btn').text(val);
            });

            $('.form-submit-btn').on('input click', function() {
                $("#customisation-tab").click(); // Assuming this exists in your actual code

                if (!$("#buttonSettings .tab-item-header").attr('aria-expanded') ||
                    $("#buttonSettings .tab-item-header").attr('aria-expanded') === "false") {
                    $("#buttonSettings .tab-item-header").click();
                }

                // Get the button text and trim spaces, then replace multiple spaces with a single space
                const text = $(this).text().trim().replace(/\s+/g, ' '); // Trim and normalize spaces

                $('#form-button-text').val(text); // Set the input field value
                $('#form-button-text').focus(); // Focus the input field
            });


            $('#form-button-bg').on('input', function() {
                const val = `${$(this).val()} !important`;
                $('.form-submit-btn').css('background-color', val);
            });

            $('#form-button-color').on('input', function() {
                const val = `${$(this).val()} !important`;
                $('.form-submit-btn').css('color', val);
            });

            $('#form-button-alignment').on('change', function() {
                const alignment = $(this).val().toLowerCase();
                console.log($('.form-submit-btn').parents('.form-submit-btn-container'));

                $('.form-submit-btn').parents('.form-submit-btn-container').css('text-align', alignment);
            });
            $('#form-button-type').on('change', function() {
                const type = $(this).val().toLowerCase();
                if (type == 'rounded') {
                    $('.form-submit-btn').addClass('rounded-pill');
                } else {
                    $('.form-submit-btn').removeClass('rounded-pill');
                }
            });

            $('input[name="roundedButton"]').on('change', function() {
                if ($(this).val() === 'yes') {
                    $('.form-submit-btn').addClass('rounded-pill');
                } else {
                    $('.form-submit-btn').remove('rounded-pill');
                }
            });

            // Update background settings dynamically
            $('#form-bg-color').on('input', function() {
                $('.canvas-container').css('background-color', $(this).val());
            });

            $('#form-bg-text-color').on('input', function() {
                let colorValue = $(this).val();
                $('.canvas-container').find('*').not('.element-wrapper, .element-wrapper *').each(
                    function() {
                        $(this).css('color', ''); // Clear existing color style
                        this.style.setProperty('color', colorValue, 'important');
                    });
            });

            $('#form-bg-url').on('input', function() {
                const bgUrl = $(this).val();
                if (bgUrl) {
                    $('.canvas-container').css('background-image', `url(${bgUrl})`);
                    $('.canvas-container').css('background-size',
                        'cover'); // Make sure the image covers the background
                    $('.canvas-container').css('background-repeat', 'no-repeat'); // No repeat of the image
                } else {
                    $('.canvas-container').css('background-image', 'none');
                }
            });
            $(".trigger-copy").on("click", function() {
                // Get the value of the hidden input field
                var inputValue = $("#jsonInput").val();

                // Select and copy the text from the temporary textarea
                jsonInput.select();
                document.execCommand("copy");

                // Optionally, give feedback to the user
                $(this).find("i").removeClass("bi-copy").addClass("bi-check-circle");

                // Reset the icon back after 2 seconds
                setTimeout(() => {
                    $(this)
                        .find("i")
                        .removeClass("bi-check-circle")
                        .addClass("bi-copy");
                }, 2000);
            });

            $(".trigger-fullscreen").on("click", function() {
                $(".canvas-container").toggleClass("full-screen");

                // Change the icon based on whether full-screen is active
                const icon = $(this).find("i");
                if ($(".canvas-container").hasClass("full-screen")) {
                    icon.removeClass("bi-eye-fill").addClass("bi-fullscreen-exit");
                } else {
                    icon.removeClass("bi-fullscreen-exit").addClass("bi-eye-fill");
                }
            });

            $("#open-code-modal").on("click", function() {
                //Open the paste modal #codeModal
                $("#codeModal").modal("show");

                var inputValue = $("#form_data_json").val();
                $("#jsonInput").val(inputValue);
                $("#paste-form-data").on("click", function() {
                    // Get the JSON data from the textarea
                    var jsonData = $("#jsonInput").val();
                    // console.log(jsonData);

                    // Validate JSON
                    try {
                        const defaultItems = JSON.parse(jsonData);
                        addDefaultItems(defaultItems);

                        // Close the modal    
                        $('#codeModal').modal('hide');
                    } catch (e) {
                        console.log(e);
                    }
                });
            });



            $('#save_new_form').on('click', function(e) {
                e.preventDefault();

                const form_data_json = $("#form_data_json").val();
                const formData = JSON.parse(form_data_json);

                let formLabels = [];
                const expected_form = [
                    // "First Name",
                    // "Last Name",
                    // "Phone Number",
                    // "Whatsapp Phone Number",
                    // "Email",
                    // "State",
                    // "City",
                    // "Address"
                ];

                formData.forEach(element => {
                    if (element?.type == 'form') {
                        formLabels.push(element?.config?.label)
                    }
                });

                // Check if all required labels exist in the form labels
                const missingFields = expected_form.filter(field => !formLabels.includes(field));

                if (missingFields.length > 0) {
                    // Show a confirmation dialog if fields are missing
                    const confirmMessage =
                        `The following required fields are missing: ${missingFields.join(', ')}. This may be required for communicating with the customer. Do you still want to proceed?`;
                    if (confirm(confirmMessage)) {
                        // Proceed if the user confirms
                        $('#form-data').submit();
                    } else {
                        // If the user cancels, you can return early
                        return;
                    }
                } else {
                    // All required fields are present, proceed with the form submission
                    $('#form-data').submit();
                }


            });

        });
    </script>


    <script>
        $(document).ready(function() {
            const $orderbumpOptions = $('#orderbumpOptions');
            const $switchOrderbumpOn = $('.switch_orderbump #on');
            const $switchOrderbumpOff = $('.switch_orderbump #off');

            function toggleOrderbumpOptions() {
                if ($switchOrderbumpOn.is(':checked')) {
                    $orderbumpOptions.removeClass('d-none');
                } else {
                    $orderbumpOptions.addClass('d-none');
                }
            }

            // Initial check when the page loads
            toggleOrderbumpOptions();

            // Event listeners for radio buttons
            $switchOrderbumpOn.on('change', toggleOrderbumpOptions);
            $switchOrderbumpOff.on('change', toggleOrderbumpOptions);
        });
    </script>

    <script>
        $(document).ready(function() {
            const $upsellOptions = $('#upsellOptions');
            const $switchupsellOn = $('.switch_upsell #on');
            const $switchupsellOff = $('.switch_upsell #off');

            function toggleupsellOptions() {

                if ($switchupsellOn.is(':checked')) {
                    $upsellOptions.removeClass('d-none');
                } else {
                    $upsellOptions.addClass('d-none');
                }
            }

            // Initial check when the page loads
            toggleupsellOptions();

            // Event listeners for radio buttons
            $switchupsellOn.on('change', toggleupsellOptions);
            $switchupsellOff.on('change', toggleupsellOptions);
        });
    </script>

    <script>
        $(document).ready(function() {
            const $downsellOptions = $('#downsellOptions');
            const $switchdownsellOn = $('.switch_downsell #on');
            const $switchdownsellOff = $('.switch_downsell #off');

            function toggledownsellOptions() {

                if ($switchdownsellOn.is(':checked')) {
                    $downsellOptions.removeClass('d-none');
                } else {
                    $downsellOptions.addClass('d-none');
                }
            }

            // Initial check when the page loads
            toggledownsellOptions();

            // Event listeners for radio buttons
            $switchdownsellOn.on('change', toggledownsellOptions);
            $switchdownsellOff.on('change', toggledownsellOptions);
        });
    </script>
@endsection
