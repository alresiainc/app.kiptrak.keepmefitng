@extends('layouts.design')
@section('title')
    Edit ThankYou Template
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
            <h1>Edit ThankYou Template</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('thankYouTemplates') }}">ThankYou Templates</a></li>
                    <li class="breadcrumb-item active">Edit ThankYou Template: {{ $thankYou->template_name }}</li>
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

                            <form class="row g-3 needs-validation"
                                action="{{ route('editThankYouTemplatePost', $thankYou->unique_key) }}" method="POST">@csrf
                                <div class="col-md-12 mb-3">The field labels marked with * are required input fields.</div>

                                <div class="col-md-12">
                                    <h4>Template</h4>
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Template Name</label>
                                    <input type="text" name="template_name"
                                        class="form-control @error('template_name') is-invalid @enderror"
                                        value="{{ $thankYou->template_name }}">
                                    @error('template_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Background Color</label>
                                    <input type="color" name="body_bg_color"
                                        class="form-control @error('body_bg_color') is-invalid @enderror"
                                        value="{{ $thankYou->body_bg_color }}">
                                    @error('body_bg_color')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Border Style</label>
                                    <select name="body_border_style" id="body_border_style" data-live-search="true"
                                        class="custom-select form-control border @error('sale_status') is-invalid @enderror"
                                        id="">
                                        <option value="{{ $thankYou->body_border_style }}" selected>
                                            {{ $thankYou->body_border_style }}</option>
                                        <option value="dotted">dotted</option>
                                        <option value="dashed">dashed</option>
                                        <option value="solid">solid</option>
                                        <option value="double">double</option>
                                        <option value="groove">groove</option>
                                        <option value="ridge">ridge</option>
                                        <option value="inset">inset</option>
                                        <option value="outset">outset</option>
                                        <option value="none">none</option>
                                        <option value="double">double</option>

                                    </select>
                                    @error('body_border_style')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Border Color</label>
                                    <input type="color" name="body_border_color"
                                        class="form-control @error('body_border_color') is-invalid @enderror"
                                        value="#ffffff">
                                    @error('body_border_color')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Border Thickness</label>
                                    <select name="body_border_thickness" id="body_border_thickness" data-live-search="true"
                                        class="custom-select form-control border @error('body_border_thickness') is-invalid @enderror"
                                        id="">
                                        <option value="{{ $thankYou->body_border_thickness }}" selected>
                                            {{ $thankYou->body_border_thickness }}</option>
                                        <option value="1px">1px</option>
                                        <option value="2px">2px</option>
                                        <option value="3px">3px</option>
                                        <option value="4px">4px</option>
                                        <option value="5px">5px</option>

                                    </select>
                                    @error('body_border_thickness')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Border Radius</label>
                                    <select name="body_border_radius" id="body_border_radius" data-live-search="true"
                                        class="custom-select form-control border @error('body_border_radius') is-invalid @enderror"
                                        id="">
                                        <option value="{{ $thankYou->body_border_radius }}" selected>
                                            {{ $thankYou->body_border_radius }}</option>
                                        <option value="normal">normal</option>
                                        <option value="rounded">rounded</option>
                                        <option value="rounded-pill">rounded-pill</option>
                                        <option value="rounded-circle">rounded-circle</option>
                                    </select>
                                    @error('body_border_radius')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <hr>

                                <div class="col-md-12">
                                    <h4>Heading</h4>
                                </div>

                                <div class="col-md-12">
                                    <label for="" class="form-label">Header Text</label>
                                    <input type="text" name="heading_text"
                                        class="form-control @error('heading_text') is-invalid @enderror"
                                        value="{{ $thankYou->heading_text }}">
                                    @error('heading_text')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Heading Text Style</label>
                                    <select name="heading_text_style" id="header_text_style" data-live-search="true"
                                        class="custom-select form-control border @error('heading_text_style') is-invalid @enderror"
                                        id="">
                                        <option value="{{ $thankYou->header_text_style }}" selected>
                                            {{ $thankYou->header_text_style }}</option>
                                        <option value="normal">normal</option>
                                        <option value="italic">italic</option>
                                    </select>
                                    @error('heading_text_style')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Heading Text Align</label>
                                    <select name="heading_text_align" id="heading_text_align" data-live-search="true"
                                        class="custom-select form-control border @error('heading_text_align') is-invalid @enderror"
                                        id="">
                                        <option value="{{ $thankYou->heading_text_align }}" selected>
                                            {{ $thankYou->heading_text_align }}</option>
                                        <option value="left">left</option>
                                        <option value="right">right</option>
                                        <option value="center">center</option>
                                    </select>
                                    @error('heading_text_align')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Heading Text Color</label>
                                    <input type="color" name="heading_text_color"
                                        class="form-control @error('heading_text_color') is-invalid @enderror"
                                        value="{{ $thankYou->heading_text_color }}">
                                    @error('heading_text_color')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">Heading Text Thickness</label>
                                    <select name="heading_text_weight" id="heading_text_weight" data-live-search="true"
                                        class="custom-select form-control border @error('heading_text_weight') is-invalid @enderror"
                                        id="">
                                        <option value="{{ $thankYou->heading_text_weight }}" selected>
                                            {{ ucFirst($thankYou->heading_text_weight) }}</option>
                                        <option value="normal">Normal</option>
                                        <option value="bold">Bold</option>
                                        <option value="bolder">Bolder</option>
                                        <option value="light">Light</option>
                                        <option value="lighter">Lighter</option>
                                    </select>
                                    @error('heading_text_weight')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">Heading Text Size</label>
                                    <select name="heading_text_size" id="heading_text_size" data-live-search="true"
                                        class="custom-select form-control border @error('heading_text_size') is-invalid @enderror"
                                        id="">
                                        <option value="{{ $thankYou->heading_text_size }}" selected>
                                            Size-{{ $thankYou->heading_text_size }}</option>
                                        <option value="1">Size-1 largest</option>
                                        <option value="2">Size-2</option>
                                        <option value="3">Size-3</option>
                                        <option value="4">Size-4</option>
                                        <option value="5">Size-5</option>
                                        <option value="6">Size-6 Smallest</option>
                                    </select>
                                    @error('heading_text_size')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <hr>

                                <div class="col-md-12">
                                    <h4>Subheading</h4>
                                </div>

                                <div class="col-md-12">
                                    <label for="" class="form-label">Subheading Text</label>
                                    <input type="text" name="subheading_text"
                                        class="form-control @error('subheading_text') is-invalid @enderror"
                                        value="{{ $thankYou->subheading_text }}">
                                    @error('subheading_text')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Subheading Text Style</label>
                                    <select name="subheading_text_style" id="subheading_text_style"
                                        data-live-search="true"
                                        class="custom-select form-control border @error('subheading_text_style') is-invalid @enderror"
                                        id="">
                                        <option value="{{ $thankYou->subheading_text_style }}" selected>
                                            {{ $thankYou->subheading_text_style }}</option>
                                        <option value="normal">normal</option>
                                        <option value="italic">italic</option>
                                    </select>
                                    @error('subheading_text_style')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Subheading Text Align</label>
                                    <select name="subheading_text_align" id="subheading_text_align"
                                        data-live-search="true"
                                        class="custom-select form-control border @error('subheading_text_align') is-invalid @enderror"
                                        id="">
                                        <option value="{{ $thankYou->subheading_text_align }}" selected>
                                            {{ ucFirst($thankYou->subheading_text_align) }}</option>
                                        <option value="left">left</option>
                                        <option value="right">right</option>
                                        <option value="center">center</option>
                                    </select>
                                    @error('subheading_text_align')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Subheading Text Color</label>
                                    <input type="color" name="subheading_text_color"
                                        class="form-control @error('subheading_text_color') is-invalid @enderror"
                                        placeholder="" value="{{ $thankYou->subheading_text_color }}">
                                    @error('subheading_text_color')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">Subheading Text Thickness</label>
                                    <select name="subheading_text_weight" id="subheading_text_weight"
                                        data-live-search="true"
                                        class="custom-select form-control border @error('subheading_text_weight') is-invalid @enderror"
                                        id="">
                                        <option value="{{ $thankYou->subheading_text_weight }}" selected>
                                            {{ ucFirst($thankYou->subheading_text_weight) }}</option>
                                        <option value="normal">Normal</option>
                                        <option value="bold">Bold</option>
                                        <option value="bolder">Bolder</option>
                                        <option value="light">Light</option>
                                        <option value="lighter">Lighter</option>
                                    </select>
                                    @error('subheading_text_weight')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">Subheading Text Size</label>
                                    <select name="subheading_text_size" id="subheading_text_size" data-live-search="true"
                                        class="custom-select form-control border @error('subheading_text_size') is-invalid @enderror"
                                        id="">
                                        <option value="{{ $thankYou->subheading_text_size }}" selected>
                                            Size-{{ $thankYou->subheading_text_size }}</option>
                                        <option value="1">Size-1 largest</option>
                                        <option value="2">Size-2</option>
                                        <option value="3">Size-3</option>
                                        <option value="4">Size-4</option>
                                        <option value="5">Size-5</option>
                                        <option value="6">Size-6 Smallest</option>
                                    </select>
                                    @error('subheading_text_size')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <hr>

                                <!---Download Invoice Button--->
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>Download Invoice Button</h4>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="" class="form-label">Text</label>
                                        <input type="text" name="button_text"
                                            class="form-control @error('button_text') is-invalid @enderror"
                                            placeholder="" value="{{ $thankYou->button_text }}">
                                        @error('button_text')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="form-label">Background Color</label>
                                        <input type="color" name="button_bg_color"
                                            class="form-control @error('button_bg_color') is-invalid @enderror"
                                            placeholder="" value="{{ $thankYou->button_bg_color }}">
                                        @error('button_bg_color')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="form-label">Text Style</label>
                                        <select name="button_text_style" id="button_text_style" data-live-search="true"
                                            class="custom-select form-control border @error('button_text_style') is-invalid @enderror"
                                            id="">
                                            <option value="{{ $thankYou->button_text_style }}" selected>
                                                {{ $thankYou->button_text_style }}</option>
                                            <option value="normal">normal</option>
                                            <option value="italic">italic</option>
                                        </select>
                                        @error('button_text_style')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="form-label">Text Align</label>
                                        <select name="button_text_align" id="button_text_align" data-live-search="true"
                                            class="custom-select form-control border @error('button_text_align') is-invalid @enderror"
                                            id="">
                                            <option value="{{ $thankYou->button_text_align }}" selected>
                                                {{ $thankYou->button_text_align }}</option>
                                            <option value="left">left</option>
                                            <option value="right">right</option>
                                            <option value="center">center</option>
                                        </select>
                                        @error('button_text_align')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="form-label">Text Color</label>
                                        <input type="color" name="button_text_color"
                                            class="form-control @error('button_text_color') is-invalid @enderror"
                                            value="{{ $thankYou->button_text_color }}">
                                        @error('button_text_color')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">Text Thickness</label>
                                        <select name="button_text_weight" id="button_text_weight" data-live-search="true"
                                            class="custom-select form-control border @error('button_text_weight') is-invalid @enderror"
                                            id="">
                                            <option value="{{ $thankYou->button_text_weight }}" selected>
                                                {{ ucFirst($thankYou->button_text_weight) }}</option>
                                            <option value="normal">Normal</option>
                                            <option value="bold">Bold</option>
                                            <option value="bolder">Bolder</option>
                                            <option value="light">Light</option>
                                            <option value="lighter">Lighter</option>
                                        </select>
                                        @error('button_text_weight')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">Text Size</label>
                                        <select name="button_text_size" id="button_text_size" data-live-search="true"
                                            class="custom-select form-control border @error('button_text_size') is-invalid @enderror"
                                            id="">
                                            <option value="{{ $thankYou->button_text_size }}" selected>
                                                Size-{{ $thankYou->button_text_size }}</option>
                                            <option value="1">Size-1 largest</option>
                                            <option value="2">Size-2</option>
                                            <option value="3">Size-3</option>
                                            <option value="4">Size-4</option>
                                            <option value="5">Size-5</option>
                                            <option value="6">Size-6 Smallest</option>
                                        </select>
                                        @error('button_text_size')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>Hover Effect: Download Invoice Button</h4>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="form-label">Background Color</label>
                                        <input type="color" name="onhover_button_bg_color"
                                            class="form-control @error('button_bg_color') is-invalid @enderror"
                                            placeholder="" value="{{ $thankYou->onhover_button_bg_color }}">
                                        @error('button_bg_color')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="form-label">Border Color</label>
                                        <input type="color" name="onhover_button_border_color"
                                            class="form-control @error('onhover_button_border_color') is-invalid @enderror"
                                            placeholder="" value="{{ $thankYou->onhover_button_border_color }}">
                                        @error('onhover_button_border_color')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="form-label">Text Style</label>
                                        <select name="onhover_button_text_style" id="button_text_style"
                                            data-live-search="true"
                                            class="custom-select form-control border @error('button_text_style') is-invalid @enderror"
                                            id="">
                                            <option value="{{ $thankYou->onhover_button_text_style }}" selected>
                                                {{ $thankYou->onhover_button_text_style }}</option>
                                            <option value="normal">normal</option>
                                            <option value="italic">italic</option>
                                        </select>
                                        @error('button_text_style')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="form-label">Text Color</label>
                                        <input type="color" name="onhover_button_text_color"
                                            class="form-control @error('button_text_color') is-invalid @enderror"
                                            value="{{ $thankYou->onhover_button_text_color }}">
                                        @error('button_text_color')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">Text Thickness</label>
                                        <select name="onhover_button_text_weight" id="button_text_weight"
                                            data-live-search="true"
                                            class="custom-select form-control border @error('button_text_weight') is-invalid @enderror"
                                            id="">
                                            <option value="{{ $thankYou->onhover_button_text_weight }}" selected>
                                                {{ ucFirst($thankYou->onhover_button_text_weight) }}</option>
                                            <option value="normal">Normal</option>
                                            <option value="bold">Bold</option>
                                            <option value="bolder">Bolder</option>
                                            <option value="light">Light</option>
                                            <option value="lighter">Lighter</option>
                                        </select>
                                        @error('button_text_weight')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">Text Size</label>
                                        <select name="onhover_button_text_size" id="button_text_size"
                                            data-live-search="true"
                                            class="custom-select form-control border @error('button_text_size') is-invalid @enderror"
                                            id="">
                                            <option value="{{ $thankYou->onhover_button_text_size }}" selected>
                                                Size-{{ $thankYou->onhover_button_text_size }}</option>
                                            <option value="1">Size-1 largest</option>
                                            <option value="2">Size-2</option>
                                            <option value="3">Size-3</option>
                                            <option value="4">Size-4</option>
                                            <option value="5">Size-5</option>
                                            <option value="6">Size-6 Smallest</option>
                                        </select>
                                        @error('button_text_size')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                                <!---Download Invoice Button--->
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="" class="form-label">Header Scripts</label>
                                        <textarea id="header_scripts" name="header_scripts" class="form-control" rows="5">{{ old('header_scripts', $thankYou->header_scripts) }}</textarea>
                                        @error('header_scripts')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="" class="form-label">Footer Scripts</label>
                                        <textarea id="footer_scripts" name="footer_scripts" class="form-control" rows="5">{{ old('footer_scripts', $thankYou->footer_scripts) }}</textarea>

                                        @error('footer_scripts')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <hr>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Update Template</button>
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                </div>
                            </form><!-- End Multi Columns Form -->

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->
@endsection

@section('extra_js')
@endsection
