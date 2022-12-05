@extends('layouts.design')
@section('title')Edit Sale @endsection
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
      <h1>Edit Sale</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Edit Sale</li>
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

    <section>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              
              <form class="row g-3 needs-validation" action="{{ route('editSalePost', $sale->unique_key) }}" method="POST"
              enctype="multipart/form-data">@csrf
              <div class="col-md-12 mb-3">The field labels marked with * are required input fields.</div>

                <div class="col-md-3 d-none">
                  <label for="" class="form-label">Sale Code *</label>
                  <input type="text" name="sale_code" class="form-control @error('sale_code') is-invalid @enderror" value="">
                  @error('sale_code')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                <div class="col-md-4">
                    <label for="" class="form-label">Select Customer *</label>

                    <div class="d-flex">

                        <select name="customer" data-live-search="true" class="custom-select form-control border @error('customer') is-invalid @enderror" id="">
                        <option value="{{ $sale->customer->id }}" selected>{{ $sale->customer->firstname.' '.$sale->customer->lastname }}</option>
    
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->firstname.' '.$customer->lastname }}
                            </option>
                        @endforeach
                            
                        </select>
                        
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCustomer">
                            <i class="bi bi-plus"></i></button>
                    </div>
                    @error('customer')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="" class="form-label">Select Warehouse *</label>
                    <select name="warehouse" data-live-search="true" class="custom-select form-control border @error('warehouse') is-invalid @enderror">
                      <option value="{{ $sale->warehouse->id }}" selected>
                        {{ $sale->warehouse->name }}</option>
  
                      @foreach ($warehouses as $warehouse)
                          <option value="{{ $warehouse->id }}">
                              {{ $warehouse->name }}
                          </option>
                      @endforeach
                          
                    </select>
                    @error('warehouse')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Date</label>
                  <input type="date" name="sale_date" class="form-control @error('sale_date') is-invalid @enderror" value="{{ $sale->sale_date }}">
                  @error('sale_date')
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
                        <option value="{{ $product->code }}|{{ $product->name }}|{{ $product->id }}|{{ $product->sale_price }}">
                            {{ $product->code }} | {{ $product->name }} | Stock: {{ $product->stock_available() }}
                            @if (isset($product->purchase_price)) | Purchase Price {{ $product->purchase_price }} @endif
                            @if (isset($product->sale_price)) | Selling Price {{ $product->sale_price }} @endif
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
                            @foreach ($sales as $sale)
                            <tr>
                                <input type='hidden' name='outgoing_stock_id[]' value='{{ $sale->outgoing_stock_id }}'>
                                <th scope='row'>{{ $sale->product->name }}</th>

                                <td><input type='hidden' name='product_id[]' value='{{ $sale->product->id }}'>
                                    {{ $sale->product->code }}</td>

                                <td style='width:150px'><input type='number' name='product_qty[]' class='form-control product-qty'
                                    value='{{ $sale->product_qty_sold }}'>
                                </td>

                                <td style='width:150px'><input type='number' name='unit_price[]' class='form-control unit-price'
                                    value='{{ $sale->product->sale_price }}'>
                                </td>

                                <td class="total">{{ $sale->product->sale_price * $sale->product_qty_sold }}</td>
                                <td class='btnDelete btn btn-danger btn-sm mt-1 mb-1'>Remove</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                
                <div class="col-md-4">
                    <label for="" class="form-label">Order Status *</label>
                    <select name="sale_status" id="sale_status" data-live-search="true" class="custom-select form-control border @error('payment_type') is-invalid @enderror" id="">
                      
                      <option value="{{ $sale->status }}" selected>{{ $sale->status }}</option>
                      <option value="pending">Pending</option>
                      <option value="completed">Completed</option>
            
                    </select>
                    @error('sale_status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="" class="form-label">Payment Status *</label>
                    <select name="payment_status" id="payment_status" data-live-search="true" class="custom-select form-control border @error('sale_status') is-invalid @enderror" id="">
                      <option value="{{ $sale->payment_status }}">{{ $sale->payment_status }}</option>
                      <option value="due">Due</option>
                      <option value="partial">Partial</option>
                      <option value="paid">Paid</option>
                        
                    </select>
                    @error('payment_status')
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
                    <textarea name="note" id="" name="note" class="form-control @error('note') is-invalid @enderror" cols="30" rows="10">
                        {{ isset($sale->note) ? $sale->note : ''}}
                    </textarea>
                    
                    @error('note')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Save Sale</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- End Multi Columns Form -->
              
            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

<!-- Modal -->
<div class="modal fade" id="addCustomer" tabindex="-1" aria-labelledby="addCustomerLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add
                    Customer</h1>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('addCustomerPost') }}" method="POST" enctype="multipart/form-data">@csrf
                <div class="modal-body">
                    
                    <div class="d-grid mb-2">
                        <label for="">First Name</label>
                        <input type="text" name="firstname" class="form-control" placeholder="">
                    </div>

                    <div class="d-grid mb-2">
                        <label for="">Last Name</label>
                        <input type="text" name="lastname" class="form-control" placeholder="">
                    </div>
                    <div class="d-grid mb-2">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="">
                    </div>

                    <div class="d-grid mb-2">
                        <label for="">Phone Number</label>
                        <input type="text" name="phone_number" class="form-control"
                            placeholder="">
                    </div>

                    <div class="d-grid mb-2">
                        <label for="">Whatsapp Number</label>
                        <input type="text" name="whatsapp_phone_number" class="form-control"
                            placeholder="">
                    </div>

                    <div class="d-grid mb-2">
                        <label for="">Address</label>
                        <input type="text" name="delivery_address" class="form-control" placeholder="">
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

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