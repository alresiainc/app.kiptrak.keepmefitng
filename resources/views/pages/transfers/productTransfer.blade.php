@extends('layouts.design')
@section('title')Products Transfer @endsection
@section('extra_css')
<style>
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
</style>
@endsection
@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Products Transfer from '{{ $from_warehouse->name }}' to '{{ $to_warehouse->name }}'</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active" data-bs-toggle="modal" data-bs-target="#productTransfer" style="cursor: pointer;">Create Products Transfer</li>
        <li class="breadcrumb-item"><a href="{{ route('allProductTransfers') }}">Products Transfer List</a></li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  
  <section class="users-list-wrapper">
    <div class="users-list-filter px-1">
      
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
  @if(Session::has('empty_error'))
  <div class="alert alert-danger mb-3 text-center">
      {{Session::get('empty_error')}}
  </div>
  @endif

  <section>
    <div class="row">
      <div class="col-md-12">
        <form class="row g-3 needs-validation" action="{{ route('productTransferPost', ['from_warehouse_unique_key'=>$from_warehouse->unique_key, 'to_warehouse_unique_key'=>$to_warehouse->unique_key]) }}" method="POST">@csrf
        <div class="card">
          <div class="card-body pt-3">
          
            <div class="col-md-12">
                <label for="" class="form-label">Select Product *</label>
                <select name="product" id="product" data-live-search="true" class="custom-select form-control border @error('product') is-invalid @enderror" id="">
                  <option value="">Nothing Selected</option>
                  
                  @foreach ($products as $product)
                      <!---1-30-3000--->
                      <option value="{{ $product->code }}|{{ $product->name }}|{{ $product->id }}|{{ $product->sale_price }}|{{ $from_warehouse->stock_available_by_warehouse($product->id) }}|{{ $to_warehouse->stock_available_by_warehouse($product->id) }}">
                          {{ $product->code }} | {{ $product->name }} | Stock: {{ $from_warehouse->stock_available_by_warehouse($product->id) }}
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

          </div>
        </div>

        <div class="card">
          <div class="card-body pt-3">
          
          <div class="table table-responsive">
            <table id="products-table" class="table caption-top" style="width:100%">
                <caption class="fw-bolder">Transfer Table *</caption>
              <thead>
                  <tr>
                      <th>Product Name</th>
                      <th>Qty avail. in {{ $from_warehouse->name }}</th>
                      <th>Qty transfer to {{ $to_warehouse->name }}</th>
                      <th>Qty avail. in {{ $to_warehouse->name }}</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                {{-- <tr>
                    <td>product name1 product name2 product name3</td>
                    <td style="width: 150px;" class="qty_avail_in_from">100</td>
                    <td style="width: 150px;"><input type="number" class="form-control qty_transfer" value="1"></td>
                    <td style="width: 150px;" class="qty_avail_in_to">11</td>
                    <td><div><i class="bi bi-trash"></i></div></td>
                </tr> --}}
                  
              </tbody>
          </table>
          </div>
          </div>
        </div>

            <div class="text-end submitbtn" style="display: none;">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </div>
        </form>
      </div>
    </div>
  </section>

</main><!-- End #main -->

