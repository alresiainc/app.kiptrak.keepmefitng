@extends('layouts.design')

@section('title')Create Order @endsection

@section('content')
    
<main id="main" class="main">

    <div class="pagetitle">
      <h1>Create Order</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Create Order</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    @if(Session::has('success'))
    <div class="alert alert-success mb-3">
        {{Session::get('success')}}
    </div>
    @endif

    <form action="{{ route('addOrderPost') }}" method="POST" enctype="multipart/form-data">@csrf
        <!--top-section--->
        <section class="section">
            <article class="card">
                <div class="card-body pt-3">
                    <div class="row">

                        <!-- Left side columns -->
                        <div class="col-lg-12">
                        
                            <div class="d-flex align-items-center">
                                
                                <div class="w-50">
                                    <select name="staff_assigned" class="form-control @error('staff_assigned') is-invalid @enderror" id="">
                                        <option value="" selected>No Agent Selected</option>
                                        <option value="1">Ugo Sunday</option>
                                        <option value="2">Dare Ike</option>
                                    </select>
                                    @error('staff_assigned')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                    
                                </div>
                                
                                <a href="{{ route('allOrders') }}" class="btn btn-success btn-block glow users-list-clear mb-0 ms-auto">
                                    <i class="bx bx-align-justify"></i> View Orders
                                </a>
                            </div>
                        
                        </div><!-- End Left side columns -->
            
                    </div>
                </div>
            </article>
        
        </section>

        <section class="section">
            <article class="card">
                
                <div class="card-body">
                    <h5 class="card-title">First Phase: Product View</h5>
                    <div class="row">
                        <div class="col-6 mb-3"><label class="form-label">Order Heading</label>
                            <input type="text" class="form-control @error('order_heading') is-invalid @enderror" name="order_heading"
                            value="{{ old('order_heading') ? old('order_heading') : 'Choose your products'}}">
                            @error('order_heading')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> <!-- col end.// -->
    
                        <div class="col-6"> <label class="form-label">Order Sub-heading</label>
                            <input type="text" name="order_subheading" class="form-control @error('order_subheading') is-invalid @enderror"
                            value="{{ old('order_subheading') ? old('order_subheading') : 'Click each product to select a package'}}">
                            @error('order_heading')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div> <!-- col end.// -->
                            
                    </div> <!-- row.// -->

                    <!--add product-->
                    <div class="row">
                        <div class="col-lg-12 mb-1"> <label class="form-label fw-bold">Add Product</label></div>
                    </div>
                    
                    <div class="product-clone-section wrapper">
    
                        <div class="row element">
                            
                            <div class="col-lg-6 mb-3"> <label class="form-label">Product Name</label>
                                <select name="product_id[]" class="form-control select_product" id="select_product">
                                    <option value=""></option>
                                    <!--id-stock-unitprice-->
                                    <option value="15-10-1000">Coca Cola | 10 @1000</option>
                                    <option value="2-20-2000">Fanta | 20 @2000</option>
                                    <option value="3-30-3000">Heineken | 30 @3000</option>
                                    <option value="4-40-4000">Sprite | 40 @4000</option>
                                </select>
                            </div> <!-- col end.// -->
        
                            <div class="col-lg-3 mb-3"> <label class="form-label">Qty
                                <span class="product_stock"></span>
                                <span class="product_unitprice"></span>
                            </label>
                                <input name="product_quantity[]" type="text" class="form-control" value="">
                            </div> <!-- col end.// -->
        
                            <div class="col-lg-3 mb-3"> <label class="form-label">Discount (% or fixed)</label>
                                <input name="product_discount[]" type="text" class="form-control" placeholder="ex. 5% or 5">
                            </div> <!-- col end.// -->
                            
                        </div> <!-- row.// -->
    
                        <!--append elements to-->
                        <div class="results"></div>
                        
                        <div class="buttons d-flex justify-content-between">
                            <button type="button" class="clone btn btn-success btn-sm">Add</button>
                            <button type="button" class="remove btn btn-danger btn-sm">Remove</button>
                        </div>
                    </div>
                    <!--add-product-end--->
                    
                    
                    <hr class="my-4">

                    <!--orderbump-section-->
                    <div class="d-flex align-items-center">
                        <h5 class="card-title">Next: Order Bump (optional)</h5>
                        <button type="button" id="orderbump" class="btn btn-success btn-sm rounded-pill">Show</button> 
                    </div>
                    
                    <div class="row orderbump" style="display: none;">
                        <input type="hidden" id="orderbump_status" name="orderbump_status" value="false">
    
                        <div class="col-sm-6 mb-3"> <label class="form-label">Heading</label>
                            <input name="orderbump_heading" type="text" class="form-control" value="Would You Like To Add To Your Order?">
                        </div> <!-- col end.// -->
    
                        <div class="col-sm-6 mb-3"> <label class="form-label">Sub-heading</label>
                            <input name="orderbump_subheading" type="text" class="form-control" value="Brand new, Amazing, 100% Genuine etc">
                        </div> <!-- col end.// -->
    
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Select Product*</label>
                            <select id="select_orderbump_product" name="select_orderbump_product" class="form-select"
                                aria-label="select_orderbump_product*">
                                <option value=""></option>
                                <!-- id-qty-unitprice// -->
                                <option value="1-10-1000">Coca Cola</option>
                            </select>
                        </div> <!-- col end.// -->
    
                        <div class="col-sm-3 mb-2">
                            <label class="form-label">Qty*
                            <span class="orderbump_stock"></span>
                            <span class="orderbump_unitprice"></span>
                            </label>
                            <input name="orderbump_product_quantity" type="text" class="form-control" value="">
                        </div> <!-- col end.// -->
                        
                        <!--unused-->
                        <!-- <input type="hidden" id="orderbump_product_unitprice" name="orderbump_product_unitprice" value="">
                        <input type="hidden" id="orderbump_product_id" name="orderbump_product_id" value=""> -->
                        <!-- col end.// unit price -->
                        
                        <div class="col-sm-3 mb-2"> <label class="form-label">Discount</label>
                            <input name="orderbump_discount" type="text" class="form-control" value="" placeholder="% or fixed">
                        </div> <!-- col end.// -->
                        
                    </div> <!-- row.// -->
                    <!--orderbump-section-end-->
                    
                    <hr class="my-4">
    
                    <!--upsell-section--->
                    <div class="d-flex align-items-center">
                        <h5 class="card-title">Next: Upsell (optional)</h5>
                        <button type="button" id="upsell" class="btn btn-success btn-sm rounded-pill">Show</button> 
                    </div>
                    
                    <div class="row upsell" style="display: none;">
                        <input type="hidden" id="upsell_status" name="upsell_status" value="false">
    
                        <div class="col-sm-6 mb-3"> <label class="form-label">Heading</label>
                            <input name="upsell_heading" type="text" class="form-control" value="Wait! Your Order Is Almost Complete...">
                        </div> <!-- col end.// -->
    
                        <div class="col-sm-6 mb-3"> <label class="form-label">Sub-heading</label>
                            <input name="upsell_subheading" type="text" class="form-control" value="Check Out This Amazing Offer Below">
                        </div> <!-- col end.// -->
    
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Select Product*</label>
                            <select name="select_upsell_product" id="select_upsell_product" class="form-select" aria-label="select_upsell_product*">
                                <option value=""></option>
                                <!-- id-qty-unitprice// -->
                                <option value="1-10-1000">Coca Cola</option>
                            </select>
                        </div> <!-- col end.// -->
    
                        <div class="col-sm-3 mb-2">
                            <label class="form-label">Qty*
                            <span class="upsell_stock"></span>
                            <span class="upsell_unitprice"></span>
                            </label>
                            <input name="upsell_product_quantity" type="text" class="form-control" value="">
                        </div> <!-- col end.// -->
                        
                        <!--unused-->
                        <!--<input type="hidden" id="upsell_product_unitprice" name="upsell_product_unitprice" value="">
                        <input type="hidden" id="upsell_product_id" name="upsell_product_id" value="">-->
                        <!-- col end.// unit price -->
                        
                        <div class="col-sm-3 mb-2"> <label class="form-label">Discount</label>
                            <input name="upsell_discount" type="text" class="form-control" value="" placeholder="% or fixed">
                        </div> <!-- col end.// -->
                        
                    </div> <!-- row.// -->
    
                    <hr class="my-4">
                    
                    <!--customer-section--->
                    <h5 class="card-title">Next: Customer Details</h5>
                    <div class="row">
    
                        <div class="col-sm-4 mb-3"><label class="form-label">First Name Label*</label>
                            <input type="text" name="firstname" class="form-control @error('order_subheading') is-invalid @enderror" id="" value="First name">
                            @error('firstname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> <!-- col end.// -->
    
                        <div class="col-sm-4 mb-3"><label class="form-label">Last Name Label*</label>
                            <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" id="" value="Last name">
                            @error('lastname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> <!-- col end.// -->
    
                        <div class="col-sm-4 mb-2"> <label class="form-label">Phone Label*</label>
                            <input name="phone" type="text" class="form-control @error('phone') is-invalid @enderror" value="Phone">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> <!-- col end.// -->
    
                        <div class="col-sm-4 mb-2"> <label class="form-label">Email Label*</label>
                            <input name="email" type="text" class="form-control @error('email') is-invalid @enderror" value="Email" placeholder="">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> <!-- col end.// -->
    
                        <div class="col-sm-4 mb-2"> <label class="form-label">Address Label*</label>
                            <input name="address" type="text" class="form-control @error('address') is-invalid @enderror" value="Address" placeholder="">
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> <!-- col end.// -->
    
                        <div class="col-sm-4 mb-2"> <label class="form-label">State Label*</label>
                            <input name="state" type="text" class="form-control @error('state') is-invalid @enderror" value="State" placeholder="">
                            @error('state')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> <!-- col end.// -->
    
                    </div> <!-- row.// --> 

                    <hr class="my-4">
                    
                    <!--thankyou-section--->
                    <h5 class="card-title">Next: Thank You Page</h5>
                    <div class="row">
    
                        <div class="col-sm-6 mb-3"><label class="form-label">Heading*</label>
                            <input type="text" name="thankyou_heading" class="form-control @error('thankyou_heading') is-invalid @enderror" id="" value="Thank you for order">
                            @error('thankyou_heading')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> <!-- col end.// -->
    
                        <div class="col-sm-6 mb-3"><label class="form-label">Sub-heading*</label>
                            <input type="text" name="thankyou_subheading" class="form-control @error('thankyou_subheading') is-invalid @enderror" id=""
                            value="We have received your order confirmation. One of our agents will contact you shortly.">
                            @error('thankyou_subheading')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> <!-- col end.// -->
    
                    </div> <!-- row.// --> 
    
                    <div class="row my-4">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-success w-50">Submit Order</button>
                        </div> <!-- col end.// -->
                    </div>
                    
                </div> <!-- card-body end.// -->
                
            </article>
        </section>

    </form>

    

  </main>

@endsection

@section('extra_js')
    <script>
        $('.wrapper').on('click', '.remove', function() {
            $('.remove').closest('.wrapper').find('.element').not(':first').last().remove();
        });
        $('.wrapper').on('click', '.clone', function() {
            $('.clone').closest('.wrapper').find('.element').first().clone().appendTo('.results');
        });

        //toggle order-bump & upsell
        $("button#orderbump").click(function(){

            //toggle btn text
            var orderbump = $(this).text();
            if(orderbump == 'Show'){
                $(this).text('Hide').css({'backgroundColor':'red', 'borderColor':'red'});
            }else{
                $(this).text('Show').css({'backgroundColor':'#198754', 'borderColor':'#198754'});;
            }

            //toggle status val
            var orderbump_status = $("#orderbump_status").val();
            if(orderbump_status == 'false'){
                $("#orderbump_status").val('true')
            }else{
                $("#orderbump_status").val('false') 
            }
            $("div.orderbump").toggle();
        });

        $("button#upsell").click(function(){

            //toggle btn text
            var upsell = $(this).text();
            if(upsell == 'Show'){
                $(this).text('Hide').css({'backgroundColor':'red', 'borderColor':'red'});
            }else{
                $(this).text('Show').css({'backgroundColor':'#198754', 'borderColor':'#198754'});;
            }

            //toggle status val
            var upsell_status = $("#upsell_status").val();
            if(upsell_status == 'false'){
                $("#upsell_status").val('true')
            }else{
                $("#upsell_status").val('false') 
            }
            $("div.upsell").toggle();
        });

        //select_product
        // $('.select_product').on('change', function() {
        //     var value = $(this).val(); //1-10
        //     var array = value.split("-"); //[1,10,1000], id,qty,unitprice
            
        //     $(this).closest('.element').find('.product_stock').text(array[1]);
        //     $(this).closest('.element').find('.product_unitprice').text('@'+array[2]);
        //     $(this).closest('.element').find('.product_id_array').val(array[1]);
        //     $(this).closest('.element').find('.product_unitprice_array').val(array[2]);
        
        // });

        //orderbump select-option product
        $('#select_orderbump_product').on('change', function() {
            var value = $(this).val(); //1-10
            var array = value.split("-"); //[1,10,1000], id,qty,unitprice
            $('.orderbump_stock').text(array[1]) //qty
            $('.orderbump_unitprice').text('@'+array[2]) //unitprice
            // $('#orderbump_product_id').val(array[0]);
            // $('#orderbump_product_unitprice').val(array[2]);
        });

        //upsell select-option product
        $('#select_upsell_product').on('change', function() {
            var value = $(this).val(); //1-10
            var array = value.split("-"); //[1,10,1000], id,qty,unitprice
            $('.upsell_stock').text(array[1])
            $('.upsell_unitprice').text('@'+array[2])
            // $('#upsell_product_id').val(array[0]);
            // $('#upsell_product_unitprice').val(array[2]);
        });
    </script>
@endsection