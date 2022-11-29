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

    <nav class="navbar bg-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
            <img src="{{asset('/customerform/assets/img/logo.png')}}" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
            <span class="project-name"></span>
            </a>
        </div>
    </nav>

    <main class="container mb-5 py-5 min-vh-100">
        
        <ul class="steps-wrap mx-auto" style="max-width: 600px">
            <li class="step" id="products-step"> 
                <span class="icon">1</span>
                <span class="text">Accept your Orders</span>
            </li> <!-- step.// --> 
            <li class="step" id="order-dump-step">
                <span class="icon">2</span>
                <span class="text">Products you might like</span>
            </li> <!-- step.// --> 
            <li class="step" id="upsell-step">
                <span class="icon">3</span>
                <span class="text">Get Discounted Offers</span>
            </li> <!-- step.// --> 
            <li class="step" id="checkout-step">
                <span class="icon">4</span>
                <span class="text">Checkout</span>
            </li> <!-- step.// --> 
            <li class="step" id="thank-you-step">
                <span class="icon">5</span>
                <span class="text">Complete</span>
            </li> <!-- step.// --> 
        </ul> <!-- tracking-wrap.// --> 

        <!-- PRODUCT VIEW -->
        <div class="view" id="products">
            <div class="row">
                <div class="col-md-12 py-3 text-center">
                    <div class="display-6">{{ $orderLabel->order_heading }}</div>
                    <div class="lead">{{ $orderLabel->order_subheading }}</div>
                </div>
            </div>

            <hr>
            <div class="row d-flex justify-content-center product-list" id="product-list">
                <?php $mainProduct_revenue = 0; ?>
                @if (count($mainProducts_outgoingStocks) == 1)
                    @foreach ($mainProducts_outgoingStocks as $main_outgoingStock)
                    <!---change col-lg-6 accordingly-->
                    <div class="col-lg-6 col-sm-6 col-12 each_product"> 
                        <div class="card card-product-grid">
                            <div class="img-wrap">  
                                <img src="{{ asset('/storage/products/'.$main_outgoingStock->product->image) }}">
                            </div> 
                            <div class="info-wrap text-center border-top"> 
                                <a href="#" class="title">{{ $main_outgoingStock->product->name }}</a> 
                                <strong class="price">Starting from N{{ $main_outgoingStock->product->price }}</strong>
                                <hr>
                                <div class="text-center add_to_order">
                                    <button class="btn btn-primary btn-lg text-center w-100"><i class="me-2 fa fa-shopping-basket"></i>Add to Order</button>
                                </div>
                                <div class="text-center added_successfully" style="display: none;">
                                    <button class="btn btn-info btn-lg text-center text-white w-100"><i class="me-2 bi bi-check-circle-fill text-success"></i>Added Successfully...</button>
                                </div> 
                                <hr>
                                
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                    (<span class="product_quantity">{{ $main_outgoingStock->quantity_removed }}</span>
                                    pieces - <span class="product_amount">
                                        <!---unitprice * qty_removed---->
                                        <span class="currency"></span>{{ $main_outgoingStock->product->price * $main_outgoingStock->quantity_removed }}</span>) Selected
                            </div> 
                        </div> 
                    </div>
                    <!---accumulated incr--->
                    <?php $mainProduct_revenue = $mainProduct_revenue + ($main_outgoingStock->product->price * $main_outgoingStock->quantity_removed); ?>
                    @endforeach
                    <input type="hidden" name="mainProduct_revenue" class="mainProduct_revenue" value="{{ $mainProduct_revenue }}">
                @endif
                

            </div>
        </div>
        
        <!-- ORDER DUMP VIEW -->
        
        <!-- UPSELL VIEW -->
        
        <!-- CHECKOUT VIEW -->
        <div class="row view" id="checkout" style="display: none;">
            <div class="col-md-12">

                <article class="card">
                    <div class="card-body">
                        <h5 class="card-title">Contact info</h5>
                        <form name="contactform">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">{{ $orderLabel->customer_firstname_label }}</label>
                                    <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Type here" required
                                    value="loremfirst">
                                </div> <!-- col end.// -->

                                <div class="col-6">
                                    <label class="form-label">{{ $orderLabel->customer_lastname_label }}</label>
                                    <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Type here" required
                                    value="loremlast">
                                </div> <!-- col end.// -->

                                @if (isset( $orderLabel->customer_phone_label ))
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">{{ $orderLabel->customer_phone_label }}</label>
                                        <input type="tel" name="phone" id="phone" class="form-control" placeholder=""
                                        value="09876534567">
                                    </div>
                                @endif

                                @if (isset( $orderLabel->customer_email_label ))
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">{{ $orderLabel->customer_email_label }}</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="example@gmail.com"
                                        value="lorem@lorem.com">
                                    </div>
                                @endif

                                @if (isset( $orderLabel->customer_city_label ))
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">{{ $orderLabel->customer_city_label }}</label>
                                        <input type="text" name="city" id="city" class="form-control" placeholder="Type here"
                                        value="loremcity">
                                    </div>
                                @endif

                                @if (isset( $orderLabel->customer_state_label ))
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">{{ $orderLabel->customer_state_label }}</label>
                                        <input type="text" name="state" id="state" class="form-control" placeholder="Type here"
                                        value="loremstate">
                                    </div>
                                @endif

                                @if (isset( $orderLabel->customer_country_label ))
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">{{ $orderLabel->customer_country_label }}</label>
                                        <select name="country" class="form-control" id="country">
                                            <option value="1">Nigeria</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if (isset( $orderLabel->customer_address_label ))
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">{{ $orderLabel->customer_address_label }}</label>
                                        <input type="text" name="address" id="address" class="form-control" placeholder="Type here"
                                        value="loremaddress">
                                    </div>
                                @endif
                                <!-- col end.// -->
                            </div> <!-- row.// -->
                        </form> 
                        
                        <hr class="my-4">
                         <!-- row.// --> 
                    </div> <!-- card-body end.// -->
                </article>

            </div>
        </div>
        
        <!-- THANKYOU VIEW -->
        <div class="view" id="thank-you" style="display: none;">
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
                <div class="col-lg-12">
                    <article class="card shadow-sm mb-3">
                        <div class="card-body">
                            <header class="d-md-flex">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"> Order ID: {{ $orderId }} <i class="dot"></i><span class="text-danger"> Pending </span> </h6> 
                                    <span>Date: <span class="order_updated_date"></span></span>
                                </div> 
                                <div> 
                                    <!-- <a href="#" class="btn btn-sm btn-outline-danger">Cancel order</a> -->
                                    <a href="#" class="btn btn-sm btn-success"><i class="bi bi-download text-white"></i> Download Invoice</a> 
                                </div> 
                            </header> 
                            <hr> 
                            <div class="row"> 
                                <div class="col-md-4"> 
                                    <p class="fw-bold mb-0 text-success">Contact</p> 
                                    <hr>
                                    <p class="m-0"> 
                                        <span class="customer_name"></span> 
                                        <br> Phone: <span class="customer_phone"></span> 
                                        <br> Email: <span class="customer_email"></span> 
                                    </p>
                                </div> <!-- col.// --> 
                                <div class="col-md-4 border-start"> 
                                    <p class="fw-bold mb-0 text-success">Shipping address</p> 
                                    <hr>
                                    <p class="m-0"> <span class="customer_country"></span> 
                                        <br> <span class="customer_address"></span> 
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
                                <dt class="fw-bolder">No. of Items:</dt> <dd><span class="no_of_items"></span></dd>
                            </dl>
                            <dl class="dlist-align">
                                <dt class="fw-bolder">Order Amount:</dt> <dd>N<span class="order_amount"></span></dd> 
                            </dl> 
                            <dl class="dlist-align">
                                <dt class="fw-bolder">Discount:</dt> <dd>N0.00</dd> 
                            </dl> 
                            <dl class="dlist-align">
                                <dt class="fw-bolder">Grand Total:</dt> <dd>N<span class="grand_total"></span></dd> 
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
                                {{-- <li class="col-lg-4 col-md-6"> 
                                    <div class="itemside mb-3"> 
                                        <div class="aside"> 
                                            <img width="72" height="72" src="{{ asset('/storage/products/1.jpg') }}" class="img-sm rounded border"> 
                                        </div> 
                                        <div class="info"> 
                                            <p class="title">Gaming Headset with mic</p> 
                                            <strong>N5,537.00 (1 item)</strong> 
                                        </div> 
                                    </div> 
                                </li>  --}}
                                

                            </ul> 
                        </div> <!-- card-body .// --> 
                    </article> <!-- card .// -->                 
                </div> 
                <!--/order-summary-->
            </div>
             
        </div>

        <div class="card shadow-sm bg-light mt-4" id="cart-summary">
            <div class="card-body text-center">
                <div class="display-6">
                    Total: <span class="currency"></span>
                    <span class="total revenue_subtotal">
                        {{ $mainProduct_revenue }}
                    </span>
                </div>
                <div class="lead">Items in Cart: <span class="fw-bold" id="itemsInCart">{{ count($mainProducts_outgoingStocks) }}</span> </div>
            </div>
        </div>

        <input type="hidden" name="grand_total" class="grand_total" value="">
        <input type="hidden" name="unique_key" class="unique_key" value="{{ $unique_key }}">

        <div class="row pt-3 mt-3 border-top" id="navigation">
            <div class="col-md-8 mx-auto text-center d-flex justify-content-between">
                <div id="prevBtnDiv" style="display: none;"><button class="btn btn-outline-medium btn-lg d-flex" id="prevBtn"><i class="bi bi-arrow-left me-2"></i> Previous</button></div>
                <div id="nextBtnDiv"><button class="btn btn-primary btn-lg d-flex" id="nextBtn">Next <i class="bi bi-arrow-right"></i></button></div>
                <div id="completeBtnDiv" class="completeBtnDiv" style="display: none;"><button class="btn btn-success btn-lg completeBtn" id="completeBtn"><i class="bi bi-check-circle-fill"></i> Complete Order</button></div>
            </div>
        </div>
    </main>

    <!-- <hr> -->
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
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
        <!-- Template Main JS File -->
        <script src="{{asset('/customerform/assets/js/main.js?v=42')}}"></script>
        <script src="{{asset('/customerform/assets/js/navigation.js?v=4')}}"></script>

