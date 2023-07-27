<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0, user-scalable=0"
            name="viewport">
    
        <title>Thank-You Template :: KipTrak</title>
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
    
        
        <!-- upsell->Template Main CSS File -->
        <link href="{{asset('/customerform/assets/css/ui.css')}}" rel="stylesheet">
        <link href="{{asset('/customerform/assets/css/form-style.css')}}" rel="stylesheet">
    
        <style>
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
        </style>
    
        
    </head>

<body class="">

<!-- will be shown in singlelink-->
<nav class="navbar bg-light d-none">
    <div class="container">
        {{-- <a class="navbar-brand" href="/">
        <img src="{{asset('/assets/img/logo.png')}}" alt="Kiptrak Logo" class="d-inline-block align-text-top" style="max-height: 130px; margin-right: 6px;" />
        <span class="project-namek"></span>
        </a> --}}

        <a href="/" class="logo d-flex align-items-center d-none">
            <img src="{{asset('/assets/img/logo.png')}}" alt="Kiptrak Logo" style="max-height: 130px; margin-right: 6px;">
            {{-- <span class="d-none d-lg-block project-name"></span> --}}
        </a>
    </div>
</nav>

<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
      <a href="javascript:void(0);" class="logo d-flex align-items-center">
        <img src="{{asset('/assets/img/logo.png')}}" alt="Kiptrak Logo" style="width: 30%; !important">
        <span class="d-none d-lg-block project-namek"></span>
      </a>
    </div>
    <!-- End Logo -->    
</header>

