@extends('layouts.design')
@section('title')View Product @endsection

@section('extra_css')
    <style>
        .camera{
            cursor: pointer;
        }
    </style>
@endsection

@section('content')

<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
   const download = () => {
      html2canvas(document.querySelector('#upsell-section')).then(canvas => {
         document.getElementById('output').appendChild(canvas);
      });
   }
</script>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Upsell Template</h1>
      <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('allUpsellTemplates') }}">Upsell Templates</a></li>
          <li class="breadcrumb-item active">Upsell Template<li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <hr>
    <section>
        <div class="row view" id="upsell-section">
                
            <div class="col-md-12">

                <!--design-starts-->
                <article class="card @if($upsellTemplate->body_border_radius != 'normal') {{ $upsellTemplate->body_border_radius }} @endif"
                style="background-color: {{ $upsellTemplate->body_bg_color }};
                border-style: {{ $upsellTemplate->body_border_style }};
                border-color: {{ $upsellTemplate->body_border_color }};
                border-width: {{ $upsellTemplate->body_border_thickness }};
                "
                >
                    <div class="card-body">
                        {{-- <h5 class="card-title">Contact info</h5> --}}
                        
                        <div class="row">

                            <!--upsell-->
                            <div class="col-12 mb-3">
                                <div class="d-flex justify-content-center">
                                    <div class="content p-3">
                                        <p class="heading text-{{ $upsellTemplate->heading_text_align }} fst-{{ $upsellTemplate->heading_text_style }} fw-{{$upsellTemplate->heading_text_weight}} fs-{{$upsellTemplate->heading_text_size}}"
                                        style="color: {{ $upsellTemplate->heading_text_color }};">{{ $upsellTemplate->heading_text }}</p>
                                        
                                        @foreach ($upsellTemplate->subheading_text as $subheading)
                                        <p class="subheading text-{{ $upsellTemplate->subheading_text_align }} fst-{{ $upsellTemplate->subheading_text_style }} fw-{{$upsellTemplate->subheading_text_weight}} fs-{{$upsellTemplate->subheading_text_size}}"
                                        style="color: {{ $upsellTemplate->subheading_text_color }};">{!! $subheading !!}</p>
                                        @endforeach
                                        
                                        @if (isset($upsellTemplate->description_text))
                                        <p class="description text-{{$upsellTemplate->description_text_align}} fst-{{ $upsellTemplate->description_text_style }} fw-{{$upsellTemplate->description_text_weight}} fs-{{$upsellTemplate->description_text_size}}"
                                        style="color: {{ $upsellTemplate->description_text_color }};">
                                            {{ $upsellTemplate->description_text }}
                                        </p>
                                        @endif
                                        
                                        <div class="upsell-product-image mb-3">
                                            <img src="https://via.placeholder.com/400.png?text=Product+Image" class="img-thumbnail img-fluid"
                                            alt="">
                                        </div>

                                        <div class="before_btn">
                                            <p class="before_button text-{{$upsellTemplate->before_button_text_align}} fst-{{ $upsellTemplate->before_button_text_style }} fw-{{$upsellTemplate->before_button_text_weight}} fs-{{$upsellTemplate->before_button_text_size}}"
                                                style="color: {{ $upsellTemplate->before_button_text_color }};">{{ $upsellTemplate->before_button_text }}</p>
                                        </div>

                                        <div class="select-upsell-product text-center">


                                            <div class="call-to-action d-flex justify-content-center align-items-center">
                                                <label for="upsell_product" class="form-label d-flex align-items-center">
                                                    <span class="text-{{ $upsellTemplate->package_text_align }} fst-{{ $upsellTemplate->package_text_style }} fw-{{$upsellTemplate->package_text_weight}} fs-{{$upsellTemplate->package_text_size}}"
                                                    style="color: {{ $upsellTemplate->package_text_color }};">Sample Package Title =
                                                        N(Sample Package Title Price)</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="make-your-choice">

                                            <button type="submit" class="btn w-100 p-2 upsell_submit_btn text-{{ $upsellTemplate->button_text_align }} fst-{{ $upsellTemplate->button_text_style }} fw-{{$upsellTemplate->button_text_weight}} fs-{{$upsellTemplate->button_text_size}}"
                                                style="background-color: {{ $upsellTemplate->button_bg_color }}; color: {{ $upsellTemplate->button_text_color }};">{{ $upsellTemplate->button_text }}</button>

                                        </div>
                                        
                                            
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="make-your-choice d-flex justify-content-center">

                                    <label for="upsell_refusal" class="form-label d-flex align-items-center">
                                        <input type="checkbox" name="upsell_refusal" id="upsell_refusal" class="cta-check2 me-1 upsell_refusal invisible"
                                        @error('product') checked @enderror value="true"/>
                                        <span class="fw-light" style="color: #012970;">No, thank you</span>
                                    </label>

                                </div>
                            </div>
                                
                        </div> <!-- row.// --> 
                            
                    </div> <!-- card-body end.// -->
                </article>
                <!--design-ends-->

            </div>

        </div>
    </section>

    <div id="camera" class="col-md-12 text-center d-none">
        <div onclick="download()"><i class="bi bi-camera-fill text-dark p-1 display-1 camera"></i></div>
    </div>

    <section id="output"></section>

</main><!-- End #main -->

@endsection

@section('extra_ja')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script> --}}

<script>
  
    // Define the function 
    // to screenshot the div
    function takeshot() {
        alert('122')
        let div =
            document.getElementById('camera');

        // Use the html2canvas
        // function to take a screenshot
        // and append it
        // to the output div
        html2canvas(div).then(
            function (canvas) {
                document
                .getElementById('output')
                .appendChild(canvas);
            })
    }
</script>

@endsection