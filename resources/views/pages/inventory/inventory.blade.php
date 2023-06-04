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
  .order-status, #show-orders, #hide-orders, #show-transfers, #hide-transfers{
    cursor: pointer;
  }
  
</style>
@endsection

@section('content')
    
<main id="main" class="main">
  <div class="pagetitle">
    <h1>{{ $selected_warehouse !== '' ? $selected_warehouse->name : '' }} Inventory Management</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('inventoryDashboard') }}">Inventory Management</a></li>
      </ol>
    </nav>
  </div>
  <!-- End Page Title -->

  <!-- Alert -->
  <div id="liveAlertPlaceholder d-none"></div>
  <!-- /Alert -->

  <div class="d-flex justify-content-between align-items-center">
    <div class="text-center mb-3">
      <div class="btn-group">
        
        <button type="button" class="btn btn-info btn-sm dropdown-toggle fw-bolder" data-bs-toggle="dropdown" style="font-size: 10px;">
          <span>{{ $selected_warehouse !== '' ? $selected_warehouse->name : 'Select Warehouse' }}</span>
        </button>
        
        @if (count($warehouses) > 0)
        @if ($record == 'all')
        <ul class="dropdown-menu">
          
          @foreach ($warehouses as $warehouse)
          <li><a class="dropdown-item" href="{{ route('inventoryDashboard', $warehouse->unique_key) }}">{{ $warehouse->name }}</a></li>
          <li><hr class="dropdown-divider"></li>
          @endforeach
          
        </ul> 
        @endif
        @if ($record == 'today')
        <ul class="dropdown-menu">
          
          @foreach ($warehouses as $warehouse)
          <li><a class="dropdown-item" href="{{ route('inventoryDashboardToday', $warehouse->unique_key) }}">{{ $warehouse->name }}</a></li>
          <li><hr class="dropdown-divider"></li>
          @endforeach
          
        </ul> 
        @endif
        @if ($record == 'weekly')
        <ul class="dropdown-menu">
          
          @foreach ($warehouses as $warehouse)
          <li><a class="dropdown-item" href="{{ route('inventoryDashboardWeekly', $warehouse->unique_key) }}">{{ $warehouse->name }}</a></li>
          <li><hr class="dropdown-divider"></li>
          @endforeach
          
        </ul> 
        @endif
        @if ($record == 'monthly')
        <ul class="dropdown-menu">
          
          @foreach ($warehouses as $warehouse)
          <li><a class="dropdown-item" href="{{ route('inventoryDashboardMonthly', $warehouse->unique_key) }}">{{ $warehouse->name }}</a></li>
          <li><hr class="dropdown-divider"></li>
          @endforeach
          
        </ul> 
        @endif
        @if ($record == 'yearly')
        <ul class="dropdown-menu">
          
          @foreach ($warehouses as $warehouse)
          <li><a class="dropdown-item" href="{{ route('inventoryDashboardYearly', $warehouse->unique_key) }}">{{ $warehouse->name }}</a></li>
          <li><hr class="dropdown-divider"></li>
          @endforeach
          
        </ul> 
        @endif
        
        @endif
        
  
      </div>
    </div>
    
    <div class="text-center mb-3">
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
  
        <a href="{{route('inventoryDashboard') }}"><button type="button" class="btn btn-sm btn-light-success {{ $record == 'all' ? 'active' : '' }}">
          All
        </button></a>
        
      </div>
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
                <h2 class="fw-bold">{{ $currency }}{{ $sales_sum }}</h2>
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

  <section>
    <!---warehouse-orders--->
    
    @if ($outgoingStocks != '')
    @if (count($packages) > 0)
    
    
    <div class="d-flex justify-content-start align-items-center mb-3 gap-3">
      <div class="btn-group">
        
        <button type="button" class="btn btn-info btn-sm dropdown-toggle fw-bolder" data-bs-toggle="dropdown">
          <span><span id="active-order-status">All Orders</span> {{ $selected_warehouse !== '' ? 'in '. $selected_warehouse->name : '' }}</span>
        </button>
        
        <ul class="dropdown-menu">
          
          <li class="dropdown-item order-status">All Orders</li>
          <li><hr class="dropdown-divider"></li>

          <li class="dropdown-item order-status">Delivered and Remitted</li>
          <li><hr class="dropdown-divider"></li>

          <li class="dropdown-item order-status">Delivered Not Remitted</li>
          <li><hr class="dropdown-divider"></li>

          <li class="dropdown-item order-status">Cancelled</li>
          <li><hr class="dropdown-divider"></li>

          <li class="dropdown-item order-status">Pending</li>
          <li><hr class="dropdown-divider"></li>

          <li class="dropdown-item order-status">New</li>
          <li><hr class="dropdown-divider"></li>
          
        </ul> 
        
      </div>
      <div class="display-6" id="show-orders"><i class="bi bi-eye"></i></div>
      <div class="display-6" id="hide-orders" style="display: none;"><i class="bi bi-eye-slash"></i></div>
    </div>
      

    <div id="orders-section">

      @foreach ($packages as $package)

      <div class="row each-order">
        <div class="col-md-12">
          <div class="card">
            
            <div class="card-body pt-3">
              
              <div class="row g-3 m-1">
                <div class="col-lg-3">
                    <label class="fw-bolder">Order Code</label>
                    <div class="text-dark display-7 fw-bold">{{ $package['warehouseOrder']['order']->orderCode($package['warehouseOrder']['order']->id) }}</div>
                    <div class="each-order-status">{{ ucFirst(str_replace('_', ' ', $package['warehouseOrder']['order']->status )) }}</div>
                    
                </div>
                <div class="col-lg-5">
                    <label class="fw-bolder">Customer</label>
                    <div class="text-dark">{{ $package['warehouseOrder']['order']->customer_id ? $package['warehouseOrder']['order']->customer->firstname : 'N/A' }} 
                      {{ $package['warehouseOrder']['order']->customer_id ? $package['warehouseOrder']['order']->customer->lastname : 'N/A' }}
                        | Email: <span class="lead">{{ $package['warehouseOrder']['order']->customer_id ? $package['warehouseOrder']['order']->customer->email : 'N/A' }}</div>
                    <div>Phone:  <span class="lead">{{ $package['warehouseOrder']['order']->customer_id ? $package['warehouseOrder']['order']->customer->phone_number : 'N/A' }}</span><br>
                        @if ($package['warehouseOrder']['order']->customer_id)
                    
                        @php
                            $whatsapp = substr($package['warehouseOrder']['order']->customer->whatsapp_phone_number, 1)
                        @endphp
                        Whatsapp:  <span class="lead"><a href="https://wa.me/{{ '234'.$whatsapp }}?text=Hi" target="_blank">
                            {{ $package['warehouseOrder']['order']->customer->whatsapp_phone_number }}</a></span>
                        @else
                            Whatsapp:  <span class="lead">None</span>
                        @endif
                        {{-- <a href="https://wa.me/2348066216874?text=Hi">Whatsapp link</a> --}}
                    </div>
                    <div>Location:  <span class="lead">{{ $package['warehouseOrder']['order']->customer_id ? $package['warehouseOrder']['order']->customer->city : 'None' }}, {{ $package['warehouseOrder']['order']->customer_id ? $package['warehouseOrder']['order']->customer->state : 'None' }}</span></div>
                    <div>Delivery Address:  <span class="lead">{{ $package['warehouseOrder']['order']->customer_id ? $package['warehouseOrder']['order']->customer->delivery_address : 'None' }}</span></div>
                    
                </div>
                <div class="col-lg-2">
                    <label class="fw-bolder">Expected Revenue({{ $currency }})</label>
                    <div class="text-dark display-7 fw-bold">{{ number_format($package['warehouseOrder']['orderRevenue']) }}</div>
                </div>
                <div class="col-lg-2">
                    <label class="fw-bolder">Agent</label>
                    <div class="text-dark">{{ $package['warehouseOrder']['order']->agent_assigned_id ? $package['warehouseOrder']['order']->agent->name : 'None' }}</div>
                </div>
              </div>
              
              @foreach ($package['warehouseOrder']['outgoingStock'] as $outgoingStock)
              <div class="row g-3 m-1 border {{ $outgoingStock->customer_acceptance_status == 'accepted' ? 'border-success' : 'border-danger' }} rounded">
                
                <div class="col-lg-6">
                    <label class="fw-bolder">Product Name</label>
                    <div class="text-dark" style="font-size: 14px;">{{ $outgoingStock->product->name }}</div>
                </div>

                <div class="col-lg-1">
                    <label class="fw-bolder">Qty Ordered</label>
                    <div class="text-dark d-none" style="font-size: 14px;">{{ $outgoingStock->quantity_removed.' @'. $outgoingStock->product->price }}</div>
                    <div class="text-dark" style="font-size: 14px;">{{ $outgoingStock->quantity_removed }}</div>
                </div>
                
                <div class="col-lg-3">
                    <label class="fw-bolder">Revenue</label>
                    <div class="text-dark" style="font-size: 14px;">{{ $outgoingStock->amount_accrued }}</div>
                </div>

                <div class="col-lg-2">
                  <label class="fw-bolder">Customer Action</label>
                  <div class="{{ $outgoingStock->customer_acceptance_status == 'accepted' ? 'text-success' : 'text-danger' }}" style="font-size: 14px;">{{ $outgoingStock->customer_acceptance_status }}</div>
              </div>
            
              </div>
              
              @endforeach
              
              
            </div>

          </div>
        </div>
      </div>

      @endforeach  
    
    </div>
    @endif
    @endif
  </section>

  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-top-border border-top-primary">
          <div class="card-body">
            <div class="card-title">
              <div class="d-flex justify-content-start align-items-center gap-3">
                <div>Products Transfers {{ $selected_warehouse !== '' ? 'in '. $selected_warehouse->name : '' }}</div>
                <div class="display-7" id="show-transfers"><i class="bi bi-eye"></i></div>
                <div class="display-7" id="hide-transfers" style="display: none;"><i class="bi bi-eye-slash"></i></div>
              </div>
              
            </div>

            @if ($selected_warehouse !== '')
            <div id="transfers-section">

              <div class="row mb-3 d-none">
                <div class="col-lg-3 col-md-6">
                  <label for="minTransferDate">Start Date</label>
                  <input type="text" id="minTransferDate" class="form-control filter" readonly>
                </div>

                <div class="col-lg-3 col-md-6">
                  <label for="maxTransferDate">End Date</label>
                  <input type="text" id="maxTransferDate" class="form-control filter" readonly>
                </div>
                
              </div>

              <div class="table table-responsive">
                <table class="table custom-table" style="width:100%">
                  <thead>
                      <tr>
                          <th>From Warehouse</th>
                          <th>Products Transferred</th>
                          <th>To Warehouse</th>
                          <th>Done By</th>
                          <th>Date Added</th>
                      </tr>
                  </thead>
                  <tbody>
                    @if (count($transfers) > 0)
                        @foreach ($transfers as $transfer)
                        <tr>
                        
                          <td class="@if($transfer->fromWarehouse->name==$selected_warehouse->name) fw-bold @endif">{{ $transfer->fromWarehouse->name }}</td>
                          <td>
                            @php
                                $product_qty_transferred = $transfer->product_qty_transferred
                            @endphp
                            @foreach ($product_qty_transferred as $productQty)
                                <div class="badge badge-secondary">{!! isset($productQty['each_product'][0]) ? $productQty['each_product'][0] : '' !!}</div>
                            @endforeach
                          </td>
                          <td class="@if($transfer->toWarehouse->name==$selected_warehouse->name) fw-bold @endif">{{ $transfer->toWarehouse->name }}</td>
                          <td>{{ $transfer->createdBy->name }}</td>
                          <td>{{ $transfer->created_at }}</td>
                          
                      </tr>
                        @endforeach
                    @endif
                      
                  </tbody>
                </table>
              </div>

            </div>
            @endif

            @if ($selected_warehouse == '')
            <div id="transfers-section2">

              <div class="row mb-3 d-none">
                <div class="col-lg-3 col-md-6">
                  <label for="minTransferDate2">Start Date..</label>
                  <input type="text" id="minTransferDate2" class="form-control filter form_date">
                </div>

                <div class="col-lg-3 col-md-6">
                  <label for="maxTransferDate2">End Date</label>
                  <input type="text" id="maxTransferDate2" class="form-control filter form_date">
                </div>
                
              </div>

              <div class="table table-responsive">
                <table class="table custom-table" style="width:100%">
                  <thead>
                      <tr>
                          <th>From Warehouse</th>
                          <th>Products Transferred</th>
                          <th>To Warehouse</th>
                          <th>Done By</th>
                          <th>Date Added</th>
                      </tr>
                  </thead>
                  <tbody>
                    @if (count($transfers) > 0)
                        @foreach ($transfers as $transfer)
                        <tr>
                        
                          <td>{{ $transfer->fromWarehouse->name }}</td>
                          <td>
                            @php
                                $product_qty_transferred = $transfer->product_qty_transferred
                            @endphp
                            @foreach ($product_qty_transferred as $productQty)
                                <div class="badge badge-secondary">{!! isset($productQty['each_product'][0]) ? $productQty['each_product'][0] : '' !!}</div>
                            @endforeach
                          </td>
                          <td>{{ $transfer->toWarehouse->name }}</td>
                          <td>{{ $transfer->createdBy->name }}</td>
                          <td>{{ $transfer->created_at }}</td>
                          
                      </tr>
                        @endforeach
                    @endif
                      
                  </tbody>
                </table>
              </div>

            </div>
            @endif

          </div>
        </div>
      </div>
    </div>
  </section>

  <!--warehouse-products-section--->
  @if ($selected_warehouse !== '')
  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-top-border border-top-primary">
          <div class="card-body">
            <div class="card-title">Products in {{ $selected_warehouse->name }}</div>

            <div class="row mb-3">
              <div class="col-lg-3 col-md-6">
                <label for="minDateWarehouseStock">Start Date</label>
                <input type="text" id="minDateWarehouseStock" class="form-control filter minDateStock form_date">
              </div>

              <div class="col-lg-3 col-md-6">
                <label for="maxDateWarehouseStock">End Date</label>
                <input type="text" id="maxDateWarehouseStock" class="form-control filter maxDateStock form_date">
              </div>

            </div>

            <div class="table table-responsive">
              <table id="warehouse-stock-table" class="table custom-table2 table-striped" style="width:100%">
                <thead>
                  <tr>
                    <th scope="col">Product Image</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Qty In Warehouse</th>
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
                            
                            <td>{{ $selected_warehouse->productQtyInWarehouse($product->id) }}</td>
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
  @endif
  
  <!--all-products-section--->
  @if ($selected_warehouse == '')
  <section class="section">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-top-border border-top-primary">
          <div class="card-body">
            <div class="card-title">Recent Stock Report</div>

            <div class="row mb-3">
              <div class="col-lg-3 col-md-6">
                <label for="minDateStock">Start Date</label>
                <input type="text" id="minDateStock" class="form-control filter minDateStock form_date" readonly>
              </div>

              <div class="col-lg-3 col-md-6">
                <label for="maxDateStock">End Date</label>
                <input type="text" id="maxDateStock" class="form-control filter maxDateStock form_date" readonly>
              </div>

              <div class="col-lg-3 col-md-6">
                <label for="filter-categoryname">Category</label>
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
              <table id="stock-table" class="table custom-table2 table-striped" style="width:100%">
                <thead>
                  <tr>
                    <th scope="col">S/N</th>
                    <th scope="col">Product Image</th>
                    <th scope="col">Product Name</th>
                    {{-- <th scope="col">Category</th> --}}
                    <th scope="col">Qty Recieved</th>
                    <th scope="col">Qty Sold</th>
                    <th scope="col">Qty Remaining(Stock)</th>
                    <th scope="col">Date Last Updated</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($recently_products->count() > 0)
                      
                      @foreach ($recently_products as $product)
                          
                          <tr>
                            <td>{{ $loop->iteration }}</td>
                            <th scope="row">
                                <a
                                  href="{{ asset('/storage/products/'.$product->image) }}"
                                  data-fancybox="gallery"
                                  data-caption="{{ isset($product->name) ? $product->name : 'no caption' }}"
                                  >   
                                  <img src="{{ asset('/storage/products/'.$product->image) }}" width="50" class="img-thumbnail img-fluid"
                                  alt="{{$product->name}}" style="height: 30px;"></a>
                            </th>
                            <td>{{ $product->name }}<input type="hidden" data-categoryname="{{ $product->category->name }}" class="categoryname" value="{{ $product->category->name }}"></td>
                            
                            
                            <td>{{ $product->purchases->sum('product_qty_purchased') }}</td>
                            <td>{{ $product->purchases->sum('product_qty_purchased') - $product->stock_available() }}</td>
                            <td>{{ $product->stock_available() }}</td>
                            <td>{{ $product->updated_at->format('Y-m-d') }}</td>
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
  @endif

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

