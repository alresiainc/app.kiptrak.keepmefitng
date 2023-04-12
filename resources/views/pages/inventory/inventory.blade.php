@extends('layouts.design')
@section('title')Inventory @endsection

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
    <h1>Inventory Management</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Inventory Management</li>
      </ol>
    </nav>
  </div>
  <!-- End Page Title -->

  <!-- Alert -->
  <div id="liveAlertPlaceholder d-none"></div>
  <!-- /Alert -->
  
  <div class="text-lg-end text-center mb-3">
    <div class="btn-group" role="group" aria-label="Basic example">
      
      <a href="{{ route('inventoryDashboardToday') }}"><button type="button" class="btn btn-sm btn-light-success {{ $record == 'today' ? 'active' : '' }}">
        Today
      </button></a>

      <a href="{{ route('inventoryDashboardWeekly') }}"><button type="button" class="btn btn-sm btn-light-success {{ $record == 'weekly' ? 'active' : '' }}">
        This Week
      </button></a>

      <a href="{{ route('inventoryDashboardMonthly') }}"><button type="button" class="btn btn-sm btn-light-success {{ $record == 'monthly' ? 'active' : '' }}">
        This Month
      </button></a>

      <a href="{{ route('inventoryDashboardYearly') }}"><button type="button" class="btn btn-sm btn-light-success {{ $record == 'yearly' ? 'active' : '' }}">
        This Year
      </button></a>

      <a href="/"><button type="button" class="btn btn-sm btn-light-success {{ $record == 'all' ? 'active' : '' }}">
        All
      </button></a>
      
    </div>
  </div>
  <hr />

  <section class="section m-0">
    <div class="row">

      <!-- Total Products Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card bg-4">
            <a href="{{ route('allProductInventory') }}" class="text-white">
              <div class="card-body p-2">
                <div class="d-flex align-items-center justify-content-between">
                <div class="text-start">
                    <h2 class="fw-bold">{{ count($total_products) }}</h2>
                    <small class="text-uppercase small pt-1 fw-bold">Total Products</small>
                </div>
                <div class="rounded-circle float-end">
                    <i class="bi bi-box display-1 text-light-black"></i>
                </div>
                </div>
              </div>
            </a>

        </div>
      </div>
      <!-- Total Products Card -->

      <!-- In-Stock-Products Card -->
      <div class="col-lg-3 col-md-6" data-bs-toggle="modal" data-bs-target="#inStock" style="cursor: pointer;">
        
          <div class="card bg-2">
            
            <div class="card-body p-2">
              <div class="d-flex align-items-center justify-content-between">
                <div class="text-start">
                  <h2 class="fw-bold">{{ count($total_products) - count($out_of_stock_products) }}</h2>
                  <small class="text-uppercase small pt-1 fw-bold">In-Stock Products</small>
                </div>
                <div class="rounded-circle float-end">
                  <i class="bi bi-calendar-minus display-1 text-light-black"></i>
                </div>
              </div>
            </div>
            
          </div>

      </div>
      <!-- In-Stock-Products Card -->

      <!-- Out-Of-Stock-Products Card -->
      <div class="col-lg-3 col-md-6" style="cursor: pointer;">
        <div class="card bg-3">
          <a href="{{ route('allProductInventory', 'out_of_stock') }}" class="text-white">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-between">
              <div class="text-start">
                <h2 class="fw-bold">{{ count($out_of_stock_products) }}</h2>
                <small class="text-uppercase small pt-1 fw-bold">Out-Of-Stock Products</small
                >
              </div>
              <div class="rounded-circle float-end">
                <i class="bi bi-cart-check display-1 text-light-black"></i>
              </div>
            </div>
          </div>
          </a>
        </div>
      </div>
      <!-- Out-Of-Stock-Products Card -->

      <!-- Purchases & Sales Card -->
      <div class="col-lg-3 col-md-6" style="cursor: pointer;">
        <div class="card bg-1">
          <a href="javascript:void(0);" class="text-white">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-between">
              <div class="text-start">
                <small class="text-uppercase small pt-1 fw-bold">Total Purchases</small>
              </div>

              <div class="text-end">
                {{ $purchases_amount_paid }}
              </div>
            </div>
          </div>
          
          </a>
        </div>
        <div class="card bg-2">
          <a href="javascript:void(0);" class="text-white">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-between">
              <div class="text-start">
                <small class="text-uppercase small pt-1 fw-bold">Total Sales</small>
              </div>

              <div class="text-end">
                {{ $sales_paid }}
              </div>
            </div>
          </div>
          
          </a>
        </div>
      </div>
      <!-- Out-Of-Stock-Products Card -->

      <!-- Warehouses Card -->
      <div class="col-lg-3 col-md-6 d-none">
        <div class="card bg-1">
          <div class="card-body p-2">
            <div class="d-flex justify-content-between align-items-center">
              <div class="text-start">
                <h2 class="fw-bold">{{ count($warehouses) }}</h2>
                <small class="text-uppercase small pt-1 fw-bold">Warehouses</small
                >
              </div>
              <div class="rounded-circle float-end">
                <i class="bi bi-house display-1 text-light-black"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Warehouses Card -->

      
    </div>

  </section>

  <section class="section m-0 d-none">
    <div class="row">
      <!-- Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card border-right-warning card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="border rounded shadow-sm px-2 me-2">
                <i class="bi bi-cash-stack display-1 text-light-black"></i>
              </div>
              <div class="text-start">
                <h2 class="fw-bold">{{ $currency }}{{ $sale_revenue }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Revenue</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Card -->
      <!-- Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card border-right-primary card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="border rounded shadow-sm px-2 me-2">
                <i class="bi bi-wallet2 display-1 text-light-black"></i>
              </div>
              <div class="text-start">
                <h2 class="fw-bold">{{ $currency }}{{ $total_expenses }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Expenses</small
                >
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Card -->

      <!-- Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card border-right-success card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="border rounded shadow-sm px-2 me-2">
                <i class="bi bi-cash-coin display-1 text-light-black"></i>
              </div>
              <div class="text-start">
                <h2 class="fw-bold">
                    @if ($profit_val > 0)
                  {{ $currency }}{{ $profit }}
                  @else
                  -{{ $profit }}
                  @endif
                </h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Profit</small
                >
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Card -->

      <!-- Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card border-right-danger card-right-border">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-start">
              <div class="border rounded shadow-sm px-2 me-2">
                <i
                  class="bi bi-receipt display-1 text-light-black"
                ></i>
              </div>
              <div class="text-start">
                <h2 class="fw-bold">{{ count($orders) }}</h2>
                <small class="text-uppercase text-muted small pt-1 fw-bold">Orders</small
                >
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Card -->
    </div>
  </section>

  <section class="section m-0 d-none">
    <div class="row">

        <!-- Total Suppliers Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card" style="border-radius: 10px; background-color: #caeb11;">
            <div class="card-body p-2">
                <div class="d-flex align-items-center justify-content-between">
                <div class="text-start">
                    <h2 class="fw-bold text-white">{{ count($suppliers) }}</h2>
                    <small class="text-uppercase text-white small pt-1 fw-bold">Suppliers</small>
                </div>
                <div class="rounded-circle float-end">
                    <i class="bi bi-truck display-1 text-light-black"></i>
                </div>
                </div>
            </div>
        </div>
      </div>
        <!-- End Total Suppliers Card -->

      <!-- In-Stock-Products Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card bg-primary" style="border-radius: 10px;">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-between">
              <div class="text-start">
                <h2 class="fw-bold text-white">{{ $currency }}{{ $purchase_sum }}</h2>
                <small class="text-uppercase text-white small pt-1 fw-bold">Purchases</small>
              </div>
              <div class="rounded-circle float-end">
                <i
                  class="bi bi-credit-card display-1 text-light-black"
                ></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Sales Card -->

      <!-- Sales Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card bg-danger" style="border-radius: 10px;">
          <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-between">
              <div class="text-start">
                <h2 class="fw-bold text-white">{{ count($customers) }}</h2>
                <small class="text-uppercase text-white small pt-1 fw-bold">Customers</small
                >
              </div>
              <div class="rounded-circle float-end">
                <i class="bi bi-person-check display-1 text-light-black"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Sales Card -->

      <!-- Warehouses Card -->
      <div class="col-lg-3 col-md-6">
        <div class="card bg-success" style="border-radius: 10px;">
          <div class="card-body p-2">
            <div class="d-flex justify-content-between align-items-center">
              <div class="text-start">
                <h2 class="fw-bold text-white">{{ $currency }}{{ $sales_sum }}</h2>
                <small class="text-uppercase text-white small pt-1 fw-bold">Sales</small
                >
              </div>
              <div class="rounded-circle float-end">
                <i class="bi bi-cart3 display-1 text-light-black"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Warehouses Card -->

    </div>

  </section>

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-top-border border-top-primary">
          <div class="card-body">
            <div class="card-title">Recently Added Products</div>

            <div class="row mb-3">
              <div class="col-lg-3 col-md-6">
                <label for="">Start Date</label>
                <input type="text" name="start_date" id="min" class="form-control filter">
              </div>

              <div class="col-lg-3 col-md-6">
                <label for="">End Date</label>
                <input type="text" name="end_date" id="max" class="form-control filter">
              </div>

              <div class="col-lg-3 col-md-6">
                <label for="">Category</label>
                <select id="filter-categoryname" type="select" class="custom-select border form-control filter">
                  <option value="">Nothing Selected</option>
                  @if (count($categories))
                      @foreach ($categories as $category)
                      <option value="{{ $category->name }}">{{ $category->name }}</option>
                      @endforeach
                  @endif
                  
    
                </select>
              </div>

              
            </div>

            <div class="table table-responsive">
              <table id="stock-table" class="table custom-table table-striped" style="width:100%">
                <thead>
                  <tr>
                    <th scope="col">Product Image</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Qty Added</th>
                    <th scope="col">Qty Removed</th>
                    <th scope="col">Qty Remaining(Stock)</th>
                    <th scope="col">Date Added</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($recently_products->count() > 0)
                      @foreach ($recently_products as $product)
                          
                          <tr>
                            <th scope="row">
                                <a
                                  href="{{ asset('/storage/products/'.$product->image) }}"
                                  data-fancybox="gallery"
                                  data-caption="{{ isset($product->name) ? $product->name : 'no caption' }}"
                                  >   
                                  <img src="{{ asset('/storage/products/'.$product->image) }}" width="50" class="img-thumbnail img-fluid"
                                  alt="{{$product->name}}" style="height: 30px;"></a>
                            </th>
                            <td>{{ $product->name }}</td>
                            <td data-categoryname="{{ $product->category->name }}" class="categoryname">{{ $product->category->name }}</td>
                            <td>{{ $product->purchases->sum('product_qty_purchased') }}</td>
                            <td>{{ $product->purchases->sum('product_qty_purchased') - $product->stock_available() }}</td>
                            <td>{{ $product->stock_available() }}</td>
                            <td>{{ $product->created_at->format('Y-m-d') }}</td>
                          </tr>
                          
                      @endforeach
                  @endif
                  
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>

  <hr />
  
</main>

<!-- Modal -->
<div class="modal fade" id="inStock" tabindex="-1" aria-labelledby="inStockLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h1 class="modal-title fs-5">Select Option</h1>
              <button type="button" class="btn-close"
                  data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          
          <div class="modal-body">
              
              <div class="d-grid mb-2">
                  <a href="{{ route('inStockProductsByWarehouse') }}" class="btn btn-dark">By WareHouse (major)</a>
              </div>

              <div class="d-grid mb-2">
                <a href="{{ route('inStockProductsByOtherAgents') }}" class="btn btn-dark">By Other Agents (minor)</a>
              </div>

          </div>
      </div>
  </div>
</div>

@endsection

@section('extra_js')

<script>
  $('.filter').change(function(){
        filter_function();
        //calling filter function each select box value change
  });

    $('table tbody tr').show(); //intially all rows will be shown

    function filter_function(){
        $('table tbody tr').hide(); //hide all rows

        var categorynameFlag = 0;
        var categorynameValue = $('#filter-categoryname').val();

        //date
        // var rangeFlag = 0;
        // var rangeValue = $('#filter-range').val();
        // var rangeminValue = $('#filter-range').find(':selected').attr('data-min');
        // var rangemaxValue = $('#filter-range').find(':selected').attr('data-max');

        //setting intial values and flags needed

        //traversing each row one by one
        $('table tr').each(function() {  

            if(categorynameValue == 0){   //if no value then display row
                categorynameFlag = 1;
            }
            else if(categorynameValue == $(this).find('td.categoryname').data('categoryname')){ 
                categorynameFlag = 1;       //if value is same display row
            }
            else{
                categorynameFlag = 0;
            }
        
            // if(rangeValue == 0){
            // rangeFlag = 1;
            // }
            // //condition to display rows for a range
            // else if((rangeminValue <= $(this).find('td.range').data('min') && rangemaxValue >  $(this).find('td.range').data('min')) ||  (
            //     rangeminValue < $(this).find('td.range').data('max') &&
            //     rangemaxValue >= $(this).find('td.range').data('max'))){
            //     rangeFlag = 1;
            // }
            // else{
            //     rangeFlag = 0;
            // }
    
            // console.log(rangeminValue +' '+rangemaxValue);
            // console.log($(this).find('td.range').data('min') +' '+$(this).find('td.range').data('max'));
  
  
        if(categorynameFlag){
        $(this).show();  //displaying row which satisfies all conditions
        }

        });

    }
</script>

<script>
  var minDate, maxDate;
 
 // Custom filtering function which will search data in column four between two values(dates)
 $.fn.dataTable.ext.search.push(
     function( settings, data, dataIndex ) {
         var min = minDate.val();
         var max = maxDate.val();
         var date = new Date( data[6] ); //6 is the date column on datatable
  
         if (
             ( min === null && max === null ) ||
             ( min === null && date <= max ) ||
             ( min <= date   && max === null ) ||
             ( min <= date   && date <= max )
         ) {
             return true;
         }
         return false;
     }
 );

  // $(document).ready(function() {
  //   // Create date inputs
  //   minDate = new DateTime($('#min'), {
  //       format: 'MMMM Do YYYY'
  //   });
  //   maxDate = new DateTime($('#max'), {
  //       format: 'MMMM Do YYYY'
  //   });
 
  //   // DataTables initialisation
  //   var table = $('.custom-table').DataTable({ "bSort" : false });
 
  //   // Refilter the table
  //   $('#min, #max').on('change', function () {
  //       table.draw();
  //   });
  // });
</script>
@endsection