@extends('layouts.design')
@section('title')Add Purchase @endsection
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
      <h1>Add Purchase</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Add Purchase</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

      </div>
    </section>

    @if(Session::has('success'))
    <div class="alert alert-success mb-3 text-center">
        {{Session::get('success')}}
    </div>
    @endif

    @if(Session::has('duplicate_error'))
    <div class="alert alert-danger mb-3 text-center">
        {{Session::get('duplicate_error')}}
    </div>
    @endif

    <section>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              
              <form class="row g-3 needs-validation" action="{{ route('addPurchasePost') }}" method="POST"
              enctype="multipart/form-data">@csrf
              <div class="col-md-12 mb-3">The field labels marked with * are required input fields.</div>
                <div class="col-md-4">
                  <label for="" class="form-label">Purchase Code *</label>
                  <input type="text" name="purchase_code" class="form-control @error('purchase_code') is-invalid @enderror" value="{{ $purchase_code }}">
                  @error('purchase_code')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                <div class="col-md-4">
                    <label for="" class="form-label">Select Supplier *</label>
                    <select name="supplier" data-live-search="true" class="custom-select form-control border @error('supplier') is-invalid @enderror" id="">
                      <option value="">Nothing Selected</option>
  
                      @foreach ($suppliers as $supplier)
                          <option value="{{ $supplier->id }}">
                              {{ $supplier->company_name }}
                          </option>
                      @endforeach
                          
                    </select>
                    @error('supplier')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Date</label>
                  <input type="date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" id="" >
                  @error('purchase_date')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-12">
                  <label for="" class="form-label">Select Product *</label>
                  <select name="product" id="product" data-live-search="true" class="custom-select form-control border @error('product') is-invalid @enderror" id="">
                    <option value="">Nothing Selected</option>

                    @foreach ($products as $product)
                        <!---1-30-3000--->
                        <option value="{{ $product->code }}|{{ $product->name }}|{{ $product->id }}|{{ $product->purchase_price }}">
                            {{ $product->code }} | {{ $product->name }} | Stock: {{ $product->stock_available() }}
                            @if (isset($product->purchase_price)) | Purchase Price: {{ $product->purchase_price }} @endif
                            @if (isset($product->sale_price)) | Selling Price: {{ $product->sale_price }} @endif
                        </option>
                    @endforeach
                        
                  </select>
                  @error('product')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
            
                <div class="col-md-12">
                    <table id="orderTable" class="table caption-top">
                        <caption class="fw-bolder">Order Table *</caption>
                        <thead>
                          <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Code</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Total</th>
                            <th scope="col"><i class="bi bi-trash fw-bolder"></i></th>
                          </tr>
                        </thead>
                        <tbody>
                          
                        </tbody>
                    </table>
                </div>

                
                <div class="col-md-4">
                    <label for="" class="form-label">Payment Type *</label>
                    <select name="payment_type" id="payment_type" data-live-search="true" class="custom-select form-control border @error('payment_type') is-invalid @enderror" id="">
                      <option value="cash">Cash</option>
                      <option value="card">Card</option>
                      <option value="cheque">Cheque</option>
                      <option value="bank_transfer">Bank Transfer</option>
                        
                    </select>
                    @error('payment_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="" class="form-label">Purchase Status *</label>
                    <select name="purchase_status" id="purchase_status" data-live-search="true" class="custom-select form-control border @error('purchase_status') is-invalid @enderror" id="">
                      <option value="received">Received</option>
                      <option value="partial">Partial</option>
                      <option value="pending">Pending</option>
                      <option value="ordered">Ordered</option>
                        
                    </select>
                    @error('purchase_status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="" class="form-label">Attach File
                        <i class="bi bi-question-circle text-info border rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top" title="Only jpg, jpeg, png, pdf, csv, docx, xlsx, gif, svg, webp and txt file is supported"></i>
                      </label>
                    <input type="file" name="attached_document" class="form-control @error('attached_document') is-invalid @enderror" placeholder="" >
                    @error('attached_document')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4 d-none">
                    <label for="" class="form-label">Order Tax</label>
                    <input type="text" name="order_tax" class="form-control @error('order_tax') is-invalid @enderror" placeholder="" >
                    @error('order_tax')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4 d-none">
                    <label for="" class="form-label">Discount</label>
                    <input type="text" name="discount" class="form-control @error('discount') is-invalid @enderror" placeholder="" >
                    @error('discount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4 d-none">
                    <label for="" class="form-label">Shipping Cost</label>
                    <input type="text" name="shipping_cost" class="form-control @error('shipping_cost') is-invalid @enderror" placeholder="" >
                    @error('shipping_cost')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="" class="form-label">Note</label>
                    <textarea name="note" id="" name="note" class="form-control @error('note') is-invalid @enderror" cols="30" rows="10"></textarea>
                    
                    @error('note')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Save Purchase</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- End Multi Columns Form -->
              
            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

@endsection

@section('extra_js')

<script>
    $('#product').change(function(){ 
    var product = $(this).val();
    // {{ $product->code }}|{{ $product->name }}|{{ $product->stock_available() }}|{{ $product->price }}
    // alert(product)
    var productArr = product.split('|');
    var code = productArr[0];
    var name = productArr[1];
    var id = productArr[2];
    var unitprice = productArr[3];
    // console.log(productArr)

    var productText = '';
    $("#orderTable > tbody").append("<tr><th scope='row'>"+name+"</th><td><input type='hidden' name='product_id[]' value='"+id+"'>"+code+"</td><td style='width:150px'><input type='number' name='product_qty[]' class='form-control product-qty' value='1'></td><td style='width:150px'><input type='number' name='unit_price[]' class='form-control unit-price' value='"+unitprice+"'></td><td class='total'>"+unitprice+"</td><td class='btnDelete btn btn-danger btn-sm mt-1 mb-1'>Remove</td></tr>");
});
</script>

<script>
    $("#orderTable").on('click', '.btnDelete', function () {
        $(this).closest('tr').remove();
    });
</script>

<script>
    $("#orderTable").on('click', '.editOrderBtn', function () {
        var product = $(this).attr('data-product');
        console.log(product)
    });
</script>

<script>
    $("#orderTable").on('input', '.product-qty', function () {
        var productQty = $(this).val();
        //console.log(productQty)
        var unitPrice = parseInt($(this).closest('tr').find('.unit-price').val());
        var total = productQty * unitPrice;
        //replace total
        $(this).closest('tr').find('.total').text(total);
    });

    $("#orderTable").on('input', '.unit-price', function () {
        var unitPrice = $(this).val();
        //console.log(productQty)
        var productQty = parseInt($(this).closest('tr').find('.product-qty').val());
        var total = productQty * unitPrice;
        //replace total
        $(this).closest('tr').find('.total').text(total);
    });
</script>







@endsection