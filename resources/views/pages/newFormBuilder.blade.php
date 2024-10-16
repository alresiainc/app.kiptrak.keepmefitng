@extends('layouts.design')
@section('title')
    Form Builder
@endsection

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

        .full-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 9999;
            /* To bring it to the top layer */
            background: white;
            /* Optional, set the background color as needed */
            max-height: 100vh;
        }

        .trigger-fullscreen {
            float: right;
            border: 1px solid #dde1e5;
            padding: 0px 10px;
            cursor: pointer;
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
            margin-top: 50px;
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



        .canvas-element {
            padding: 8px;

            align-items: center;
        }

        .canvas-element .item-move i {
            cursor: move;
        }

        .canvas-element .item-remove i {
            cursor: pointer;
            color: red;
        }

        .canvas-element .item-move,
        .canvas-element .item-remove {
            display: none;
        }

        .canvas-element:hover {
            border: 1px dashed #ccc;
            border-radius: 3px;
        }

        .canvas-element:hover .item-move,
        .canvas-element:hover .item-remove {
            display: block;
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
            background-color: #f9f9f9;
        }

        .product-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        /* Image Styling */
        .product-item .product-img-container {
            width: 100px;
            height: 100px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
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
        }

        .product-item .product-title {
            font-size: 1rem;
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
            width: 15px;
            height: 15px;
            border-radius: 50%;
            border: 2px solid transparent;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        .product-item .color-circle:hover {
            width: 18px;
            height: 18px;
        }

        /* Checked State for Color Circles */
        .color-radio:checked+.color-circle {
            width: 18px;
            height: 18px;
        }

        /* Size Box */
        .product-item .size-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border: 2px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            transition: border-color 0.3s, background-color 0.3s;
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
    </style>
@endsection

@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Form Builder</h1>
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


        <section class="mt-5">
            <div class="container" id="form">
                <form id="form-data" action="{{ route('newFormBuilderPost') }}" method="POST">
                    @csrf
                    <input type="hidden" name="form_data_json" id="form_data_json">

                    <h5 title="Unique Form Code" class="text-center mb-3">Form Code: {{ $form_code }}</h5>
                    <input type="hidden" name="form_code" value="{{ $form_code }}">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="">
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" id=""
                                    placeholder="Enter Form Name" value="{{ old('name') }}">
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
                            <div class="canvas-container">
                                <div class="element-wrapper">
                                    <div class="text-muted text-xs text-center my-2">
                                        Drag items from the list below to the form area <i
                                            class="bi bi-info-circle-fill ms-2"></i>
                                        <span class="trigger-fullscreen" data-bs-toggle="tooltip" data-bs-placement="auto"
                                            data-bs-title="Preview the form in full screen mode">
                                            <i class="bi bi-arrows-fullscreen"></i>
                                        </span>
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
                                                data-bs-title="Add product">
                                                <i class="bi bi-box me-2"></i> <span
                                                    class="d-none d-md-block">Product</span>
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
                                    <div class="drop-container">
                                        <div class="sortable-placeholder">
                                            <div class="text-center">
                                                <i class="bi bi-menu-button-fill" style="font-size: 50px;"></i>
                                                <h5>Work Space. </h5>
                                                <p>Drag items here.</p>
                                            </div>
                                        </div>
                                        <!-- Dropped elements will appear here -->
                                    </div>
                                    <div class="my-3 form-submit-btn-container" style="text-align: center;">
                                        <button type="button" class="rounded-pill w-50 p-2 form-submit-btn"
                                            style="background-color: #04512d; color: #ffffff; border:0;">Submit
                                            Order</button>
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

                                                <p class="text-sm">Selected an item in the workspace to show it properties
                                                    here.</p>
                                            </div>
                                        </div>
                                        <div id="form-properties">

                                        </div>
                                        <!-- Dynamic form fields for properties will go here -->

                                    </div>

                                    <div class="tab-pane fade" id="settings" role="tabpanel"
                                        aria-labelledby="settings-tab">
                                        <div class="accordion" id="settingsAccordion">
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
                                                    aria-labelledby="buttonSettings" data-bs-parent="#settingsAccordion">
                                                    <div class="accordion-body">
                                                        <div class="form-group">
                                                            <label class="form-label d-block">Text:</label>
                                                            <input type="text" id="form-button-text"
                                                                class="form-control form-control-sm mb-2"
                                                                value="Submit Order">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label d-block">Background:</label>
                                                            <input type="color" id="form-button-bg"
                                                                class="form-control form-control-sm mb-2" value="#04512d">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label d-block">Color:</label>
                                                            <input type="color" id="form-button-color"
                                                                class="form-control form-control-sm mb-2" value="#ffffff">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label d-block">Alignment:</label>
                                                            <select class="form-control form-control-sm mb-2"
                                                                id="form-button-alignment">
                                                                <option>Left</option>
                                                                <option selected>Center</option>
                                                                <option>Right</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Button Type:</label>
                                                            <select class="form-control form-control-sm mb-2"
                                                                id="form-button-type">
                                                                <option>Regular</option>
                                                                <option selected>Rounded</option>
                                                            </select>
                                                        </div>
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
                                                    aria-labelledby="bgSettings" data-bs-parent="#settingsAccordion">
                                                    <div class="accordion-body">
                                                        <label>Background Color:</label>
                                                        <input type="color" id="form-bg-color"
                                                            class="form-control form-control-sm mb-2" value="#ffffff">
                                                        <label>Background Image URL:</label>
                                                        <input type="url" id="form-bg-url"
                                                            class="form-control form-control-sm mb-2" value="">
                                                        <label>Background Text Color:</label>
                                                        <input type="color" id="form-bg-text-color"
                                                            class="form-control form-control-sm mb-2">

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

                                                        <div class="mt-3">
                                                            <label for="" class="form-label">Heading |
                                                                Optional</label>
                                                            <input type="text" name="orderbump_heading"
                                                                id="editOrderbump_heading"
                                                                class="form-control form-control-sm" value="">
                                                        </div>

                                                        <div class="mt-3">
                                                            <label for="" class="form-label">Sub Heading |
                                                                Optional</label>
                                                            <div class="row" id="myList">
                                                                {{-- <div class="col-md-12 mb-2">
                                                                <input type="text" class="form-control form-control-sm" value="lorem color">
                                                              </div> --}}


                                                            </div>
                                                        </div>

                                                        <div class="mt-3">
                                                            <div class="product-clone-section wrapper2">

                                                                <div class="col-md-12 mt-1 element2">
                                                                    <label for="" class="form-label">More Sub
                                                                        Headings | Optional</label>
                                                                    <input type="text" name="orderbump_subheading[]"
                                                                        class="form-control form-control-sm"
                                                                        placeholder="" value="">
                                                                </div>

                                                                <!--append elements to-->
                                                                <div class="results2"></div>

                                                                <div class="buttons d-flex justify-content-between">
                                                                    <button type="button"
                                                                        class="clone2 btn btn-success btn-sm rounded-pill"><i
                                                                            class="bi bi-plus"></i></button>
                                                                    <button type="button"
                                                                        class="remove2 btn btn-danger btn-sm rounded-pill"><i
                                                                            class="bi bi-dash"></i></button>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="mt-3">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="" class="form-label">Actual Sale
                                                                        Price</label>
                                                                    <input type="number"
                                                                        name="product_actual_selling_price"
                                                                        id="productActualSellingPrice"
                                                                        class="form-control form-control-sm"
                                                                        placeholder="" readonly value="">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="" class="form-label">Assumed Sale
                                                                        Price</label>
                                                                    <input type="number"
                                                                        name="product_assumed_selling_price"
                                                                        id="productAssumedSellingPrice"
                                                                        class="form-control form-control-sm"
                                                                        placeholder="" value="">
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="mt-3" id="orderbumpProductSelectWrapper">
                                                            <label for="orderbump_product" class="form-label">Select
                                                                Product Package</label>
                                                            <select id="orderbumpProductSelect" name="orderbump_product"
                                                                data-live-search="true"
                                                                class="form-control form-control-sm border @error('orderbump_product') is-invalid @enderror">
                                                                <option value="">Nothing Selected</option>
                                                                @if (count($products) > 0)
                                                                    @foreach ($products as $product)
                                                                        <option value="{{ $product->id }}"
                                                                            class="selected_option">{{ $product->name }}
                                                                            @<span
                                                                                class="product_sale_price">{{ $product->sale_price }}</span>
                                                                        </option>
                                                                    @endforeach
                                                                @endif

                                                            </select>

                                                            @error('orderbump_product')
                                                                <span class="invalid-feedback mb-3" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>

                                                        <div class="mt-3 d-flex align-items-center" style="gap: 20px;">
                                                            <div class='category'>
                                                                <input type="radio" name="switch_orderbump"
                                                                    value="on" id="on" checked />
                                                                <label for="on" class="ml-1">On</label>
                                                            </div>

                                                            <div class='category'>
                                                                <input type="radio" name="switch_orderbump"
                                                                    value="off" id="off" />
                                                                <label for="off">Off</label>
                                                            </div>
                                                        </div>

                                                        <div class="mt-3 d-none">
                                                            <label for="" class="form-label">Discount
                                                                Amount</label>
                                                            <input type="text" name="orderbump_discount"
                                                                class="form-control form-control-sm" value="">
                                                        </div>



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

                                                        <div class="mt-3 d-none">
                                                            <label for="" class="form-label">Heading</label>
                                                            <input type="text" name="upsell_heading"
                                                                id="editUpsell_heading" class="form-control"
                                                                value="">
                                                        </div>

                                                        <div class="mt-3 d-none">
                                                            <label for="" class="form-label">Sub Heading</label>
                                                            <textarea name="upsell_subheading" id="editUpsell_subheading" cols="30" rows="5"
                                                                class="mytextarea form-control"></textarea>
                                                        </div>

                                                        <div class="mt-3">
                                                            <label for="upsell_product" class="form-label">Select
                                                                Template</label>
                                                            <select name="upsell_setting_id" id="upsell_setting_id"
                                                                data-live-search="true"
                                                                class="form-control form-control-sm border @error('upsell_product') is-invalid @enderror"
                                                                id="">

                                                                @if (isset($formHolder->upsell_id) && isset($formHolder->upsell->template->id))
                                                                    <option
                                                                        value="{{ $formHolder->upsell->template->id }}">
                                                                        {{ $formHolder->upsell->template->template_code }}
                                                                    </option>
                                                                @endif

                                                                @if (count($upsellTemplates) > 0)
                                                                    @foreach ($upsellTemplates as $template)
                                                                        <option value="{{ $template->id }}">
                                                                            {{ $template->template_code }}</option>
                                                                    @endforeach
                                                                @endif

                                                            </select>

                                                            @error('upsell_product')
                                                                <span class="invalid-feedback mb-3" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>

                                                        <div class="mt-3">
                                                            <label for="upsell_product" class="form-label">Select Product
                                                                Package</label>
                                                            <select name="upsell_product" id="upsellProductSelect"
                                                                data-live-search="true"
                                                                class="form-control form-control-sm border @error('upsell_product') is-invalid @enderror"
                                                                id="">

                                                                @if (isset($formHolder->upsell_id) && isset($formHolder->upsell->product->id))
                                                                    <option
                                                                        value="{{ $formHolder->upsell->product->id }}">
                                                                        {{ $formHolder->upsell->product->name }}</option>
                                                                @endif

                                                                @if (count($products) > 0)
                                                                    @foreach ($products as $product)
                                                                        <option value="{{ $product->id }}">
                                                                            {{ $product->name }}</option>
                                                                    @endforeach
                                                                @endif

                                                            </select>

                                                            @error('upsell_product')
                                                                <span class="invalid-feedback mb-3" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>

                                                        <div class="mt-3 d-flex align-items-center" style="gap: 20px;">
                                                            <div class='category'>
                                                                <input type="radio" name="switch_upsell" value="on"
                                                                    id="on" checked />
                                                                <label for="on" class="ml-1">On</label>
                                                            </div>

                                                            <div class='category'>
                                                                <input type="radio" name="switch_upsell" value="off"
                                                                    id="off" />
                                                                <label for="off">Off</label>
                                                            </div>
                                                        </div>
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




    </main>


@endsection

@section('extra_js')
    <script src="{{ asset('/assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('/myassets/js/my-form-builder.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>

    <script>
        $(document).ready(function() {


            $(".draggable").draggable({
                helper: "clone",
                revert: "invalid",
                connectToSortable: ".drop-container",
            });

            // Initialize the sortable functionality
            $(".drop-container")
                .sortable({
                    placeholder: "sortable-placeholder",
                    start: function(event, ui) {
                        // Hide the placeholder when dragging starts
                        $(".sortable-placeholder").hide();
                    },
                    stop: function(event, ui) {
                        // Show the placeholder if there are no items left
                        if ($(".drop-container").children().not('.sortable-placeholder').length === 0) {
                            $(".sortable-placeholder").show();
                        }

                        // Check if the item being sorted is not already part of the canvas
                        if (!ui.item.hasClass("canvas-element")) {
                            const type = ui.item.data("type");
                            addElementToCanvas(type, ui.item);
                        }
                    },
                    receive: function(event, ui) {
                        // Remove the placeholder when an item is received
                        $(".sortable-placeholder").hide();
                    }
                })
                .disableSelection();




            // Example addElementToCanvas function to handle adding elements
            function addElementToCanvas(type, item) {
                // Add your logic here to handle the element being added to the canvas
                item.addClass('canvas-element'); // Add a class to identify canvas items
                console.log('Element added to canvas:', type);
            }

            $(document).on('click', ".item-remove i", function() {
                $(this).parents('.canvas-element').remove();
                const form = $("#form-properties");
                form.empty();
                $(".properties-placeholder").show();
                if ($(".drop-container").children().not('.sortable-placeholder').length === 0) {
                    $(".sortable-placeholder").show();
                }
            })


            // Function to update the data-config attribute
            function updateConfig(element, type, current) {
                let config = {};

                // For different types, set the config properties accordingly
                switch (type) {
                    case 'form':
                        config = {
                            label: element.prev("label").text() || "",
                            size: element.attr("class") || "form-control",
                            type: element.attr("type") || "text",
                            placeholder: element.attr("placeholder") || "",
                            defaultValue: element.val() || "",
                            required: element.prop("required") || false,
                            options: element.is("select") ? element.find("option").map(function() {
                                return $(this).text();
                            }).get() : [],
                        };
                        break;

                    case 'text':
                        config = {
                            mode: 'simple',
                            color: element.css("color") || "",
                            fontWeight: element.css("font-weight") || "",
                            fontFamily: element.css("font-family") || "",
                            fontSize: element.css("font-size") || "",
                            fontStyle: element.css("font-style") || "",
                            textAlign: element.css("text-align") || "",
                            textTransform: element.css("text-transform") || "",
                            textDecoration: element.css("text-decoration") || "",
                            content: element.text() || "",
                        };
                        break;

                    case 'image':
                        config = {
                            width: element.css("width") || "",
                            height: element.css("height") || "",
                            textAlign: element.parents('.canvas-element')?.css("text-align") || "",
                            src: element.attr("src") || ""
                        };
                        break;

                    case 'seperator':
                        config = {
                            width: element.css("width") || "",
                            height: element.css("height") || ""
                        };
                        break;

                    case 'product':
                        config = {
                            package_choice: element.css("width") || "",
                        };
                        break;

                    default:
                        console.warn('Unknown type:', type);
                }

                // Add properties common for all types
                config.marginBottom = element.parents('.canvas-element')?.css("margin-bottom") || "";
                config.marginTop = element.parents('.canvas-element')?.css("margin-top") || "";
                config.marginLeft = element.parents('.canvas-element')?.css("margin-left") || "";
                config.marginRight = element.parents('.canvas-element')?.css("margin-right") || "";

                // Merge the current object into the config object
                Object.assign(config, current);

                var canvasElement = element.parents('.canvas-element');
                let data = {
                    type: type,
                    config: config
                };

                // Update the attribute with the new configuration
                canvasElement.attr('data-config', JSON.stringify(data));

                //Update all config input
                const formConfig = collectFormConfig();
                $('#form_data_json').val(JSON.stringify(data));
            }

            function text_field(form, element) {
                var canvasElement = element.parents('.canvas-element');
                var data = canvasElement?.data('config');
                let config = data?.config ? data?.config : {
                    mode: 'simple',
                    color: element.css("color") || "",
                    fontWeight: element.css("font-weight") || "",
                    fontFamily: element.css("font-family") || "",
                    fontSize: element.css("font-size") || "",
                    fontStyle: element.css("font-style") || "",
                    textAlign: element.css("text-align") || "",
                    textTransform: element.css("text-transform") || "",
                    textDecoration: element.css("text-decoration") || "",
                    content: element.text() || "",
                    marginBottom: element.parents('.canvas-element')?.css("margin-bottom") || "",
                    marginTop: element.parents('.canvas-element')?.css("margin-top") || "",
                    marginLeft: element.parents('.canvas-element')?.css("margin-left") || "",
                    marginRight: element.parents('.canvas-element')?.css("margin-right") || "",
                };
                // Initial HTML structure with a toggle for Simple/Advanced
                form.append(`
            <div class="toggle-editor">
                <label class="propertiy-label">Editor Mode:</label>
                <select class="form-control form-control-sm" id="editor-mode">
                    <option value="simple">Simple Text</option>
                    <option value="advanced">Advanced Text</option>
                </select>
            </div>

            <div id="simple-editor" class="editor-content">
                <label class="propertiy-label">Content:</label>
                <textarea class="form-control form-control-sm" id="text-content">${element.text()}</textarea>
                <h6>Attributes</h6>
                <label class="propertiy-label">Text Color:</label>
                <input class="form-control form-control-sm" type="color" id="text-color" value="${element.css("color")}">
                <label class="propertiy-label">Text Size:</label>
                <div class="input-group input-group-sm mb-2">
                    <input class="form-control form-control-sm" type="number" id="font-size" value="${element.css("font-size").replace("px", "")}">
                    <span>px</span>
                </div>
                <label class="propertiy-label">Font Family:</label>
                <select class="form-control form-control-sm" id="text-font">
                    <option value="Arial">Arial</option>
                    <option value="Times New Roman">Times New Roman</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Courier New">Courier New</option>
                    <option value="Verdana">Verdana</option>
                </select>
                <label class="propertiy-label">Text Weight:</label>
                <select class="form-control form-control-sm" id="text-weight">
                    <option value="normal">Normal</option>
                    <option value="bold">Bold</option>
                    <option value="bolder">Bolder</option>
                    <option value="lighter">Lighter</option>
                    <option value="100">100 - Thin</option>
                    <option value="200">200 - Extra Light</option>
                    <option value="300">300 - Light</option>
                    <option value="400">400 - Normal</option>
                    <option value="500">500 - Medium</option>
                    <option value="600">600 - Semi Bold</option>
                    <option value="700">700 - Bold</option>
                    <option value="800">800 - Extra Bold</option>
                    <option value="900">900 - Black</option>
                </select>
                <label class="propertiy-label">Text Style:</label>
                <select class="form-control form-control-sm" id="text-style">
                    <option value="normal">Normal</option>
                    <option value="italic">Italic</option>
                    <option value="oblique">Oblique</option>
                    <option value="underline">Underline</option>
                    <option value="line-through">Line-through</option>
                    <option value="overline">Overline</option>
                    <option value="none">None</option>
                    <option value="uppercase">Uppercase</option>
                    <option value="lowercase">Lowercase</option>
                    <option value="capitalize">Capitalize</option>
                </select>
                <label class="propertiy-label">Alignment:</label>
                <select class="form-control form-control-sm" id="text-alignment">
                    <option value="left">Left</option>
                    <option value="center">Center</option>
                    <option value="right">Right</option>
                    <option value="justify">Justify</option>
                </select>
            </div>

            <div id="advanced-editor" class="editor-content" style="display: none;">
                <label class="propertiy-label">Content:</label>
                <textarea id="tinymce-editor">${element.html()}</textarea>
            </div>
            

        
        `);

                // Initialize TinyMCE for the advanced editor
                tinymce.init({
                    selector: '#tinymce-editor',
                    menubar: false,
                    plugins: 'lists link image table code',
                    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image | code',
                    setup: function(editor) {
                        editor.on('keyup', function() {
                            element.html(editor.getContent());
                        });
                    }
                });

                // Toggle between simple and advanced editors
                $("#editor-mode").on("change", function() {
                    const mode = $(this).val();
                    if (mode === "simple") {
                        $("#simple-editor").show();
                        $("#advanced-editor").hide();
                    } else {
                        $("#simple-editor").hide();
                        $("#advanced-editor").show();
                        tinymce.get("tinymce-editor").setContent(element.html());
                    }
                    updateConfig(element, 'text', {
                        mode: mode
                    })
                });

                // Simple Editor Event Listeners
                $("#text-content").on("input", function() {
                    element.text($(this).val());
                    updateConfig(element, 'text', {
                        content: $(this).val()
                    });
                });

                $("#text-color").on("input", function() {
                    element.css("color", $(this).val());
                    updateConfig(element, 'text', {
                        color: $(this).val()
                    });
                });

                $("#font-size").on("input", function() {
                    element.css("font-size", $(this).val() + "px");
                    updateConfig(element, 'text', {
                        fontSize: $(this).val() + "px"
                    });
                });

                $("#text-font").val(element.css("font-family"));
                $("#text-font").on("change", function() {
                    element.css("font-family", $(this).val());
                    updateConfig(element, 'text', {
                        fontFamily: $(this).val()
                    });
                });

                $("#text-alignment").val(element.css("text-align"));
                $("#text-alignment").on("change", function() {
                    element.css("text-align", $(this).val());
                    updateConfig(element, 'text', {
                        textAlign: $(this).val()
                    });
                });

                $("#text-weight").val(element.css("font-weight"));
                $("#text-weight").on("change", function() {
                    element.css("font-weight", $(this).val());
                    updateConfig(element, 'text', {
                        fontWeight: $(this).val()
                    });
                });

                $("#text-style").val(getInitialTextStyle(element));
                $("#text-style").on("change", function() {
                    const selectedStyle = $(this).val();
                    element.css({
                        "font-style": "normal",
                        "text-decoration": "none",
                        "text-transform": "none"
                    });
                    switch (selectedStyle) {
                        case "italic":
                        case "oblique":
                            element.css("font-style", selectedStyle);
                            updateConfig(element, 'text', {
                                fontStyle: selectedStyle
                            });
                            break;
                        case "underline":
                        case "line-through":
                        case "overline":
                            element.css("text-decoration", selectedStyle);
                            updateConfig(element, 'text', {
                                textDecoration: selectedStyle
                            });
                            break;
                        case "uppercase":
                        case "lowercase":
                        case "capitalize":
                            element.css("text-transform", selectedStyle);
                            updateConfig(element, 'text', {
                                textTransform: selectedStyle
                            });
                            break;
                        case "normal":
                        default:
                            break;
                    }

                });

                // Helper function to get initial text style
                function getInitialTextStyle(element) {
                    const fontStyle = element.css("font-style");
                    const textDecoration = element.css("text-decoration");
                    const textTransform = element.css("text-transform");

                    if (fontStyle === "italic" || fontStyle === "oblique") {
                        return fontStyle;
                    } else if (textDecoration.includes("underline")) {
                        return "underline";
                    } else if (textDecoration.includes("line-through")) {
                        return "line-through";
                    } else if (textDecoration.includes("overline")) {
                        return "overline";
                    } else if (textTransform === "uppercase") {
                        return "uppercase";
                    } else if (textTransform === "lowercase") {
                        return "lowercase";
                    } else if (textTransform === "capitalize") {
                        return "capitalize";
                    }

                    return "normal";
                }

                updateConfig(element, 'text', config);
            }

            function product_field(form, element) {
                var canvasElement = element.parents('.canvas-element');
                var data = canvasElement?.data('config');
                let config = data?.config ? data?.config : {
                    label: element.prev(".product-label").text() || "",
                    package_choice: 'package_single',
                    marginBottom: element.parents('.canvas-element')?.css("margin-bottom") || "",
                    marginTop: element.parents('.canvas-element')?.css("margin-top") || "",
                    marginLeft: element.parents('.canvas-element')?.css("margin-left") || "",
                    marginRight: element.parents('.canvas-element')?.css("margin-right") || "",
                };
                var container = $('<div>');
                container.attr({
                    "class": "mb-3 w-100"
                });
                // Add the initial three select fields by default
                for (var i = 0; i < 3; i++) {
                    package_field(container, "product", "Select Product");
                }
                form.append(`<div><label class="propertiy-label">Label:</label>
            <input type="text" class="form-control form-control-sm" id="product-label" value="${config.label}">
            </div>
            `);

                form.append(`<label>Select Products</label>`);
                form.append(container);
                form.append(
                    '<button type="button" class="add_package btn btn-sm btn-success border mt-2"><i class="bi bi-plus"></i> Add option</button><br>'
                );
                $('.add_package').click(function() {
                    package_field(container, "product", "Select Product");
                });
                form.append(`
            <label class="propertiy-choice">Package Choice:</label>
                <select class="form-control form-control-sm" id="package-choice">
                    <option value="package_single">Multi-Choice Package (single option)</option>
                    <option value="package_multi">Multi-Choice Package (multiple option)</option>
                </select>
        `);




                $("#product-label").on("input", function() {

                    config.label = $(this).val();
                    if (element.prev(".product-label").length) {
                        element.prev(".product-label").text(config.label);
                    } else {
                        $("<label class='propertiy-label'>" + config.label + "</label>").insertBefore(
                            element);
                    }
                    updateConfig(element, 'form', {
                        label: config.label
                    });
                });

                $("#package-choice").on("change", function() {
                    const choice = $(this).val();
                    if (choice === "package_single") {
                        displaySelectedValues(element, 'radio')
                    } else {
                        displaySelectedValues(element, 'checkbox')
                    }
                    updateConfig(element, 'product', {
                        package_choice: choice
                    })
                });


                $(document).on('change', '.product-selector', function() {
                    console.log('field changed');
                    // Call the function to display all selected values
                    displaySelectedValues(element);
                });

                // Define a function to gather and display all selected values from all select fields
                function displaySelectedValues(element, type = null) {
                    var canvasElement = element.parents('.canvas-element');
                    var data = canvasElement?.data('config');
                    let package_choice = $('#package-choice').val();


                    if (!type) {
                        if (package_choice === "package_single") {
                            type = 'radio';
                        } else {
                            type = 'checkbox';
                        }
                    }

                    console.log(type);

                    // Clear previous content before appending the updated HTML
                    element.html('');
                    // Get all product-selector elements
                    $('.product-selector').each(function() {
                        // Find the currently selected option within this select field
                        var selectedOption = $(this).find('option:selected');
                        var productId = selectedOption.data('id');
                        let productHtml;


                        // Retrieve data attributes from the selected option and set default values if they are undefined or null
                        var availableColors = selectedOption.data('available-colors') ||
                    []; // Default to an empty array if null or undefined
                        var amount = selectedOption.data('amount') ||
                            '0'; // Default to '0' if not available
                        var currency = selectedOption.data('currency') ||
                            'NGN'; // Default to 'NGN' if not available
                        var imageUrl = selectedOption.data('image-url') ||
                            'https://via.placeholder.com/150'; // Default image placeholder
                        var availableSizes = selectedOption.data('available-sizes') || [
                            '1'
                        ]; // Default to an empty array if null or undefined
                        var totalQuantity = selectedOption.data('total-quantity') ||
                            1; // Default to 1 if not available

                        // Generate HTML for the product based on selected values
                        if (productId) {
                            productHtml = `
                    <label class="product_field form-label me-3 product-item p-3 rounded shadow-sm">
                        <span class="product-title me-1 fw-bold mb-2">${amount} ${currency}</span>
                        <div class="d-flex align-items-start align-items-center">
                            <input type="${type}" name="product_packages[]" class="me-3 product-package">
                            <div class="d-flex flex-column flex-md-row w-100 align-items-start">
                                <div class="product-img-container me-3">
                                    <img class="product-img img-fluid rounded" src="${imageUrl}" alt="Image">
                                </div>
                                <div class="product-info d-flex flex-column">
                                    <select name="select_product_qty" class="select_product_qty form-control mt-2">
                                        <option value="1">Select Quantity</option>
                                        ${[...Array(totalQuantity).keys()].map(i => `<option value="${i + 1}">${i + 1}</option>`).join('')}
                                    </select>
                                    <div class="color-options d-flex mt-3">
                                        ${availableColors?.map(color => `
                                                                                                                                                                                                                                                                                                                                                        <input type="radio" id="color-${color}" name="product_color" value="${color}" class="color-radio d-none">
                                                                                                                                                                                                                                                                                                                                                        <label for="color-${color}" class="color-circle" style="background-color: ${color};"></label>
                                                                                                                                                                                                                                                                                                                                                    `).join('')}
                                    </div>
                                    <div class="size-options d-flex mt-3">
                                        ${availableSizes?.map(size => `
                                                                                                                                                                                                                                                                                                                                                        <input type="radio" id="size-${size}" name="product_size" value="${size}" class="size-radio d-none">
                                                                                                                                                                                                                                                                                                                                                        <label for="size-${size}" class="size-box">${size}</label>
                                                                                                                                                                                                                                                                                                                                                    `).join('')}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </label>   
                `;
                        }


                        // Update the canvas-content element with the generated HTML
                        element.append(productHtml);

                        // Prevent interaction when the label is clicked
                        element.on('click', '.product_field', function(event) {
                            // event.preventDefault();
                            event.stopPropagation();

                        });
                    });
                }

                // Function to create a select field
                function create_select_field(_field, _text) {
                    var select = $('.package_select').val()
                    var element = $(select);
                    element.attr({
                        "class": "select2 form-control form-control-sm product-selector",
                    });
                    return element;
                }

                // Function to create a package field (row with select and remove button)
                function package_field(_this, _field, _text = "Option") {


                    // Remove button setup
                    var rem = $(
                        "<button class='btn btn-sm btn-default ms-2' type='button'><span class='bi bi-x-lg'></span></button>"
                    );
                    // rem.append(removeButton);
                    rem.click(function() {
                        $(this).closest('.product-container').remove();
                        displaySelectedValues(element);
                    });

                    var chk = create_select_field(_field, _text);
                    // chk.append(item);

                    // The row containing the checkbox and the remove button
                    var el = $("<div class='d-flex align-items-center product-container mb-2 w-100'>");

                    el.append(chk);
                    el.append(rem);

                    _this.append(el);

                    // Initialize Select2 for newly added select fields
                    el.find('.select2').select2();
                }


            }

            function image_field(form, element) {
                var canvasElement = element.parents('.canvas-element');
                var data = canvasElement?.data('config');
                let config = data?.config ? data?.config : {
                    width: element.css("width") || "",
                    height: element.css("height") || "",
                    textAlign: element.parents('.canvas-element')?.css("text-align") || "",
                    src: element.attr("src") || "",
                    marginBottom: element.parents('.canvas-element')?.css("margin-bottom") || "",
                    marginTop: element.parents('.canvas-element')?.css("margin-top") || "",
                    marginLeft: element.parents('.canvas-element')?.css("margin-left") || "",
                    marginRight: element.parents('.canvas-element')?.css("margin-right") || "",
                };

                // Image Element Properties
                form.append(`
            <label>Image Source:</label>
            <input class="form-control form-control-sm" type="text" id="image-src" value="${element.attr("src")}">
            <label>Width:</label>
            <input class="form-control form-control-sm" type="number" id="image-width" value="${element.css("width").replace("px", "")}">
            <label>Height:</label>
            <input class="form-control form-control-sm" type="number" id="image-height" value="${element.css("height").replace("px", "")}">
            <label class="propertiy-label">Alignment:</label>
            <select class="form-control form-control-sm" id="image-alignment">
                <option value="left">Left</option>
                <option value="center">Center</option>
                <option value="right">Right</option>
            </select>

        `);

                // Event listeners for image properties
                $("#image-src").on("input", function() {
                    element.attr("src", $(this).val());
                    updateConfig(element, 'image', {
                        src: $(this).val()
                    });
                });

                $("#image-width").on("input", function() {
                    element.css("width", $(this).val() + "px");
                    updateConfig(element, 'image', {
                        width: $(this).val() + "px"
                    });
                });

                $("#image-height").on("input", function() {
                    element.css("height", $(this).val() + "px");
                    updateConfig(element, 'image', {
                        height: $(this).val() + "px"
                    });
                });
                $("#image-alignment").on("input", function() {
                    element.parent().css("text-align", $(this).val());
                    updateConfig(element, 'image', {
                        textAlign: $(this).val()
                    });
                });

                updateConfig(element, 'image', config);
            }

            function seperator_field(form, element) {
                var canvasElement = element.parents('.canvas-element');
                var data = canvasElement?.data('config');
                let config = data?.config ? data?.config : {
                    width: element.css("width") || "",
                    height: element.css("height") || "",
                    marginBottom: element.parents('.canvas-element')?.css("margin-bottom") || "",
                    marginTop: element.parents('.canvas-element')?.css("margin-top") || "",
                    marginLeft: element.parents('.canvas-element')?.css("margin-left") || "",
                    marginRight: element.parents('.canvas-element')?.css("margin-right") || "",
                };

                // Image Element Properties
                form.append(`
            <label>Width:</label>
            <input class="form-control form-control-sm" type="number" id="seperator-width" value="${element.css("width").replace("px", "")}">
            <label>Height:</label>
            <input class="form-control form-control-sm" type="number" id="seperator-height" value="${element.css("height").replace("px", "")}">
        `);


                $("#seperator-width").on("input", function() {
                    element.css("width", $(this).val() + "px");
                    updateConfig(element, 'seperator', {
                        width: $(this).val() + "px"
                    });
                });

                $("#seperator-height").on("input", function() {
                    element.css("height", $(this).val() + "px");
                    updateConfig(element, 'seperator', {
                        height: $(this).val() + "px"
                    });
                });


                updateConfig(element, 'seperator', config);
            }

            function form_field(form, element) {
                // Retrieve existing config from the element or initialize it
                var canvasElement = element.parents('.canvas-element');
                var data = canvasElement?.data('config');
                let config = data?.config ? data?.config : {
                    label: element.prev("label").text() || "",
                    size: element.attr("class") || "form-control",
                    type: element.attr("type") || "text",
                    placeholder: element.attr("placeholder") || "",
                    defaultValue: element.val() || "",
                    required: element.prop("required") || false,
                    options: element.is("select") ? element.find("option").map(function() {
                        return $(this).text();
                    }).get() : [],

                    marginBottom: element.parents('.canvas-element')?.css("margin-bottom") || "",
                    marginTop: element.parents('.canvas-element')?.css("margin-top") || "",
                    marginLeft: element.parents('.canvas-element')?.css("margin-left") || "",
                    marginRight: element.parents('.canvas-element')?.css("margin-right") || "",
                };

                // Append input fields to the form
                form.append(`
            <label class="propertiy-label">Label:</label>
            <input type="text" class="form-control form-control-sm" id="input-label" value="${config.label}">
            <label class="propertiy-label">Size:</label>
            <select class="form-control form-control-sm" id="input-size">
                <option value="form-control form-control-xs">Extra Small (xs)</option>
                <option value="form-control form-control-sm">Small (sm)</option>
                <option value="form-control">Medium (md)</option>
                <option value="form-control form-control-lg">Large (lg)</option>
                <option value="form-control form-control-xl">Extra Large (xl)</option>
            </select>
            <label class="propertiy-label">Type:</label>
            <select class="form-control form-control-sm" id="input-type">
                <option value="text">Text</option>
                <option value="password">Password</option>
                <option value="email">Email</option>
                <option value="number">Number</option>
                <option value="file">File</option>
                <option value="select">Select</option>
                <option value="textarea">Textarea</option>
            </select>
            <div id="select-options-container" style="display: none;">
                <label class="propertiy-label">Select Options (Comma-separated):</label>
                <input type="text" class="form-control form-control-sm" id="select-options" value="${config.options.join(', ')}">
            </div>
            <label class="propertiy-label">Placeholder:</label>
            <input type="text" class="form-control form-control-sm" id="input-placeholder" value="${config.placeholder}">
            <label class="propertiy-label">Default Value:</label>
            <input type="text" class="form-control form-control-sm" id="input-default-value" value="${config.defaultValue}">
            <div class="form-check">
                <input class="form-check-input req-item" id="input-required" type="checkbox" ${config.required ? "checked" : ""}>
                <label class="form-check-label req-chk" for="input-required">
                    * Required
                </label>
            </div>
        `);

                // Set the selected size in the dropdown
                $("#input-size").val(config.size);
                $("#input-type").val(config.type);



                $("#input-label").on("input", function() {
                    config.label = $(this).val();
                    if (element.prev("label").length) {
                        element.prev("label").text(config.label);
                    } else {
                        $("<label class='propertiy-label'>" + config.label + "</label>").insertBefore(
                            element);
                    }
                    updateConfig(element, 'form', {
                        label: config.label
                    });
                });

                $("#input-size").on("change", function() {
                    config.size = $(this).val();
                    element.removeClass(
                        "form-control-xs form-control-sm form-control form-control-lg form-control-xl");
                    element.addClass(config.size);
                    updateConfig(element, 'form', {
                        size: config.size
                    });
                });

                $("#input-type").on("change", function() {
                    const selectedType = $(this).val();
                    config.type = selectedType;
                    const parent = element.parent();

                    let newElement; // Declare a variable for the new element

                    // Create the appropriate element based on the selected type
                    if (selectedType === "select") {
                        newElement = $("<select class='" + config.size +
                            " canvas-content form-control select2'></select>");
                        $("#select-options-container").show();
                        updateSelectOptions(); // Initialize with existing options
                    } else if (selectedType === "textarea") {
                        newElement = $("<textarea class='" + config.size +
                            " canvas-content form-control'></textarea>");
                        $("#select-options-container").hide();
                    } else {
                        newElement = $("<input type='" + selectedType + "' class='" + config.size +
                            " canvas-content form-control'>");
                        $("#select-options-container").hide();
                    }

                    // Replace the existing element with the new element
                    element.replaceWith(newElement);

                    // Reassign 'element' to the new jQuery object
                    element = newElement;

                    // Trigger change event on the newly created element
                    element.trigger("change");



                    // Update config
                    updateConfig(element, 'form', {
                        type: config.type
                    });
                });

                $("#input-placeholder").on("input", function() {
                    config.placeholder = $(this).val();
                    element.attr("placeholder", config.placeholder);
                    updateConfig(element, 'form', {
                        placeholder: $(this).val()
                    });
                });

                $("#input-default-value").on("input", function() {
                    config.defaultValue = $(this).val();
                    element.val(config.defaultValue);
                    updateConfig(element, 'form', {
                        defaultValue: $(this).val()
                    });
                });

                $("#input-required").on("change", function() {
                    config.required = $(this).is(":checked");
                    element.prop("required", config.required);
                    updateConfig(element, 'form', {
                        required: config.required
                    });

                });

                $("#select-options").on("input", function() {
                    updateSelectOptions();
                });

                function updateSelectOptions() {
                    const optionsString = $("#select-options").val();
                    const options = optionsString.split(",").map(option => option.trim());
                    config.options = options;

                    element.empty(); // Clear existing options
                    config.options.forEach(option => {
                        if (option) {
                            element.append(`<option value="${option}">${option}</option>`);
                        }
                    });

                    updateConfig(element, 'form', {
                        options
                    });
                }

                // Initialize the select options if the element is a select
                if (element.is("select")) {
                    $("#input-type").val("select");
                    $("#select-options-container").show();
                } else if (element.is("textarea")) {
                    $("#input-type").val("textarea");
                    $("#select-options-container").hide();
                }

                // Initial config update
                updateConfig(element, 'form', config);
            }


            function addElementToCanvas(type, item) {
                let element;

                switch (type) {
                    case "text":
                        element = $(
                            '<div class="canvas-element"><span class="item-remove text-center"><i class="bi bi-trash" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Remove item from form"></i></span><div class="canvas-content" contenteditable="true">Editable Text</div> <span class="item-move text-center"><i class="bi bi-grip-horizontal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Drag item to another position"></i></span></div>'
                        );
                        break;
                    case "form":
                        element = $(
                            '<div class="canvas-element"><span class="item-remove text-center"><i class="bi bi-trash" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Remove item from form"></i></span><div class="form-group w-100"><label class="form-label">Label</label><input class="canvas-input canvas-content form-control" type="text" placeholder="Enter value"></div> <span class="item-move text-center"><i class="bi bi-grip-horizontal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Drag item to another position"></i></span></div>'
                        );
                        break;
                    case "image":
                        element = $(
                            '<div class="canvas-element"><span class="item-remove text-center"><i class="bi bi-trash" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Remove item from form"></i></span><img class="canvas-img canvas-content img-fluid" src="https://via.placeholder.com/150" alt="Image" style="width:100px;"> <span class="item-move text-center"><i class="bi bi-grip-horizontal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Drag item to another position"></i></span></div>'
                        );
                        break;
                    case "product":
                        element = $(
                            '<div class="canvas-element"><span class="item-remove text-center"><i class="bi bi-trash" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Remove item from form"></i></span><div class="product-label">Product Placeholder</div><div class="product-container canvas-content"><label for="package0" class="product_field form-label  me-3 product-item p-3 rounded shadow-sm"><span class="me-1 product-title">Add products to display here</span></label></div> <span class="item-move text-center"><i class="bi bi-grip-horizontal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Drag item to another position"></i></span></div>'
                        );

                        break;
                    case "seperator":
                        element = $(
                            '<div class="canvas-element"><span class="item-remove text-center"><i class="bi bi-trash" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Remove item from form"></i></span><hr class="canvas-content canvas-element-seperator"> <span class="item-move text-center"><i class="bi bi-grip-horizontal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Drag item to another position"></i></span></div>'
                        );
                        break;

                    default:
                        return;
                }

                item.replaceWith(element);
                showPropertiesForm(type, element);

                element.on("click", function() {
                    showPropertiesForm(type, element);
                });
            }

            function showPropertiesForm(type, canvasElement) {

                let element = canvasElement.find('.canvas-content');




                $("#form-properties-tab").click();
                const form = $("#form-properties");
                form.empty();
                $(".properties-placeholder").hide();

                // Common style fields for all elements
                form.append(`<h6>Attributes</h6>`);

                $("#element-class").on("input", function() {
                    element.attr("class", $(this).val());
                });
                $("#element-margin").on("input", function() {
                    element.css("margin", $(this).val());
                });
                $("#element-padding").on("input", function() {
                    element.css("padding", $(this).val());
                });

                // Specific fields based on the type
                if (type === "text" && element.attr("contenteditable")) {
                    text_field(form, element)
                }

                if (type === "product") {
                    product_field(form, element);
                }

                if (type === "form") {
                    form_field(form, element);
                }

                if (type === "seperator") {
                    seperator_field(form, element);
                }

                if (type === "image") {
                    image_field(form, element)
                }

                form.append(`
            <label class="propertiy-label">Margin:</label>
            <div class="input-group input-group-sm mb-2">
                <span>Top</span>
                <input class="form-control form-control-sm" type="number" id="margin-top" placeholder="Top" value="${element.css("margin-top").replace("px", "")}">
                <span>px</span>
            </div>
            <div class="input-group input-group-sm mb-2">
                <span>Right</span>
                <input class="form-control form-control-sm" type="number" id="margin-right" placeholder="Right" value="${element.css("margin-right").replace("px", "")}">
                <span>px</span>
            </div>
            <div class="input-group input-group-sm mb-2">
                <span>Bottom</span>
                <input class="form-control form-control-sm" type="number" id="margin-bottom" placeholder="Bottom" value="${element.css("margin-bottom").replace("px", "")}">
                <span>px</span>
            </div>
            <div class="input-group input-group-sm mb-2">
                <span>Left</span>
                <input class="form-control form-control-sm" type="number" id="margin-left" placeholder="Left" value="${element.css("margin-left").replace("px", "")}">
                <span>px</span>
            </div>
        `);

                $("#margin-top").on("input", function() {
                    element.parents('.canvas-element')?.css("margin-top", $(this).val());
                    updateConfig(element, 'form', {
                        marginTop: $(this).val()
                    });
                });
                $("#margin-right").on("input", function() {
                    element.parents('.canvas-element')?.css("margin-right", $(this).val());
                    updateConfig(element, 'form', {
                        marginRight: $(this).val()
                    });
                });
                $("#margin-bottom").on("input", function() {
                    element.parents('.canvas-element')?.css("margin-bottom", $(this).val());
                    updateConfig(element, 'form', {
                        marginBottom: $(this).val()
                    });
                });
                $("#margin-left").on("input", function() {
                    element.parents('.canvas-element')?.css("margin-left", $(this).val());
                    updateConfig(element, 'form', {
                        marginLeft: $(this).val()
                    });
                });

            }


            function collectFormConfig() {
                const elements = $(".drop-container .canvas-element");
                const formConfig = [];

                elements.each(function() {
                    const element = $(this);
                    const config = element.data("config") || element.prop("tagName").toLowerCase();
                    formConfig.push(config);
                });

                return formConfig;
            }

            function saveElementConfig(elementType) {
                const config = elementConfig[elementType];
                const newConfig = {
                    type: elementType,
                    properties: {}
                };

                Object.keys(config.properties).forEach((key) => {
                    const prop = config.properties[key];
                    let value;

                    switch (prop.type) {
                        case "input":
                            value = $(`#${key}`).val();
                            break;
                        case "select":
                            value = $(`#${key}`).val();
                            break;
                        case "checkbox":
                            value = $(`#${key}`).is(":checked");
                            break;
                        default:
                            break;
                    }

                    newConfig.properties[key] = value;
                });

                return newConfig;
            }


            // Update button settings dynamically
            $('#form-button-text').on('input', function() {
                const val = $(this).val();
                $('.form-submit-btn').text(val);
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

            $('.trigger-fullscreen').on('click', function() {
                $('.canvas-container').toggleClass('full-screen');

                // Change the icon based on whether full-screen is active
                const icon = $(this).find('i');
                if ($('.canvas-container').hasClass('full-screen')) {
                    icon.removeClass('bi-arrows-fullscreen').addClass('bi-fullscreen-exit');
                } else {
                    icon.removeClass('bi-fullscreen-exit').addClass('bi-arrows-fullscreen');
                }
            });


        });
    </script>
@endsection
