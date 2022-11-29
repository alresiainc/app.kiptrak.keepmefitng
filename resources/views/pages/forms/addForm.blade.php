@extends('layouts.design')
@section('title')Add Form @endsection

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
</style>
@endsection

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Add Form</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Add Form</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    @if(Session::has('success'))
    <div class="alert alert-success mb-3 text-center">
        {{Session::get('success')}}
    </div>
    @endif

    <form action="{{ route('addFormPost') }}" method="POST">@csrf
        <!--assign agent & discount-->
        <section class="users-list-wrapper">
            <div class="users-list-filter px-1">
            
                <div class="row border rounded py-2 mb-2">
        
                <div class="col-12 col-md-6 col-lg-6">
                    <label for="">Assign Agent</label>
                    <fieldset class="form-group">
                    <select name="agent_assigned" data-live-search="true" class="custom-select form-control border btn-dark @error('agent_assigned') is-invalid @enderror"
                    id="" style="color: black !important;">
                        <option value="">Nothing Selected</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }} | {{ $agent->state }}</option>
                        @endforeach
                        
                    </select>
                    @error('agent_assigned')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </fieldset>
                    
                </div>
                
                <div class="col-12 col-md-6 col-lg-3">
                    <label for="">Discount (% or Fixed)</label>
                    <fieldset class="form-group">
                        <input type="text" name="discount" class="form-control" value="">
                    </fieldset>
                </div>
        
                <!--buttons here-->
                <div class="col-12 col-md-6 col-lg-3 d-flex align-items-end">
                    <div class="d-grid w-100">
                      <a href="{{ route('allForms') }}" class="btn btn-primary btn-block glow users-list-clear mb-0">View Forms</a>
                    </div>
                </div>
        
                </div>
            
            </div>
        
        </section>

        <!---product orderbmp upsell contact thankyou--->
        <section>
        <div class="row">
            <div class="col-md-12">

            <!--firstpahse heading & sub--->
            <div class="card">

                <div class="card-body">
                    <h6 class="card-title">First Phase: Product View</h6>
                    <div class="row g-3">
                        
                        <!--heading & sub--->
                        <div class="col-md-6">
                            <label for="" class="form-label">Heading</label>
                            <input type="text" name="order_heading" class="form-control @error('order_heading') is-invalid @enderror"
                                value="{{ old('order_heading') ? old('order_heading') : 'Click "Add to Order button" to add the product'}}" id="">
                                @error('order_heading')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="" class="form-label">Subheading</label>
                            <input type="text" name="order_subheading" class="form-control @error('order_subheading') is-invalid @enderror"
                            value="{{ old('order_subheading') ? old('order_subheading') : 'Click "Next button" to continue'}}" id="">
                            @error('order_subheading')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!--order products without clone--->
                        <div class="col-md-9">
                            <label for="" class="form-label">Select Product</label>
                            <select name="product_id" data-live-search="true" class="custom-select form-control border" id="">
                                <option value="">Nothing Selected</option>

                                @foreach ($products as $product)
                                    <!---1-30-3000--->
                                    <option value="{{ $product->id }}-{{ $product->stock_available() }}-{{ $product->price }}">
                                        {{ $product->name }} | {{ $product->stock_available() }} {{ '@'.$product->price }}
                                    </option>
                                @endforeach
                                
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="" class="form-label">Quantity</label>
                            <input name="product_quantity" type="text" class="form-control" value="">
                        </div>
                        <!--order products without clone--->
                        
                    
                        
                    </div><!-- End Multi Columns Form -->

                    <!--order products clone--->
                    {{-- <div class="mt-2 d-none">
                        <div class="product-clone-section wrapper">

                            <div class="row g-3 mt-1 element">
                                
                                <div class="col-md-9">
                                    <label for="" class="form-label">Select Product</label>
                                    <select name="product_id[]" data-live-search="true" class="myselect form-control border" id="">
                                        <option value="">Nothing Selected</option>
                                        <option value="1-30-3000">Fanta 33cl | 30 @3000</option>
                                        <option value="2-20-2000">Coca Cola | 20 @2000</option>
                                        <option value="3-40-4000">Heineken | 40 @4000</option>
                                        <option value="4-50-2000">Maltina | 50 @2000</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="form-label">Quantity</label>
                                    <input name="product_quantity[]" type="text" class="form-control" value="">
                                </div>
                            </div>

                            <!--append elements to-->
                            <div class="results"></div>
                            
                            <div class="buttons d-flex justify-content-between">
                                <button type="button" class="clone btn btn-success btn-sm">Add</button>
                                <button type="button" class="remove btn btn-danger btn-sm">Remove</button>
                            </div>

                        </div>
                    </div> --}}
                    <!--order products clone--->
                
                </div>

            </div>

            <!--orderbump heading & sub--->
            <div class="card">

                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title">Order Bump (optional)</h5>
                        <button type="button" id="orderbump" class="btn btn-success btn-sm rounded-pill">Show</button> 
                    </div>

                    <input type="hidden" id="orderbump_status" name="orderbump_status" value="false">
                    <div class="row g-3 orderbump" style="display: none;">
                        
                        <div class="col-md-6">
                            <label for="" class="form-label">Heading</label>
                            <input type="text" name="orderbump_heading" class="form-control" value="Would You Like To Add To Your Order?" placeholder="">
                        
                        </div>
                        <div class="col-md-6">
                            <label for="" class="form-label">Subheading</label>
                            <input type="text" name="orderbump_subheading" class="form-control"
                            value="Brand new, Amazing, 100% Genuine etc" placeholder="">
                        </div>

                        <div class="col-md-9">
                            <label for="" class="form-label">Product</label>
                            <select name="orderbump_product_id" data-live-search="true" class="custom-select form-control border" id="">
                                <option value="">Nothing Selected</option>
                                @foreach ($products as $product)
                                    <!---1-30-3000--->
                                    <option value="{{ $product->id }}-{{ $product->stock_available() }}-{{ $product->price }}">
                                        {{ $product->name }} | {{ $product->stock_available() }} {{ '@'.$product->price }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="" class="form-label">Quantity</label>
                            <input type="text" name="orderbump_product_quantity" class="form-control" value="" placeholder="">
                        
                        </div>
                        
                    </div><!-- End Multi Columns Form -->
                
                </div>

            </div>

            <!--upsell heading & sub--->
            <div class="card">

                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title">UpSell (optional)</h5>
                        <button type="button" id="upsell" class="btn btn-success btn-sm rounded-pill">Show</button> 
                    </div>
                    <input type="hidden" id="upsell_status" name="upsell_status" value="false">
                    <div class="row g-3 upsell"style="display: none;">
                        
                        <div class="col-md-6">
                            <label for="" class="form-label">Heading</label>
                            <input type="text" name="upsell_heading" class="form-control" value="Wait! Your Order Is Almost Complete..." placeholder="">
                        
                        </div>
                        <div class="col-md-6">
                            <label for="" class="form-label">Subheading</label>
                            <input type="text" name="upsell_subheading" class="form-control" value="Check Out This Amazing Offer Below" placeholder="">
                        </div>

                        <div class="col-md-9">
                            <label for="" class="form-label">Product</label>
                            <select name="upsell_product_id" data-live-search="true" class="custom-select form-control border" id="">
                                <option value=""></option>
                                <option value="">Nothing Selected</option>
                                @foreach ($products as $product)
                                    <!---1-30-3000--->
                                    <option value="{{ $product->id }}-{{ $product->stock_available() }}-{{ $product->price }}">
                                        {{ $product->name }} | {{ $product->stock_available() }} {{ '@'.$product->price }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="" class="form-label">Quantity</label>
                            <input type="text" name="upsell_product_quantity" class="form-control" value="" placeholder="">
                        
                        </div>
                        
                    </div><!-- End Multi Columns Form -->
                
                </div>

            </div>

            <!--contact details label--->
            <div class="card">

                <div class="card-body">
                    <h6 class="card-title">Contact Details Label</h6>
                <div class="row g-3">
                    
                    <div class="col-md-6 contact-content">
                        <label for="" class="form-label d-flex align-items-center">
                            <span class="me-1">Firstname Label</span><input id="checkfirstname" class="contact-check p-5" type="checkbox" checked/>
                        </label>
                        <input type="text" name="firstname" class="contact-input form-control @error('firstname') is-invalid @enderror"
                        id="firstname" value="First name" placeholder="">
                    
                    </div>
                    <div class="col-md-6 contact-content">
                        <label for="" class="form-label d-flex align-items-center">
                            <span class="me-1">Lastname Label</span><input id="checklastname" class="contact-check" type="checkbox" checked/>
                        </label>
                        <input type="text" name="lastname" class="contact-input form-control @error('lastname') is-invalid @enderror"
                        id="lastname" value="Last name" placeholder="">
                    
                    </div>
                    
                    <div class="col-md-6">
                        <label for="" class="form-label d-flex align-items-center">
                            <span class="me-1">Phone Label</span><input id="checkphone" class="contact-check" type="checkbox" checked/>
                        </label>
                        <input type="text" name="phone" class="form-control" class="contact-input form-control @error('phone') is-invalid @enderror"
                        id="phone" value="Phone" placeholder="">
                        
                    </div>
                    <div class="col-md-6">
                        <label for="" class="form-label d-flex align-items-center">
                            <span class="me-1">Email Label</span><input id="checkemail" class="contact-check" type="checkbox" checked/>
                        </label>
                        <input type="text" name="email" class="contact-input form-control @error('email') is-invalid @enderror"
                        id="email" value="Email" placeholder="">
                    
                    </div>

                    <div class="col-md-6">
                        <label for="" class="form-label d-flex align-items-center">
                            <span class="me-1">City Label</span><input id="checkcity" class="contact-check" type="checkbox" checked/>
                        </label>
                        <input type="text" name="city" class="contact-input form-control @error('city') is-invalid @enderror"
                        id="city" value="City" placeholder="">
                    
                    </div>

                    <div class="col-md-6">
                        <label for="" class="form-label d-flex align-items-center">
                            <span class="me-1">State Label</span><input id="checkstate" class="contact-check" type="checkbox" checked/>
                        </label>
                        <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                        id="state" value="State" placeholder="">
                    
                    </div>

                    <div class="col-md-6">
                        <label for="" class="form-label d-flex align-items-center">
                            <span class="me-1">Country Label</span><input id="checkcountry" class="contact-check" type="checkbox" checked/>
                        </label>
                        <input type="text" name="country" class="form-control @error('country') is-invalid @enderror"
                        id="country" value="Country" placeholder="">
                    
                    </div>
                    
                    <div class="col-md-6">
                        <label for="" class="form-label d-flex align-items-center">
                            <span class="me-1">Address Label</span><input id="checkaddress" class="contact-check" type="checkbox" checked/>
                        </label>
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                        id="address" value="Address" placeholder="">
                    
                    </div>
                    
                </div><!-- End Multi Columns Form -->
                
                </div>

            </div>

            <!--thankYou label--->
            <div class="card">

                <div class="card-body">
                    <h6 class="card-title">ThankYou Label</h6>
                <div class="row g-3">
                    
                    <div class="col-md-12">
                        <label for="" class="form-label">Heading</label>
                        <input type="text" name="thankyou_heading" class="form-control @error('thankyou_heading') is-invalid @enderror" id=""
                        value="Thank you for order" placeholder="">
                        @error('thankyou_heading')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="" class="form-label">Subheading</label>
                        <input type="text" name="thankyou_subheading" class="form-control @error('thankyou_subheading') is-invalid @enderror" id=""
                        value="We have received your order confirmation. One of our agents will contact you shortly.">
                        @error('thankyou_subheading')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Create Form</button>                    
                    </div>
                </div><!-- End Multi Columns Form -->
                
                </div>

            </div>

            </div>
        </div>
        </section>
    </form>
  </main><!-- End #main -->


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


    // $(".contact-content").each(function(index, element) {
    //     var contentinput = $(element).find("input.contact-input").val();
    //     $(element).on('click', '.contact-check', function() {
    //         var closestinput = $(this).closest("div.contact-content").find("input.contact-input");
    //         if (contentinput == closestinput.val()) {
    //             if (closestinput != 'N/A') {
    //                 contentinput = closestinput.val()
    //                 closestinput.val('N/A')
    //             } else {
    //                 closestinput.val(contentinput)
    //             }
    //         }
    //     });
    //     // console.log('uuu')
    // });
    
  var toggleText_firstname = $("#firstname").val();
  $("#checkfirstname").click(function() {
    if ($("#firstname").val() != '') {
        toggleText_firstname = $("#firstname").val();
      $("#firstname").val('');
    } else
      $("#firstname").val(toggleText_firstname);
  });

  var toggleText_lastname = $("#lastname").val();
  $("#checklastname").click(function() {
    if ($("#lastname").val() != '') {
        toggleText_lastname = $("#lastname").val();
      $("#lastname").val('');
    } else
      $("#lastname").val(toggleText_lastname);
  });

  var toggleText_phone = $("#phone").val();
  $("#checkphone").click(function() {
    if ($("#phone").val() != '') {
        toggleText_phone = $("#phone").val();
      $("#phone").val('');
    } else
      $("#phone").val(toggleText_phone);
  });

  var toggleText_email = $("#email").val();
  $("#checkemail").click(function() {
    if ($("#email").val() != '') {
        toggleText_email = $("#email").val();
      $("#email").val('');
    } else
      $("#email").val(toggleText_email);
  });

  var toggleText_city = $("#city").val();
  $("#checkcity").click(function() {
    if ($("#city").val() != '') {
        toggleText_city = $("#city").val();
      $("#city").val('');
    } else
      $("#city").val(toggleText_city);
  });

  var toggleText_state = $("#state").val();
  $("#checkstate").click(function() {
    if ($("#state").val() != '') {
        toggleText_state = $("#state").val();
      $("#state").val('');
    } else
      $("#state").val(toggleText_state);
  });

  var toggleText_country = $("#country").val();
  $("#checkcountry").click(function() {
    if ($("#country").val() != '') {
        toggleText_country = $("#country").val();
      $("#country").val('');
    } else
      $("#country").val(toggleText_country);
  });

  var toggleText_address = $("#address").val();
  $("#checkaddress").click(function() {
    if ($("#address").val() != '') {
        toggleText_address = $("#address").val();
      $("#address").val('');
    } else
      $("#address").val(toggleText_address);
  });

</script>

@endsection