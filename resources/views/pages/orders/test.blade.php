@extends('layouts.design')

@section('title')Orders @endsection
@section('extra_css')
<style>
  /* select2 arrow */
  select{
      -webkit-appearance: listbox !important
  }

  .btn-light {
      background-color: #fff !important;
      color: #000 !important;
  }

  div.filter-option-inner-inner{
      color: #000 !important;
  }
    
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
    <h1>Orders</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Orders</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section d-none">
    <div class="users-list-filter px-1">
      <form method="get" action="" id="searchtform" novalidate="novalidate">
        <div class="row border rounded py-2 mb-2">
          <div class="col-12 col-sm-6 col-lg-3 mb-2">
            <fieldset class="form-group">
              <select name="s_period" id="s_period" class="form-control">
                <option value="" selected="">Select</option>
                <option value="Today">Today</option>
                <option value="Yesterday">Yesterday</option>
                <option value="This Week">This Week</option>
                <option value="Last Week">Last Week</option>
                <option value="This Month">This Month</option>
                <option value="Last Month">Last Month</option>
                <option value="This Year">This Year</option>
                <option value="Last Year">Last Year</option>
              </select>
            </fieldset>
          </div>
          <div class="col-12 col-sm-6 col-lg-3 mb-2">
            <fieldset class="form-group position-relative has-icon-left">
              <input name="s_daterange" id="s_daterange" type="date" class="form-control" placeholder="Select Date"
                autocomplete="off">
            </fieldset>
          </div>

          <div class="col-12 col-sm-6 col-lg-3 mb-2">
            <fieldset class="form-group">
              <select name="whichdate" id="whichdate" class="form-control">
                <option value="0" selected="">Date Filtered by Order Date</option>
                <option value="1">Date Filtered by Delivered Date</option>
                <option value="2">Date Filtered by Scheduled Date</option>
              </select>
            </fieldset>
          </div>

          <div class="col-12 col-sm-6 col-lg-3 mb-2">
            <fieldset class="form-group">
              <select name="s_product" class="form-control" id="s_product">
                <option value="" selected="">All Products</option>
                <option value=""> ()</option>
              </select>
            </fieldset>
          </div>
          <div class="col-6 col-sm-3 col-lg-3 mb-2">
            <fieldset class="form-group">
              <select class="form-control" name="country" id="country">
                <option value="" selected="">All</option>
                <option value=""></option>
              </select>
            </fieldset>
          </div>

          <div class="col-6 col-sm-3 col-lg-3 mb-2">
            <fieldset class="form-group">
              <select name="s_states" class="form-control" id="s_states">
                <option value="">Select State/Region</option>

                <option value="Abia">Abia</option>
                <option value="Abuja">Abuja</option>
                <option value="Adamawa">Adamawa</option>
                <option value="Akwa Ibom">Akwa Ibom</option>
                <option value="Anambra">Anambra</option>
                <option value="Bauchi">Bauchi</option>
                <option value="Bayelsa">Bayelsa</option>
                <option value="Benue">Benue</option>
                <option value="Borno">Borno</option>
                <option value="Cross River">Cross River</option>
                <option value="Delta">Delta</option>
                <option value="Ebonyi">Ebonyi</option>
                <option value="Edo">Edo</option>
                <option value="Ekiti">Ekiti</option>
                <option value="Enugu">Enugu</option>
                <option value="Gombe">Gombe</option>
                <option value="Imo">Imo</option>
                <option value="Jigawa">Jigawa</option>
                <option value="Kaduna">Kaduna</option>
                <option value="Kano">Kano</option>
                <option value="Katsina">Katsina</option>
                <option value="Kebbi">Kebbi</option>
                <option value="Kogi">Kogi</option>
                <option value="Kwara">Kwara</option>
                <option value="Lagos">Lagos</option>
                <option value="Nasarawa">Nasarawa</option>
                <option value="Niger">Niger</option>
                <option value="Ogun">Ogun</option>
                <option value="Ondo">Ondo</option>
                <option value="Osun">Osun</option>
                <option value="Oyo">Oyo</option>
                <option value="Plateau">Plateau</option>
                <option value="Rivers">Rivers</option>
                <option value="Sokoto">Sokoto</option>
                <option value="Taraba">Taraba</option>
                <option value="Yobe">Yobe</option>
                <option value="Zamfara">Zamfara</option>

              </select>
            </fieldset>
          </div>
          <div class="col-12 col-sm-6 col-lg-3 mb-2">
            <fieldset class="form-group">
              <input type="text" class="form-control customers" name="s_customer"
                placeholder="Customers Name or Phone" id="s_customer">
            </fieldset>
          </div>
          <div class="col-12 col-sm-6 col-lg-3 mb-2">
            <fieldset class="form-group">
              <select class="form-control agents" name="s_agent" id="s_agent">
                <option value="" selected="">Select Agent</option>
                <option value=""></option>
              </select>
            </fieldset>
          </div>

          <div class="col-12 col-sm-6 col-lg-3 mb-2">
            <fieldset class="form-group">
              <select class="form-control" name="searchformx" id="searchformx">
                <option value="" selected="">Orders By Form</option>
                <option value=""></option>
              </select>
            </fieldset>
          </div>


          <div class="col-12 col-sm-6 col-lg-3 mb-2">
            <fieldset class="form-group">
              <select class="form-control" name="searchname" id="searchname">
                <option value="" selected="">Orders Added By</option>
                <option value="5551">Admin</option>
              </select>
            </fieldset>
          </div>


          <div class="col-12 col-sm-6 col-lg-3 mb-2">
            <fieldset class="form-group">
              <select class="form-control" name="searchnamex" id="searchnamex">
                <option value="" selected="">Orders Processed By</option>
                <option value=""></option>
              </select>
            </fieldset>
          </div>


          <div class="col-12 col-sm-6 col-lg-3 mb-2">
            <fieldset class="form-group">
              <select class="form-control" name="exportorders" id="exportorders">
                <option value="" selected="">Export Orders</option>
                <option value="exportorders?orderstatus=All">All</option>
                <option value="exportorders?orderstatus=Pending">Pending</option>
                <option value="exportorders?orderstatus=Awaiting Delivery">Awaiting</option>
                <option value="exportorders?orderstatus=Delivered">Delivered</option>
                <option value="exportorders?orderstatus=Not Picking Calls">Not
                  Picking Calls</option>
                <option value="exportorders?orderstatus=Shipped">Shipped</option>
                <option value="exportorders?orderstatus=Deliver On...">Scheduled</option>
                <option value="exportorders?orderstatus=Cancelled">Cancelled</option>
                <option value="exportorders?orderstatus=Cash Remitted">Cash
                  Remitted</option>
                <option value="exportorders?orderstatus=Cart Abandonment">Cart
                  Abandonment</option>
                <option value="exportorders?orderstatus=Deleted">Deleted</option>
              </select>
            </fieldset>
          </div>



          <div class="col-12 col-sm-6 col-lg-3 d-grid mb-2 align-items-center">
            <button type="submit" name="s_submit" id="s_submit" class="btn
                btn-primary btn-sm glow">Search</button>
          </div>
          <div class="col-12 col-sm-6 col-lg-3 d-grid mb-2 align-items-center">
            <button type="button" name="s_reset" id="s_reset" class="btn
                btn-secondary btn-sm">Reset</button>
          </div>




          <div class="col-12 col-sm-6 col-lg-3 mb-2 d-flex align-items-center">
            <button type="button" class="btn btn-primary btn-sm glow
                users-list-clear mb-0" data-toggle="modal" data-target="#import"><i class="bx bx-downvote"></i>Import
              Orders</button>
          </div>

        </div>
      </form>
    </div>
  </section>

  <!-- <hr> -->

  <section>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">

            <div class="card-title"></div>


            <table id="orders-table" class="table table-striped custom-table" style="width:100%">
              <thead>
                <tr>
                  <th scope="col">Order Code</th>
                  <th scope="col">Customer</th>
                  <th scope="col">Delivery Address</th>
                  <th scope="col">Agent</th>
                  <th scope="col">Date Created</th>
                  <th scope="col">Status</th>
                  <th scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>

                @if (count($orders) > 0)
                  @foreach ($orders as $key=>$order)
                    <tr>
                      <th scope="row">{{ $order->orderCode($order->id) }}</th>
                      <td>{{ $order->customer->firstname }} {{ $order->customer->lastname }}</td>
                      <td>{{ $order->customer->delivery_address }}</td>

                      {{ isset($order->agent_assigned_id) ? $order->agent_assigned->name : 'None' }}
                      @if (isset($order->agent_assigned_id))
                      <td>
                        {{ $order->agent_assigned->name }}
                        <button class="btn btn-sm btn-dark rounded-pill" onclick="addAgentModal('{{ $order->id }}')" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                          <i class="bi bi-plus"></i> <span>Change Agent</span></button>
                      </td>
                      @else
                      <td style="width: 120px">
                        <button class="btn btn-sm btn-success rounded-pill" onclick="addAgentModal('{{ $order->id }}')" data-bs-toggle="tooltip" data-bs-title="Assign Agent">
                          <i class="bi bi-plus"></i> <span>Assign Agent</span></button> 
                      </td>
                      @endif
                      <td>

                        
                      
                      </td>
                      
                      <td>{{ $order->created_at->format('D, jS M Y, g:ia') }}</td>
                      
                      <td>
                        

                        <div class="btn-group">
                          @if (!isset($order->status) || $order->status=='pending')
                          <button type="button" class="btn btn-danger btn-sm dropdown-toggle rounded-pill fw-bolder" data-bs-toggle="dropdown" style="font-size: 10px;">
                            <span>pending</span>
                          </button>
                          @endif
                          <ul class="dropdown-menu">
                              
                            <li><a class="dropdown-item" href="">Pending</a></li>
                            <li><hr class="dropdown-divider"></li>

                            <li><a class="dropdown-item" href="">Confirmed</a></li>
                            <li><hr class="dropdown-divider"></li>

                            <li><a class="dropdown-item" href="">Awaiting</a></li>
                            <li><hr class="dropdown-divider"></li>

                            <li><a class="dropdown-item" href="">Delivered</a></li>
                            <li><hr class="dropdown-divider"></li>
                            
                          </ul>
                      </div>
                      </td>
                      <td>
                        <a class="btn btn-success btn-sm" href="{{ route('singleOrder', $order->unique_key) }}">View</a>
                      </td>
                    </tr>
                  @endforeach
                @endif

                
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </section>

