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

        table .dropdown-menu{
          position: fixed !important;
          top: 50% !important;
          left:92% !important;
          transform: translate(-92%, -50%) !important;
        }

        
    </style>
@endsection
@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Orders @if (!isset($status) || $status=='new') New @elseif($status=='pending') Pending
      @elseif($status=='cancelled') Cancelled @elseif($status=='delivered_not_remitted') Delivered not Remitted
      @elseif($status=='delivered_and_remitted') Delivered and Remitted @endif</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('allOrders') }}">Orders</a></li>
        <li class="breadcrumb-item active">@if (!isset($status) || $status=='new') New @elseif($status=='pending') Pending
          @elseif($status=='cancelled') Cancelled @elseif($status=='delivered_not_remitted') Delivered not Remitted
          @elseif($status=='delivered_and_remitted') Delivered and Remitted @endif</li>
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

  <section>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body pt-3">
            
          <div class="clearfix mb-2">

            <div class="float-start text-start d-none">
                <button data-bs-target="#addMoneyTransfer" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                  <i class="bi bi-plus"></i> <span>Add Money Transfer</span></button>
            </div>

            <div class="float-end text-end d-none">
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
                    <th>Order Code</th>
                    <th>Customer</th>
                    <th>Delivery Due Date</th>
                    <th>Delivery Address</th>
                    <th>Agent</th>
                    <th>Date Created</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
              </thead>
              <tbody>

                @if (count($orders) > 0)
                  @foreach ($orders as $key=>$order)
                    <tr>
                      <th>{{ $order->orderCode($order->id) }}</th>
                      <td>{{ $order->customer_id ? $order->customer->firstname : 'No response' }} {{ $order->customer_id ? $order->customer->lastname : '' }}</td>
                      
                      <td>
                        @if (isset($order->customer->delivery_duration))
                        {{ \Carbon\Carbon::parse($order->customer->created_at->addDays($order->customer->delivery_duration))->format('D, jS M Y') }}
                        
                        @else
                         No reponse   
                        @endif
                        
                      </td>
                      <td>{{ $order->customer_id ? $order->customer->delivery_address : 'No response' }}</td>

                      @if (isset($order->agent_assigned_id))
                      <td>
                        {{ $order->agent->name }} <br>
                        <button class="btn btn-sm btn-dark rounded-pill" onclick="changeAgentModal('{{ $order->id }}')" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Change Agent">
                          <i class="bi bi-plus"></i> <span>Change Agent</span></button>
                      </td>
                      @else
                      <td style="width: 120px">
                        <button class="btn btn-sm btn-success rounded-pill" onclick="addAgentModal('{{ $order->id }}')" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Assign Agent">
                          <i class="bi bi-plus"></i> <span>Assign Agent</span></button> 
                      </td>
                      @endif

                      <td>{{ $order->created_at->format('D, jS M Y, g:ia') }}</td>
                      
                      <td>
    
                        <div class="btn-group">
                          @if (!isset($order->status) || $order->status=='new')
                          <button type="button" class="btn btn-info btn-sm dropdown-toggle rounded-pill fw-bolder" data-bs-toggle="dropdown" style="font-size: 10px;">
                            <span>new</span>
                          </button>
                          @elseif($order->status=='pending')
                          <button type="button" class="btn btn-danger btn-sm dropdown-toggle rounded-pill fw-bolder" data-bs-toggle="dropdown" style="font-size: 10px;">
                            <span>pending</span>
                          </button>
                          @elseif($order->status=='cancelled')
                          <button type="button" class="btn btn-dark btn-sm dropdown-toggle rounded-pill fw-bolder" data-bs-toggle="dropdown" style="font-size: 10px;">
                            <span>cancelled</span>
                          </button>
                          @elseif($order->status=='delivered_not_remitted')
                          <button type="button" class="btn btn-warning btn-sm dropdown-toggle rounded-pill fw-bolder" data-bs-toggle="dropdown" style="font-size: 10px;">
                            <span>delivered not remitted</span>
                          </button>
                          @elseif($order->status=='delivered_and_remitted')
                          <button type="button" class="btn btn-success btn-sm dropdown-toggle rounded-pill fw-bolder" data-bs-toggle="dropdown" style="font-size: 10px;">
                            <span>delivered & remitted</span>
                          </button>
                          

                          @endif
                          <ul class="dropdown-menu">

                            <li><a class="dropdown-item" href="{{ route('updateOrderStatus', [$order->unique_key, 'new']) }}">New</a></li>
                            <li><hr class="dropdown-divider"></li>
                              
                            <li><a class="dropdown-item" href="{{ route('updateOrderStatus', [$order->unique_key, 'pending']) }}">Pending</a></li>
                            <li><hr class="dropdown-divider"></li>

                            <li><a class="dropdown-item" href="{{ route('updateOrderStatus', [$order->unique_key, 'cancelled']) }}">Cancelled</a></li>
                            <li><hr class="dropdown-divider"></li>

                            <li><a class="dropdown-item" href="{{ route('updateOrderStatus', [$order->unique_key, 'delivered_not_remitted']) }}">Delivered Not Remitted</a></li>
                            <li><hr class="dropdown-divider"></li>

                            <li><a class="dropdown-item" href="{{ route('updateOrderStatus', [$order->unique_key, 'delivered_and_remitted']) }}">Delivered & Remitted</a></li>
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
    </div>
  </section>