<script>
// $('#myButton1').click(function(){
//       $('#myButton1').hide();
//       $('.load').addClass('loading');
//         setTimeout(function () { 
//         $('.load').removeClass('loading');
//         $('#myButton1').show();

//       }, 2000);
//     });
$(".add_to_order").click(function(){
    var mainProduct_revenue = $('.mainProduct_revenue').val();
    $('.grand_total').val(mainProduct_revenue);
    $(this).hide();
    $('.added_successfully').show();
        setTimeout(function () { 
            $('.added_successfully').hide();
            $('.add_to_order').show();

        }, 2000);
    $('#nextBtnDiv').css({'display':'block'});
    

});

//orderbump_add_to_order click

//upsell_add_to_order click

//enable confirm button

</script>        

<script>
    
  // To style all selects
  $(function () {
    let orderData;
    var products;
    // $.post('json.php',(res)=>{
    //     // products = res;
    //     // alert(JSON.stringify(res))
    // }).fail(()=>{
    //     alert('Error fetching data');
    // })
    /** VIEWS */
    const productView = document.getElementById('products');
    // const orderDumpView = document.getElementById('order-dump');
    // const upSellView = document.getElementById('upsell');
    const checkoutView = document.getElementById('checkout');
    const thankYouView = document.getElementById('thank-you');

    /** LIST CONTAINERS */
    const productList = document.getElementById('product-list');

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
    setView('products');

    /** Next Button Action */
    const nextStep = () => {
        document.getElementById('nextBtn').addEventListener('click',()=>{            
            switch (currentView) {
                case 'products':
                    setView('checkout');
                    setActionButton();
                    break;
                case 'checkout':
                    setView('thank-you');
                    setActionButton();
                    break;
            
                default:
                    setView('products');
                    setActionButton();
                    break;
            }
        });
    }
    nextStep(); //initiliase next action

    
    /** Previous Button Action */
    const prevStep = () => {
        document.getElementById('prevBtn').addEventListener('click',()=>{            
            switch (currentView) {
                case 'checkout':
                    setView('products');
                    setActionButton();
                    break;
                default:
                    setView('products');
                    setActionButton();
                    break;
            }
        });
        // setActionButton();
    }
    prevStep(); //initiliase next action
    
    
    /** Previous Button Action */
    const completeStep = () => {
        document.getElementById('completeBtn').addEventListener('click',()=>{ 
            
            var firstname = document.forms["contactform"]["firstname"].value;
            var lastname = document.forms["contactform"]["lastname"].value;
            var phone = document.forms["contactform"]["phone"].value;
            var email = document.forms["contactform"]["email"].value;
            var city = document.forms["contactform"]["city"].value;
            var state = document.forms["contactform"]["state"].value;
            var country = document.forms["contactform"]["country"].value;
            var address = document.forms["contactform"]["address"].value;
            if (firstname == "" || firstname == null) {
                alert("First name must be filled");
                return false;
            }   
            if (lastname == "" || lastname == null) {
                alert("Last name must be filled");
                return false;
            }
            if (phone == "" || phone == null) {
                alert("Phone number must be filled");
                return false;
            }
            if (email == "" || email == null) {
                alert("Email address must be filled");
                return false;
            }
            if (city == "" || city == null) {
                alert("City or Town must be filled");
                return false;
            }
            if (state == "" || state == null) {
                alert("Your State must be filled");
                return false;
            }
            if (country == "" || country == null) {
                alert("Your Country must be selected");
                return false;
            }
            if (address == "" || address == null) {
                alert("Your Address must be filled");
                return false;
            }

            //grab all total
            var grand_total = $('.grand_total').val();
            var unique_key = $('.unique_key').val(); //order unique_key

            // var orderbump_revenue = $('.orderbump_revenue_store').val();
            // var upsell_revenue = $('.upsell_revenue_store').val();
            
            $.ajax({
                type:'get',
                url:'/product-only-customer-order',
                data:{grand_total:grand_total, unique_key:unique_key, firstname:firstname, lastname:lastname, phone:phone,
                    email:email, city:city, state:state, country:country, address:address
                    },
                success:function(resp){
                    console.log(resp.data.order_updated_date)
                    setView('thank-you');
                    setActionButton();

                    $('.order_updated_date').text(resp.data.order_updated_date);

                    var customer_name = resp.data.firstname+' '+resp.data.lastname;
                    $('.customer_name').text(customer_name);
                    $('.customer_phone').text(resp.data.phone);
                    $('.customer_email').text(resp.data.email);
                    $('.customer_country').text(resp.data.selected_country);
                    $('.customer_address').text(resp.data.address);

                    $('.no_of_items').text(resp.data.qty_total);
                    $('.order_amount').text(resp.data.grand_total);
                    $('.grand_total').text(resp.data.grand_total);
                    

                },error:function(){
                    alert("Error");
                }
            });
      
            
        });
        // setActionButton();
    }
    completeStep(); //initiliase next action
    

    const setSteps = () => {
        // alert(currentView)
        const steps = document.querySelectorAll('.step');
        for (let i = 0; i < steps.length; i++) {
            const step = steps[i];
            // step.classList.remove('active');
        }

        document.getElementById(currentView+'-step').classList.add('active');
    }
    // setSteps();

    const setActionButton = () => {
        if(currentView=='products'){
            document.querySelector('#nextBtnDiv').style.display = 'none';
            document.querySelector('#prevBtnDiv').style.display = 'none';
            document.querySelector('#completeBtnDiv').style.display = 'none';
            document.querySelector('#nextBtnDiv').classList.add('mx-auto');
            document.querySelector('#completeBtnDiv').classList.remove('mx-auto');
            
            // toggle navigation and cart summary sections
            document.querySelector('#cart-summary').style.display = 'block';
            document.querySelector('#navigation').style.display = 'block';
        }
        else if(currentView=='checkout'){
            document.querySelector('#nextBtnDiv').style.display = 'none';
            document.querySelector('#prevBtnDiv').style.display = 'none';
            document.querySelector('#completeBtnDiv').style.display = 'block';
            document.querySelector('#completeBtnDiv').classList.add('mx-auto');
            
            // toggle navigation and cart summary sections
            document.querySelector('#cart-summary').style.display = 'block';
            document.querySelector('#navigation').style.display = 'block';
        }        
        else if(currentView=='thank-you'){
            document.querySelector('#nextBtnDiv').style.display = 'none';
            document.querySelector('#prevBtnDiv').style.display = 'none';
            document.querySelector('#completeBtnDiv').style.display = 'none';
            document.querySelector('#nextBtnDiv').classList.add('mx-auto');
            
            // toggle navigation and cart summary sections
            document.querySelector('#cart-summary').style.display = 'none';
            document.querySelector('#navigation').style.display = 'none';
        } else {
            document.querySelector('#nextBtnDiv').style.display = 'block';
            document.querySelector('#prevBtnDiv').style.display = 'block';
            document.querySelector('#completeBtnDiv').style.display = 'none';
            document.querySelector('#nextBtnDiv').classList.remove('mx-auto');
            document.querySelector('#completeBtnDiv').classList.remove('mx-auto');

            // toggle navigation and cart summary sections
            document.querySelector('#cart-summary').style.display = 'block';
            document.querySelector('#navigation').style.display = 'block';
        }

        // document.getElementById('current').innerHTML = currentView;
        setSteps();
    }
    setActionButton();

    const qtyAdjuster = () => {
        const btns = document.getElementsByTagName('button');
        const input = document.getElementsByTagName('input');

        for (let i = 0; i < btns.length; i++) {
            const btn = btns[i];
        }        

    }
    qtyAdjuster();
  });


  $(function(){
    $(".each_product").each(function(index, element) {
        $(element).on('click', '.plus', function() {
            // input = $(this).parent().prev('.qty'); 
            input = $(this).prev('.qty');
            qty = input.val();
            
            product_unitprice = input.attr("data-unitprice");
            amtByQty = product_unitprice;
            
            // //holding spans
            product_quantity = $(this).next('product_quantity');
            alert(product_unitprice)
            product_amount = $(this).next('.product_amount');
            
        })
    });
  })

  $(document).on('click', '.minus', function() {
    input = $(this).next('.qty');
    qty = input.val();
    product_unitprice = input.attr("data-unitprice");
    amtByQty = product_unitprice;

    //holding spans
    product_quantity = $('.product_quantity');
    product_amount = $('.product_amount');
    
    if (qty > 1) {
        qty = parseFloat(qty) - 1;
    } else {
        qty = 1;
    }
    input.val(qty);
    product_quantity.text(qty);

    amtByQty = qty * product_unitprice;
    product_amount.text(amtByQty);
  });
</script>
    </body>

</html>