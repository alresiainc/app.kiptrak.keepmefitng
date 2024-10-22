@extends('layouts.design')
@section('title')Create UpSale Template @endsection
@section('extra_css')
    <style>
        select{
        -webkit-appearance: listbox !important
        }
        .btn-light {
            background-color: #fff !important;
            color: #000 !important;
        }
        /* .bootstrap-select>.dropdown-toggle.bs-placeholder, .bootstrap-select>.dropdown-toggle.bs-placeholder:active, .bootstrap-select>.dropdown-toggle.bs-placeholder:focus, .bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
            color: #999;
        } */
        div.filter-option-inner-inner{
            color: #000 !important;
        }
        .ck-editor__editable[role="textbox"] {
                /* editing area */
                min-height: 200px;
            }
    </style>
@endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Create Downsell Template</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('allDownsellTemplates') }}">Downsell Templates</a></li>
          <li class="breadcrumb-item active">Create Downsell Template</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

      </div>
    </section>

    @if(Session::has('success'))
    <div class="alert alert-success mb-3 text-center">
        {{Session::get('success')}}
    </div>
    @endif

    <section>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              
              <form class="row g-3 needs-validation" action="{{ route('addDownsellTemplatePost') }}" method="POST"
              enctype="multipart/form-data">@csrf
              <div class="col-md-12 mb-3">The field labels marked with * are required input fields.</div>

                <div class="col-md-12"><h4>Template</h4></div>

                <div class="col-md-4">
                    <label for="" class="form-label">Template ID</label>
                    <input type="text" name="template_code" class="form-control @error('template_code') is-invalid @enderror" value="{{ $template_code }}" readonly>
                    @error('template_code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Background Color</label>
                  <input type="color" name="body_bg_color" class="form-control @error('body_bg_color') is-invalid @enderror" value="#ead7d7">
                  @error('body_bg_color')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="" class="form-label">Border Style</label>
                    <select name="body_border_style" id="body_border_style" data-live-search="true" class="custom-select form-control border @error('sale_status') is-invalid @enderror" id="">
                      <option value="dotted" selected>dotted</option>
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
                    <input type="color" name="body_border_color" class="form-control @error('body_border_color') is-invalid @enderror" value="#000000">
                    @error('body_border_color')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="" class="form-label">Border Thickness</label>
                    <select name="body_border_thickness" id="body_border_thickness" data-live-search="true" class="custom-select form-control border @error('body_border_thickness') is-invalid @enderror" id="">
                      <option value="1px" selected>1px</option>
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
                    <select name="body_border_radius" id="body_border_radius" data-live-search="true" class="custom-select form-control border @error('body_border_radius') is-invalid @enderror" id="">
                      <option value="normal" selected>normal</option>
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
                    <input type="text" name="heading_text" class="form-control @error('heading_text') is-invalid @enderror" value="Wait, One More Chance" >
                    @error('heading_text')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="" class="form-label">Heading Text Style</label>
                    <select name="heading_text_style" id="header_text_style" data-live-search="true" class="custom-select form-control border @error('heading_text_style') is-invalid @enderror" id="">
                      <option value="normal" selected>normal</option>
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
                    <select name="heading_text_align" id="heading_text_align" data-live-search="true" class="custom-select form-control border @error('heading_text_align') is-invalid @enderror" id="">
                      <option value="left">left</option>
                      <option value="right">right</option>
                      <option value="center" selected>center</option>
                    </select>
                    @error('heading_text_align')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="" class="form-label">Heading Text Color</label>
                    <input type="color" name="heading_text_color" class="form-control @error('heading_text_color') is-invalid @enderror" value="#000000" >
                    @error('heading_text_color')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">Heading Text Thickness</label>
                    <select name="heading_text_weight" id="heading_text_weight" data-live-search="true" class="custom-select form-control border @error('heading_text_weight') is-invalid @enderror" id="">
                      <option value="normal">Normal</option>
                      <option value="bold" selected>Bold</option>
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
                    <select name="heading_text_size" id="heading_text_size" data-live-search="true" class="custom-select form-control border @error('heading_text_size') is-invalid @enderror" id="">
                      <option value="1">Size-1 largest</option>
                      <option value="2">Size-2</option>
                      <option value="3" selected>Size-3</option>
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

                <div class="col-md-12 d-none">
                    <label for="" class="form-label">Subheading Text</label>
                    
                    <input type="text" name="subheading_text2" class="form-control d-none @error('subheading_text') is-invalid @enderror" value="We're giving this at a giveaway price">
                    {{-- <textarea name="subheading_text" id="" cols="30" rows="5" class="tinymce-editor form-control @error('subheading_text') is-invalid @enderror">We're giving this at a giveaway price</textarea> --}}
                    <textarea name="subheading_text" id="ckeditor" class="ckeditor"></textarea>
                    @error('subheading_text')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="product-clone-section wrapper">
                    <div class="col-md-12 mt-1 element">
                      <label for="" class="form-label">Subheading Text</label>
                      <input type="text" name="subheading_text[]" class="form-control @error('subheading_text') is-invalid @enderror" placeholder="" value="">
                    </div>
  
                    <!--append elements to-->
                    <div class="results"></div>
  
                    <div class="buttons d-flex justify-content-between">
                      <button type="button" class="clone btn btn-success btn-sm rounded-pill"><i class="bi bi-plus"></i></button>
                      <button type="button" class="remove btn btn-danger btn-sm rounded-pill"><i class="bi bi-dash"></i></button>
                    </div>

                    @error('subheading_text')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="" class="form-label">Subheading Text Style</label>
                    <select name="subheading_text_style" id="subheading_text_style" data-live-search="true" class="custom-select form-control border @error('subheading_text_style') is-invalid @enderror" id="">
                      <option value="normal" selected>normal</option>
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
                    <select name="subheading_text_align" id="subheading_text_align" data-live-search="true" class="custom-select form-control border @error('subheading_text_align') is-invalid @enderror" id="">
                      <option value="left">left</option>
                      <option value="right">right</option>
                      <option value="center" selected>center</option>
                    </select>
                    @error('subheading_text_align')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="" class="form-label">Subheading Text Color</label>
                    <input type="color" name="subheading_text_color" class="form-control @error('subheading_text_color') is-invalid @enderror" placeholder="" >
                    @error('subheading_text_color')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">Subheading Text Thickness</label>
                    <select name="subheading_text_weight" id="subheading_text_weight" data-live-search="true" class="custom-select form-control border @error('subheading_text_weight') is-invalid @enderror" id="">
                      <option value="normal" selected>Normal</option>
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
                    <select name="subheading_text_size" id="subheading_text_size" data-live-search="true" class="custom-select form-control border @error('subheading_text_size') is-invalid @enderror" id="">
                      <option value="1">Size-1 largest</option>
                      <option value="2">Size-2</option>
                      <option value="3">Size-3</option>
                      <option value="4">Size-4</option>
                      <option value="5" selected>Size-5</option>
                      <option value="6">Size-6 Smallest</option>
                    </select>
                    @error('subheading_text_size')
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
                    <input type="text" name="description_text" class="form-control @error('description_text') is-invalid @enderror" value="This will cost less, as you buy with the package" >
                    @error('description_text')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="" class="form-label">Text Style</label>
                    <select name="description_text_style" id="description_text_style" data-live-search="true" class="custom-select form-control border @error('description_text_style') is-invalid @enderror" id="">
                      <option value="normal" selected>normal</option>
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
                    <select name="description_text_align" id="description_text_align" data-live-search="true" class="custom-select form-control border @error('description_text_align') is-invalid @enderror" id="">
                      <option value="left">left</option>
                      <option value="right">right</option>
                      <option value="center" selected>center</option>
                    </select>
                    @error('description_text_align')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="" class="form-label">Text Color</label>
                    <input type="color" name="description_text_color" class="form-control @error('description_text_color') is-invalid @enderror" placeholder="" >
                    @error('description_text_color')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">Text Thickness</label>
                    <select name="description_text_weight" id="description_text_weight" data-live-search="true" class="custom-select form-control border @error('description_text_weight') is-invalid @enderror" id="">
                      <option value="normal">Normal</option>
                      <option value="bold">Bold</option>
                      <option value="bolder">Bolder</option>
                      <option value="light" selected>Light</option>
                      <option value="lighter">Lighter</option>
                    </select>
                    @error('description_text_weight')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">Text Size</label>
                    <select name="description_text_size" id="description_text_size" data-live-search="true" class="custom-select form-control border @error('description_text_size') is-invalid @enderror" id="">
                      <option value="1">Size-1 largest</option>
                      <option value="2">Size-2</option>
                      <option value="3">Size-3</option>
                      <option value="4">Size-4</option>
                      <option value="5" selected>Size-5</option>
                      <option value="6">Size-6 Smallest</option>
                    </select>
                    @error('description_text_size')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <hr>
                
                <div class="col-md-12"><h4>Package</h4></div>

                <div class="col-md-4">
                    <label for="" class="form-label">Text Style</label>
                    <select name="package_text_style" id="package_text_style " data-live-search="true" class="custom-select form-control border @error('package_text_style ') is-invalid @enderror" id="">
                      <option value="normal" selected>normal</option>
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
                    <select name="package_text_align" id="package_text_align" data-live-search="true" class="custom-select form-control border @error('package_text_align') is-invalid @enderror" id="">
                      <option value="left">left</option>
                      <option value="right">right</option>
                      <option value="center" selected>center</option>
                    </select>
                    @error('package_text_align')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="" class="form-label">Text Color</label>
                    <input type="color" name="package_text_color" class="form-control @error('package_text_color') is-invalid @enderror" placeholder="" >
                    @error('package_text_color')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">Text Thickness</label>
                    <select name="package_text_weight" id="package_text_weight" data-live-search="true" class="custom-select form-control border @error('package_text_weight') is-invalid @enderror" id="">
                      <option value="normal" selected>Normal</option>
                      <option value="bold">Bold</option>
                      <option value="bolder">Bolder</option>
                      <option value="light">Light</option>
                      <option value="lighter">Lighter</option>
                    </select>
                    @error('package_text_weight')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">Text Size</label>
                    <select name="package_text_size" id="package_text_size" data-live-search="true" class="custom-select form-control border @error('package_text_size') is-invalid @enderror" id="">
                      <option value="1">Size-1 largest</option>
                      <option value="2">Size-2</option>
                      <option value="3">Size-3</option>
                      <option value="4">Size-4</option>
                      <option value="5" selected>Size-5</option>
                      <option value="6">Size-6 Smallest</option>
                    </select>
                    @error('package_text_size')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <hr>

                <div class="col-md-12">
                    <h4>Before Button Section</h4>
                </div>

                <div class="col-md-12">
                    <label for="" class="form-label">Text</label>
                    <input type="text" name="before_button_text" class="form-control @error('before_button_text') is-invalid @enderror" placeholder="" value="Click the Button below to add this product to your order">
                    @error('before_button_text')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="" class="form-label">Text Style</label>
                    <select name="before_button_text_style" id="before_button_text_style " data-live-search="true" class="custom-select form-control border @error('before_button_text_style ') is-invalid @enderror" id="">
                      <option value="normal" selected>normal</option>
                      <option value="italic">italic</option>
                    </select>
                    @error('before_button_text_style ')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="" class="form-label">Text Align</label>
                    <select name="before_button_text_align" id="before_button_text_align" data-live-search="true" class="custom-select form-control border @error('before_button_text_align') is-invalid @enderror" id="">
                      <option value="left">left</option>
                      <option value="right">right</option>
                      <option value="center" selected>center</option>
                    </select>
                    @error('before_button_text_align')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="" class="form-label">Text Color</label>
                    <input type="color" name="before_button_text_color" class="form-control @error('before_button_text_color') is-invalid @enderror" placeholder="" >
                    @error('before_button_text_color')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">Text Thickness</label>
                    <select name="before_button_text_weight" id="before_button_text_weight" data-live-search="true" class="custom-select form-control border @error('before_button_text_weight') is-invalid @enderror" id="">
                      <option value="normal" selected>Normal</option>
                      <option value="bold">Bold</option>
                      <option value="bolder">Bolder</option>
                      <option value="light">Light</option>
                      <option value="lighter">Lighter</option>
                    </select>
                    @error('before_button_text_weight')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">Text Size</label>
                    <select name="before_button_text_size" id="before_button_text_size" data-live-search="true" class="custom-select form-control border @error('before_button_text_size') is-invalid @enderror" id="">
                      <option value="1">Size-1 largest</option>
                      <option value="2">Size-2</option>
                      <option value="3">Size-3</option>
                      <option value="4">Size-4</option>
                      <option value="5" selected>Size-5</option>
                      <option value="6">Size-6 Smallest</option>
                    </select>
                    @error('before_button_text_size')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <hr>

                <div class="col-md-12">
                    <h4>Add this to my Order Button</h4>
                </div>

                <div class="col-md-12">
                    <label for="" class="form-label">Text</label>
                    <input type="text" name="button_text" class="form-control @error('button_text') is-invalid @enderror" placeholder="" value="Add to Order">
                    @error('button_text')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="" class="form-label">Background Color</label>
                    <input type="color" name="button_bg_color" class="form-control @error('button_bg_color') is-invalid @enderror" placeholder="" value="#04512d">
                    @error('button_bg_color')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="" class="form-label">Text Style</label>
                    <select name="button_text_style" id="button_text_style" data-live-search="true" class="custom-select form-control border @error('button_text_style') is-invalid @enderror" id="">
                      <option value="normal" selected>normal</option>
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
                    <select name="button_text_align" id="button_text_align" data-live-search="true" class="custom-select form-control border @error('button_text_align') is-invalid @enderror" id="">
                      <option value="left">left</option>
                      <option value="right">right</option>
                      <option value="center" selected>center</option>
                    </select>
                    @error('button_text_align')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="col-md-3">
                    <label for="" class="form-label">Text Color</label>
                    <input type="color" name="button_text_color" class="form-control @error('button_text_color') is-invalid @enderror" value="#ffffff" >
                    @error('button_text_color')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">Text Thickness</label>
                    <select name="button_text_weight" id="button_text_weight" data-live-search="true" class="custom-select form-control border @error('button_text_weight') is-invalid @enderror" id="">
                      <option value="normal" selected>Normal</option>
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
                    <select name="button_text_size" id="button_text_size" data-live-search="true" class="custom-select form-control border @error('button_text_size') is-invalid @enderror" id="">
                      <option value="1">Size-1 largest</option>
                      <option value="2">Size-2</option>
                      <option value="3">Size-3</option>
                      <option value="4">Size-4</option>
                      <option value="5" selected>Size-5</option>
                      <option value="6">Size-6 Smallest</option>
                    </select>
                    @error('button_text_size')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
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
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <input type="text" name="phone_number" class="form-control"
                            placeholder="">
                    </div>

                    <div class="d-grid mb-2">
                        <label for="">Whatsapp Number</label>
                        <input type="text" name="whatsapp_phone_number" class="form-control"
                            placeholder="">
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

<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>

  <script>
    ClassicEditor
        .create( document.querySelector( '.ckeditor' ) )
        .catch( error => {
            console.error( error );
        } );
</script>
@endsection