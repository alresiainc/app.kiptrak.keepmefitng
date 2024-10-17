<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, user-scalable=0" name="viewport">

    <title>Order Form :: CRM</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('/assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('/customerform/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/customerform/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css')}}"> -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css" />
    <link href="{{ asset('/customerform/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/customerform/assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('/customerform/assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('/customerform/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('/customerform/assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Font awesome 5 -->
    <link rel="preload" href="{{ asset('/customerform/assets/vendor/font-awesome/webfonts/fa-solid-900.woff2') }}"
        as="font" type="font/woff" crossorigin>
    <link href="{{ asset('/customerform/assets/vendor/font-awesome/css/all.min.css') }}" type="text/css"
        rel="stylesheet">


    <!-- upsell->Template Main CSS File -->
    <link href="{{ asset('/customerform/assets/css/ui.css') }}" rel="stylesheet">
    <link href="{{ asset('/customerform/assets/css/form-style.css') }}" rel="stylesheet">

    <style>
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

        .header {
            transition: all 0.5s;
            z-index: 997;
            height: 50px;
            box-shadow: 0px 2px 20px rgb(1 41 112 / 10%);
            background-color: #D2FFE8;
            padding-left: 20px;
        }

        .btn:hover {
            background-color: #fff !important;
            border-color: #04512d !important;
            color: #04512d !important;
        }
    </style>


</head>

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
        background-color: {{ $formSettings['form_bg_color'] ?? '#ffffff' }};
        background-image: url({{ $formSettings['form_bg_url'] ?? '' }});
        background-size: cover;
        background-repeat: no-repeat;
    }

    .canvas-container *:not(.element-wrapper):not(.element-wrapper *) {
        color: {{ $formSettings['form_bg_text_color'] ?? '' }};

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

    .trigger-fullscreen {
        position: absolute;
        top: 0;
        right: 0;
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

<body class="">

    <!-- will be shown in singlelink-->
    <header id="header" class="header fixed-top d-flex align-items-center d-none">
        <div class="d-flex align-items-center justify-content-between">
            <a href="javascript:void(0);" class="logo d-flex align-items-center">
                <img src="{{ asset('/assets/img/logo.png') }}" alt="Kiptrak Logo" style="width: 30%; !important">
                <span class="d-none d-lg-block project-namek"></span>
            </a>
        </div>
        <!-- End Logo -->
    </header>

    <main class="container mb-5 py-5 min-vh-100">

        @if (Session::has('success'))
            <div class="alert alert-success mb-3 text-center">
                {{ Session::get('success') }}
            </div>
        @endif

        @if (Session::has('info'))
            <div class="alert alert-success mb-3 text-center">
                {{ Session::get('info') }}
            </div>
        @endif

        <!-- Monitoring diferent stages in the form -->
        <input type="hidden" name="main_stage" class="main_stage" value="">
        <input type="hidden" name="orderbump_stage" class="orderbump_stage" value="">
        <input type="hidden" name="upsell_stage" class="upsell_stage" value="">
        <input type="hidden" name="thankyou_stage" class="thankyou_stage" value="">
        <input type="hidden" name="current_order_id" class="current_order_id" value="">
        <input type="hidden" name="cartAbandoned_id" class="cartAbandoned_id" value="{{ $cartAbandoned_id }}">
        <!-- Monitoring diferent stages in the form -->

        <!-- CHECKOUT VIEW Main + orderbump + upsell -->
        @if ($stage == '')

            <div class="row view" id="main-section" style="display: block;">
                <div class="col-md-12">

                    <article class="card">
                        <div class="card-body">
                            <form action="">@csrf

                                <div class="row">

                                    @foreach ($formData as $data)
                                        @php
                                            $form = $data['config'];
                                            $config_type = $data['type'];
                                        @endphp
                                        @if ($config_type == 'seperator')
                                        @endif
                                        <div class="{{ $form['column_width'] ?? 'col-sm-12' }} mb-3"
                                            style="margin-top: {{ $form['marginTop'] ?? '0' }};
                                                margin-bottom: {{ $form['marginBottom'] ?? '0' }};
                                                margin-left: {{ $form['marginLeft'] ?? '0' }};
                                                margin-right: {{ $form['marginRight'] ?? '0' }};
                                                text-align: {{ $form['textAlign'] ?? 'inherit' }};"
                                        >
                                            @if ($config_type == 'form' && !in_array($form['type'], ['select', 'textarea', 'radio', 'checkbox']))
                                                <div class="contact-parent">
                                                    <label class="form-label">{{ $form['label'] }}</label>

                                                    <input type="{{ $form['type'] }}" data-name="{{ $form['name'] }}"
                                                        name="{{ $form['name'] }}"
                                                        value="{{ old($form['name'], $form['default_value'] ?? '') }}"
                                                        class="contact-input form-control form-control-{{ $form['size'] ?? 'md' }} {{ $form['name'] }} @error($form['name']) is-invalid @enderror"
                                                        placeholder="{{ $form['placeholder'] }}"
                                                        @if ($form['required']) required @endif>

                                                    <!--if such error-->
                                                    @error($form['name'])
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror

                                                </div>
                                            @endif

                                            @if ($config_type == 'form' && in_array($form['type'], ['radio', 'checkbox']))
                                                <div class="form-group w-100"
                                                    style="margin-left: {{ $form['margin_left'] ?? '0' }}; margin-right: {{ $form['margin_right'] ?? '0' }}; margin-top: {{ $form['margin_top'] ?? '0' }}; margin-bottom: {{ $form['margin_bottom'] ?? '0' }};">
                                                    <label class="form-label">{{ $form['label'] }}</label>
                                                    <div class="d-flex gap-1">
                                                        @if (isset($form['options']) && count($form['options']) > 0)
                                                            @foreach ($form['options'] as $option)
                                                                <div class="form-check">
                                                                    <input class="form-check-input"
                                                                        type="{{ $form['type'] }}"
                                                                        @if ($form['type'] == 'checkbox') name="{{ $form['name'] }}[]"
                                                                        @else
                                                                        name="{{ $form['name'] }}" @endif
                                                                        id="{{ $form['name'] }}_{{ $loop->index }}"
                                                                        value="{{ $option }}">
                                                                    <label class="form-check-label"
                                                                        for="{{ $form['name'] }}_{{ $loop->index }}"
                                                                        style="text-transform: none;">
                                                                        {{ $option }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        @endif

                                                    </div>
                                                </div>
                                            @endif

                                            @if ($config_type == 'form' && $form['type'] == 'select')
                                                <div class="form-group w-100">
                                                    <label class="form-label">{{ $form['label'] }}</label>
                                                    <select data-name="{{ $form['name'] }}"
                                                        name="{{ $form['name'] }}"
                                                        value="{{ old($form['name'], $form['default_value'] ?? '') }}"
                                                        class="contact-input custom-select form-control form-control-{{ $form['size'] ?? 'md' }} {{ $form['name'] }} @error($form['name']) is-invalid @enderror"
                                                        placeholder="{{ $form['placeholder'] }}"
                                                        @if ($form['required']) required @endif>
                                                        @if (isset($form['options']) && count($form['options']) > 0)
                                                            @foreach ($form['options'] as $option)
                                                                <option value="{{ $option }}">
                                                                    {{ $option }}</option>
                                                            @endforeach
                                                        @endif

                                                    </select>
                                                </div>
                                            @endif



                                            @if ($config_type == 'form' && $form['type'] == 'textarea')
                                                <div class="form-group w-100">
                                                    <label class="form-label">{{ $form['label'] }}</label>
                                                    <textarea data-name="{{ $form['name'] }}" name="{{ $form['name'] }}"
                                                        class="contact-input custom-select form-control form-control-{{ $form['size'] ?? 'md' }} {{ $form['name'] }} @error($form['name']) is-invalid @enderror"
                                                        placeholder="{{ $form['placeholder'] }}" @if ($form['required']) required @endif>{{ old($form['name'], $form['default_value'] ?? '') }}</textarea>
                                                </div>
                                            @endif


                                            @if ($config_type == 'text')
                                                <div
                                                    style="padding-top: {{ $form['paddingTop'] ?? '0' }};
                                                padding-bottom: {{ $form['paddingBottom'] ?? '0' }};
                                                padding-left: {{ $form['paddingLeft'] ?? '0' }};
                                                padding-right: {{ $form['paddingRight'] ?? '0' }};
                                                color: {{ $form['color'] ?? '#000' }}; /* Default to black */
                                                font-weight: {{ $form['fontWeight'] ?? 'normal' }};
                                                font-family: {{ $form['fontFamily'] ?? 'Arial, sans-serif' }}; /* Default font family */
                                                font-size: {{ $form['fontSize'] ?? '16px' }}; /* Default font size */
                                                font-style: {{ $form['fontStyle'] ?? 'normal' }};
                                                text-align: {{ $form['textAlign'] ?? 'left' }}; /* Default to left alignment */
                                                text-transform: {{ $form['textTransform'] ?? 'none' }};
                                                text-decoration: {{ $form['textDecoration'] ?? 'none' }};
                                                line-height: {{ $form['lineHeight'] ?? 'normal' }};
                                                letter-spacing: {{ $form['letterSpacing'] ?? 'normal' }};
                                                word-spacing: {{ $form['wordSpacing'] ?? 'normal' }};
                                                white-space: {{ $form['whiteSpace'] ?? 'normal' }};
                                                overflow-wrap: {{ $form['overflowWrap'] ?? 'normal' }};
                                                background-color: {{ $form['backgroundColor'] ?? 'transparent' }}; /* Default to transparent */
                                                border: {{ $form['border'] ?? 'none' }}; /* Default to no border */
                                                border-radius: {{ $form['borderRadius'] ?? '0' }}; /* Default to no rounding */
                                                box-shadow: {{ $form['boxShadow'] ?? 'none' }};
                                                opacity: {{ $form['opacity'] ?? '1' }}; /* Fully opaque by default */
                                            ">
                                                    {!! $form['content'] ?? '' !!}
                                                </div>
                                            @endif

                                            @if ($config_type == 'image')
                                                <img src="{{ $form['src'] ?? '' }}"
                                                    style="width: {{ $form['width'] ?? '0' }};
                                                height: {{ $form['height'] ?? '0' }};" />
                                            @endif

                                            @if ($config_type == 'seperator')
                                                <hr {{-- style="width: {{ $form['width'] ?? '0' }};
                                                height: {{ $form['height'] ?? '0' }};"  --}} />
                                            @endif


                                        </div>





                                        @if ($config_type == 'product' && count($products) > 0)
                                            <div class="col-12">
                                                <div class="product-label text-field-content">Label</div>
                                                <div class="product-container row">
                                                    @foreach ($products as $key => $item)
                                                        {{-- @dd($products); --}}
                                                        @php
                                                            $is_combo = $item['combo_product_ids'] ? true : false;
                                                        @endphp
                                                        <div class="{{ $form['column_width'] ?? 'col-sm-12' }}">
                                                            <input
                                                                type="{{ $form['package_choice'] == 'package_single' ? 'radio' : 'checkbox' }}"
                                                                name="product_packagesss[]"
                                                                id="package{{ $key }}"
                                                                class="me-3 product-packages product-checker"
                                                                value="{{ $item['id'] }}-{{ $item['price'] }}" />
                                                            <label for="package{{ $key }}"
                                                                class="product_field form-label me-3 product-item p-3 rounded shadow-sm w-100"
                                                                style="min-width: 100%; width: 100%;">

                                                                <div>
                                                                    <div class="product-title me-1 fw-bold mb-2">
                                                                        {{ $item['name'] }} @if ($is_combo)
                                                                            <span
                                                                                class="badge badge-success"><span>Combo</span></span>'
                                                                        @endif
                                                                    </div>


                                                                    <div
                                                                        class="d-flex flex-column flex-md-row w-100 align-items-start flex-wrap gap-2">
                                                                        <div class="product-img-container me-3">
                                                                            <img class="product-img img-fluid rounded"
                                                                                src="{{ $item['image_url'] }}"
                                                                                alt="{{ $item['name'] }} Image">

                                                                        </div>
                                                                        <div
                                                                            class="product-info d-flex flex-wrap flex-column">
                                                                            <div class="text-sm text-muted fw-bold">
                                                                                {{ $item['currency'] }}{{ $item['price'] }}
                                                                            </div>
                                                                            <div
                                                                                class="d-flex align-items-center mb-2 gap-1  @if ($item['combo_product_ids']) d-none @endif">
                                                                                <span
                                                                                    style="font-size: 14px; font-weight: 600; opacity: 0.5;">
                                                                                    Qty:
                                                                                </span>
                                                                                <div class="input-group product-qty">
                                                                                    <button
                                                                                        class="btn btn-sm btn-icon btn-light border minusQty"
                                                                                        type="button">
                                                                                        <i class="bi bi-dash"></i>
                                                                                    </button>
                                                                                    <input
                                                                                        class="form-control border text-center select_product_qtys"
                                                                                        placeholder="" value="1"
                                                                                        min="1"
                                                                                        name="select_product_qty[{{ $item['id'] }}]"
                                                                                        max="{{ $item['stock_available'] }}">
                                                                                    <button
                                                                                        class="btn btn-sm btn-icon btn-light border plusQty"
                                                                                        type="button">
                                                                                        <i class="bi bi-plus-lg"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="size-options d-flex align-items-center mt-1 {{ !empty($item['available_sizes']) ? '' : 'd-none' }}">
                                                                                <span
                                                                                    style="font-size: 14px; font-weight: 600; opacity: 0.5;">Sizes:
                                                                                </span>
                                                                                @foreach ($item['available_sizes'] as $size)
                                                                                    <input type="radio"
                                                                                        id="size-{{ $size }}"
                                                                                        name="product_size"
                                                                                        value="{{ $size }}"
                                                                                        class="size-radio d-none">
                                                                                    <label
                                                                                        for="size-{{ $size }}"
                                                                                        class="size-box">{{ $size }}</label>
                                                                                @endforeach
                                                                            </div>

                                                                            <div
                                                                                class="color-options d-flex align-items-center mt-1 {{ !empty($item['available_colors']) ? '' : 'd-none' }}">
                                                                                <span
                                                                                    style="font-size: 14px; font-weight: 600; opacity: 0.5;">Colors:
                                                                                </span>
                                                                                @foreach ($item['available_colors'] as $color)
                                                                                    <input type="radio"
                                                                                        id="color-{{ $color }}"
                                                                                        name="product_color"
                                                                                        value="{{ $color }}"
                                                                                        class="color-radio d-none">
                                                                                    <label
                                                                                        for="color-{{ $color }}"
                                                                                        class="color-circle">{{ $color }}</label>
                                                                                @endforeach
                                                                            </div>

                                                                            <div
                                                                                class="{{ $is_combo ? '' : 'd-none' }}">
                                                                                <span
                                                                                    class="">{{ $item['short_description'] }}</span>
                                                                            </div>



                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </label>
                                                        </div>
                                                    @endforeach

                                                    @error('selected_products')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach








                                    <div class="my-3 form-submit-btn-container"
                                        style="text-align: {{ strtolower($settingsData['form_button_alignment'] ?? 'center') }};">
                                        <button type="button"
                                            class="{{ $settingsData['form_button_type'] == 'Rounded' ? 'rounded-pill' : '' }} w-50 p-2 form-submit-btn text-field-content"
                                            style="background-color: {{ $settingsData['form_button_bg'] ?? '#04512d' }}; color: {{ $settingsData['form_button_color'] ?? '#ffffff' }}; border:0; : text;">{{ $settingsData['form_button_text'] ?? 'Submit Order' }}</button>
                                    </div>

                                </div> <!-- row.// -->

                            </form>
                        </div> <!-- card-body end.// -->
                    </article>

                </div>

            </div>

        @endif

        <!---orderbump view--->
        <input type="hidden" name="has_orderbump" class="has_orderbump"
            value="{{ isset($formHolder->orderbump_id) ? 'true' : 'false' }}">
        @if ($stage == '')
            @if (isset($formHolder->orderbump_id))
                <div class="row view" id="orderbump-section" style="display: none;">

                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-body">
                                {{-- <h5 class="card-title">Contact info</h5> --}}
                                <form action="">@csrf
                                    <div class="row">

                                        <div class="col-12 mb-3">
                                            <div class="d-flex justify-content-center">
                                                <div class="content text-center p-3"
                                                    style="border: 3px dashed black; background-color: #D2FFE8;">
                                                    <h3 class="heading">
                                                        {{ $formHolder->orderbump->orderbump_heading }}</h3>

                                                    @foreach ($formHolder->orderbump->orderbump_subheading as $subheading)
                                                        <h5 class="subheading">{{ $subheading }}</h5>
                                                    @endforeach
                                                    {{-- <p class="product-feature">Melts Away Fats In 2 Days!</p> --}}

                                                    <div class="orderbump-product-image mb-3">
                                                        <img src="{{ asset('/storage/products/' . $formHolder->orderbump->product->image) }}"
                                                            class="w-100 img-thumbnail img-fluid"
                                                            alt="{{ $formHolder->orderbump->product->name }}">
                                                    </div>

                                                    <p class="discount-info">
                                                        Kindly click the box below to add this to your order now for
                                                        just {{ $formHolder->orderbump->product->sale_price }} instead
                                                        of paying normal price of
                                                        {{ $formHolder->orderbump->product_assumed_selling_price }}!
                                                    </p>

                                                    <p class="more-info">
                                                        This offer is not available at ANY other time or place
                                                    </p>

                                                    <div class="col-12 mb-3 select-product">
                                                        <p class="form-label">Click button to Add Product Package</p>
                                                        <label for="product"
                                                            class="form-label btn btn-outline border d-flex align-items-center me-3
                                                        @error('product') is-invalid @enderror">
                                                            <input type="hidden" name="orderbump_product_checkbox"
                                                                id="product" class="orderbump_product_checkbox me-3"
                                                                value="{{ $formHolder->orderbump->product->id }}" />
                                                            <span
                                                                class="me-1 fw-bold">{{ $formHolder->orderbump->product->name }}
                                                                =
                                                                {{ $formHolder->orderbump->product->country->symbol }}{{ $formHolder->orderbump->product->sale_price }}</span>
                                                        </label>
                                                        @error('product')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="make-your-choice d-flex justify-content-center">

                                                        <div class="d-flex justify-content-center">
                                                            <button type="submit"
                                                                class="btn rounded-pill w-100 p-2 text-white orderbump_submit_btn"
                                                                style="background-color: #04512d;">ADD TO MY
                                                                ORDER</button>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="make-your-choice d-flex justify-content-center">

                                                <label for="orderbump_refusal"
                                                    class="form-label d-flex align-items-center">
                                                    <input type="checkbox" name="orderbump_refusal"
                                                        id="orderbump_refusal"
                                                        class="cta-check2 me-1 orderbump_refusal invisible"
                                                        @error('product') checked @enderror value="true" />
                                                    <span class="fw-light" style="color: #012970;">No, thank
                                                        you</span>
                                                </label>

                                            </div>
                                        </div>

                                    </div> <!-- row.// -->

                                </form>
                            </div> <!-- card-body end.// -->
                        </div>

                    </div>

                </div>
            @endif
        @endif

        <!---upsell view--->
        <input type="hidden" name="has_upsell" class="has_upsell"
            value="{{ isset($formHolder->upsell_id) ? 'true' : 'false' }}">
        @if ($stage == '')
            @if (isset($formHolder->upsell_id))

                <div class="row view" id="upsell-section" style="display: none;">

                    <div class="col-md-12">

                        <article
                            class="card @if ($formHolder->upsell->template->body_border_radius != 'normal') {{ $formHolder->upsell->template->body_border_radius }} @endif"
                            style="background-color: {{ $formHolder->upsell->template->body_bg_color }};
                            border-style: {{ $formHolder->upsell->template->body_border_style }};
                            border-color: {{ $formHolder->upsell->template->body_border_color }};
                            border-width: {{ $formHolder->upsell->template->body_border_thickness }};
                            ">
                            <div class="card-body">
                                {{-- <h5 class="card-title">Contact info</h5> --}}
                                <form action="">@csrf
                                    <div class="row">

                                        <!--upsell-->
                                        <div class="col-12 mb-3">
                                            <div class="d-flex justify-content-center">
                                                <div class="content text-center p-3">

                                                    <h3 class="heading text-{{ $formHolder->upsell->template->heading_text_align }} fst-{{ $formHolder->upsell->template->heading_text_style }}"
                                                        style="color: {{ $formHolder->upsell->template->heading_text_color }};">
                                                        {{ $formHolder->upsell->upsell_heading }}</h3>

                                                    @foreach ($formHolder->upsell->template->subheading_text as $subheading)
                                                        <h5 class="subheading text-{{ $formHolder->upsell->template->subheading_text_align }} fst-{{ $formHolder->upsell->template->subheading_text_style }}"
                                                            style="color: {{ $formHolder->upsell->template->subheading_text_color }};">
                                                            {{ $subheading }}</h5>
                                                    @endforeach

                                                    @if (isset($formHolder->upsell->template->description_text))
                                                        <p class="description text-{{ $formHolder->upsell->template->description_text_align }} fst-{{ $formHolder->upsell->template->description_text_style }}"
                                                            style="color: {{ $formHolder->upsell->template->description_text_color }};">
                                                            {{ isset($upsell->upsell_description) ? $upsell->upsell_description : '' }}
                                                        </p>
                                                    @else
                                                        <p class="description">
                                                            {{ isset($upsell->upsell_description) ? $upsell->upsell_description : '' }}
                                                        </p>
                                                    @endif

                                                    <div class="upsell-product-image mb-3 d-none"
                                                        style="width: 400px; height:300px;">
                                                        <img src="{{ asset('/storage/products/' . $formHolder->upsell->product->image) }}"
                                                            class="img-thumbnail img-fluid"
                                                            alt="{{ $formHolder->upsell->product->name }}">
                                                    </div>

                                                    <div class="row" id="product-list">

                                                        <div class="col-lg-12 col-sm-6 col-12">
                                                            <div
                                                                class="card card-product-grid bg-transparent border-0">
                                                                <div class="img-wrap">
                                                                    <img
                                                                        src="{{ asset('/storage/products/' . $formHolder->upsell->product->image) }}">
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="select-upsell-product text-center">
                                                        <p class="form-label">Click button to Add Product Package</p>
                                                        <div
                                                            class="call-to-action d-flex justify-content-center align-items-center">
                                                            <label for="upsell_product"
                                                                class="form-label d-flex align-items-center">
                                                                <input type="hidden" name="upsell_product"
                                                                    class="upsell_product_checkbox me-1"
                                                                    id="upsell_product"
                                                                    value="{{ $formHolder->upsell->product->id }}">

                                                                <span
                                                                    class="text-{{ $formHolder->upsell->template->package_text_align }} fst-{{ $formHolder->upsell->template->package_text_style }}"
                                                                    style="color: {{ $formHolder->upsell->template->package_text_color }};">
                                                                    {{ $formHolder->upsell->product->name }} =
                                                                    {{ $formHolder->upsell->product->country->symbol }}{{ $formHolder->upsell->product->sale_price }}
                                                                </span>

                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="make-your-choice d-flex justify-content-center">

                                                        {{-- <button type="submit" class="btn w-100 p-2 text-white upsell_submit_btn" style="background-color: #012970;">ADD TO MY ORDER</button> --}}

                                                        <button type="submit"
                                                            class="btn rounded-pill w-100 p-2 upsell_submit_btn text-{{ $formHolder->upsell->template->button_text_align }} fst-{{ $formHolder->upsell->template->button_text_style }}"
                                                            style="background-color: {{ $formHolder->upsell->template->button_bg_color }}; color: {{ $formHolder->upsell->template->button_text_color }};">ADD
                                                            TO MY ORDER</button>

                                                    </div>


                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="make-your-choice d-flex justify-content-center">

                                                <label for="upsell_refusal"
                                                    class="form-label d-flex align-items-center">
                                                    <input type="checkbox" name="upsell_refusal" id="upsell_refusal"
                                                        class="cta-check2 me-1 upsell_refusal invisible"
                                                        @error('product') checked @enderror value="true" />
                                                    <span class="fw-light" style="color: #012970;">No, thank
                                                        you</span>
                                                </label>

                                            </div>
                                        </div>

                                    </div> <!-- row.// -->

                                </form>
                            </div> <!-- card-body end.// -->
                        </article>

                    </div>

                </div>


            @endif
        @endif

        <!-- THANKYOU VIEW -->
        @if ($stage != '')
            <div class="view" id="thankyou-section" style="display: block;">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <article class="card shadow-sm">
                            <div class="card-body">
                                <div class="mt-4 mx-auto text-center" style="max-width:600px">
                                    <svg width="96px" height="96px" viewBox="0 0 96 96" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="round-check">
                                                <circle id="Oval" fill="#D3FFD9" cx="48" cy="48"
                                                    r="48"></circle>
                                                <circle id="Oval-Copy" fill="#87FF96" cx="48" cy="48"
                                                    r="36"></circle>
                                                <polyline id="Line" stroke="#04B800" stroke-width="4"
                                                    stroke-linecap="round"
                                                    points="34.188562 49.6867496 44 59.3734993 63.1968462 40.3594229">
                                                </polyline>
                                            </g>
                                        </g>
                                    </svg>
                                    <div class="my-3">
                                        <h4>Thank you for order</h4>
                                        <p>We have received your order confirmation. One of our agents will contact you
                                            shortly.</p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>

                    <!--order-summary-->
                    @if ($customer !== '')

                        <div class="col-lg-12">
                            <article class="card shadow-sm mb-3">
                                <div class="card-body">
                                    <header class="d-md-flex">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0"> Order ID: {{ $orderId }} <i
                                                    class="dot"></i><span class="text-danger"> Pending </span>
                                            </h6>
                                            <span>Date: <span
                                                    class="order_updated_date">{{ $order->updated_at->format('D, jS M Y') }}</span></span>
                                        </div>
                                        <div>
                                            <!-- <a href="#" class="btn btn-sm btn-outline-danger">Cancel order</a> -->
                                            <a href="#" id="generate-pdf" class="btn btn-sm btn-success"><i
                                                    class="bi bi-download text-white"></i> Download Invoice</a>
                                        </div>
                                    </header>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="fw-bold mb-0 text-success">Contact</p>
                                            <hr>
                                            <p class="m-0">
                                                <span class="customer_name">Name: {{ $customer->firstname }}
                                                    {{ $customer->lastname }}</span>
                                                <br> Phone: <span
                                                    class="customer_phone">{{ $customer->phone_number }},
                                                    {{ $customer->whatsapp_phone_number }}</span>
                                                <br> Email: <span
                                                    class="customer_email">{{ $customer->email }}</span>
                                            </p>
                                        </div> <!-- col.// -->
                                        <div class="col-md-4 border-start">
                                            <p class="fw-bold mb-0 text-success">Shipping address</p>
                                            <hr>
                                            <p class="m-0"> <span class="customer_country"></span>
                                                <br> <span
                                                    class="customer_address">{{ $customer->delivery_address }}</span>
                                            </p>
                                        </div> <!-- col.// -->
                                        <div class="col-md-4 border-start">
                                            <p class="fw-bold mb-0 text-success">Payment</p>
                                            <hr>
                                            <p class="m-0">
                                                <!-- <span class="text-success"> Cash Payment </span>  -->
                                            <dl class="dlist-align">
                                                <dt class="fw-bolder">Method:</dt>
                                                <dd>Cash Payment</dd>
                                            </dl>
                                            <dl class="dlist-align">
                                                <dt class="fw-bolder">No. of Packages:</dt>
                                                <dd><span class="no_of_items">{{ $qty_total }}</span></dd>
                                            </dl>
                                            <dl class="dlist-align">
                                                <dt class="fw-bolder">Order Amount:</dt>
                                                <dd>N<span class="order_amount">{{ $order_total_amount }}</span></dd>
                                            </dl>
                                            <dl class="dlist-align">
                                                <dt class="fw-bolder">Discount:</dt>
                                                <dd>N0.00</dd>
                                            </dl>
                                            <dl class="dlist-align">
                                                <dt class="fw-bolder">Grand Total:</dt>
                                                <dd>N<span class="grand_total">{{ $grand_total }}</span></dd>
                                            </dl>
                                            </p>
                                        </div> <!-- col.// -->
                                    </div> <!-- row.// -->
                                    <hr>
                                    <ul class="row g-3">
                                        <div class="text-center">
                                            <p class="fw-bold mb-0 text-success">Products you ordered</p>
                                            <hr>
                                        </div>
                                        @foreach ($mainProducts_outgoingStocks as $main_outgoingStock)
                                            @if (
                                                !empty($main_outgoingStock->customer_acceptance_status) &&
                                                    $main_outgoingStock->customer_acceptance_status == 'accepted' &&
                                                    $main_outgoingStock->reason_removed == 'as_order_firstphase')
                                                <li class="col-lg-4 col-md-6">
                                                    <div class="itemside mb-3">
                                                        <div class="aside">
                                                            <img width="72" height="72"
                                                                src="{{ asset('/storage/products/' . $main_outgoingStock->product->image) }}"
                                                                class="img-sm rounded border">
                                                        </div>
                                                        <div class="info">
                                                            <p class="title">
                                                                {{ $main_outgoingStock->product->name }}
                                                            </p>
                                                            <strong>N{{ $main_outgoingStock->amount_accrued }}
                                                                ({{ $main_outgoingStock->quantity_removed }}
                                                                items)
                                                            </strong>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach

                                        <!---for orderbump or upsell--->
                                        @if (
                                            $orderbumpProduct_revenue !== 0 &&
                                                !empty($orderbump_outgoingStock->customer_acceptance_status) &&
                                                $orderbump_outgoingStock->customer_acceptance_status == 'accepted' &&
                                                $orderbump_outgoingStock->reason_removed == 'as_orderbump')
                                            <li class="col-lg-4 col-md-6">
                                                <div class="itemside mb-3">
                                                    <div class="aside">
                                                        <img width="72" height="72"
                                                            src="{{ asset('/storage/products/' . $orderbump_outgoingStock->product->image) }}"
                                                            class="img-sm rounded border">
                                                    </div>
                                                    <div class="info">
                                                        <p class="title">
                                                            {{ $orderbump_outgoingStock->product->name }}</p>
                                                        <strong>N{{ $orderbump_outgoingStock->product->sale_price * $orderbump_outgoingStock->quantity_removed }}
                                                            ({{ $orderbump_outgoingStock->quantity_removed }}
                                                            item)</strong>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif

                                        @if ($upsellProduct_revenue !== 0)
                                            <li class="col-lg-4 col-md-6">
                                                <div class="itemside mb-3">
                                                    <div class="aside">
                                                        <img width="72" height="72"
                                                            src="{{ asset('/storage/products/' . $upsell_outgoingStock->product->image) }}"
                                                            class="img-sm rounded border">
                                                    </div>
                                                    <div class="info">
                                                        <p class="title">{{ $upsell_outgoingStock->product->name }}
                                                        </p>
                                                        <strong>N{{ $upsell_outgoingStock->product->sale_price * $upsell_outgoingStock->quantity_removed }}
                                                            ({{ $upsell_outgoingStock->quantity_removed }}
                                                            item)</strong>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif

                                    </ul>
                                </div> <!-- card-body .// -->
                            </article> <!-- card .// -->
                        </div>

                    @endif


                    <!--/order-summary-->
                </div>

            </div>
        @endif
        <!--/ THANKYOU VIEW -->
        <div id="pdf-content" style="display: none"></div>
        <div id="pdf-renderer"></div>

    </main>

    {{-- <!-- <hr> will be shown in singlelink-->
    <footer class="container-fluid position-relative bg-dark py-5 text-white bottom-0"
        style="position: relative; bottom: 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div>&copy; <span class="copyright-date"></span> <span class="project-name"></span>. All rights
                        reserved. </div>
                </div>
            </div>
        </div>
    </footer> --}}

    <!-- Vendor JS Files -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js')}}"></script> -->
    <script src="{{ asset('/customerform/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('/customerform/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/customerform/assets/vendor/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('/customerform/assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('/customerform/assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('/customerform/assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('/customerform/assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('/customerform/assets/vendor/php-email-form/validate.js') }}"></script>


    <!-- Latest compiled and minified JavaScript -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js')}}"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
    <!-- upsell->Template Main JS File -->
    <script src="{{ asset('/customerform/assets/js/main.js?v=42') }}"></script>
    <script src="{{ asset('/customerform/assets/js/navigation.js?v=4') }}"></script>

    <!-- submit main form -->
    <script>
        $(document).ready(function() {
            $(document).on('click', '.plusQty', function() {
                var product_quantity = parseInt($(this).closest('.product-qty').find('input').val());

                product_quantity++;
                $(this).closest('.product-qty').find('input').val(product_quantity)
            });
            $(document).on('click', '.minusQty', function() {
                var product_quantity = parseInt($(this).closest('.product-qty').find('input').val());
                if (product_quantity > 1) {
                    product_quantity--;
                    $(this).closest('.product-qty').find('input').val(product_quantity)
                }
            });
        });
    </script>

    @if ($stage == '')
        <script>
            var main_stage = localStorage.hasOwnProperty('main_stage') ? localStorage.getItem('main_stage') : '';
            var orderbump_stage = localStorage.hasOwnProperty('orderbump_stage') ? localStorage.getItem('orderbump_stage') : '';
            var upsell_stage = localStorage.hasOwnProperty('upsell_stage') ? localStorage.getItem('upsell_stage') : '';
            var thankyou_stage = localStorage.hasOwnProperty('thankyou_stage') ? localStorage.getItem('thankyou_stage') : '';
            $("#thankyou-section").show()
            // }

            /** SET VIEWS */
            let currentView; //initialise currentView
            const setView = (viewId) => {
                var views = document.querySelectorAll('.view');

                for (let i = 0; i < views.length; i++) {
                    const view = views[i];

                    view.style.display = 'none';
                }

                document.getElementById(viewId).style.display = 'block';

                currentView = viewId;
            }
            //initialise view
            setView('main-section');
            /** END SET VIEWS */

            //cart abandoned
            var contacts = [];
            $("input.contact-input").click(function() {
                // console.log($(this).val())
                var parentC = $(this).parent().prev();
                var cartAbandoned_id = $(".cartAbandoned_id").val();
                var unique_key = $(".formholder_unique_key").val();

                //if prev exist, incase firstinput is typed, eg(firstname)
                if (parentC.length > 0) {
                    var prev = parentC.find('.contact-input');

                    if (prev.val() == '' || prev.val() == null) {
                        var inputName = prev.attr('data-name');
                        //console.log(label)
                        var msg = inputName + ' ' + 'must be filled';
                        alert(msg)
                    } else {
                        //store prev value
                        var prevVal = prev.val();
                        var inputName = prev.attr('data-name');
                        var inputVal = prevVal + '|' + inputName;
                        //console.log(inputVal)

                        contacts.push(inputVal) //store in array
                        //check for duplicates
                        var contact_copy = unique(contacts)
                        //console.log(contact_copy)

                        $.ajax({
                            type: 'get',
                            url: '/cart-abandon-contact',
                            data: {
                                unique_key: unique_key,
                                inputValueName: contact_copy,
                                inputVal: inputVal,
                                cartAbandoned_id: cartAbandoned_id
                            },
                            success: function(resp) {
                                //console.log(resp)


                            },
                            error: function() {
                                alert("Error");
                            }
                        });
                    }
                }

            });

            //remove dups
            function unique(list) {
                var result = [];
                $.each(list, function(i, e) {
                    if ($.inArray(e, result) == -1) result.push(e);
                });
                return result;
            }

            //cart-abandon-delivery-duration, & delivery addr check
            $('.delivery_duration').change(function() {
                var cartAbandoned_id = $('.cartAbandoned_id').val();
                var unique_key = $(".formholder_unique_key").val();
                var delivery_duration = $(this).val();
                var customer_delivery_addr = $('.address').val();
                var last_Val = $('input.contact-input:last').val();
                var last_inputName = $('input.contact-input:last').attr('data-name');

                if (last_Val == '' || last_Val == null) {
                    var msg = last_inputName + ' ' + 'must be filled';
                    alert(msg)
                } else {
                    var inputVal = last_Val + '|' + last_inputName;
                    $.ajax({
                        type: 'get',
                        url: '/cart-abandon-delivery-duration',
                        data: {
                            unique_key: unique_key,
                            cartAbandoned_id: cartAbandoned_id,
                            delivery_duration: delivery_duration,
                            inputVal: inputVal
                        },
                        success: function(resp) {
                            //console.log(resp)

                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                }

                //cart-abandon-delivery-address
            })

            //cart-abandon-package
            var packages = [];
            $(".product-package").click(function() {
                var cartAbandoned_id = $('.cartAbandoned_id').val();
                var unique_key = $(".formholder_unique_key").val();
                var product_package = $(this).val();
                var package_field_type = $(this).attr('type');

                var selected_qty = $(this).closest(".product_package_label").find("select[name='select_product_qty']")
                    .val(); //notused
                //console.log(selected_qty)
                product_package = $(this).val(); //1-1000-10


                //var product_package = $(".product-package").val();

                if (package_field_type == 'radio') {
                    if (packages.length > 0) {
                        packages = []
                    }
                    packages.push(product_package) //store in array
                    //check for duplicates
                    var packages_copy = unique(packages)
                    //console.log(packages_copy)
                } else {
                    packages.push(product_package) //store in array
                    //check for duplicates
                    var packages_copy = unique(packages)
                    //console.log(packages_copy)
                }

                var last_Val = $('input.contact-input:last').val();
                var last_inputName = $('input.contact-input:last').attr('data-name');

                if (last_Val == '' || last_Val == null) {
                    var msg = last_inputName + ' ' + 'must be filled';
                    alert(msg)
                } else {
                    var inputVal = last_Val + '|' + last_inputName;
                    $.ajax({
                        type: 'get',
                        url: '/cart-abandon-package',
                        data: {
                            unique_key: unique_key,
                            cartAbandoned_id: cartAbandoned_id,
                            product_package: packages_copy,
                            inputVal: inputVal
                        },
                        success: function(resp) {
                            //console.log(resp)

                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                }

            })

            //main package
            $('.main_package_submit_btn').click(function(e) {
                e.preventDefault();

                var unique_key = $(".formholder_unique_key").val();

                var firstname = $(".first-name").val();
                var lastname = $(".last-name").val();
                var phone_number = $(".phone-number").val();
                var whatsapp_phone_number = $(".whatsapp-phone-number").val();
                var active_email = $(".active-email").val();
                var state = $(".state").val();
                var city = $(".city").val();
                var address = $(".address").val();
                var delivery_duration = $(".delivery_duration").val();
                var product_package = $(".product-package").val();

                if (firstname == "" || firstname == null) {
                    alert("First name must be filled");
                    return false;
                }
                if (lastname == "" || lastname == null) {
                    alert("Last name must be filled");
                    return false;
                }
                if (phone_number == "" || phone_number == null) {
                    alert("Phone number must be filled");
                    return false;
                }
                if (whatsapp_phone_number == "" || whatsapp_phone_number == null) {
                    alert("Phone number must be filled");
                    return false;
                }
                // if (active_email == "" || active_email == null) {
                //     alert("Email address must be filled");
                //     return false;
                // }
                if (state == "" || state == null) {
                    alert("Your State must be filled");
                    return false;
                }
                if (city == "" || city == null) {
                    alert("City or Town must be filled");
                    return false;
                }
                if (address == "" || address == null) {
                    alert("Your Address must be selected");
                    return false;
                }
                if (product_package == "" || product_package == null) {
                    alert("Your Product Package must be filled");
                    return false;
                }

                var unique_key = $(".formholder_unique_key").val();
                var cartAbandoned_id = $('.cartAbandoned_id').val();
                var product_packages = $('input[name^="product_packages[]"]').map(function() {
                    if ($(this).is(':checked')) {
                        var selected_qty = $(this).closest(".product_package_label").find(
                            "select[name='select_product_qty']").val();
                        //console.log(selected_qty)
                        return $(this).val() + '-' + selected_qty; //1-1000-2
                    }
                }).get();

                var has_orderbump = $(".has_orderbump").val();
                var has_upsell = $(".has_upsell").val();

                $(this).text('Please wait...')
                $(this).prop('disabled', true);

                //ajax start
                $.ajax({
                    type: 'get',
                    url: '/ajax-save-new-form-link',
                    data: {
                        unique_key: unique_key,
                        cartAbandoned_id: cartAbandoned_id,
                        firstname: firstname,
                        lastname: lastname,
                        phone_number: phone_number,
                        whatsapp_phone_number: whatsapp_phone_number,
                        active_email: active_email,
                        state: state,
                        city: city,
                        address: address,
                        delivery_duration: delivery_duration,
                        product_packages: product_packages,
                    },
                    success: function(resp) {
                        //console.log(resp)
                        $(".main_stage").val('done')
                        localStorage.setItem('main_stage', 'done');
                        $('.current_order_id').val(resp.data.order_id);
                        if (resp.data.has_orderbump) {
                            setView('orderbump-section')

                        } else if (resp.data.has_upsell) {
                            setView('upsell-section')

                        } else {
                            var current_order_id = $('.current_order_id').val();
                            var thankyou_unique_key = $(".thankyou_unique_key").val();

                            if (thankyou_unique_key == '') {
                                window.parent.location.href = "/new-form-link/" + unique_key + "/" +
                                    current_order_id + "/thankYou";
                                $('.current_order_id').val('');
                                setView('thankyou-section')
                            } else {
                                $('.current_order_id').val('');
                                //mutate browser url & redirect
                                window.parent.location.href = "/view-thankyou-templates/" +
                                    thankyou_unique_key + "/" + current_order_id
                            }


                        }

                    },
                    error: function() {
                        alert("Error");
                    }
                });

                //ajax end

            })

            //orderbump_stage
            $('.orderbump_submit_btn').click(function(e) {
                e.preventDefault();
                var unique_key = $(".formholder_unique_key").val();

                var current_order_id = $(".current_order_id").val();
                var orderbump_product_checkbox = ''
                if ($('.orderbump_product_checkbox').val() != '') {
                    var orderbump_product_checkbox = $('.orderbump_product_checkbox').val();

                    $(this).text('Please wait...');
                    $(this).prop('disabled', true);

                    $.ajax({
                        type: 'get',
                        url: '/ajax-save-new-form-link-orderbump',
                        data: {
                            unique_key: unique_key,
                            orderbump_product_checkbox: orderbump_product_checkbox,
                            current_order_id: current_order_id
                        },
                        success: function(resp) {
                            //console.log(resp)
                            localStorage.setItem('orderbump_stage', 'done');
                            if (resp.data.has_upsell) {
                                setView('upsell-section')

                            } else {
                                var current_order_id = $('.current_order_id').val();
                                var thankyou_unique_key = $(".thankyou_unique_key").val();

                                if (thankyou_unique_key == '') {
                                    window.parent.location.href = "/new-form-link/" + unique_key + "/" +
                                        current_order_id + "/thankYou";
                                    $('.current_order_id').val('');
                                    setView('thankyou-section')
                                } else {
                                    $('.current_order_id').val('');
                                    window.parent.location.href = "/view-thankyou-templates/" +
                                        thankyou_unique_key + "/" + current_order_id
                                }

                            }

                        },
                        error: function() {
                            alert("Error");
                        }
                    });

                } else {
                    alert('Error: Something went wrong')
                }
            });

            //upsell_stage
            $('.upsell_submit_btn').click(function(e) {
                e.preventDefault();
                var unique_key = $(".formholder_unique_key").val();
                var current_order_id = $(".current_order_id").val();
                var upsell_product_checkbox = ''
                if ($('.upsell_product_checkbox').val() != '') {
                    var upsell_product_checkbox = $('.upsell_product_checkbox').val();
                    $(this).text('Please wait...');
                    $(this).prop('disabled', true);

                    $.ajax({
                        type: 'get',
                        url: '/ajax-save-new-form-link-upsell',
                        data: {
                            unique_key: unique_key,
                            upsell_product_checkbox: upsell_product_checkbox,
                            current_order_id: current_order_id
                        },
                        success: function(resp) {
                            //console.log(resp)
                            localStorage.setItem('upsell_stage', 'done');

                            // window.location.href = "/new-form-link/"+unique_key+"/"+current_order_id+"/thankYou"
                            // $('.current_order_id').val('');
                            // setView('thankyou-section')

                            var current_order_id = $('.current_order_id').val();
                            var thankyou_unique_key = $(".thankyou_unique_key").val();

                            if (thankyou_unique_key == '') {
                                window.parent.location.href = "/new-form-link/" + unique_key + "/" +
                                    current_order_id + "/thankYou";
                                $('.current_order_id').val('');
                                setView('thankyou-section')
                            } else {
                                $('.current_order_id').val('');
                                window.parent.location.href = "/view-thankyou-templates/" +
                                    thankyou_unique_key + "/" + current_order_id
                            }


                        },
                        error: function() {
                            alert("Error");
                        }
                    });

                } else {
                    alert('Error: Something went wrong')
                }
            });

            //orderbump_refusal
            $('.orderbump_refusal').click(function() {
                if ($(this).is(':checked')) {

                    var unique_key = $(".formholder_unique_key").val();
                    var current_order_id = $(".current_order_id").val();
                    var orderbump_product_checkbox = ''
                    if ($('.orderbump_product_checkbox').val() != '') {
                        var orderbump_product_checkbox = $('.orderbump_product_checkbox').val();
                        $(this).text('Please wait...');
                        $(this).prop('disabled', true);

                        $.ajax({
                            type: 'get',
                            url: '/ajax-save-new-form-link-orderbump-refusal',
                            data: {
                                unique_key: unique_key,
                                orderbump_product_checkbox: orderbump_product_checkbox,
                                current_order_id: current_order_id
                            },
                            success: function(resp) {
                                //console.log(resp)
                                localStorage.setItem('orderbump_stage', 'done');
                                if (resp.data.has_upsell) {
                                    setView('upsell-section')

                                } else {
                                    // window.location.href = "/new-form-link/"+unique_key+"/"+current_order_id+"/thankYou"
                                    // $(".current_order_id").val('');
                                    // setView('thankyou-section')

                                    var current_order_id = $('.current_order_id').val();
                                    var thankyou_unique_key = $(".thankyou_unique_key").val();

                                    if (thankyou_unique_key == '') {
                                        window.parent.location.href = "/new-form-link/" + unique_key + "/" +
                                            current_order_id + "/thankYou";
                                        $('.current_order_id').val('');
                                        setView('thankyou-section')
                                    } else {
                                        $('.current_order_id').val('');
                                        window.parent.location.href = "/view-thankyou-templates/" +
                                            thankyou_unique_key + "/" + current_order_id
                                    }
                                }

                            },
                            error: function() {
                                alert("Error");
                            }
                        });

                    } else {
                        alert('Error: Something went wrong')
                    }

                }

            });

            //upsell_refusal
            $('.upsell_refusal').click(function() {
                if ($(this).is(':checked')) {

                    var unique_key = $(".formholder_unique_key").val();
                    var current_order_id = $(".current_order_id").val();
                    var upsell_product_checkbox = ''
                    if ($('.upsell_product_checkbox').val() != '') {
                        var upsell_product_checkbox = $('.upsell_product_checkbox').val();
                        $(this).text('Please wait...');
                        $(this).prop('disabled', true);

                        $.ajax({
                            type: 'get',
                            url: '/ajax-save-new-form-link-upsell-refusal',
                            data: {
                                unique_key: unique_key,
                                upsell_product_checkbox: upsell_product_checkbox,
                                current_order_id: current_order_id
                            },
                            success: function(resp) {
                                //console.log(resp)
                                localStorage.setItem('upsell_stage', 'done');
                                // window.location.href = "/new-form-link/"+unique_key+"/"+current_order_id+"/thankYou"
                                // $(".current_order_id").val('');
                                // setView('thankyou-section')

                                var current_order_id = $('.current_order_id').val();
                                var thankyou_unique_key = $(".thankyou_unique_key").val();

                                if (thankyou_unique_key == '') {
                                    window.parent.location.href = "/new-form-link/" + unique_key + "/" +
                                        current_order_id + "/thankYou";
                                    $('.current_order_id').val('');
                                    setView('thankyou-section')
                                } else {
                                    $('.current_order_id').val('');
                                    window.parent.location.href = "/view-thankyou-templates/" +
                                        thankyou_unique_key + "/" + current_order_id
                                }

                            },
                            error: function() {
                                alert("Error");
                            }
                        });

                    } else {
                        alert('Error: Something went wrong')
                    }

                }
            });
        </script>
    @endif

    <script>
        //not in use
        $(".cta-check").click(function() {
            if ($(this).is(':checked')) {
                $(".select-product").show();
            } else {
                $(".select-product").hide();
            }

        });
    </script>

    <!---validate number only in phone-number field-->
    <script>
        $(document).on("input", ".phone-number", function() {
            this.value = this.value.replace(/\D/g, '');
        });
    </script>

    <!---validate number only in whatsapp-phone-number field-->
    <script>
        $(document).on("input", ".whatsapp-phone-number", function() {
            this.value = this.value.replace(/\D/g, '');
        });
    </script>

    <!---not in use PDF--->
    <script>
        var doc = new jsPDF();
        var specialElementHandlers = {
            '#pdf-renderer': function(element, renderer) {
                return true;
            }
        };

        $('#generate-pdf').click(function() {
            $('#pdf-content').show();
            doc.fromHTML($('#pdf-content').html(), 15, 15, {
                'width': 170,
                'elementHandlers': specialElementHandlers
            });
            doc.save('sample-file.pdf');
            $('#pdf-content').hide();
        });
    </script>


</body>

</html>
