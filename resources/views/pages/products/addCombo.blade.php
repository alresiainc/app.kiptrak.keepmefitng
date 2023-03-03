@extends('layouts.design')
@section('title')Create Combo Product @endsection

@section('extra_css')
<style>
    /* select2 arrow */
    select{
        -webkit-appearance: listbox !important
    }

    /* custom-select border & inline edit */
    .btn-light {
        background-color: #fff !important;
        color: #000 !important;
    }
    div.filter-option-inner-inner{
        color: #000 !important;
    }
    /* custom-select border & inline edit */

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
    /* select2 height proper */
</style>
@endsection
@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Create Combo Product</h1>
        <h1>Grand Total: <span>₦</span><span class="total_after_discount">0</span></h1>
    </div>
    
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('allCombo') }}">Combo Product List</a></li>
        <li class="breadcrumb-item active">Create Combo Product</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if(Session::has('error'))
  <div class="alert alert-danger mb-3 text-center">
      {{Session::get('error')}}
  </div>
  @endif
  @if(Session::has('success'))
  <div class="alert alert-success mb-3 text-center">
      {{Session::get('success')}}
  </div>
  @endif

  <section>
    <div class="row">
        <div class="col-md-5">
            

            <div class="card" id="products-list" style="overflow-y:auto">
                <div class="card-body pt-3 ">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 style="font-size: 12px !important; text-transform: uppercase; color:#000;">Click on any product to add as combo</h5>
                        </div>
                    </div>
                    <hr>

                    <div>
                        <input type="search" id="product_filter" class="form-control rounded-pill" placeholder="Search product...">
                    </div>
                    <hr>

                    <div class="row">
                        
                        @if (count($products) > 0)

                        @foreach ($products as $product)
                        <div class="col-md-3 p-0 each_item" data-product_id="{{ $product->id }}" data-product_name="{{ $product->name }}"
                            data-product_saleprice="{{ $product->sale_price }}" data-product_purchaseprice="{{ $product->purchase_price }}">
                            <div class="card">
                                <div class="card-body text-center pt-2
                                    px-1">
                                    <div class="image">
                                        <img src="{{ asset('/storage/products/'.$product->image) }}" class="img-fluid img-thumbnail" alt="">
                                    </div>
                                    <div class="small"><small class="text-dark">{{ $product->name }}</small></div>
                                </div>
                            </div>
                        </div>                        
                        @endforeach

                        @else
                        <div class="col-md-12 p-0 each_item">
                            <div class="card">
                                <div class="card-body text-center pt-2 px-1">
                                    No product available
                                </div>
                            </div>
                        </div> 
                        @endif
                        
                    </div>

                    

                </div>
            </div>

        </div>

        <div class="col-md-7">
            <form action="{{ route('addComboPost') }}" method="post" enctype="multipart/form-data">@csrf

                <!----table---->
                <div class="card" style="height: 50vh; overflow-y: auto;">
                    <div class="card-body">
                        <table class="table table-borderless" id="comboTable">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col">Remove</th>
                                </tr>
                            </thead>
                            <tbody id="comboTableBody">
                                {{-- <tr>
                                    <td>Product Name</td>
                                    <td><span class="currency">₦</span><span class="product_unitprice">1000</span></td>
                                    <td>
                                        <div class="input-group input-spinner mb-3">
                                            <button class="btn btn-sm btn-icon btn-light border minusQty" type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#999" viewBox="0 0
                                                    24 24"> <path d="M19
                                                        13H5v-2h14v2z"></path>
                                                </svg> </button>
                                            <input style="max-width: 50px; min-width: 50px;" class="form-control form-control-sm border text-center product_quantity" placeholder="" value="1" min="1">
                                            <button class="btn btn-sm btn-icon btn-light border plusQty" type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#999" viewBox="0 0
                                                    24 24"> <path d="M19
                                                        13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"></path>
                                                </svg> 
                                            </button> 
                                        </div>
                                    </td>
                                    <td><span class="currency">₦</span><span class="product_subtotal">1000.00</span></td>
                                    <td>
                                        <button class="btn btn-danger btn-sm bi bi-x btnDelete"></button>
                                    </td>
                                </tr> --}}
                            </tbody>
                            <input type="hidden" name="total_before_discount" id="total_before_discount_input" value="">
                            <input type="hidden" name="total_after_discount" id="total_after_discount_input" value="">
                            <input type="hidden" name="total_purchase" id="total_purchase" value="">
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body pt-3">
                        <card-title>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Product Combo name</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div> 
                            </div>
                        </card-title>

                        <hr>

                        <card-title>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Discount Type</label>
                                    <select name="discount_type" id="discount_type" class="form-control @error('discount_type') is-invalid @enderror">
                                        <option value="{{ old('discount_type') ? old('discount_type') : 'fixed' }}" selected>{{ old('discount_type') ? old('discount_type') : 'Fixed' }}</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                    @error('discount_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="">Discount</label>
                                    <input type="text" name="discount_value" id="discount_value" class="form-control @error('discount_value') is-invalid @enderror">
                                    @error('discount_value')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="">Combo Image</label>
                                    <input type="file" name="image" id="" class="form-control @error('image') is-invalid @enderror">
                                    @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                
                            </div>  
                        </card-title>
                        
                        <hr>

                        <!---category-desc--->
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Category</label> <span class="text-danger">*</span>
                                <select name="category" id="category" class="select2 form-control @error('category') is-invalid @enderror">
                                    <option value="" selected>Nothing Selected</option>
                                    @if (count($categories))
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    @endif
                                    
                                </select>
                                @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-9">
                                <label for="">Short Description</label>
                                <textarea name="short_description" id="" cols="30" rows="1" class="form-control @error('category') is-invalid @enderror"></textarea>
                                @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                <!--grand-total--->
                <div class="card bg-medium">
                    <div class="card-body p-2">
                        <div class="card-title">
                            <div class="row">
                                <div class="col-md-4"><h6>Total Before Discount:<br> <b class="currency">₦</b><span class="total_before_discount" style="color:inherit; font-size: 16px;">0.00</span></h6></div>
                                <div class="col-md-4"><h6>Total After Discount:<br> <b class="currency">₦</b><span class="total_after_discount" style="color:inherit; font-size: 16px;">0.00</span></h6></div>
                                <div class="col-md-4"><button class="btn w-100">Submit</button></div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            
            </form>
        </div>
    </div>
  </section>


</main><!-- End #main -->

<!-- Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Import Product CSV File</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>Download sample product CSV file <a href="#" class="btn btn-sm rounded-pill btn-primary"><i class="bi bi-download me-1"></i> Download</a></div>
        <div class="mt-3">
          <label for="formFileSm" class="form-label">Click to upload file</label>
          <input class="form-control form-control-sm" id="formFileSm" type="file">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary"><i class="bi bi-upload"></i> Upload</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('extra_js')

<script>

</script>

<!---search-product--->
<script>
    $("#product_filter").keyup(function(){
    console.log('lorem')
    // Retrieve the input field text and reset the count to zero
    var filter = $(this).val(), count = 0;

    // Loop through the comment list
    $(".each_item").each(function(){

          // If the list item does not contain the text phrase fade it out
          if ($(this).text().search(new RegExp(filter, "i")) < 0) {
             $(this).fadeOut();

          // Show the list item if the phrase matches and increase the count by 1
          } else {
             $(this).show();
             count++;
          }
    });

    // Update the count,if need be
    // var numberItems = count;
    // $("#filter-count").text("Articles = "+count);
 });
</script>

<!---append each product to table--->
<script>
    $(".each_item").click(function(){
        var product_name = $(this).attr('data-product_name');
        var product_saleprice = $(this).attr('data-product_saleprice');
        var product_purchaseprice = $(this).attr('data-product_purchaseprice');
        
        var product_id = $(this).attr('data-product_id');
        var idQty = product_id+'-'+1;

        var start = '<tr>';

        var productName = '<td><input type="hidden" class="row_product_id" value="'+product_id+'"><input type="hidden" name="idQty[]" class="row_product_idQty" value="'+idQty+'">'+product_name+'</td>';
        var productSalePrice = '<td><input type="hidden" class="row_product_purchaseprice" value="'+product_purchaseprice+'"><span class="currency">₦</span><span class="product_unitprice">'+product_saleprice+'</span></td>';

        var productQty = '<td>';
            productQty += '<div class="input-group input-spinner mb-3">';
            productQty += '<button class="btn btn-sm btn-icon btn-light border minusQty" type="button">';       
            productQty +=  '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#999" viewBox="0 0 24 24">';
            productQty +=  '<path d="M19 13H5v-2h14v2z"></path>';
            productQty += '</svg>';
            productQty +=  '</button>';
            productQty +=  '<input style="max-width: 50px; min-width: 50px;" class="form-control form-control-sm border text-center product_quantity" placeholder="" value="1" min="1">';
            productQty +=  '<button class="btn btn-sm btn-icon btn-light border plusQty" type="button">';
            productQty +=    '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#999" viewBox="0 0 24 24">';
            productQty +=    '<path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"></path>';
            productQty +=   '</svg>'; 
            productQty +=  '</button>'; 
            productQty += '</div>';
            productQty += '</td>';

        var productSubTotal = '<td><span class="currency">₦</span><span class="product_subtotal">'+product_saleprice+'</span></td>';
        var btnDelete = '<td><button class="btn btn-danger btn-sm bi bi-x btnDelete"></button></td>';

        var end = '</tr>';

        var row = start + productName + productSalePrice + productQty + productSubTotal + btnDelete + end;

        // console.log(row)
        

        //check for duplicates
        if( !$.trim( $('#comboTableBody').html() ).length ) {
            $("#comboTable > tbody").append(row);
            total_calculate();
            total_purchase();  
        } else {
            $("#comboTable .row_product_id").each(function() {
                if ($(this).val() == product_id) {
                    alert('Product Already Exists. Remove Duplicates');
                    return;
                }
            })
            $("#comboTable > tbody").append(row);
            total_calculate();
            total_purchase(); 
        }
        

        

    });
</script>

<script>
    //keyup of discount input-field
    $(document).on('input', '#discount_value', function(){
    // $('#discount_value').keyup(function(){ 
     total_calculate();
     total_purchase();
    })
</script>

<script>
    //keyup of discount input-field
    $('#discount_type').change(function(){ 
     total_calculate();
     total_purchase();
    })
</script>

<!---input product_quantity-->
<script>
    $("#comboTable").on('input', '.product_quantity', function () {
        var productQty = $(this).val();
        //console.log(productQty)
        var unitPrice = parseInt($(this).closest('tr').find('.product_unitprice').text());
        var subtotal = productQty * unitPrice;
        //replace subtotal
        $(this).closest('tr').find('.product_subtotal').text(subtotal);

        //find closest row-product-id
        var row_product_id = $(this).closest('tr').find('.row_product_id').val();
        var idQty = row_product_id+'-'+productQty;
        $(this).closest('tr').find('.row_product_idQty').val(idQty);

        total_calculate();
        total_purchase();
    });
</script>

<!---plus-minus--qty-->
<script>
    
    $(document).on('click', '.plusQty', function() {
        var product_quantity = parseInt($(this).closest('tr').find('.product_quantity').val());
        product_quantity++;
        $(this).closest('tr').find('.product_quantity').val(product_quantity)

        var unitPrice = parseInt($(this).closest('tr').find('.product_unitprice').text());
        var subtotal = product_quantity * unitPrice;
        
        //replace subtotal
        $(this).closest('tr').find('.product_subtotal').text(subtotal);

        //find closest row-product-id
        var row_product_id = $(this).closest('tr').find('.row_product_id').val();
        var idQty = row_product_id+'-'+product_quantity;
        $(this).closest('tr').find('.row_product_idQty').val(idQty);

        total_calculate();
        total_purchase();
    });

    //minusQty
    $(document).on('click', '.minusQty', function() {
        var product_quantity = parseInt($(this).closest('tr').find('.product_quantity').val());
        if (product_quantity > 1) {
            product_quantity--;
            $(this).closest('tr').find('.product_quantity').val(product_quantity)
            
            var unitPrice = parseInt($(this).closest('tr').find('.product_unitprice').text());
            var subtotal = product_quantity * unitPrice;
            //replace subtotal
            $(this).closest('tr').find('.product_subtotal').text(subtotal);

            //find closest row-product-id
            var row_product_id = $(this).closest('tr').find('.row_product_id').val();
            var idQty = row_product_id+'-'+product_quantity;
            $(this).closest('tr').find('.row_product_idQty').val(idQty);

            total_calculate();
            total_purchase();
        }
    });
</script>

<!---btn-delete-->
<script>
    $("#comboTable").on('click', '.btnDelete', function () {
        $(this).closest('tr').remove();
        total_calculate();
        total_purchase();
    });
</script>

<!---total_calculate()-common-fxn--->
<script>
    function total_calculate() {
        var total_before_discount = 0;
        var total_after_discount = 0;
        //loop through subtotal
        $("#comboTable .product_subtotal").each(function() {
            //chck if not empty
            var subtotal = $(this).text() != "" ? parseFloat($(this).text()) : 0.00;
            total_before_discount += subtotal; //add that value

        })
        //assign to total span
        // $(".total_before_discount").text(total.toFixed(2)) //to 2dp

        //check for discount
        var discount_value = $('#discount_value').val() != "" ? parseFloat($('#discount_value').val()) : 0;
        var discount_type = $('#discount_type').val();
        if (discount_type == "fixed") {
            total_after_discount = total_before_discount - discount_value;
        }
        if (discount_type == "percentage") {
            discount_value = (discount_value * total_before_discount) / 100;
            total_after_discount = total_before_discount - discount_value;
        }
        
        $(".total_before_discount").text(total_before_discount.toLocaleString()); //for comma after 3 zeros
        $(".total_after_discount").text(total_after_discount.toLocaleString());
        $("#total_before_discount_input").val(total_before_discount);
        $("#total_after_discount_input").val(total_after_discount);
    }
    total_calculate()
</script>

<!--total_purchase--->
<script>
    function total_purchase() {
        var total_purchase = 0;
    
        //loop through subtotal
        $("#comboTable .row_product_purchaseprice").each(function() {
            //chck if not empty
            var purchase_price = $(this).val() != "" ? parseFloat($(this).val()) : 0;
            var product_quantity = parseInt($(this).closest('tr').find('.product_quantity').val());
            var final_purchase_price = purchase_price * product_quantity;
            total_purchase += final_purchase_price; //add that value

        })
        //assign to total span
        $("#total_purchase").val(total_purchase);
    }
    total_purchase()
</script>

<script>
    // function checkDuplicate($product_id) {
        
    //     //loop through subtotal
    //     $("#comboTable .row_product_id").each(function() {
    //        if ($(this).val() == $product_id) {

    //        }

    //     })
    //     //assign to total span
    //     $("#total_purchase").val(total_purchase);
    // }
    // total_purchase()
</script>
    
@endsection