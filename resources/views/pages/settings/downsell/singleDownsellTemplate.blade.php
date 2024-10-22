@extends('layouts.design')
@section('title')
    View Product
@endsection

@section('extra_css')
    <style>
        .camera {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
        const download = () => {
            html2canvas(document.querySelector('#downsell-section')).then(canvas => {
                document.getElementById('output').appendChild(canvas);
            });
        }
    </script>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Downsell Template</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('allDownsellTemplates') }}">Downsell Templates</a></li>
                    <li class="breadcrumb-item active">Downsell Template
                    <li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <hr>
        <section>
            <div class="row view" id="downsell-section">

                <div class="col-md-12">

                    <!--design-starts-->
                    <article
                        class="card @if ($downsellTemplate->body_border_radius != 'normal') {{ $downsellTemplate->body_border_radius }} @endif"
                        style="background-color: {{ $downsellTemplate->body_bg_color }};
                border-style: {{ $downsellTemplate->body_border_style }};
                border-color: {{ $downsellTemplate->body_border_color }};
                border-width: {{ $downsellTemplate->body_border_thickness }};
                ">
                        <div class="card-body">
                            {{-- <h5 class="card-title">Contact info</h5> --}}

                            <div class="row">

                                <!--downsell-->
                                <div class="col-12 mb-3">
                                    <div class="d-flex justify-content-center">
                                        <div class="content p-3">
                                            <p class="heading text-{{ $downsellTemplate->heading_text_align }} fst-{{ $downsellTemplate->heading_text_style }} fw-{{ $downsellTemplate->heading_text_weight }} fs-{{ $downsellTemplate->heading_text_size }}"
                                                style="color: {{ $downsellTemplate->heading_text_color }};">
                                                {{ $downsellTemplate->heading_text }}</p>

                                            @foreach ($downsellTemplate->subheading_text as $subheading)
                                                <p class="subheading text-{{ $downsellTemplate->subheading_text_align }} fst-{{ $downsellTemplate->subheading_text_style }} fw-{{ $downsellTemplate->subheading_text_weight }} fs-{{ $downsellTemplate->subheading_text_size }}"
                                                    style="color: {{ $downsellTemplate->subheading_text_color }};">
                                                    {!! $subheading !!}</p>
                                            @endforeach

                                            @if (isset($downsellTemplate->description_text))
                                                <p class="description text-{{ $downsellTemplate->description_text_align }} fst-{{ $downsellTemplate->description_text_style }} fw-{{ $downsellTemplate->description_text_weight }} fs-{{ $downsellTemplate->description_text_size }}"
                                                    style="color: {{ $downsellTemplate->description_text_color }};">
                                                    {{ $downsellTemplate->description_text }}
                                                </p>
                                            @endif

                                            <div class="downsell-product-image mb-3">
                                                <img src="https://via.placeholder.com/400.png?text=Product+Image"
                                                    class="img-thumbnail img-fluid" alt="">
                                            </div>

                                            <div class="before_btn">
                                                <p class="before_button text-{{ $downsellTemplate->before_button_text_align }} fst-{{ $downsellTemplate->before_button_text_style }} fw-{{ $downsellTemplate->before_button_text_weight }} fs-{{ $downsellTemplate->before_button_text_size }}"
                                                    style="color: {{ $downsellTemplate->before_button_text_color }};">
                                                    {{ $downsellTemplate->before_button_text }}</p>
                                            </div>

                                            <div class="select-downsell-product text-center">


                                                <div
                                                    class="call-to-action d-flex justify-content-center align-items-center">
                                                    <label for="downsell_product"
                                                        class="form-label d-flex align-items-center">
                                                        <span
                                                            class="text-{{ $downsellTemplate->package_text_align }} fst-{{ $downsellTemplate->package_text_style }} fw-{{ $downsellTemplate->package_text_weight }} fs-{{ $downsellTemplate->package_text_size }}"
                                                            style="color: {{ $downsellTemplate->package_text_color }};">Sample
                                                            Package Title =
                                                            N(Sample Package Title Price)</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="make-your-choice">

                                                <button type="submit"
                                                    class="btn w-100 p-2 downsell_submit_btn text-{{ $downsellTemplate->button_text_align }} fst-{{ $downsellTemplate->button_text_style }} fw-{{ $downsellTemplate->button_text_weight }} fs-{{ $downsellTemplate->button_text_size }}"
                                                    style="background-color: {{ $downsellTemplate->button_bg_color }}; color: {{ $downsellTemplate->button_text_color }};">{{ $downsellTemplate->button_text }}</button>

                                            </div>


                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="make-your-choice d-flex justify-content-center">

                                        <label for="downsell_refusal" class="form-label d-flex align-items-center">
                                            <input type="checkbox" name="downsell_refusal" id="downsell_refusal"
                                                class="cta-check2 me-1 downsell_refusal invisible"
                                                @error('product') checked @enderror value="true" />
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
                function(canvas) {
                    document
                        .getElementById('output')
                        .appendChild(canvas);
                })
        }
    </script>
@endsection