<main class="container mb-5 py-5 min-vh-100 @if($thankYou->body_border_radius != 'normal') {{ $thankYou->body_border_radius }} @endif"
    style="background-color: {{ $thankYou->body_bg_color }};
        border-style: {{ $thankYou->body_border_style }};
        border-color: {{ $thankYou->body_border_color }};
        border-width: {{ $thankYou->body_border_thickness }};
    ">
    
    <!-- Monitoring diferent stages in the form -->
    <input type="hidden" name="main_stage" class="main_stage" value="">
    <input type="hidden" name="orderbump_stage" class="orderbump_stage" value="">
    <input type="hidden" name="upsell_stage" class="upsell_stage" value="">
    <input type="hidden" name="thankyou_stage" class="thankyou_stage" value="">
    <input type="hidden" name="current_order_id" class="current_order_id" value="">
    <input type="hidden" name="cartAbandoned_id" class="cartAbandoned_id" value="2">
    <!-- Monitoring diferent stages in the form -->

    <!-- CHECKOUT VIEW Main + orderbump + upsell -->
    
    <!---orderbump view--->
    <input type="hidden" name="has_orderbump" class="has_orderbump" value="false">
    
    <!---upsell view--->
    <input type="hidden" name="has_upsell" class="has_upsell" value="false">
    
    <!-- THANKYOU VIEW -->
        
    <div class="view" id="thankyou-section" style="margin-top: 10px;">
        <div class="row">
            <div class="col-lg-12 mb-3 d-none">
                <article class="card shadow-sm">
                    <div class="card-body"> 
                        <div class="mt-4 mx-auto text-center" style="max-width:600px">
                            <svg width="96px" height="96px" viewBox="0 0 96 96" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="round-check"><circle id="Oval" fill="#D3FFD9" cx="48" cy="48" r="48"></circle>
                                        <circle id="Oval-Copy" fill="#87FF96" cx="48" cy="48" r="36"></circle>
                                        <polyline id="Line" stroke="#04B800" stroke-width="4" stroke-linecap="round" points="34.188562 49.6867496 44 59.3734993 63.1968462 40.3594229"></polyline>
                                    </g>
                                </g>
                            </svg> 
                            <div class="my-3"> 
                                <h4 class="heading text-{{ $thankYou->heading_text_align }} fst-{{ $thankYou->heading_text_style }} fw-{{$thankYou->heading_text_weight}} fs-{{$thankYou->heading_text_size}}"
                                    style="color: {{ $thankYou->heading_text_color }};">{{ $thankYou->heading_text }}</h4>

                                <p class="subheading text-{{ $thankYou->subheading_text_align }} fst-{{ $thankYou->subheading_text_style }} fw-{{$thankYou->subheading_text_weight}} fs-{{$thankYou->subheading_text_size}}"
                                    style="color: {{ $thankYou->subheading_text_color }};">{{ $thankYou->subheading_text }}</p> 
                            </div>
                        </div>                 
                    </div>
                </article>
            </div>
            
            <!--order-summary-->
            @if ($customer != "")           
            <div class="col-lg-12">
                <article class="card shadow-sm mb-3 text-end">
                    <div onclick="generatePDF()"> 
                        {{-- <a href="javascript:void(0);" id="generate-pdf" class="btn btn-sm btn-success"><i class="bi bi-download text-white"></i> Download Invoice</a> --}}
                        
                        <a href="javascript:void(0);" class="btn btn-sm text-{{ $thankYou->button_text_align }} fst-{{ $thankYou->button_text_style }} fw-{{$thankYou->button_text_weight}} fs-{{$thankYou->button_text_size}}"
                            style="background-color: {{ $thankYou->button_bg_color }}; color: {{ $thankYou->button_text_color }};"><i class="bi bi-download text-white"></i> {{ $thankYou->button_text }}</a>
                    </div>
                    <div class="card-body" id="invoice">
                        <header class="d-md-flex text-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-0"> Order ID: {{ $orderId }} <i class="dot"></i><span class="text-danger"> Pending </span> </h6> 
                                <span>Date: <span class="order_updated_date">{{ $order->updated_at->format('D, jS M Y') }}</span></span>
                            </div>
                             
                        </header> 
                        <hr>

                        <div class="row"> 
                            <div class="col-md-4"> 
                                <p class="fw-bold mb-0 text-success">Contact</p> 
                                <hr>
                                <p class="m-0"> 
                                    <span class="customer_name">Name: {{ $customer->firstname }} {{ $customer->lastname }}</span> 
                                    <br> Phone: <span class="customer_phone">{{ $customer->phone_number }}, {{ $customer->whatsapp_phone_number }}</span> 
                                    <br> Email: <span class="customer_email">{{ $customer->email }}</span> 
                                </p>
                            </div> <!-- col.// --> 
                            <div class="col-md-4 border-start"> 
                                <p class="fw-bold mb-0 text-success">Shipping address</p> 
                                <hr>
                                <p class="m-0"> <span class="customer_country"></span> 
                                    <br> <span class="customer_address">{{ $customer->delivery_address }}</span> 
                                </p>
                            </div> <!-- col.// --> 
                            <div class="col-md-4 border-start">
                                <p class="fw-bold mb-0 text-success">Payment</p> 
                                <hr>
                                <p class="m-0">
                                    <!-- <span class="text-success"> Cash Payment </span>  -->
                                    <dl class="dlist-align">
                                        <dt class="fw-bolder">Method:</dt> <dd>Cash Payment</dd>
                                    </dl>
                                    <dl class="dlist-align">
                                        <dt class="fw-bolder">No. of Packages:</dt> <dd><span class="no_of_items">{{ $qty_total }}</span></dd>
                                    </dl>
                                    <dl class="dlist-align">
                                        <dt class="fw-bolder">Order Amount:</dt> <dd>N<span class="order_amount">{{ $order_total_amount }}</span></dd> 
                                    </dl> 
                                    <dl class="dlist-align">
                                        <dt class="fw-bolder">Discount:</dt> <dd>N0.00</dd> 
                                    </dl> 
                                    <dl class="dlist-align">
                                        <dt class="fw-bolder">Grand Total:</dt> <dd>N<span class="grand_total">{{ $grand_total }}</span></dd> 
                                    </dl> 
                                </p>
                            </div> <!-- col.// --> 
                        </div> <!-- row.// -->
                        <hr>

                        <ul class="row g-3">
                            <div class="text-center"><p class="fw-bold mb-0 text-success">Products you ordered</p> <hr></div>
                            @foreach ($mainProducts_outgoingStocks as $main_outgoingStock)
                            @if (isset($main_outgoingStock->product))
                            <li class="col-lg-4 col-md-6"> 
                                <div class="itemside mb-3"> 
                                    <div class="aside"> 
                                        <img width="72" height="72" src="{{ asset('/storage/products/'.$main_outgoingStock->product->image) }}" class="img-sm rounded border">
                                    </div> 
                                    <div class="info text-start"> 
                                        <p class="title">{{ $main_outgoingStock->product->name }}</p> 
                                        <strong>N{{ $main_outgoingStock->amount_accrued }} ({{ $main_outgoingStock->quantity_removed }} items)</strong> 
                                    </div> 
                                </div> 
                            </li>
                            @endif
                            
                            @endforeach
                            
                            <!---for orderbump or upsell--->
                            @if (isset($orderbump_outgoingStock->product) && $orderbumpProduct_revenue !== 0)
                            <li class="col-lg-4 col-md-6"> 
                                <div class="itemside mb-3"> 
                                    <div class="aside"> 
                                        <img width="72" height="72" src="{{ asset('/storage/products/'.$orderbump_outgoingStock->product->image) }}" class="img-sm rounded border"> 
                                    </div> 
                                    <div class="info text-start"> 
                                        <p class="title">{{ $orderbump_outgoingStock->product->name }}</p> 
                                        <strong>N{{ $orderbump_outgoingStock->product->sale_price * $orderbump_outgoingStock->quantity_removed }} ({{ $orderbump_outgoingStock->quantity_removed }} item)</strong> 
                                    </div> 
                                </div> 
                            </li>
                            @endif
                            
                            @if (isset($upsell_outgoingStock->product) && $upsellProduct_revenue !== 0)
                            <li class="col-lg-4 col-md-6"> 
                                <div class="itemside mb-3"> 
                                    <div class="aside"> 
                                        <img width="72" height="72" src="{{ asset('/storage/products/'.$upsell_outgoingStock->product->image) }}" class="img-sm rounded border"> 
                                    </div> 
                                    <div class="info text-start"> 
                                        <p class="title">{{ $upsell_outgoingStock->product->name }}</p> 
                                        <strong>N{{ $upsell_outgoingStock->product->sale_price * $upsell_outgoingStock->quantity_removed }} ({{ $upsell_outgoingStock->quantity_removed }} item)</strong> 
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
                <!--/ THANKYOU VIEW -->
</main>

<!-- <hr> will be shown in singlelink-->
<footer class="container-fluid position-relative bg-dark py-5 text-white bottom-0 d-none" style="position: relative; bottom: 0;">
    <div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <div>© <span class="copyright-date">2023</span> <span class="project-name">KipTrak</span>. All rights reserved. </div>
        </div>
    </div>
    </div>
</footer>

<!-- Vendor JS Files -->
<script src="{{asset('/customerform/assets/js/html2pdf.bundle.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script src="{{asset('/customerform/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<!-- submit main form -->
    
    <svg id="SvgjsSvg1001" width="2" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" style="overflow: hidden; top: -100%; left: -100%; position: absolute; opacity: 0;">
        <defs id="SvgjsDefs1002"></defs>
        <polyline id="SvgjsPolyline1003" points="0,0"></polyline>
        <path id="SvgjsPath1004" d="M0 0 "></path>
    </svg>
</body>

<script>
    function generatePDF() {
        const element = document.getElementById('invoice');

        html2pdf()
            .from(element)
            .save(); 
    }
</script>

</html>