<link href="{{asset('/assets/css/jquery.datetimepicker.min.css')}}" rel="stylesheet">
<script src="{{asset('/assets/js/jquery.datetimepicker.min.js')}}"></script>
<script>
  jQuery('.form_date').datetimepicker({
    datepicker:true,
    //showPeriod: true,
    format:'Y-m-d',
    timepicker:false,
  });
</script>

<script>
  $('#show-orders').on('click', function () {
    $('#hide-orders').show();
    $('#orders-section').toggle();
    $(this).hide();
  });
  $('#hide-orders').on('click', function () {
    $('#show-orders').show();
    $('#orders-section').toggle();
    $(this).hide();
  });
</script>

<script>
  $('#show-transfers').on('click', function () {
    $('#hide-transfers').show();
    $('#transfers-section').toggle();
    $('#transfers-section2').toggle();
    $(this).hide();
  });
  $('#hide-transfers').on('click', function () {
    $('#show-transfers').show();
    $('#transfers-section').toggle();
    $('#transfers-section2').toggle();
    $(this).hide();
  });
</script>

<script>
  $(".order-status").click(function(){
  
  // Retrieve the input field text and reset the count to zero
  var order_status = $(this).text() == 'All Orders' ? $(this).text() : $(this).text() + ' Orders', count = 0;
  //console.log(filter);
  $('#active-order-status').text(order_status)

  var filter = $(this).text() != 'All Orders' ? $(this).text() : ''
  
  // Loop through the comment list
  $(".each-order").each(function(){

    // If the list item does not contain the text phrase fade it out
    if ($(this).find('.each-order-status').text().search(new RegExp(filter, "i")) < 0) {
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
            else if(categorynameValue == $(this).find('input.categoryname').data('categoryname')){ 
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
          
      var tableStock = $('.custom-table2').DataTable({
        
      });

      // Event handler for datetimepicker value change on the tableStock table
      $('.minDateStock, .maxDateStock').on('change', function() {
        tableStock.draw();
      });

      // Add custom filtering functions for the tableStock table
      $.fn.dataTable.ext.search.push(function(settings, searchData) {
        var min = $('.minDateStock').val();
        var max = $('.maxDateStock').val();
        var date = searchData[6]; // Assuming the date column is at index 5

        if ((min === '' && max === '') ||
            (min === '' && date <= max) ||
            (min <= date && max === '') ||
            (min <= date && date <= max)) {
              //console.log('up');
          return true;
        }
        return false;
      });

      // Apply filtering on the tableStock table
      $('.minDateStock, .maxDateStock').on('keyup', function() {
        tableStock.draw();
      });
  

</script>

@endsection