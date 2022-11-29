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
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css')}}"
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
                    <div class="display-6">Choose your products</div>
                    <div class="lead">Click each product to select a package</div>
                </div>
            </div>

            <hr>
            <div class="row d-flex justify-content-center product-list" id="product-list">
                
                <div class="col-lg-3 col-sm-6 col-12 each_product"> 
                    <div class="card card-product-grid">
                        <div class="img-wrap">  
                            <img src="{{asset('/customerform/assets/img/product-3.jpg')}}">
                        </div> 
                        <div class="info-wrap text-center border-top"> 
                            <a href="#" class="title">Green Tea Face Serum 1</a> 
                            <strong class="price">Starting from N2000</strong>
                            <hr>
                            <div class="text-center">
                                <div class="input-group input-spinner">
                                    <button data-type="minus" data-id="1" class="btn btn-light minus" type="button"> 
                                        <img src="{{asset('/customerform/assets/img/minus.svg')}}" alt="-">
                                    </button> 
                                    <input type="text" data-type="input" data-id="1" data-unitprice="2000" class="form-control qty" value="1"> 
                                        <button type="button" data-type="add" class="btn btn-light plus" type="button"> 
                                        <img src="{{asset('/customerform/assets/img/add.svg')}}" alt="+">
                                    </span> 
                                </div> 
                            </div> 
                            <hr>
                            
                                <i class="bi bi-check-circle-fill text-success"></i>
                                (<span class="product_quantity">1</span> pieces - N<span class="product_amount">2000</span>) Selected
                        </div> 
                    </div> 
                </div>

                <div class="col-lg-3 col-sm-6 col-12 each_product"> 
                    <div class="card card-product-grid">
                        <div class="img-wrap">  
                            <img src="{{asset('/customerform/assets/img/product-3.jpg')}}">
                        </div> 
                        <div class="info-wrap text-center border-top"> 
                            <a href="#" class="title">Green Tea Face Serum</a> 
                            <strong class="price">Starting from N2000</strong>
                            <hr>
                            <div class="text-center">
                                <div class="input-group input-spinner">
                                    <button data-type="minus" data-id="1" class="btn btn-light minus" type="button"> 
                                    <img src="{{asset('/customerform/assets/img/minus.svg')}}" alt="-">
                                    </button> 
                                    <input type="text" data-type="input" data-id="1" data-unitprice="3000" class="form-control qty" value="2"> 
                                    {{-- <span class="plus-wrapper"> --}}
                                        <button type="button" data-type="add" class="btn btn-light plus" type="button"> 
                                            <img src="{{asset('/customerform/assets/img/add.svg')}}" alt="+">
                                        {{-- </button>     --}}
                                    </span> 
                                </div> 
                            </div> 
                            <hr>
                            <div class="text-success">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                (<span class="product_quantity">1</span> pieces - N<span class="product_amount">3000</span>) Selected
                            </div>
                        </div> 
                    </div> 
                </div>

                <div class="col-lg-3 col-sm-6 col-12 each_product"> 
                    <div class="card card-product-grid">
                        <div class="img-wrap">  
                            <img src="{{asset('/customerform/assets/img/product-3.jpg')}}">
                        </div> 
                        <div class="info-wrap text-center border-top"> 
                            <a href="#" class="title">Green Tea Face Serum</a> 
                            <strong class="price">Starting from N2000</strong>
                            <hr>
                            <div class="text-center">
                                <div class="input-group input-spinner">
                                    <button data-type="minus" data-id="1" class="btn btn-light minus" type="button"> 
                                    <img src="{{asset('/customerform/assets/img/minus.svg')}}" alt="-">
                                    </button> 
                                    <input type="text" data-type="input" data-id="1" class="form-control qty" value="1"> 
                                    <button data-type="add" class="btn btn-light plus" type="button"> 
                                        <img src="{{asset('/customerform/assets/img/add.svg')}}" alt="+">
                                    </button> 
                                </div> 
                            </div> 
                            <hr>
                            <div class="text-success"><i class="bi bi-check-circle-fill text-success"></i> (10 pieces - N15,000) Selected</div>
                        </div> 
                    </div> 
                </div>

                <div class="col-lg-3 col-sm-6 col-12 each_product"> 
                    <div class="card card-product-grid">
                        <div class="img-wrap">  
                            <img src="{{asset('/customerform/assets/img/product-3.jpg')}}">
                        </div> 
                        <div class="info-wrap text-center border-top"> 
                            <a href="#" class="title">Green Tea Face Serum</a> 
                            <strong class="price">Starting from N2000</strong>
                            <hr>
                            <div class="text-center">
                                <div class="input-group input-spinner">
                                    <button data-type="minus" data-id="1" class="btn btn-light minus" type="button"> 
                                    <img src="{{asset('/customerform/assets/img/minus.svg')}}" alt="-">
                                    </button> 
                                    <input type="text" data-type="input" data-id="1" class="form-control qty" value="1"> 
                                    <button data-type="add" class="btn btn-light plus" type="button"> 
                                        <img src="{{asset('/customerform/assets/img/add.svg')}}" alt="+">
                                    </button> 
                                </div> 
                            </div> 
                            <hr>
                            <div class="text-success"><i class="bi bi-check-circle-fill text-success"></i> (10 pieces - N15,000) Selected</div>
                        </div> 
                    </div> 
                </div>

            </div>
        </div>
        
        <!-- ORDER DUMP VIEW -->
        <div class="row view" id="order-dump" style="display: none;">
            
            <div class="row">
                <div class="col-md-12 py-3 text-center">
                    <div class="display-6">Would You Like To Add To Your Order?</div>
                    <div class="fw-bold lead">Brand new, Amazing, 100% Genuine etc</div>
                </div>
            </div>

            <hr>

            <div class="row">
                <aside class="col-lg-6">
                    <article class="gallery-wrap gallery-vertical">
                        <a href="#" class="img-big-wrap img-thumbnail bg-light">
                            <img height="520" src="{{asset('/customerform/assets/img/product-3.jpg')}}" class="mix-blend-multiply">
                        </a> <!-- img-big-wrap.// --> 
                    </article> <!-- gallery-wrap .end// --> 
                </aside> 
                <div class="col-lg-6"> 
                    <article class="ps-lg-3"> 
                        <h4 class="title text-dark">Green Tea Face Serum <br> Layer Slim Muscle</h4> 
                        <hr> 
                        <ul class="list-check cols-two mb-4"> 
                            <li>Optical heart sensor </li> 
                            <li>Some feature name </li> 
                            <li>Super fast and amazing </li> 
                            <li>Optical heart sensor </li> 
                            <li>Easy fast and ver good </li> 
                            <li>Metallic corpus </li> 
                            <li>Modern style and design</li> 
                        </ul> 
                        <div class="mb-4"> 
                            <var class="price h5">
                                <span class="currency"></span>5,800.00
                            </var> 
                            <span>/ 1 unit, incl VAT </span> 
                        </div> <!-- price-wrap.// --> 
                        <div class="row gx-2 mb-4"> 
                            <div class="col-3"> 
                            <div class="input-group input-spinner">
                                <button class="btn btn-light minus" type="button"> 
                                <img src="{{asset('/customerform/assets/img/minus.svg')}}" alt="-">
                                </button> 
                                <input type="text" class="form-control qty" value="1"> 
                                <button class="btn btn-light plus" type="button"> 
                                    <img src="{{asset('/customerform/assets/img/add.svg')}}" alt="+">
                                </button> 
                            </div>  
                            </div> <!-- col.// --> 
                            <div class="col-auto"> 
                                <a href="#" class="btn btn-primary w-100"> <i class="me-2 fa fa-shopping-basket"></i> Add to Order </a> 
                            </div> <!-- col.// --> 
                        </div> <!-- row.// --> 
                    </article> 
                </div> <!-- col.// -->
            </div> <!-- row.// -->
        </div>
        
        <!-- UPSELL VIEW -->
        <div class="row view" id="upsell" style="display: none;">
         
            <div class="row">
                <div class="col-md-12 py-3 text-center">
                    <div class="display-6">Wait! Your Order Is Almost Complete...</div>
                    <div class="fw-bold lead">Check Out This Amazing Offer Below</div>
                </div>
            </div>

            <hr>

            <article class="card card-product-list">
                <div class="row g-0">
                    <aside class="col-xl-3 col-lg-4 col-md-12 col-12">
                        <a href="#" class="img-wrap"> 
                            <img src="{{asset('/customerform/assets/img/product-5.jpg')}}">
                        </a>
                    </aside> <!-- col.// --> 
                    <div class="col-xl-6 col-lg-5 col-md-7 col-sm-7">
                        <div class="card-body">
                            <a href="#" class="h6 title mb-3">Apple HeadPhone 12 Pro, Black </a> 
                            <ul class="list-check mb-2"> 
                                <li>Triple camera with selfie, wide angle </li> 
                                <li>Metallic corpus edge </li> 
                                <li>Modern style and design</li> 
                                <li>Some other feature name</li> 
                            </ul> 
                        </div> <!-- info-div.// --> 
                    </div> <!-- col.// --> 
                    <aside class="col-xl-3 col-lg-3 col-md-5 col-sm-5">
                        <div class="info-aside"> 
                            <div class="price-wrap"> 
                                <span class="price h5"><span class="currency"></span>9400</span> 
                                <del class="price-old"> <span class="currency"></span>9980</del> 
                            </div> <!-- info-price-detail // --> 
                            <p class="text-success">Free shipping</p> 
                            <p class="text-muted"> 1-2 weeks </p> 
                            <br>
                            <div class="mb-2">
                                <div class="input-group input-spinner">
                                    <button class="btn btn-light minus" type="button"> 
                                    <img src="{{asset('/customerform/assets/img/minus.svg')}}" alt="-">
                                    </button> 
                                    <input type="text" class="form-control qty" value="1"> 
                                    <button class="btn btn-light plus" type="button"> 
                                        <img src="{{asset('/customerform/assets/img/add.svg')}}" alt="+">
                                    </button> 
                                </div> 
                            </div>
                            <a href="#" class="btn btn-primary w-100 mb-2"><i class="me-2 fa fa-shopping-basket"></i> Add to Order</a> 
                        </div> <!-- info-aside.// --> 
                    </aside> <!-- col.// --> 
                </div> <!-- row.// -->
            </article>
        </div>
        
        <!-- CHECKOUT VIEW -->
        <div class="row view" id="checkout" style="display: none;">
            <div class="col-md-12">

                <div class="card shadow-sm mb-3">
                    <div class="row gx-0"> 
                        <aside class="col-md-9"> 
                            <article class="card-body border-bottom"> 
                                <div class="row gy-3"> 
                                    <div class="col-md-7"> 
                                        <div class="itemside"> 
                                            <a href="#" class="aside"> 
                                                <img src="{{asset('/customerform/assets/img/product-1.jpg')}}" class="img-md img-thumbnail"> 
                                            </a> 
                                            <div class="info"> 
                                                <a href="#" class="title">Product Name 1 </a> 
                                                <strong class="price lead d-block mb-2"><span class="currency"></span>1570.00</strong> 
                                                <div> 
                                                    <a href="#" class="btn-link text-danger"> Remove </a> 
                                                </div> 
                                            </div> 
                                        </div> 
                                    </div> <!-- col.// --> 
                                    <div class="col-md-5"> 
                                        <div class="input-group input-spinner float-lg-end"> 
                                            <button class="btn btn-light minus" type="button"> 
                                                <img src="{{asset('/customerform/assets/img/minus.svg')}}" alt="-"> 
                                            </button> 
                                            <input type="text" class="form-control qty" value="1"> 
                                            <button class="btn btn-light plus" type="button"> 
                                                <img src="{{asset('/customerform/assets/img/add.svg')}}" alt="+"> 
                                            </button> 
                                        </div> <!-- input-group.// --> 
                                    </div> <!-- col.// --> 
                                </div> <!-- row.// --> 
                            </article> <!-- card-group.// --> 
                            <article class="card-body border-bottom"> 
                                <div class="row gy-3"> 
                                    <div class="col-md-7"> 
                                        <div class="itemside"> 
                                            <a href="#" class="aside"> 
                                                <img src="{{asset('/customerform/assets/img/product-2.jpg')}}" class="img-md img-thumbnail"> </a> 
                                                <div class="info"> 
                                                    <a href="#" class="title">Product 2 </a> 
                                                    <strong class="price lead d-block mb-2"><span class="currency"></span>1620.00</strong> 
                                                    <div> 
                                                        <a href="#" class="btn-link text-danger"> Remove </a> 
                                                    </div> 
                                                </div> 
                                            </div> 
                                        </div> <!-- col.// --> 
                                        <div class="col-md-5"> 
                                            <div class="input-group input-spinner float-lg-end"> 
                                                <button class="btn btn-light minus" type="button"> 
                                                    <img src="{{asset('/customerform/assets/img/minus.svg')}}" alt="-"> 
                                                </button> 
                                                <input type="text" class="form-control qty" value="1"> 
                                                <button class="btn btn-light plus" type="button"> 
                                                    <img src="{{asset('/customerform/assets/img/add.svg')}}" alt="+"> 
                                                </button> 
                                            </div> <!-- input-group.// --> 
                                        </div> <!-- col.// --> 
                                    </div> <!-- row.// --> 
                                </article> <!-- card-group.// --> 
                                <article class="card-body"> 
                                    <div class="row gy-3"> 
                                        <div class="col-md-7"> 
                                            <div class="itemside"> 
                                                <a href="#" class="aside"> <img src="{{asset('/customerform/assets/img/product-3.jpg')}}" class="img-md img-thumbnail"> </a> 
                                                <div class="info"> 
                                                    <a href="#" class="title">Product 3 </a> 
                                                    <strong class="price lead d-block mb-2"><span class="currency"></span>5850.00</strong> 
                                                    <div>
                                                        <a href="#" class="btn-link text-danger"> Remove </a> 
                                                    </div> 
                                                </div> 
                                            </div> 
                                        </div> <!-- col.// --> 
                                        <div class="col-md-5"> 
                                            <div class="input-group input-spinner float-lg-end"> 
                                                <button class="btn btn-light minus" type="button"> 
                                                    <img src="{{asset('/customerform/assets/img/minus.svg')}}" alt="-">
                                                </button> 
                                                <input type="text" class="form-control qty" value="1"> 
                                                <button class="btn btn-light plus" type="button"> 
                                                    <img src="{{asset('/customerform/assets/img/add.svg')}}" alt="+">
                                                </button> 
                                            </div> <!-- input-group.// --> 
                                        </div> <!-- col.// --> 
                                    </div> <!-- row.// --> 
                                </article> <!-- card-group.// --> 
                        </aside> <!-- col.// --> 
                        <aside class="col-md-3 border-start"> 
                            <div class="card-body"> 
                                <div class="input-group mb-3"> 
                                    <input type="text" class="form-control" placeholder="Promo code"> 
                                    <button class="btn btn-light text-primary">Apply</button> 
                                </div> 
                                <hr> 
                                <dl class="dlist-align"> <dt>Total price:</dt> <dd class="text-end"> <span class="currency"></span>893.00 </dd> </dl> 
                                <dl class="dlist-align"> <dt>Discount:</dt> <dd class="text-end text-danger"> - <span class="currency"></span>60.00 </dd> </dl> 
                                <dl class="dlist-align"> <dt>Total:</dt> <dd class="text-end text-dark h5"> <span class="currency"></span>957.00 </dd> </dl> 
                            </div> <!-- card-body.// --> 
                        </aside> <!-- col.// -->
                    </div> <!-- row.// -->
                </div>

                <article class="card">
                    <div class="card-body">
                        <h5 class="card-title">Contact info</h5>
                        <div class="row">
                            <div class="col-6 mb-3"> <label class="form-label">First name</label> <input type="text"
                                    class="form-control" placeholder="Type here"> </div> <!-- col end.// -->
                            <div class="col-6"> <label class="form-label">Last name</label> <input type="text" class="form-control"
                                    placeholder="Type here"> </div> <!-- col end.// -->
                            <div class="col-lg-6 mb-3"> <label class="form-label">Phone</label> <input type="text" value="+234"
                                    class="form-control" placeholder=""> </div> <!-- col end.// -->
                            <div class="col-lg-6 mb-3"> <label class="form-label">Email</label> <input type="text" class="form-control"
                                    placeholder="example@gmail.com"> </div> <!-- col end.// -->
                        </div> <!-- row.// --> 
                        
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-sm-8 mb-3"> <label class="form-label">Address</label> <input type="text"
                                    class="form-control" placeholder="Type here"> </div> <!-- col end.// -->
                            <div class="col-sm-4 mb-3"> <label class="form-label">State*</label> <select class="form-select" id="city*"
                                    aria-label="City*">
                                    <option value="1">Lagos</option>
                                </select> </div> <!-- col end.// -->
                        </div> <!-- row.// --> 
                    </div> <!-- card-body end.// -->
                </article>

            </div>
        </div>
        
        <!-- THANKYOU VIEW -->
        <div class="row view" id="thank-you" style="display: none;">
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

        <div class="card shadow-sm bg-light mt-4" id="cart-summary">
            <div class="card-body text-center">
                <div class="display-6">
                    Total: <span class="currency"></span><span class="total">0.00</span>
                </div>
                <div class="lead">Items in Cart: <span class="fw-bold" id="itemsInCart">0</span> </div>
            </div>
        </div>

        <div class="row pt-3 mt-3 border-top" id="navigation">
            <div class="col-md-8 mx-auto text-center d-flex justify-content-between">
                <div id="prevBtnDiv" style="display: none;"><button class="btn btn-outline-medium btn-lg d-flex" id="prevBtn"><i class="bi bi-arrow-left me-2"></i> Previous</button></div>
                <div id="nextBtnDiv"><button class="btn btn-primary btn-lg d-flex" id="nextBtn">Next <i class="bi bi-arrow-right"></i></button></div>
                <div id="completeBtnDiv" style="display: none;"><button class="btn btn-success btn-lg" id="completeBtn"><i class="bi bi-check-circle-fill"></i> Complete Order</button></div>
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
            src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js')}}"></script>
        <!-- Template Main JS File -->
        <script src="{{asset('/customerform/assets/js/main.js?v=42')}}"></script>
        <script src="{{asset('/customerform/assets/js/navigation.js?v=4')}}"></script>

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
    const orderDumpView = document.getElementById('order-dump');
    const upSellView = document.getElementById('upsell');
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

    //iterate product lists
    // products = [
    //     {
    //         image: 'product-1.jpg',
    //         name: 'Nike Canvas',
    //         minPrice: 15000,
    //         quantity: 1,
    //     },
    //     {
    //         image: 'product-2.jpg',
    //         name: 'Wrist Watch',
    //         minPrice: 5000,
    //         quantity: 5,
    //     },
    //     {
    //         image: 'product-3.jpg',
    //         name: 'Green Tea Face Serum',
    //         minPrice: 12500,
    //         quantity: 3,
    //     },
    //     {
    //         image: 'product-4.jpg',
    //         name: 'Gucci Sunglasses',
    //         minPrice: 7000,
    //         quantity: 6,
    //     },
    // ];

    // for (let i = 0; i < products.length; i++) {
    //     const product = products[i];
    //     // inject into list
    //     productList.innerHTML += `<div class="col-lg-3 col-sm-6 col-12"> 
    //             <div class="card card-product-grid">
    //                 <div class="img-wrap">  
    //                     <img src="{{asset('/customerform/assets/img/${product.image}"> 
    //                 </div> 
    //                 <div class="info-wrap text-center border-top"> 
    //                     <a href="#" class="title">${product.name}</a> 
    //                     <strong class="price">N${product.minPrice}</strong>
    //                     <hr>
    //                     <div class="text-center">
    //                         <div class="input-group input-spinner">
    //                             <button data-type="minus" data-id="${i}" class="btn btn-light minus" type="button"> 
    //                             <img src="{{asset('/customerform/assets/img/minus.svg" alt="-">
    //                             </button> 
    //                             <input type="text" data-type="input" data-id="${i}" class="form-control qty" value="${product.quantity}"> 
    //                             <button data-type="add" class="btn btn-light plus" type="button"> 
    //                                 <img src="{{asset('/customerform/assets/img/add.svg" alt="+">
    //                             </button> 
    //                         </div> 
    //                     </div> 
    //                 </div> 
    //             </div> 
    //         </div>`;
    // }

    /** Next Button Action */
    const nextStep = () => {
        document.getElementById('nextBtn').addEventListener('click',()=>{            
            switch (currentView) {
                case 'products':
                    setView('order-dump');
                    setActionButton();
                    break;
                case 'order-dump':
                    setView('upsell');
                    setActionButton();
                    break;
                case 'upsell':
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
                case 'order-dump':
                    setView('products');
                    setActionButton();
                    break;
                case 'upsell':
                    setView('order-dump');
                    setActionButton();
                    break;
                case 'checkout':
                    setView('upsell');
                    setActionButton();
                    break;
            
                default:
                    setView('order-dump');
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
            setView('thank-you');
            setActionButton();
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
            document.querySelector('#nextBtnDiv').style.display = 'block';
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
  
    // $( ".each_product" ).each(function(index) {
    //     $(this).on("click", ".plus", function() {   
    //         input = $(this).prev('.qty');
    //         qty = input.val();
    //         product_unitprice = input.attr("data-unitprice");
    //         amtByQty = product_unitprice;
            
    //         //holding spans
    //         product_quantity = $('.product_quantity');
    //         product_amount = $('.product_amount');
            
    //         if(!qty){
    //             qty = 1;
    //         } else {
    //             qty = parseFloat(qty) + 1;
    //         }
    //         input.val(qty);
    //         product_quantity.text(qty);

    //         amtByQty = qty * product_unitprice;
    //         product_amount.text(amtByQty);
    //     });
    // });

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
            
            // if(!qty){
            //     qty = 1;
            // } else {
            //     qty = parseFloat(qty) + 1;
            // }
            // input.val(qty);
            // product_quantity.text(qty);

            // amtByQty = qty * product_unitprice;
            // product_amount.text(amtByQty);
            // console.log(product_amount)
        })
    });

//   $('.product-list').on('click', '.plus', function(e) {  
//     input = $(this).parent().prev('.qty'); 
//     // input = $(this).prev('.qty');
//     qty = input.val();
//     product_unitprice = input.attr("data-unitprice");
//     amtByQty = product_unitprice;
    
//     //holding spans
//     product_quantity = $('.product_quantity');
//     product_amount = $('.product_amount');
    
//     if(!qty){
//         qty = 1;
//     } else {
//         qty = parseFloat(qty) + 1;
//     }
//     input.val(qty);
//     product_quantity.text(qty);

//     amtByQty = qty * product_unitprice;
//     product_amount.text(amtByQty);
//   });
  
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

  })
  </script>
    </body>

</html>