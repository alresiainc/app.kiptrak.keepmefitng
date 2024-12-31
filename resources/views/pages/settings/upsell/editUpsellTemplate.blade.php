@extends('layouts.design')
@section('title')
    Edit UpSell Template
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
            <h1>Edit Upsell Template</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('allUpsellTemplates') }}">Upsell Templates</a></li>
                    <li class="breadcrumb-item active">Edit Upsell Template</li>
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
                                action="{{ route('editUpsellTemplatePost', $upsellTemplate->unique_key) }}" method="POST"
                                enctype="multipart/form-data">@csrf
                                <div class="col-md-12 mb-3">The field labels marked with * are required input fields.</div>

                                <div class="col-md-12">
                                    <h4>Template</h4>
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Template ID</label>
                                    <input type="text" name="template_code"
                                        class="form-control @error('template_code') is-invalid @enderror"
                                        value="{{ $upsellTemplate->template_code }}" readonly>
                                    @error('template_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Background Color</label>
                                    <input type="color" name="body_bg_color"
                                        class="form-control @error('body_bg_color') is-invalid @enderror"
                                        value="{{ $upsellTemplate->body_bg_color }}">
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
                                        <option value="{{ $upsellTemplate->body_border_style }}" selected>
                                            {{ $upsellTemplate->body_border_style }}</option>
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
                                        value="{{ $upsellTemplate->body_border_color }}">
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
                                        <option value="{{ $upsellTemplate->body_border_thickness }}" selected>
                                            {{ $upsellTemplate->body_border_thickness }}</option>
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
                                        <option value="{{ $upsellTemplate->body_border_radius }}" selected>
                                            {{ $upsellTemplate->body_border_radius }}</option>
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
                                        class="form-control @error('heading_text') is-invalid @enderror" placeholder=""
                                        value="{{ $upsellTemplate->heading_text }}">
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
                                        <option value="{{ $upsellTemplate->heading_text_style }}" selected>
                                            {{ $upsellTemplate->heading_text }}</option>
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
                                        <option value="{{ $upsellTemplate->heading_text_align }}" selected>
                                            {{ $upsellTemplate->heading_text_align }}</option>
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
                                        value="{{ $upsellTemplate->heading_text_color }}">
                                    @error('heading_text_color')
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
                                </div>

                                @foreach ($upsellTemplate->subheading_text as $subheading)
                                    <div class="col-md-6">
                                        <input type="text" name="subheading_text[]"
                                            class="form-control @error('subheading_text') is-invalid @enderror"
                                            value="{{ $subheading }}">
                                    </div>
                                @endforeach

                                <div class="product-clone-section wrapper">
                                    <div class="col-md-12 mt-1 element">
                                        <label for="" class="form-label">More Subheading Text</label>
                                        <input type="text" name="subheading_text[]" class="form-control"
                                            placeholder="" value="">
                                    </div>

                                    <!--append elements to-->
                                    <div class="results"></div>

                                    <div class="buttons d-flex justify-content-between">
                                        <button type="button" class="clone btn btn-success btn-sm rounded-pill"><i
                                                class="bi bi-plus"></i></button>
                                        <button type="button" class="remove btn btn-danger btn-sm rounded-pill"><i
                                                class="bi bi-dash"></i></button>
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Subheading Text Style</label>
                                    <select name="subheading_text_style" id="subheading_text_style"
                                        data-live-search="true"
                                        class="custom-select form-control border @error('subheading_text_style') is-invalid @enderror"
                                        id="">
                                        <option value="{{ $upsellTemplate->subheading_text_style }}" selected>
                                            {{ $upsellTemplate->subheading_text_style }}</option>
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
                                        <option value="{{ $upsellTemplate->subheading_text_align }}" selected>
                                            {{ $upsellTemplate->subheading_text_align }}</option>
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
                                        value="{{ $upsellTemplate->subheading_text_color }}">
                                    @error('subheading_text_color')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <hr>

                                <div class="col-md-12">
                                    <h4>Description</h4>
                                </div>

                                <div class="col-md-12">
                                    <label for="" class="form-label">Text</label>
                                    <input type="text" name="description_text"
                                        class="form-control @error('description_text') is-invalid @enderror"
                                        value="{{ $upsellTemplate->description_text }}">
                                    @error('description_text')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Text Style</label>
                                    <select name="description_text_style" id="description_text_style"
                                        data-live-search="true"
                                        class="custom-select form-control border @error('description_text_style') is-invalid @enderror"
                                        id="">
                                        <option value="{{ $upsellTemplate->description_text_style }}" selected>
                                            {{ $upsellTemplate->description_text_style }}</option>
                                        <option value="normal">normal</option>
                                        <option value="italic">italic</option>
                                    </select>
                                    @error('description_text_style')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Text Align</label>
                                    <select name="description_text_align" id="description_text_align"
                                        data-live-search="true"
                                        class="custom-select form-control border @error('description_text_align') is-invalid @enderror"
                                        id="">
                                        <option value="{{ $upsellTemplate->description_text_align }}" selected>
                                            {{ $upsellTemplate->description_text_align }}</option>
                                        <option value="normal">left</option>
                                        <option value="right">right</option>
                                        <option value="center">center</option>
                                    </select>
                                    @error('description_text_align')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Text Color</label>
                                    <input type="color" name="description_text_color"
                                        class="form-control @error('description_text_color') is-invalid @enderror"
                                        value="{{ $upsellTemplate->description_text_color }}">
                                    @error('description_text_color')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <hr>

                                <div class="col-md-12">
                                    <h4>Package</h4>
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Text Style</label>
                                    <select name="package_text_style" id="package_text_style " data-live-search="true"
                                        class="custom-select form-control border @error('package_text_style ') is-invalid @enderror"
                                        id="">
                                        <option value="{{ $upsellTemplate->package_text_style }}" selected>
                                            {{ $upsellTemplate->package_text_style }}</option>
                                        <option value="normal">normal</option>
                                        <option value="italic">italic</option>
                                    </select>
                                    @error('package_text_style ')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Text Align</label>
                                    <select name="package_text_align" id="package_text_align" data-live-search="true"
                                        class="custom-select form-control border @error('package_text_align') is-invalid @enderror"
                                        id="">
                                        <option value="{{ $upsellTemplate->package_text_align }}" selected>
                                            {{ $upsellTemplate->package_text_align }}</option>
                                        <option value="normal">left</option>
                                        <option value="right">right</option>
                                        <option value="center">center</option>
                                    </select>
                                    @error('package_text_align')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label">Text Color</label>
                                    <input type="color" name="package_text_color"
                                        class="form-control @error('package_text_color') is-invalid @enderror"
                                        value="{{ $upsellTemplate->package_text_color }}">
                                    @error('package_text_color')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <hr>

                                <div class="col-md-12">
                                    <h4>Add To Order Button</h4>
                                </div>

                                <div class="col-md-12">
                                    <label for="" class="form-label">Text</label>
                                    <input type="text" name="button_text"
                                        class="form-control @error('button_text') is-invalid @enderror" placeholder=""
                                        value="{{ $upsellTemplate->button_text }}">
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
                                        placeholder="" value="{{ $upsellTemplate->button_bg_color }}">
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
                                        <option value="{{ $upsellTemplate->button_text_style }}" selected>
                                            {{ $upsellTemplate->button_text_style }}</option>
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
                                        <option value="{{ $upsellTemplate->button_text_align }}" selected>
                                            {{ $upsellTemplate->button_text_align }}</option>
                                        <option value="normal">left</option>
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
                                        value="{{ $upsellTemplate->button_text_color }}">
                                    @error('button_text_color')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="" class="form-label">Header Scripts</label>
                                        <textarea id="header_scripts" name="header_scripts" class="form-control" rows="5">{{ old('header_scripts', $upsellTemplate->header_scripts) }}</textarea>
                                        @error('header_scripts')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="" class="form-label">Footer Scripts</label>
                                        <textarea id="footer_scripts" name="footer_scripts" class="form-control" rows="5">{{ old('footer_scripts', $upsellTemplate->footer_scripts) }}</textarea>

                                        @error('footer_scripts')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Save Template</button>
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                </div>
                            </form><!-- End Multi Columns Form -->

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- Modal -->
    <div class="modal fade" id="addCustomer" tabindex="-1" aria-labelledby="addCustomerLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add
                        Customer</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('addCustomerPost') }}" method="POST" enctype="multipart/form-data">@csrf
                    <div class="modal-body">

                        <div class="d-grid mb-2">
                            <label for="">First Name</label>
                            <input type="text" name="firstname" class="form-control" placeholder="">
                        </div>

                        <div class="d-grid mb-2">
                            <label for="">Last Name</label>
                            <input type="text" name="lastname" class="form-control" placeholder="">
                        </div>
                        <div class="d-grid mb-2">
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="">
                        </div>

                        <div class="d-grid mb-2">
                            <label for="">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" placeholder="">
                        </div>

                        <div class="d-grid mb-2">
                            <label for="">Whatsapp Number</label>
                            <input type="text" name="whatsapp_phone_number" class="form-control" placeholder="">
                        </div>

                        <div class="d-grid mb-2">
                            <label for="">Address</label>
                            <input type="text" name="delivery_address" class="form-control" placeholder="">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        //clone
        $('.wrapper').on('click', '.remove', function() {
            $('.remove').closest('.wrapper').find('.element').not(':first').last().remove();
        });
        $('.wrapper').on('click', '.clone', function() {
            $('.clone').closest('.wrapper').find('.element').first().clone().appendTo('.results');
        });
    </script>
@endsection