</main><!-- End #main -->

<!-- Modal addMoneyTransfer -->
<div class="modal fade assignAgentModal" id="assignAgentModal" tabindex="-1" aria-labelledby="assignAgentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h1 class="modal-title fs-5" id="assignAgentModalLabel">Money Transfer</h1>
              <button type="button" class="btn-close"
                  data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('addMoneyTransferPost') }}" method="POST">@csrf
              <div class="modal-body">
                  
                  <input type="hidden" class="order_id" name="order_id" value="">
                  <div class="d-grid mb-3">
                      <label for="">Select Agent</label>
                      <select name="from_account" id="" data-live-search="true" class="custom-select form-control border border-dark">
                          <option value="">Nothing Selected</option>

                          @foreach ($agents as $agent)
                              <option value="{{ $agent->id }}">{{ $agent->name }} | {{ $agent->id }}</option>
                          @endforeach
                          
                      </select>
                  </div>

                                
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary addMoneyTransferBtn">Submit Transfer</button>
              </div>
          </form>
      </div>
  </div>
</div>

@endsection

@section('extra_js')

<script>
  function addAgentModal($orderId="") {
    $('#assignAgentModal').modal("show");
    $('.order_id').val($orderId);
    // console.log($orderId)
  }
</script>
    
@endsection