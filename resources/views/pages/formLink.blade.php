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
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css')}}"> -->
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"
            />
        <link href="{{asset('/customerform/assets/vendor/boxicons/css/boxicons.min.css')}}"
            rel="stylesheet">
        <link href="{{asset('/customerform/assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
        <link href="{{asset('/customerform/assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
        <link href="{{asset('/customerform/assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
        <link href="{{asset('/customerform/assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">
    
        <!-- Font awesome 5 -->
        <link rel="preload" href="{{asset('/customerform/assets/vendor/font-awesome/webfonts/fa-solid-900.woff2')}}" as="font" type="font/woff" crossorigin>
        <link href="{{asset('/customerform/assets/vendor/font-awesome/css/all.min.css')}}" type="text/css" rel="stylesheet">

        
        <!-- Template Main CSS File -->
        <link href="{{asset('/customerform/assets/css/ui.css')}}" rel="stylesheet">
        <link href="{{asset('/customerform/assets/css/form-style.css')}}" rel="stylesheet">

        
    </head>

    <body class="">

        <!-- will be shown in singlelink-->
    <nav class="navbar bg-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
            <img src="{{asset('/customerform/assets/img/logo.png')}}" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
            <span class="project-name"></span>
            </a>
        </div>
    </nav>

    <main class="container mb-5 py-5 min-vh-100">
        
        @if(Session::has('success'))
        <div class="alert alert-success mb-3 text-center">
            {{Session::get('success')}}
        </div>
        @endif

        @if(Session::has('info'))
        <div class="alert alert-success mb-3 text-center">
            {{Session::get('info')}}
        </div>
        @endif
        
        <!-- CHECKOUT VIEW Main + orderbump + upsell -->
        {{-- <div class="@if( (!empty(Session::get('thankyou_stage'))) || (!empty(Session::get('upsell_stage'))) ) d-none @endif"> --}}
            {{-- <div class="row view @if((Session::has('order-success')) || (Session::has('thankyou_stage'))) d-none @else d-block @endif" id="checkout"> --}}
            <div class="row view @if( (!empty(Session::get('thankyou_stage'))) || (!empty(Session::get('upsell_stage'))) ) d-none @endif" id="checkout">
                <div class="col-md-12">

                    <article class="card">
                        <div class="card-body">
                            <h5 class="card-title">Contact info</h5>
                            <form action="{{ route('formLinkPost', $unique_key) }}" method="POST">@csrf
                                <div class="row">

                                    @foreach ($formContact as $contact)
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">{{ $contact['label'] }}</label>
                                        <input type="{{ $contact['type'] }}" name="{{ $contact['name'] }}" class="form-control {{ $contact['name'] }} @error($contact['name']) is-invalid @enderror"
                                        placeholder="Type here">
                                        <!--if such error-->
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
                                        <label for="package{{$key}}" class="form-label btn btn-outline border d-flex align-items-center me-3
                                        @error($item['name']) is-invalid @enderror">
                                            <input type="{{ $item['type'] }}" name="package[]" id="package{{$key}}"
                                            class="contact-check me-3" value="{{ $item['value'] }}"/>
                                            <span class="me-1 fw-bold">{{ $item['label'] }} = {{ $item['product_price'] }} naira</span>
                                        </label>
                                        @error($item['name'])
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div> <!-- col end.// -->
                                    @endforeach

                                    <!---for checking upsell in FormBuilderController--->
                                    <input type="hidden" name="upsell_available"
                                    value="{{ isset($formHolder->orderbump_id) ? $formHolder->orderbump_id : '' }}">

                                    <!--orderbump-->
                                    @if (isset($formHolder->orderbump_id))
                                    <div class="col-12 mb-3">
                                        <div class="d-flex justify-content-center">
                                            <div class="content text-center p-3" style="border: 3px dashed black; background-color: #D2FFE8;">
                                                <h3 class="heading">Would You Like To Add To Your Order:</h3>
                                                <h4 class="subheading" style="color: #012970;">Brand new, Amazing, 100% Genuine etc</h4>
                                                <p class="product-feature">Melts Away Fats In 2 Days!</p>

                                                <div class="orderbump-product-image mb-3">
                                                    <img src="{{ asset('/storage/products/'.$formHolder->orderbump->product->image) }}" width="100" class="img-thumbnail img-fluid"
                                                    alt="{{$formHolder->orderbump->product->name}}">
                                                </div>

                                                <p class="discount-info">
                                                    Kindly click the box below to add this to your order now for just xxxx instead of paying normal price of yyyy!
                                                </p>

                                                <p class="more-info">
                                                    This offer is not available at ANY other time or place
                                                </p>

                                                <div class="make-your-choice text-center">

                                                    <div class="call-to-action d-flex justify-content-center align-items-center">
                                                        <img src="{{asset('/customerform/assets/img/blinking-arrow.gif')}}" class="w-10 me-2" alt="Logo">
                                                        <label for="orderbump_check" class="form-label d-flex align-items-center">
                                                            <input type="checkbox" name="orderbump_check" id="orderbump_check" class="cta-check me-1"
                                                            @error('product') checked @enderror value="true"/>
                                                            <span class="fw-bold" style="color: #012970;">Yes, I will Take It</span>
                                                        </label>
                                                    </div>

                                                </div>
                                                

                                                <div class="col-12 mb-3 select-product" @error('product') style="display:block" @else style="display:none" @enderror>
                                                    <p class="form-label">Select Product Package</p>
                                                    <label for="product" class="form-label btn btn-outline border d-flex align-items-center me-3
                                                    @error('product') is-invalid @enderror">
                                                        <input type="checkbox" name="product" id="product"
                                                        class="contact-check me-3" value="{{ $formHolder->orderbump->product->id }}"/>
                                                        <span class="me-1 fw-bold">{{ $formHolder->orderbump->product->name }} =
                                                            {{ $formHolder->orderbump->product->country->symbol }}{{ $formHolder->orderbump->product->price }}</span>
                                                    </label>
                                                    @error('product')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                
                                                    
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="col-12">
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn w-50 p-2 text-white" style="background-color: #012970">Submit Order</button>
                                        </div>
                                    </div>
                                        
                                </div> <!-- row.// --> 
                                
                            </form>
                        </div> <!-- card-body end.// -->
                    </article>

                </div>
                
            </div>

            <!---upsell view--->
            @if (isset($formHolder->upsell_id))
                <div class="row view @if(!empty(Session::get('upsell_stage'))) d-block @else d-none @endif" id="checkout">
                
                    <div class="col-md-12">
        
                        <article class="card">
                            <div class="card-body">
                                {{-- <h5 class="card-title">Contact info</h5> --}}
                                <form action="{{ route('formLinkUpsellPost', $unique_key) }}" method="POST">@csrf
                                    <div class="row">
        
                                        <!--upsell-->
                                        <div class="col-12 mb-3">
                                            <div class="d-flex justify-content-center">
                                                <div class="content text-center p-3">
                                                    <h3 class="heading">Wait! Your Order Is Almost Complete...</h3>
                                                    <h4 class="subheading" style="color: #012970;">Check Out This Amazing Offer Below</h4>
                                                    
                                                    <div class="upsell-product-image mb-3">
                                                        <img src="{{ asset('/storage/products/'.$formHolder->upsell->product->image) }}" width="100" class="img-thumbnail img-fluid"
                                                        alt="{{$formHolder->upsell->product->name}}">
                                                    </div>
        
                                                    <div class="select-upsell-product text-center">
                                                        <p class="form-label">Select Product Package</p>
                                                        <div class="call-to-action d-flex justify-content-center align-items-center">
                                                            <label for="" class="form-label d-flex align-items-center">
                                                                <input type="checkbox" name="upsell_product" class="me-1" value="{{ $formHolder->upsell->product->id }}">
                                                                <span>{{ $formHolder->upsell->product->name }} =
                                                                    {{ $formHolder->upsell->product->country->symbol }}{{ $formHolder->upsell->product->price }}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    
                                                        
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn w-50 p-2 text-white" style="background-color: #012970;">ADD TO MY ORDER</button>
                                            </div>
                                        </div>
                                            
                                    </div> <!-- row.// --> 
                                    
                                </form>
                            </div> <!-- card-body end.// -->
                        </article>
        
                    </div>
                    
                </div>
            @endif
        {{-- </div> --}}
        
        <!-- THANKYOU VIEW -->
        <div class="view @if(!empty(Session::get('thankyou_stage'))) d-block @else d-none @endif" id="thank-you">
            <div class="row">
                <div class="col-lg-12 mb-3">
                    <article class="card shadow-sm">
                        <div class="card-body"> 
                            <div class="mt-4 mx-auto text-center" style="max-width:600px">
                                <svg width="96px" height="96px" viewBox="0 0 96 96" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="round-check"> <circle id="Oval" fill="#D3FFD9" cx="48" cy="48" r="48"></circle> <circle id="Oval-Copy" fill="#87FF96" cx="48" cy="48" r="36"></circle> <polyline id="Line" stroke="#04B800" stroke-width="4" stroke-linecap="round" points="34.188562 49.6867496 44 59.3734993 63.1968462 40.3594229"></polyline> </g> </g> </svg> 
                                <div class="my-3"> 
                                    <h4>Thank you for order</h4> 
                                    <p>We have received your order confirmation. One of our agents will contact you shortly.</p> 
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
                                    <h6 class="mb-0"> Order ID: {{ $orderId }} <i class="dot"></i><span class="text-danger"> Pending </span> </h6> 
                                    <span>Date: <span class="order_updated_date">{{ $order->updated_at->format('D, jS M Y') }}</span></span>
                                </div> 
                                <div> 
                                    <!-- <a href="#" class="btn btn-sm btn-outline-danger">Cancel order</a> -->
                                    <a href="#" id="generate-pdf" class="btn btn-sm btn-success"><i class="bi bi-download text-white"></i> Download Invoice</a> 
                                </div> 
                            </header> 
                            <hr> 
                            <div class="row"> 
                                <div class="col-md-4"> 
                                    <p class="fw-bold mb-0 text-success">Contact</p> 
                                    <hr>
                                    <p class="m-0"> 
                                        <span class="customer_name"></span> 
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

                                @foreach ($mainProducts_outgoingStocks as $main_outgoingStock)
                                <li class="col-lg-4 col-md-6"> 
                                    <div class="itemside mb-3"> 
                                        <div class="aside"> 
                                            <img width="72" height="72" src="{{ asset('/storage/products/'.$main_outgoingStock->product->image) }}" class="img-sm rounded border">
                                        </div> 
                                        <div class="info"> 
                                            <p class="title">{{ $main_outgoingStock->product->name }}</p> 
                                            <strong>N{{ $mainProduct_revenue }} ({{ $main_outgoingStock->quantity_removed }} items)</strong> 
                                        </div> 
                                    </div> 
                                </li>
                                @endforeach
                                
                                <!---for orderbump or upsell--->
                                <li class="col-lg-4 col-md-6"> 
                                    <div class="itemside mb-3"> 
                                        <div class="aside"> 
                                            <img width="72" height="72" src="{{ asset('/storage/products/'.$orderbump_outgoingStock->product->image) }}" class="img-sm rounded border"> 
                                        </div> 
                                        <div class="info"> 
                                            <p class="title">{{ $orderbump_outgoingStock->product->name }}</p> 
                                            <strong>N{{ $orderbump_outgoingStock->product->price * $orderbump_outgoingStock->quantity_removed }} ({{ $orderbump_outgoingStock->quantity_removed }} item)</strong> 
                                        </div> 
                                    </div> 
                                </li>
                                
                                <li class="col-lg-4 col-md-6"> 
                                    <div class="itemside mb-3"> 
                                        <div class="aside"> 
                                            <img width="72" height="72" src="{{ asset('/storage/products/'.$upsell_outgoingStock->product->image) }}" class="img-sm rounded border"> 
                                        </div> 
                                        <div class="info"> 
                                            <p class="title">{{ $upsell_outgoingStock->product->name }}</p> 
                                            <strong>N{{ $upsell_outgoingStock->product->price * $upsell_outgoingStock->quantity_removed }} ({{ $upsell_outgoingStock->quantity_removed }} item)</strong> 
                                        </div> 
                                    </div> 
                                </li> 
                                

                            </ul> 
                        </div> <!-- card-body .// --> 
                    </article> <!-- card .// -->                 
                </div>

                @endif
                
                
                <!--/order-summary-->
            </div>
             
        </div>
        <!--/ THANKYOU VIEW -->
        <div id="pdf-content" style="display: none"></div>
        <div id="pdf-renderer"></div>
        
    </main>

    <!-- <hr> will be shown in singlelink-->
    <footer class="container-fluid position-relative bg-dark py-5 text-white bottom-0" style="position: relative; bottom: 0;">
        <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div>&copy; <span class="copyright-date"></span> <span class="project-name"></span>. All rights reserved. </div>
            </div>
        </div>
        </div>
    </footer>

        <!-- Vendor JS Files -->
        <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js')}}"></script> -->
        <script src="{{asset('/customerform/assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
        <script src="{{asset('/customerform/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('/customerform/assets/vendor/chart.js/chart.min.js')}}"></script>
        <script src="{{asset('/customerform/assets/vendor/echarts/echarts.min.js')}}"></script>
        <script src="{{asset('/customerform/assets/vendor/quill/quill.min.js')}}"></script>
        <script src="{{asset('/customerform/assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
        <script src="{{asset('/customerform/assets/vendor/tinymce/tinymce.min.js')}}"></script>
        <script src="{{asset('/customerform/assets/vendor/php-email-form/validate.js')}}"></script>


        <!-- Latest compiled and minified JavaScript -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js')}}"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
        <!-- Template Main JS File -->
        <script src="{{asset('/customerform/assets/js/main.js?v=42')}}"></script>
        <script src="{{asset('/customerform/assets/js/navigation.js?v=4')}}"></script>

        <script>
            $(".cta-check").click(function(){
                if($(this).is(':checked')){
                    $(".select-product").show();
                } else {
                    $(".select-product").hide();
                }
                
            });

            
        </script>

        <script>
            $(document).on("input", ".phone-number", function() {
                this.value = this.value.replace(/\D/g,'');
            });
        </script>

        <script>
            $(document).on("input", ".whatsapp-phone-number", function() {
                this.value = this.value.replace(/\D/g,'');
            });
        </script>

        <script>
            var doc = new jsPDF();
            var specialElementHandlers = {
                '#pdf-renderer': function (element, renderer) {
                    return true;
                }
            };

            $('#generate-pdf').click(function () {   
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