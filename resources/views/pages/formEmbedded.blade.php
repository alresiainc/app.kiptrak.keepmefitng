<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0, user-scalable=0"
            name="viewport">

        <title>Order Form :: CRM</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        <!-- Favicons -->
        <link href="{{asset('/customerform/assets/img/favicon.png')}}" rel="icon">
        <link href="{{asset('/customerform/assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">
        <!-- Google Fonts -->
        <link href="https://fonts.gstatic.com" rel="preconnect">
        <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
            rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link href="{{asset('/customerform/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('/customerform/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
        <!-- Latest compiled and minified CSS -->
        
    
        <!-- Font awesome 5 -->
        <link rel="preload" href="{{asset('/customerform/assets/vendor/font-awesome/webfonts/fa-solid-900.woff2')}}" as="font" type="font/woff" crossorigin>
        <link href="{{asset('/customerform/assets/vendor/font-awesome/css/all.min.css')}}" type="text/css" rel="stylesheet">

        
        <!-- Template Main CSS File -->
        <link href="{{asset('/customerform/assets/css/ui.css')}}" rel="stylesheet">
        <link href="{{asset('/customerform/assets/css/form-style.css')}}" rel="stylesheet">


    </head>

    <body class="">

        <!-- will be shown in singlelink-->
        <!--nav-bar-->

    <div class="container">
        
        {{-- @if (count($errors) > 0)
            @foreach ($errors as $error)
            <div class="alert alert-danger mb-3 text-center">
                {{  $error }}
            </div>
            @endforeach
        @endif --}}
        
        <!-- CHECKOUT VIEW -->
        <div class="row view" id="checkout">
            {{-- <embed src="https://google.com" type=""> --}}
                {{-- <object data="{{ route('test') }}" type=""></object> --}}
            <div class="col-md-12">

                <article class="card">
                    <div class="card-body">
                        <h5 class="card-title">Contact info</h5>
                        <form action="{{ route('formLinkPost', $unique_key) }}" method="POST">@csrf
                            <div class="row">

                                @foreach ($formContact as $contact)
                                <div class="col-6 mb-3">
                                    <label class="form-label">{{ $contact['label'] }}</label>
                                    <input type="{{ $contact['type'] }}" name="{{ $contact['name'] }}" class="form-control @error($contact['name']) is-invalid @enderror"
                                    placeholder="Type here">
                                    @error($contact['name'])
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div> <!-- col end.// -->
                                @endforeach

                                <hr>
                                <div class="col-12 mb-1 mt-3 fw-bolder">Select A Package From Below</div>
                                
                                @foreach ($products as $key=>$item)
                                <div class="col-12 mb-3">
                                    <label for="package{{$key}}" class="form-label btn btn-outline border d-flex align-items-center me-3">
                                        <input type="{{ $item['type'] }}" name="package[]" id="package{{$key}}" class="contact-check me-3" value="{{ $item['value'] }}"/>
                                        <span class="me-1 fw-bold">{{ $item['label'] }} = {{ $item['product_price'] }} naira</span>
                                    </label>
                                </div> <!-- col end.// -->
                                @endforeach

                                <div class="col-12">
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-success w-50">Submit Order</button>
                                    </div>
                                </div>
                                    
                            </div> <!-- row.// --> 
                            
                        </form>
                        
                        
                    </div> <!-- card-body end.// -->
                </article>

            </div>
            
        </div>
    </div>

    <!-- <hr> will be shown in singlelink-->
    <!--footer--->

        <!-- Vendor JS Files -->
        <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
        
        <script src="{{asset('/customerform/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        


    </body>

</html>