<!-- Modal -->
<div class="modal fade productTransfer" id="productTransfer" tabindex="-1" aria-labelledby="productTransferLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Product Transfer Setup</h1>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="productTransferForm" action="{{ route('productTransferSetupPost') }}" method="POST">@csrf
                <div class="modal-body">
  
                    <div class="d-grid mb-3">
                        <label for="" class="form-label text-dark">From Warehouse</label>
                        <select name="from_warehouse" class="custom-select country form-control border @error('from_warehouse') is-invalid @enderror" id="">
                          <option value="">Nothing Selected</option>
                          @if (count($warehouses) > 0)
                              @foreach ($warehouses as $warehouse)
                              <option value="{{ $warehouse->unique_key }}">{{ $warehouse->name }}</option>
                              @endforeach
                          @endif
                        </select>
                        @error('from_warehouse')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
  
                    <div class="d-grid mb-2">
                      <label for="" class="form-label text-dark">To Warehouse</label>
                      <select name="to_warehouse" class="custom-select country form-control border @error('to_warehouse') is-invalid @enderror" id="">
                        <option value="">Nothing Selected</option>
                        @if (count($warehouses) > 0)
                            @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->unique_key }}">{{ $warehouse->name }}</option>
                            @endforeach
                        @endif
                      </select>
                      @error('to_warehouse')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary productTransferBtn">Submit</button>
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
    
    var productArr = product.split('|');
    var code = productArr[0];
    var name = productArr[1];
    var id = productArr[2];
    var unitprice = productArr[3];
    var qtyAvailInFrom = productArr[4];
    var qtyAvailInTo = productArr[5];
    
    // console.log(productArr)

    var productText = '';

    var start = '<tr>';
    var productName = '<input type="hidden" name="product_id[]" value="'+id+'"><td scope="row">'+name+'</td>';

    var qty_avail_in_from = '<td scope="row"><input type="hidden" class="input_qty_avail_in_from" value="'+qtyAvailInFrom+'"><input type="number" name="qty_avail_in_from[]" class="qty_avail_in_from border-0" value="'+qtyAvailInFrom+'" readonly></td>';
    
    var quantity_transfer = '<td style="width:150px"><input type="number" name="product_qty[]" class="form-control product-qty" value="0"></td>';

    var qty_avail_in_to = '<td scope="row"><input type="hidden" class="input_qty_avail_in_to" value="'+qtyAvailInTo+'"><input type="number" name="qty_avail_in_to[]" class="qty_avail_in_to border-0" value="'+qtyAvailInTo+'" readonly></td>';

    var btnDelete = '<td class="btnDelete text-danger mt-1 mb-1"><i class="bi bi-trash"></i></td>';
    
    
    var end = '</tr>';

    var row = start + productName + qty_avail_in_from + quantity_transfer + qty_avail_in_to + btnDelete + end;

    $("#products-table > tbody").append(row);

    if ($('.submitbtn').hide()) {
        $('.submitbtn').show();
    }

    //$("#orderTable > tbody").append("<tr><th scope='row'>"+name+"</th><td><input type='hidden' name='product_id[]' value='"+id+"'>"+code+"</td><td style='width:150px'><input type='number' name='product_qty[]' class='form-control product-qty' value='1'></td><td style='width:150px'><input type='number' name='unit_price[]' class='form-control unit-price' value='"+unitprice+"'></td><td class='total'>"+unitprice+"</td><td class='btnDelete btn btn-danger btn-sm mt-1 mb-1'>Remove</td></tr>");
});
</script>

<script>
    $("#products-table").on('input', '.product-qty', function () {
        var productQty = parseInt($(this).val());
        
        if (productQty > 0) {
            //add to & remove from
            var input_qty_avail_in_to = $(this).closest('tr').find('.input_qty_avail_in_to');
            var total_qty_avail_in_to = parseInt(input_qty_avail_in_to.val()) + productQty;
            //replace total
            $(this).closest('tr').find('.qty_avail_in_to').val(total_qty_avail_in_to);

            //remove from
            var input_qty_avail_in_from = $(this).closest('tr').find('.input_qty_avail_in_from');
            var total_qty_avail_in_from = parseInt(input_qty_avail_in_from.val()) - productQty;
            //replace total
            $(this).closest('tr').find('.qty_avail_in_from').val(total_qty_avail_in_from);
            
        }
        if (productQty < 0 || productQty == 0) {
            //remove to & add from
            //var productQty2 = Math.abs(productQty)
            var input_qty_avail_in_to = $(this).closest('tr').find('.input_qty_avail_in_to').val();
            var input_qty_avail_in_from = $(this).closest('tr').find('.input_qty_avail_in_from').val();
            
            $(this).closest('tr').find('.qty_avail_in_to').val(parseInt(input_qty_avail_in_to));
            $(this).closest('tr').find('.qty_avail_in_from').val(parseInt(input_qty_avail_in_from));
        }
        
    });
</script>

<script>
    $("#products-table").on('click', '.btnDelete', function () {
        $(this).closest('tr').remove();
    });
</script>

  
  <?php //if(count($errors) > 0) : ?>
    <script>
        // $( document ).ready(function() {
        //     $('#importModal').modal('show');
        // });
    </script>
  <?php //endif ?>

  @if ( $errors->has('from_warehouse') || $errors->has('to_warehouse') )
    <script type="text/javascript">
        $( document ).ready(function() {
             $('.productTransfer').modal('show');
        });
    </script>
@endif

@endsection