</main><!-- End #main -->

<!-- Modal addAgentModal -->
<div class="modal fade" id="addAgentModal" tabindex="-1" aria-labelledby="addAgentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addAgentModalLabel">Assign Agent</h1>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('assignAgentToOrder') }}" method="POST">@csrf
                <div class="modal-body">
                    
                    <input type="hidden" id="order_id" class="order_id" name="order_id" value="">
                    <div class="d-grid mb-3">
                        <label for="">Select Agent</label>
                        <select name="agent_id" id="" data-live-search="true" class="custom-select form-control border border-dark">
                            <option value="">Nothing Selected</option>

                            @foreach ($agents as $agent)
                              <option value="{{ $agent->id }}">{{ $agent->name }} | {{ $agent->id }}</option>
                            @endforeach
                            
                        </select>
                    </div>
                
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary addAgentBtn">Assign Agent</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal changeAgentModal -->
<div class="modal fade" id="changeAgentModal" tabindex="-1" aria-labelledby="changeAgentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h1 class="modal-title fs-5" id="changeAgentModalLabel">Change Assigned Agent</h1>
              <button type="button" class="btn-close"
                  data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('assignAgentToOrder') }}" method="POST">@csrf
              <div class="modal-body">
                  
                  <input type="hidden" id="order_id" class="order_id" name="order_id" value="">
                  <div class="d-grid mb-3">
                      <label for="">Select Agent</label>
                      <select name="agent_id" id="changeAgentModalSelect" data-live-search="true" class="custom-select form-control border border-dark">
                          <option value="kkk" selected>Nothing Selected</option>

                          @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }} | {{ $agent->id }}</option>
                          @endforeach
                          
                      </select>
                  </div>
              
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary addAgentBtn">Assign Agent</button>
              </div>
          </form>
      </div>
  </div>
</div>

@endsection

@section('extra_js')

<script>
  function addAgentModal($orderId="") {
    $('#addAgentModal').modal("show");
    $('.order_id').val($orderId);
  }

  function changeAgentModal($orderId="") {
    $('#changeAgentModal').modal("show");
    $('.order_id').val($orderId);

  //  var option = $('#changeAgentModalSelect').val();
  //  console.log(option)

  }

  // $('.addAgentBtn').click(function(e){
  //       e.preventDefault();
  //       var order_id = $('.order_id').val();
  //       var agent_id = $('.agent_id').val();
        
  //       // alert(category_name)
        
  //       $('#addAgentModal').modal('hide');

  //       $.ajax({
  //           type:'get',
  //           url:'/assign-agent-to-order',
  //           data:{ order_id:order_id, agent_id:agent_id },
  //           success:function(resp){
                
  //               if (resp.status) {
  //                   alert('Agent Assigned Successfully')
  //               } 
                    
  //           },error:function(){
  //               alert("Error");
  //           }
  //       });
        
        
  // });
</script>
    
@endsection