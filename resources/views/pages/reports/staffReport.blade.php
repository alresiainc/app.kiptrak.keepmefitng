@extends('layouts.design')
@section('title')Staff Report @endsection

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
    <h1>Staff Report</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Staff Report</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if(Session::has('error'))
  <div class="alert alert-danger mb-3 text-center">
      {{Session::get('error')}}
  </div>
  @endif

  <section class="users-list-wrapper">
    <div class="users-list-filter px-1">
      <form action="{{ route('staffReportQuery') }}" method="POST">@csrf
        <div class="row border rounded py-2 px-2 mb-2">
  
          <div class="col-12 col-md-6 col-lg-2 mb-3">
            <label for="">Select Aspect</label>
            <fieldset class="form-group">
                <select name="aspect" data-live-search="true" class="custom-select border form-control" id="">
                <option value="{{ $aspect != '' ? $aspect : 'Sales' }}">{{ $aspect != '' ? $aspect : 'Sales' }}</option>
                <option value="Sales">Sales</option>
                <option value="Purchases">Purchases</option>
                <option value="Expenses">Expenses</option>
                <option value="Payroll">Payroll</option>
                
                </select>
            </fieldset>
          </div>

          <div class="col-12 col-md-6 col-lg-3 mb-3">
            <label for="">Start Date</label>
            <fieldset class="form-group">
              <input type="date" name="start_date" class="form-control" id="" value="{{ $start_date != '' ? $start_date : '' }}">
            </fieldset>
          </div>
          
          <div class="col-12 col-md-6 col-lg-3 mb-3">
            <label for="">End Date</label>
            <fieldset class="form-group">
              <input type="date" name="end_date" class="form-control" id="" value="{{ $end_date != '' ? $end_date : '' }}">
            </fieldset>
          </div>

          <div class="col-12 col-md-6 col-lg-3 mb-3">
            <label for="">Select Staff</label>
            <fieldset class="form-group">
              <select name="staff_id" data-live-search="true" class="custom-select border form-control" id="">
                <option value="{{ $staff_selected != '' ? $staff_selected->id : '' }}">{{ $staff_selected != '' ? $staff_selected->name : 'Nothing Selected' }}</option>
                @if (count($staffs))
                    @foreach ($staffs as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                    @endforeach
                @endif
              </select>
            </fieldset>
          </div>

          <div class="col-12 col-md-6 col-lg-1 d-flex align-items-end mb-3">
            <div class="d-grid w-100">
              <button class="btn btn-primary btn-block glow users-list-clear mb-0">Submit</button>
            </div>
          </div>

        </div>
      </form>
    </div>

  </section>

  @if ($aspect=='Sales')
  <section>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body pt-3">
            
            <div class="clearfix mb-2">

                <div class="float-start text-start">
                    Total Sold: <span class="totalPaid badge badge-success"></span>
                </div>

                <div class="float-end text-end">
                <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                    <i class="bi bi-upload"></i> <span>Import</span></button>
                <button class="btn btn-sm btn-secondary rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data"><i class="bi bi-download"></i> <span>Export</span></button>
                <button class="btn btn-sm btn-danger rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Delete All"><i class="bi bi-trash"></i> <span>Delete All</span></button>
                </div>
            </div>
            <hr>
          
            <div class="table table-responsive">
                
                <table id="products-table" class="table custom-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sale Code</th>
                            <th>WareHouse</th>
                            <th>Products(Qty)</th>
                            <th>Grand Total</th>
                            <th>Amount Paid</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (count($staff_sales) > 0)
                    <?php $staffTotalPaid = 0; ?>
                        @foreach ($staff_sales as $sale)

                            <tr>
                                <td>{{ $sale->sale_code }}</td>
                                <td>{{ $sale->warehouse->name }}</td>

                                <td>
                                    <p>{{ $sale->product->name }} <span class="fw-bold">({{ $sale->product_qty_sold }})</span> </p>
                                    
                                    @if(!empty($sale->sales))
                                    
                                        @foreach ($sale->sales as $subsale)
                                            <p>{{ $subsale->product->name }} <span class="fw-bold">({{ $subsale->product_qty_sold }})</span></p>
                                            
                                        @endforeach

                                    @endif
                                </td>
                                
                                <td>{{ $sale->amountDueAccrued($sale->sale_code) == 0 ? $sale->amountPaidAccrued($sale->sale_code) : $sale->amountDueAccrued($sale->sale_code) }}</td>
                                <td>{{ $sale->amountPaidAccrued($sale->sale_code) }}</td>
                                
                                <td>
                                    @if ($sale->amountDueAccrued($sale->sale_code) > $sale->amountPaidAccrued($sale->sale_code))
                                        <span class="badge badge-danger">Owing</span>
                                    @else
                                        <span class="badge badge-success">Paid</span><br>Cash
                                    @endif
                                </td>
                            </tr>
                            <?php $staffTotalPaid += $sale->amountPaidAccrued($sale->sale_code); ?>
                        @endforeach
                        <input type="hidden" class="amountAccrued" value="{{ $staffTotalPaid }}">
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

  @if ($aspect=='Purchases')
  <section>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body pt-3">
            
          <div class="clearfix mb-2">

            <div class="float-start text-start">
                Total Purchased: <span class="totalPaid badge badge-success"></span>
            </div>

            <div class="float-end text-end">
              <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                <i class="bi bi-upload"></i> <span>Import</span></button>
              <button class="btn btn-sm btn-secondary rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data"><i class="bi bi-download"></i> <span>Export</span></button>
              <button class="btn btn-sm btn-danger rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Delete All"><i class="bi bi-trash"></i> <span>Delete All</span></button>
            </div>
          </div>
          <hr>
          
          <div class="table table-responsive">
            
            <table id="products-table" class="table custom-table" style="width:100%">
                <thead>
                    <tr>
                        <th>Purchase Code</th>
                        <th>Products(Qty)</th>
                        <th>Grand Total</th>
                        <th>Amount Paid</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                  @if (count($staff_purchases) > 0)
                  <?php $staffTotalPaid = 0; ?>
                      @foreach ($staff_purchases as $purchase)

                          <tr>
                              <td>{{ $purchase->purchase_code }}</td>
                              
                              <td>
                                <p>{{ $purchase->product->name }} <span class="fw-bold">({{ $purchase->product_qty_purchased }})</span> </p>
                                
                                @if(!empty($purchase->purchases))
                                
                                    @foreach ($purchase->purchases as $subpurchase)
                                        <p>{{ $subpurchase->product->name }} <span class="fw-bold">({{ $subpurchase->product_qty_purchased }})</span></p>
                                        
                                    @endforeach

                                @endif
                              </td>
                              
                              <td>{{ $purchase->amountDueAccrued($purchase->purchase_code) == 0 ? $purchase->amountPaidAccrued($purchase->purchase_code) : $purchase->amountDueAccrued($purchase->purchase_code) }}</td>
                              <td>{{ $purchase->amountPaidAccrued($purchase->purchase_code) }}</td>
                              
                              <td>
                                @if ($purchase->amountDueAccrued($purchase->purchase_code) > $purchase->amountPaidAccrued($purchase->purchase_code))
                                    <span class="badge badge-danger">Owing</span>
                                @else
                                    <span class="badge badge-success">Paid</span><br>Cash
                                @endif
                              </td>
                          </tr>
                          <?php $staffTotalPaid += $purchase->amountPaidAccrued($purchase->purchase_code); ?>
                      @endforeach
                      <input type="hidden" class="amountAccrued" value="{{ $staffTotalPaid }}">
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

  @if ($aspect=='Expenses')
  <section>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body pt-3">
            
            <div class="clearfix mb-2">

                <div class="float-start text-start">
                    Total Spent: <span class="totalPaid badge badge-success"></span>
                </div>

                <div class="float-end text-end">
                <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                    <i class="bi bi-upload"></i> <span>Import</span></button>
                <button class="btn btn-sm btn-secondary rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data"><i class="bi bi-download"></i> <span>Export</span></button>
                <button class="btn btn-sm btn-danger rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Delete All"><i class="bi bi-trash"></i> <span>Delete All</span></button>
                </div>
            </div>
            <hr>
          
            <div class="table table-responsive">
                
                <table id="products-table" class="table custom-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sale Code</th>
                            <th>WareHouse</th>
                            <th>Products(Qty)</th>
                            <th>Grand Total</th>
                            <th>Amount Paid</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (count($staff_sales) > 0)
                    <?php $staffTotalPaid = 0; ?>
                        @foreach ($staff_sales as $sale)

                            <tr>
                                <td>{{ $sale->sale_code }}</td>
                                <td>{{ $sale->warehouse->name }}</td>

                                <td>
                                    <p>{{ $sale->product->name }} <span class="fw-bold">({{ $sale->product_qty_sold }})</span> </p>
                                    
                                    @if(!empty($sale->sales))
                                    
                                        @foreach ($sale->sales as $subsale)
                                            <p>{{ $subsale->product->name }} <span class="fw-bold">({{ $subsale->product_qty_sold }})</span></p>
                                            
                                        @endforeach

                                    @endif
                                </td>
                                
                                <td>{{ $sale->amountDueAccrued($sale->sale_code) == 0 ? $sale->amountPaidAccrued($sale->sale_code) : $sale->amountDueAccrued($sale->sale_code) }}</td>
                                <td>{{ $sale->amountPaidAccrued($sale->sale_code) }}</td>
                                
                                <td>
                                    @if ($sale->amountDueAccrued($sale->sale_code) > $sale->amountPaidAccrued($sale->sale_code))
                                        <span class="badge badge-danger">Owing</span>
                                    @else
                                        <span class="badge badge-success">Paid</span><br>Cash
                                    @endif
                                </td>
                            </tr>
                            <?php $staffTotalPaid += $sale->amountPaidAccrued($sale->sale_code); ?>
                        @endforeach
                        <input type="hidden" class="amountAccrued" value="{{ $staffTotalPaid }}">
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
  

</main><!-- End #main -->

<!--Modal-->
<div class="modal fade" id="staffReportModal" tabindex="-1" aria-labelledby="staffReportModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Select Staff to See Report</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>Download sample product CSV file <a href="#" class="btn btn-sm rounded-pill btn-primary"><i class="bi bi-download me-1"></i> Download</a></div>
        <div class="mt-3">
          <label for="formFileSm" class="form-label">Click to upload file</label>
          <input type="text" name="kkk" class="form-control form-control-sm" id="formFileSm">
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
        $amountAccrued = $(".amountAccrued").val();
        $(".totalPaid").text($amountAccrued);
    </script>
@endsection