@extends('layouts.design')
@section('title')Edit Order @endsection
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
      <h1>Edit Order</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Edit Order: {{ $order->orderCode($order->id) }}</li>
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
              
              <form class="row g-3 needs-validation" action="{{ route('editOrderPost', $order->unique_key) }}" method="POST"
              enctype="multipart/form-data">@csrf
              <div class="col-md-12 mb-3">The field labels marked with * are required input fields.</div>

                <div class="col-md-6">
                    <label for="" class="form-label">Select Customer *</label>

                    <div class="d-flex">

                        <select name="customer" data-live-search="true" class="custom-select form-control border @error('customer') is-invalid @enderror" id="">
                        <option value="{{ $order->customer->id }}" selected>{{ $order->customer->firstname.' '.$order->customer->lastname }}</option>
    
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
            
                <!----table--->
                <div class="col-md-12">
                    <table id="orderTable" class="table caption-top">
                        <caption class="fw-bolder">Order Table *</caption>
                        <thead>
                          <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Customer Action</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Total</th>
                            <th scope="col"><i class="bi bi-trash fw-bolder"></i></th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($packages as $package)
                            <tr>
                                <input type='hidden' name='outgoing_stock_id[]' value=''>
                                <th scope='row'>{{ $package['product']->name }}</th>

                                <td><input type='hidden' name='product_id[]' value='{{ $package['product']->id }}'>
                                    <select name="customer_acceptance_status[]" id="" class="form-control">
                                        <option value="{{ $package['customer_acceptance_status'] }}" selected>
                                            {{ $package['customer_acceptance_status'] == 'accepted' ? 'Accepted' : 'Declined' }}
                                        </option>
                                        <option value="accepted">Accepted</option>
                                        <option value="rejected">Declined</option>
                                    </select>
                                </td>
                                
                                <td style='width:150px'><input type='number' name='product_qty[]' class='form-control product-qty'
                                    value='{{ $package['quantity_removed'] }}'>
                                </td>

                                <td style='width:150px'><input type='number' name='unit_price[]' class='form-control unit-price'
                                    value='{{ $package['amount_accrued']}}' readonly>
                                </td>

                                <td class="total">{{ $package['amount_accrued'] * $package['quantity_removed'] }}</td>
                                <td class='btnDelete btn btn-danger btn-sm mt-1 mb-1' style="visibility: hidden;">Remove</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!----orderbump--->
                @if (isset($orderbump_outgoingStock))
                <div class="col-md-12">
                    <label for="" class="form-label">Order-bump | {{ $orderbump_outgoingStock->customer_acceptance_status == 'accepted' ? 'Accepted' : 'Declined' }} |
                        {{ $orderbump_outgoingStock->product->name }}
                    </label>
                    <select name="orderbump_product" data-live-search="true" class="custom-select form-control border @error('product') is-invalid @enderror" id="">
                    
                      <option value="{{ $orderbump_outgoingStock->customer_acceptance_status == 'accepted' ? $orderbump_outgoingStock->product_id : '' }}" selected>
                        {{ $orderbump_outgoingStock->customer_acceptance_status == 'accepted' ? $orderbump_outgoingStock->product->name : 'Nothing Selected' }}
                      </option>

                      <option value="">Nothing Selected</option>
                      
                      @foreach ($products as $product)
                          <!---1-30-3000--->
                          <option value="{{ $product->id }}">
                              {{ $product->code }} | {{ $product->name }} | Stock: {{ $product->stock_available() }}
                              @if (isset($product->purchase_price)) | Purchase Price {{ $product->purchase_price }} @endif
                              @if (isset($product->sale_price)) | Selling Price {{ $product->sale_price }} @endif
                          </option>
                      @endforeach
                          
                    </select>
                  </div>
                @endif

                <!----upsell--->
                @if (isset($upsell_outgoingStock))
                <div class="col-md-12">
                    <label for="" class="form-label">Upsell | {{ $upsell_outgoingStock->customer_acceptance_status == 'accepted' ? 'Accepted' : 'Declined' }} |
                        {{ $upsell_outgoingStock->product->name }}
                    </label>
                    <select name="upsell_product" data-live-search="true" class="custom-select form-control border @error('product') is-invalid @enderror" id="">
                    
                      <option value="{{ $upsell_outgoingStock->customer_acceptance_status == 'accepted' ? $upsell_outgoingStock->product_id : '' }}" selected>
                        {{ $upsell_outgoingStock->customer_acceptance_status == 'accepted' ? $upsell_outgoingStock->product->name : 'Nothing Selected' }}
                      </option>

                      <option value="">Nothing Selected</option>
                      
                      @foreach ($products as $product)
                          <!---1-30-3000--->
                          <option value="{{ $product->id }}">
                              {{ $product->code }} | {{ $product->name }} | Stock: {{ $product->stock_available() }}
                              @if (isset($product->purchase_price)) | Purchase Price {{ $product->purchase_price }} @endif
                              @if (isset($product->sale_price)) | Selling Price {{ $product->sale_price }} @endif
                          </option>
                      @endforeach
                          
                    </select>
                  </div>
                @endif
                
                <div class="col-md-6">
                    <label for="" class="form-label">Order Status *</label>
                    <select name="order_status" id="order_status" data-live-search="true" class="custom-select form-control border @error('order_status') is-invalid @enderror" id="">
                      
                      <option value="{{ $order->status }}" selected>
                        @if ($order->status=='new')
                            New
                        @elseif($order->status=='delivered_and_remitted')
                            Delivered and Remitted
                        @elseif($order->status=='delivered_not_remitted')
                            Delivered Not Remitted
                        @elseif($order->status=='cancelled')
                            Cancelled
                        @elseif($order->status=='pending')
                            Pending
                        @endif
                      </option>
                      <option value="delivered_and_remitted">Delivered and Remitted</option>
                      <option value="delivered_not_remitted">Delivered Not Remitted</option>
                      <option value="cancelled">Cancelled</option>
                      <option value="pending">Pending</option>
                      <option value="new" selected>New</option>
            
                    </select>
                    @error('order_status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">Warehouse | Optional</label>
                    <select name="warehouse_id" id="warehouse_id" data-live-search="true" class="custom-select form-control border @error('warehouse_id') is-invalid @enderror" id="">
                      
                      <option value="{{ isset($order->warehouse_id) ? $order->warehouse->name : '' }}">
                        {{ isset($order->warehouse_id) ? $order->warehouse->name : 'Nothing Selected' }}
                      </option>
                      @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">
                            {{ $warehouse->name }}
                        </option>
                      @endforeach
            
                    </select>
                    @error('warehouse_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="" class="form-label">Note | Optional</label>
                    <textarea name="note" id="" name="note" class="form-control @error('note') is-invalid @enderror" cols="30" rows="5">{{ isset($order->order_note) ? $order->order_note : ''}}</textarea>
                    
                    @error('note')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Update Order</button>
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
    var customerAcceptanceStatus = 'accepted';
    var quantityRemoved = 10;
    // console.log(productArr)

    var productText = '';

    var start = '<tr>';
    var productName = '<input type="hidden" name="outgoing_stock_id[]" value=""><th scope="row">'+name+'</th>';
    var customer_acceptance_status = '<td>';
        customer_acceptance_status += '<input type="hidden" name="product_id[]" value="'+id+'">';
        customer_acceptance_status += '<select name="customer_acceptance_status[]" id="" class="form-control">';
        customer_acceptance_status += '<option value="accepted" selected>Accepted</option>';
        customer_acceptance_status += '<option value="rejected">Declined</option>';
        customer_acceptance_status += '</select>';
        customer_acceptance_status +='</td>';
                                
    var quantity_removed = '<td style="width:150px"><input type="number" name="product_qty[]" class="form-control product-qty" value="1"></td>';

    var unit_price = '<td style="width:150px"><input type="number" name="unit_price[]" class="form-control unit-price" value="'+unitprice+'" readonly></td>';

    var total = '<td class="total">'+unitprice+'</td>';
    var btnDelete = '<td class="btnDelete btn btn-danger btn-sm mt-1 mb-1">Remove</td>';
    
    var end = '</tr>';

    var row = start + productName + customer_acceptance_status + quantity_removed + unit_price + total + btnDelete + end;

    $("#orderTable > tbody").append(row);

    //$("#orderTable > tbody").append("<tr><th scope='row'>"+name+"</th><td><input type='hidden' name='product_id[]' value='"+id+"'>"+code+"</td><td style='width:150px'><input type='number' name='product_qty[]' class='form-control product-qty' value='1'></td><td style='width:150px'><input type='number' name='unit_price[]' class='form-control unit-price' value='"+unitprice+"'></td><td class='total'>"+unitprice+"</td><td class='btnDelete btn btn-danger btn-sm mt-1 mb-1'>Remove</td></tr>");
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